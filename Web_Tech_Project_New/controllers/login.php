<?php
require_once 'models/db_connect.php';


if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: index.php?page=admin");
    } else {
        header("Location: index.php?page=user");
    }
    exit();
}

$login_error = "";
$user_name = "";
$login_success = false;
$redirect_url = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $login_error = "Please fill all fields!";
    } else {
        // Check Users Table
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) { // Ideally verify_password() here
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'user';

            $user_name = $user['name'];
            $redirect_url = "index.php?page=user";
            $login_success = true;

        } else {
            // Check Admins Table
            $stmt = $conn->prepare("SELECT * FROM admins WHERE email=:email");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch();

            if ($admin && $admin['password'] === $password) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['role'] = 'admin';

                $user_name = $admin['name'] . " (Admin)";
                $redirect_url = "index.php?page=admin";
                $login_success = true;
            } else {
                $login_error = "Invalid Email or Password!";
            }
        }
    }
}

require 'views/login.php';
?>