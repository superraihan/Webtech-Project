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
    $conn->query("DELETE FROM pets WHERE id=$id");
    $_SESSION['flash_msg'] = "Pet deleted successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $conn->query("DELETE FROM users WHERE id=$user_id");
    $_SESSION['flash_msg'] = "User deleted successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['approve_request'])) {
    $request_id = $_GET['approve_request'];

    $req_qry = $conn->query("SELECT * FROM adoption_request WHERE id='$request_id'");

    if ($req_qry && $req_qry->num_rows > 0) {
        $request = $req_qry->fetch_assoc();
        $pet_id = $request['pet_id'];
        $user_id = $request['user_id'];

        if (!$conn->query("UPDATE adoption_request SET status='approved' WHERE id='$request_id'")) {
            die("Error updating request status: " . $conn->error);
        }

        if (!$conn->query("UPDATE pets SET status='adopted' WHERE id='$pet_id'")) {
            die("Error updating pet status: " . $conn->error);
        }

        $check_adoption = $conn->query("SELECT * FROM adoption WHERE pet_id='$pet_id'");
        if ($check_adoption->num_rows == 0) {
            $insert_query = "INSERT INTO adoption (user_id, pet_id) VALUES ('$user_id', '$pet_id')";
            if (!$conn->query($insert_query)) {
                die("Error inserting into adoption table: " . $conn->error);
            }
        }

        $conn->query("UPDATE adoption_request SET status='rejected' WHERE pet_id='$pet_id' AND id!='$request_id' AND status='pending'");
    }

    $_SESSION['flash_msg'] = "Request approved successfully!";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['reject_request'])) {
    $request_id = $_GET['reject_request'];
    $conn->query("UPDATE adoption_request SET status='rejected' WHERE id='$request_id'");
    $_SESSION['flash_msg'] = "Request rejected.";
    header("Location: index.php?page=admin");
    exit();
}

if (isset($_GET['delete_request'])) {
    $request_id = $_GET['delete_request'];
    $conn->query("DELETE FROM adoption_request WHERE id='$request_id'");
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
            $sql = "INSERT INTO pets (name, type, age, description, image, status) VALUES ('$name', '$type', '$age', '$desc', '$image', '$status')";
            if ($conn->query($sql)) {
                $_SESSION['flash_msg'] = "Pet added successfully!";
                header("Location: index.php?page=admin");
                exit();
            } else {
                $error = "Error adding pet: " . $conn->error;
            }
        }
    }

    if (isset($_POST['update_pet'])) {
        $id = $_POST['pet_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];
        $status = $_POST['status'];

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $sql = "UPDATE pets SET name='$name', type='$type', age='$age', description='$desc', image='$image', status='$status' WHERE id=$id";
        } else {
            $sql = "UPDATE pets SET name='$name', type='$type', age='$age', description='$desc', status='$status' WHERE id=$id";
        }

        if ($conn->query($sql)) {
            $_SESSION['flash_msg'] = "Pet updated successfully!";
            header("Location: index.php?page=admin");
            exit();
        } else {
            $error = "Error updating pet: " . $conn->error;
        }
    }

    if (isset($_POST['update_admin'])) {
        $new_name = $_POST['admin_name'];
        $new_password = $_POST['admin_password'];
        $admin_email = $_SESSION['email'];

        if (empty($new_name) || empty($new_password)) {
            $error = "Name and Password cannot be empty.";
        } else {
            $sql = "UPDATE admins SET name='$new_name', password='$new_password' WHERE email='$admin_email'";
            if ($conn->query($sql)) {
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
            $check = $conn->query("SELECT * FROM admins WHERE email='$new_admin_email'");
            if ($check->num_rows == 0) {
                $sql = "INSERT INTO admins (name, email, password) VALUES ('$new_admin_name', '$new_admin_email', '$new_admin_password')";
                if ($conn->query($sql)) {
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


$users_list = [];
$res = $conn->query("SELECT * FROM users");
if ($res->num_rows > 0)
    while ($r = $res->fetch_assoc())
        $users_list[] = $r;

$pets_list = [];
$res = $conn->query("SELECT * FROM pets");
if ($res->num_rows > 0)
    while ($r = $res->fetch_assoc())
        $pets_list[] = $r;

$requests_list = [];
$sql = "SELECT ar.*, u.name as user_name, u.email as user_email, p.name as pet_name, p.type as pet_type 
        FROM adoption_request ar 
        JOIN users u ON ar.user_id = u.id 
        JOIN pets p ON ar.pet_id = p.id 
        ORDER BY ar.request_at DESC";
$res = $conn->query($sql);
if ($res->num_rows > 0)
    while ($r = $res->fetch_assoc())
        $requests_list[] = $r;

$total_users = $conn->query("SELECT * FROM users")->num_rows;
$total_admins = $conn->query("SELECT * FROM admins")->num_rows;
$available_pets_count = $conn->query("SELECT * FROM pets WHERE status='available'")->num_rows;
$pending_requests_count = $conn->query("SELECT * FROM adoption_request WHERE status='pending'")->num_rows;

    $admin_email = $_SESSION['email'];
    $admin_data = $conn->query("SELECT * FROM admins WHERE email='$admin_email'")->fetch_assoc();

require 'views/admin.php';
?>