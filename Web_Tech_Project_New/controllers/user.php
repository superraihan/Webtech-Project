<?php
require_once 'models/db_connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=login");
    exit();
}

$email = $_SESSION['email'];
$msg = "";
$error = "";

$sql = "SELECT * FROM users WHERE email=:email";
$stmt = $conn->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
$user_id = $user['id'];

$my_requests = [];
$req_stmt = $conn->prepare("SELECT pet_id FROM adoption_request WHERE user_id=:user_id");
$req_stmt->execute(['user_id' => $user_id]);
$requests = $req_stmt->fetchAll();
if ($requests) {
    foreach ($requests as $row) {
        $my_requests[] = $row['pet_id'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['request_adoption'])) {
        $pet_id = $_POST['pet_id'];

        if (!in_array($pet_id, $my_requests)) {
            $insert_sql = "INSERT INTO adoption_request (user_id, pet_id, status) VALUES (:user_id, :pet_id, 'pending')";
            $stmt = $conn->prepare($insert_sql);
            if ($stmt->execute(['user_id' => $user_id, 'pet_id' => $pet_id])) {
                $msg = "Adoption request sent successfully!";
                header("Refresh:1");

            } else {
                $error = "Error: " . $conn->errorInfo()[2];
            }
        }
    }

    if (isset($_POST['add_pet_user'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];
        $status = 'available';

        if (empty($name) || empty($type) || $age < 0) {
            $error = "Please fill all fields correctly.";
        } else {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $sql = "INSERT INTO pets (name, type, age, description, image, status, owner_id) VALUES (:name, :type, :age, :desc, :image, :status, :owner_id)";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute(['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'image' => $image, 'status' => $status, 'owner_id' => $user_id])) {
                    $msg = "Pet submitted successfully!";
                    header("Refresh:1");
                } else {
                    $error = "Error submitting pet: " . $conn->errorInfo()[2];
                }
            } else {
                $error = "Failed to upload image.";
            }
        }
    }

    // Delete User Pet
    if (isset($_GET['delete_pet_id'])) {
        $del_id = $_GET['delete_pet_id'];
        $stmt = $conn->prepare("SELECT owner_id FROM pets WHERE id=:id");
        $stmt->execute(['id' => $del_id]);
        $row = $stmt->fetch();

        if ($row && $row['owner_id'] == $user_id) {
            $conn->prepare("DELETE FROM pets WHERE id=:id")->execute(['id' => $del_id]);
            $msg = "Pet deleted successfully.";
            header("Refresh:1; url=index.php?page=user");
        } else {
            $error = "Unauthorized action.";
        }
    }

    // Update User Pet
    if (isset($_POST['update_pet_user'])) {
        $id = $_POST['pet_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];

        // Verify Ownership
        $check = $conn->prepare("SELECT owner_id FROM pets WHERE id=:id");
        $check->execute(['id' => $id]);
        $p = $check->fetch();

        if ($p && $p['owner_id'] == $user_id) {
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $target = "uploads/" . basename($image);
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
                $sql = "UPDATE pets SET name=:name, type=:type, age=:age, description=:desc, image=:img WHERE id=:id";
                $conn->prepare($sql)->execute(['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'img' => $image, 'id' => $id]);
            } else {
                $sql = "UPDATE pets SET name=:name, type=:type, age=:age, description=:desc WHERE id=:id";
                $conn->prepare($sql)->execute(['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'id' => $id]);
            }
            $msg = "Pet updated successfully!";
            header("Refresh:1");
        } else {
            $error = "Unauthorized action.";
        }
    }

    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        if (empty($name) || empty($phone) || empty($address) || empty($password)) {
            $error = "All fields are required!";
        } elseif (strlen($phone) != 11) {
            $error = "Phone number must be 11 digits!";
        } else {
            $update_sql = "UPDATE users SET name=:name, phone=:phone, address=:address, password=:password WHERE email=:email";
            $stmt = $conn->prepare($update_sql);

            if ($stmt->execute(['name' => $name, 'phone' => $phone, 'address' => $address, 'password' => $password, 'email' => $email])) {
                $msg = "Profile updated successfully!";
                $_SESSION['user_name'] = $name;
                header("Refresh:1");
            } else {
                $error = "Error updating: " . $conn->errorInfo()[2];
            }
        }
    }
}


$stmt = $conn->prepare("SELECT COUNT(*) FROM adoption_request WHERE user_id=:user_id AND status='approved'");
$stmt->execute(['user_id' => $user_id]);
$adopt_count = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(*) FROM adoption_request WHERE user_id=:user_id AND status='pending'");
$stmt->execute(['user_id' => $user_id]);
$pending_count = $stmt->fetchColumn();


$pet_result = $conn->prepare("SELECT * FROM pets WHERE status = 'available' AND (owner_id IS NULL OR owner_id != :uid)");
$pet_result->execute(['uid' => $user_id]);
$available_pets = $pet_result->fetchAll(PDO::FETCH_ASSOC);


$hist_sql = "SELECT adoption_request.*, pets.name as pet_name, pets.image as pet_image 
                             FROM adoption_request 
                             JOIN pets ON adoption_request.pet_id = pets.id 
                             WHERE adoption_request.user_id = :user_id 
                             ORDER BY request_at DESC";
$stmt = $conn->prepare($hist_sql);
$stmt->execute(['user_id' => $user_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Incoming Request Actions (Approve/Reject)
if (isset($_GET['approve_request_user'])) {
    $req_id = $_GET['approve_request_user'];

    // Verify ownership
    $stmt = $conn->prepare("SELECT ar.*, p.owner_id FROM adoption_request ar JOIN pets p ON ar.pet_id = p.id WHERE ar.id = :id");
    $stmt->execute(['id' => $req_id]);
    $request = $stmt->fetch();

    if ($request && $request['owner_id'] == $user_id) {
        $pet_id = $request['pet_id'];
        $adopter_id = $request['user_id'];

        $conn->prepare("UPDATE adoption_request SET status='approved' WHERE id=:id")->execute(['id' => $req_id]);
        $conn->prepare("UPDATE pets SET status='adopted' WHERE id=:pet_id")->execute(['pet_id' => $pet_id]);

        // Add to adoption table
        $check = $conn->prepare("SELECT * FROM adoption WHERE pet_id=:pet_id");
        $check->execute(['pet_id' => $pet_id]);
        if ($check->rowCount() == 0) {
            $conn->prepare("INSERT INTO adoption (user_id, pet_id) VALUES (:uid, :pid)")->execute(['uid' => $adopter_id, 'pid' => $pet_id]);
        }

        // Reject others
        $conn->prepare("UPDATE adoption_request SET status='rejected' WHERE pet_id=:pet_id AND id!=:req_id AND status='pending'")->execute(['pet_id' => $pet_id, 'req_id' => $req_id]);

        $msg = "Request Approved!";
        header("Refresh:1; url=index.php?page=user"); // Redirect to clear GET
    }
}

if (isset($_GET['reject_request_user'])) {
    $req_id = $_GET['reject_request_user'];

    // Verify ownership
    $stmt = $conn->prepare("SELECT ar.*, p.owner_id FROM adoption_request ar JOIN pets p ON ar.pet_id = p.id WHERE ar.id = :id");
    $stmt->execute(['id' => $req_id]);
    $request = $stmt->fetch();

    if ($request && $request['owner_id'] == $user_id) {
        $conn->prepare("UPDATE adoption_request SET status='rejected' WHERE id=:id")->execute(['id' => $req_id]);
        $msg = "Request Rejected.";
        header("Refresh:1; url=index.php?page=user");
    }
}


// Fetch My Listed Pets
$my_pets = [];
$stmt = $conn->prepare("SELECT * FROM pets WHERE owner_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$my_pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Incoming Requests (For pets owned by this user)
$incoming_requests = [];
$inc_sql = "SELECT ar.*, u.name as requester_name, u.email as requester_email, p.name as pet_name 
            FROM adoption_request ar
            JOIN pets p ON ar.pet_id = p.id
            JOIN users u ON ar.user_id = u.id
            WHERE p.owner_id = :user_id AND ar.status = 'pending'
            ORDER BY ar.request_at DESC";
$stmt = $conn->prepare($inc_sql);
$stmt->execute(['user_id' => $user_id]);
$incoming_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'views/user.php';
?>