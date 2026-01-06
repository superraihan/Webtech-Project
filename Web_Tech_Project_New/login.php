<!DOCTYPE html>
<html>
<head>
    <title>Login - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="log_regi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<?php
    $login_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $login_error = "Please fill all fields!";
        } else {
            if ($email == "admin@gmail.com" && $password == "1234") {
                echo "<script>alert('Login Successful!'); window.location.href='home.php';</script>";
            } else {
                $login_error = "Invalid Email or Password!";
            }
        }
    }
?>

<section class="log-regi-section">
    <div class="log-regi-container">
        <h2>Login</h2>

        <?php if($login_error){ echo "<div class='php-error'>$login_error</div>"; } ?>
        <p id="js-login-error" class="error-msg"></p>

        <form action="" method="POST" onsubmit="return validateLogin()">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="login_email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="login_pass" placeholder="Enter your password">
                    <span id="icon_login" class="toggle-password" onclick="togglePassword('login_pass', 'icon_login')">🔒</span>
                </div>
                <a href="forgot_pass.php" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="link-text">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="log_regi.js"></script>
</body>
</html>