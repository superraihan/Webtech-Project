<?php
require_once 'models/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$msg = "";
$error = "";


if (isset($_SESSION['flash_msg'])) {
    $msg = $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}


if (!is_dir('uploads')) {
    mkdir('uploads');
}


if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM adoption_request WHERE pet_id=:id");
    $stmt->execute(['id' => $id]);

    $stmt = $conn->prepare("DELETE FROM adoption WHERE pet_id=:id");
    $stmt->execute(['id' => $id]);

    $stmt = $conn->prepare("DELETE FROM pets WHERE id=:id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash_msg'] = "Pet deleted successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=:id");
    $stmt->execute(['id' => $user_id]);

    $_SESSION['flash_msg'] = "User deleted successfully!";
    header("Location: index.php?page=admin");
    exit();
}

// Handle Stats Update (MySQLi)
if (isset($_POST['update_stats'])) {
    $adopted = $mysqli->real_escape_string($_POST['stats_adopted']);
    $families = $mysqli->real_escape_string($_POST['stats_families']);
    $shelters = $mysqli->real_escape_string($_POST['stats_shelters']);

    $mysqli->query("UPDATE site_settings SET setting_value='$adopted' WHERE setting_key='stats_adopted'");
    $mysqli->query("UPDATE site_settings SET setting_value='$families' WHERE setting_key='stats_families'");
    $mysqli->query("UPDATE site_settings SET setting_value='$shelters' WHERE setting_key='stats_shelters'");

    $_SESSION['flash_msg'] = "Stats updated successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['approve_request'])) {
    $request_id = $_GET['approve_request'];

    $stmt = $conn->prepare("SELECT * FROM adoption_request WHERE id=:id");
    $stmt->execute(['id' => $request_id]);
    $request = $stmt->fetch();

    if ($request) {
        $pet_id = $request['pet_id'];
        $user_id = $request['user_id'];

        $upd = $conn->prepare("UPDATE adoption_request SET status='approved' WHERE id=:id");
        $upd->execute(['id' => $request_id]);

        $upd_pet = $conn->prepare("UPDATE pets SET status='adopted' WHERE id=:pet_id");
        $upd_pet->execute(['pet_id' => $pet_id]);

        $check = $conn->prepare("SELECT * FROM adoption WHERE pet_id=:pet_id");
        $check->execute(['pet_id' => $pet_id]);

        if ($check->rowCount() == 0) {
            $ins = $conn->prepare("INSERT INTO adoption (user_id, pet_id) VALUES (:user_id, :pet_id)");
            $ins->execute(['user_id' => $user_id, 'pet_id' => $pet_id]);
        }

        $rej = $conn->prepare("UPDATE adoption_request SET status='rejected' WHERE pet_id=:pet_id AND id!=:req_id AND status='pending'");
        $rej->execute(['pet_id' => $pet_id, 'req_id' => $request_id]);
    }

    $_SESSION['flash_msg'] = "Request approved successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['reject_request'])) {
    $request_id = $_GET['reject_request'];
    $stmt = $conn->prepare("UPDATE adoption_request SET status='rejected' WHERE id=:id");
    $stmt->execute(['id' => $request_id]);
    $_SESSION['flash_msg'] = "Request rejected.";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['delete_request'])) {
    $request_id = $_GET['delete_request'];
    $stmt = $conn->prepare("DELETE FROM adoption_request WHERE id=:id");
    $stmt->execute(['id' => $request_id]);
    $_SESSION['flash_msg'] = "Request record deleted.";
    header("Location: index.php?page=admin");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['add_pet'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];
        $status = $_POST['status'];

        if (empty($name) || empty($type) || $age < 0) {
            $error = "Please fill all fields correctly.";
        } else {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);

            $sql = "INSERT INTO pets (name, type, age, description, image, status) VALUES (:name, :type, :age, :desc, :image, :status)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute(['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'image' => $image, 'status' => $status])) {
                $_SESSION['flash_msg'] = "Pet added successfully!";
                header("Location: index.php?page=admin");
                exit();
            } else {
                $error = "Error adding pet: " . $conn->errorInfo()[2];
            }
        }
    }

    if (isset($_POST['update_pet'])) {
        $id = $_POST['pet_id'];

        // Check Ownership
        $check = $conn->prepare("SELECT owner_id FROM pets WHERE id=:id");
        $check->execute(['id' => $id]);
        $pet = $check->fetch();

        if ($pet && $pet['owner_id']) {
            $error = "Error: Cannot edit user-listed pets.";
        } else {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $age = $_POST['age'];
            $desc = $_POST['description'];
            $status = $_POST['status'];

            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $target = "uploads/" . basename($image);
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
                $sql = "UPDATE pets SET name=:name, type=:type, age=:age, description=:desc, image=:image, status=:status WHERE id=:id";
                $params = ['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'image' => $image, 'status' => $status, 'id' => $id];
            } else {
                $sql = "UPDATE pets SET name=:name, type=:type, age=:age, description=:desc, status=:status WHERE id=:id";
                $params = ['name' => $name, 'type' => $type, 'age' => $age, 'desc' => $desc, 'status' => $status, 'id' => $id];
            }

            $stmt = $conn->prepare($sql);
            if ($stmt->execute($params)) {
                $_SESSION['flash_msg'] = "Pet updated successfully!";
                header("Location: index.php?page=admin");
                exit();
            } else {
                $error = "Error updating pet: " . $conn->errorInfo()[2];
            }
        }
    }

    if (isset($_POST['update_admin'])) {
        $new_name = $_POST['admin_name'];
        $new_password = $_POST['admin_password'];
        $admin_email = $_SESSION['email'];

        if (empty($new_name) || empty($new_password)) {
            $error = "Name and Password cannot be empty.";
        } else {
            $stmt = $conn->prepare("UPDATE admins SET name=:name, password=:pass WHERE email=:email");
            if ($stmt->execute(['name' => $new_name, 'pass' => $new_password, 'email' => $admin_email])) {
                $_SESSION['admin_name'] = $new_name;
                $_SESSION['flash_msg'] = "Profile updated!";
                header("Location: index.php?page=admin");
                exit();
            } else {
                $error = "Error updating profile.";
            }
        }
    }

    if (isset($_POST['add_admin'])) {
        $new_admin_name = $_POST['new_admin_name'];
        $new_admin_email = $_POST['new_admin_email'];
        $new_admin_password = $_POST['new_admin_password'];

        if (empty($new_admin_name) || empty($new_admin_email) || empty($new_admin_password)) {
            $error = "All fields are required.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM admins WHERE email=:email");
            $stmt->execute(['email' => $new_admin_email]);
            if ($stmt->rowCount() == 0) {
                $sql = "INSERT INTO admins (name, email, password) VALUES (:name, :email, :pass)";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute(['name' => $new_admin_name, 'email' => $new_admin_email, 'pass' => $new_admin_password])) {
                    $_SESSION['flash_msg'] = "New Admin Added!";
                    header("Location: index.php?page=admin");
                    exit();
                } else {
                    $error = "Error adding admin.";
                }
            } else {
                $error = "Email already exists.";
            }
        }
    }
}


