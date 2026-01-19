<?php
require_once 'models/db_connect.php';

$step = 1;
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['check_email'])) {
        $email = $_POST['email'];

        $check_user = "SELECT * FROM users WHERE email='$email'";
        $result_user = $conn->query($check_user);

        if ($result_user->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_table'] = 'users';
            $step = 2;
        } else {
            $check_admin = "SELECT * FROM admins WHERE email='$email'";
            $result_admin = $conn->query($check_admin);

            if ($result_admin->num_rows > 0) {
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_table'] = 'admins';
                $step = 2;
            } else {
                $error = "Email not found!";
            }
        }
    }

    if (isset($_POST['change_pass'])) {
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_new_password'];

        if (empty($new_pass) || empty($confirm_pass)) {
            $error = "Please fill all fields!";
            $step = 2;
        } elseif (strlen($new_pass) < 6) {
            $error = "Password must be at least 6 characters!";
            $step = 2;
        } elseif ($new_pass != $confirm_pass) {
            $error = "Passwords do not match!";
            $step = 2;
        } else {
            if (isset($_SESSION['reset_email']) && isset($_SESSION['reset_table'])) {

                $email_to_update = $_SESSION['reset_email'];
                $table_to_update = $_SESSION['reset_table'];

                $update_sql = "UPDATE $table_to_update SET password='$new_pass' WHERE email='$email_to_update'";

                if ($conn->query($update_sql) === TRUE) {
                    $step = 3;
                    $success = "✅ Password reset successfully !";

                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_table']);
                } else {
                    $error = "Error updating record: " . $conn->error;
                }
            } else {
                $error = "Session expired! Please try again.";
                $step = 1;
            }
        }
    }
}

require 'views/forgot_pass.php';
?>