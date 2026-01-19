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
        $sql_user = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result_user = $conn->query($sql_user);

        if ($result_user->num_rows == 1) {
            $row = $result_user->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = 'user';

            $user_name = $row['name'];
            $redirect_url = "index.php?page=user";
            $login_success = true;

        } else {
            $sql_admin = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
            $result_admin = $conn->query($sql_admin);

            if ($result_admin->num_rows == 1) {
                $row = $result_admin->fetch_assoc();
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = 'admin';

                $user_name = $row['name'] . " (Admin)";
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