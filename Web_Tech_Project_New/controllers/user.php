<?php
require_once 'models/db_connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=login");
    exit();
}

$email = $_SESSION['email'];
$msg = "";
$error = "";

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_id = $user['id'];

$my_requests = [];
$req_check = $conn->query("SELECT pet_id FROM adoption_request WHERE user_id='$user_id'");
if ($req_check->num_rows > 0) {
    while ($row = $req_check->fetch_assoc()) {
        $my_requests[] = $row['pet_id'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['request_adoption'])) {
        $pet_id = $_POST['pet_id'];

        if (!in_array($pet_id, $my_requests)) {
            $insert_sql = "INSERT INTO adoption_request (user_id, pet_id, status) VALUES ('$user_id', '$pet_id', 'pending')";
            if ($conn->query($insert_sql) === TRUE) {
                $msg = "Adoption request sent successfully!";
                header("Refresh:1");

            } else {
                $error = "Error: " . $conn->error;
            }
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
            $update_sql = "UPDATE users SET name='$name', phone='$phone', address='$address', password='$password' WHERE email='$email'";

            if ($conn->query($update_sql) === TRUE) {
                $msg = "Profile updated successfully!";
                $_SESSION['user_name'] = $name;
                header("Refresh:1");
            } else {
                $error = "Error updating: " . $conn->error;
            }
        }
    }
}


$adopt_count = $conn->query("SELECT * FROM adoption_request WHERE user_id='$user_id' AND status='approved'")->num_rows;
$pending_count = $conn->query("SELECT * FROM adoption_request WHERE user_id='$user_id' AND status='pending'")->num_rows;


$pet_result = $conn->query("SELECT * FROM pets WHERE status = 'available'");
$available_pets = [];
if ($pet_result->num_rows > 0) {
    while ($pet = $pet_result->fetch_assoc()) {
        $available_pets[] = $pet;
    }
}


$hist_result = $conn->query("SELECT adoption_request.*, pets.name as pet_name, pets.image as pet_image 
                             FROM adoption_request 
                             JOIN pets ON adoption_request.pet_id = pets.id 
                             WHERE adoption_request.user_id = '$user_id' 
                             ORDER BY request_at DESC");
$history = [];
if ($hist_result->num_rows > 0) {
    while ($row = $hist_result->fetch_assoc()) {
        $history[] = $row;
    }
}

require 'views/user.php';
?>