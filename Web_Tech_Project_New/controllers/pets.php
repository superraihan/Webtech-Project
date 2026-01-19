<?php
require_once 'models/db_connect.php';

$filter_type = "";
$message = "";
$message_type = "";

if (isset($_POST['request_adoption']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $pet_id = $_POST['pet_id'];

    $check = $conn->query("SELECT * FROM adoption_request WHERE user_id=$user_id AND pet_id=$pet_id AND status='pending'");
    if ($check->num_rows > 0) {
        $message = "You already have a pending request for this pet.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO adoption_request (user_id, pet_id, status) VALUES ($user_id, $pet_id, 'pending')";
        if ($conn->query($sql)) {
            $message = "Adoption request submitted successfully! We'll review your application soon.";
            $message_type = "success";
        } else {
            $message = "Error submitting request. Please try again.";
            $message_type = "error";
        }
    }
}

$sql = "SELECT * FROM pets WHERE status = 'available'";

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $allowed_types = ['Cat', 'Dog', 'Rabbit'];

    if (in_array($type, $allowed_types)) {
        $filter_type = $type;
        $sql .= " AND type = '$filter_type'";
    }
}

$result = $conn->query($sql);
$pets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
}

require 'views/pets.php';
?>