$users_list = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$pets_list = $conn->query("SELECT p.*, u.name as owner_name FROM pets p LEFT JOIN users u ON p.owner_id = u.id")->fetchAll(PDO::FETCH_ASSOC);

$requests_list = [];
$sql = "SELECT ar.*, u.name as user_name, u.email as user_email, p.name as pet_name, p.type as pet_type 
        FROM adoption_request ar 
        JOIN users u ON ar.user_id = u.id 
        JOIN pets p ON ar.pet_id = p.id 
        WHERE p.owner_id IS NULL OR ar.status != 'pending' 
        ORDER BY ar.request_at DESC";
$requests_list = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_admins = $conn->query("SELECT COUNT(*) FROM admins")->fetchColumn();
$available_pets_count = $conn->query("SELECT COUNT(*) FROM pets WHERE status='available'")->fetchColumn();
$pending_requests_count = $conn->query("SELECT COUNT(*) FROM adoption_request ar JOIN pets p ON ar.pet_id = p.id WHERE ar.status='pending' AND p.owner_id IS NULL")->fetchColumn();

$admin_email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE email=:email");
$stmt->execute(['email' => $admin_email]);
$admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

$settings = [];
$res = $mysqli->query("SELECT * FROM site_settings");
while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

require 'views/admin.php';
?>