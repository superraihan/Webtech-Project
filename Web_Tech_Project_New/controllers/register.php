<?php
require_once 'models/db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($pass)) {
        $error = "All fields are required!";
    } elseif ($pass != $cpass) {
        $error = "Passwords do not match!";
    } elseif (strlen($phone) != 11) {
        $error = "Phone number must be 11 digits!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already exists! Please Login.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, address, password) VALUES (:name, :email, :phone, :address, :pass)");

            if ($stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, 'pass' => $pass])) {
                $success = "Registration Successful! You can now go to Login page.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

require 'views/register.php';
?>