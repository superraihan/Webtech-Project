<!DOCTYPE html>
<html>
<head>
    <title>Register - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="log_regi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<?php
    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $cpass = $_POST['confirm_password'];

        // ভ্যালিডেশন চেক
        if (empty($name) || empty($email) || empty($pass)) {
            $error = "All fields are required!";
        } 
        elseif ($pass != $cpass) {
            $error = "Passwords do not match!";
        } 
        else {
            // ----- এই সেই DEMO লজিক -----
            // ডাটাবেস নেই, তাই আমরা ধরে নিচ্ছি রেজিস্ট্রেশন হয়ে গেছে
            $success = "Registration Successful! (You can now go to Login page)";
        }
    }
?>

<section class="log-regi-section">
    <div class="log-regi-container">
        <h2>Create Account</h2>
        
        <?php if($error){ echo "<div class='php-error'>$error</div>"; } ?>
        
        <?php if($success){ echo "<div style='color:#4caf50; background:rgba(76, 175, 80, 0.1); padding:10px; border-radius:5px; margin-bottom:15px; border:1px solid #4caf50;'>$success</div>"; } ?>

        <p id="js-error" class="error-msg"></p>

        <form action="" method="POST" onsubmit="return validateRegister()">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Create a password">
                    <span id="icon_reg" class="toggle-password" onclick="togglePassword('password', 'icon_reg')">🔒</span>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat password">
                    <span id="icon_confirm" class="toggle-password" onclick="togglePassword('confirm_password', 'icon_confirm')">🔒</span>
                </div>
            </div>

            <button type="submit" class="btn-submit">Register Now</button>
        </form>

        <p class="link-text">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="log_regi.js"></script>
</body>
</html>