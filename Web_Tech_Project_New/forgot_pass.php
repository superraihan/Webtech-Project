<?php 
session_start();
include 'db_connect.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="log_regi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<?php
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
            } 
            elseif (strlen($new_pass) < 6) {
                $error = "Password must be at least 6 characters!";
                $step = 2;
            }
            elseif ($new_pass != $confirm_pass) {
                $error = "Passwords do not match!";
                $step = 2;
            } 
            else {
                if(isset($_SESSION['reset_email']) && isset($_SESSION['reset_table'])){
                    
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
?>

<section class="log-regi-section">
    <div class="log-regi-container">
        
        <?php if ($step == 1): ?>
            <h2>Find Account</h2>
            <p class="info-text">Enter your registered email to search.</p>

            <?php if($error){ echo "<div class='php-error'>$error</div>"; } ?>
            <p id="js-forgot-error" class="error-msg"></p>

            <form action="" method="POST" onsubmit="return validateForgot()">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="forgot_email" placeholder="Enter your email">
                </div>
                <button type="submit" name="check_email" class="btn-submit">Search</button>
            </form>
            
            <p class="link-text"><a href="login.php">← Back to Login</a></p>

        <?php elseif ($step == 2): ?>
            <h2>Reset Password</h2>
            <p class="verified-text">
                Account found! Please enter your new password below.
            </p>

            <?php if($error){ echo "<div class='php-error'>$error</div>"; } ?>
            <p id="js-newpass-error" class="error-msg"></p>

            <form action="" method="POST" onsubmit="return validateNewPass()">
                <div class="form-group">
                    <label>New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="new_password" id="new_pass" placeholder="Enter new password">
                        <span id="icon_new" class="toggle-password" onclick="togglePassword('new_pass', 'icon_new')">🔒</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirm_new_password" id="confirm_new_pass" placeholder="Confirm new password">
                        <span id="icon_confirm_new" class="toggle-password" onclick="togglePassword('confirm_new_pass', 'icon_confirm_new')">🔒</span>
                    </div>
                </div>

                <button type="submit" name="change_pass" class="btn-submit">Update Password</button>
            </form>
            <p class="link-text cancel-link">
                <a href="home.php">← Back to Home</a>
            </p>
        <?php elseif ($step == 3): ?>
            <h2>Success!</h2>
            <div class="success-icon">🎉</div>
            <div class="success-box"><?php echo $success; ?></div>
            <a href="login.php" class="btn-submit btn-link">Go to Login</a>
        <?php endif; ?>

    </div>
</section>

<?php include 'footer.php'; ?>
<script src="log_regi.js"></script>
</body>
</html>