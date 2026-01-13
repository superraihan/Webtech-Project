<?php 
session_start();
include 'db_connect.php'; 

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: user.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="log_regi.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div id="custom-popup" class="popup-overlay">
    <div class="popup-content">
        <div style="font-size: 50px; margin-bottom: 10px;">🎉</div>
        <h3>Login Successful!</h3>
        <p id="popup-msg">Welcome back!</p>
        <button onclick="redirectUser()" class="popup-btn">Go to Dashboard</button>
    </div>
</div>

<?php
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
                $redirect_url = "user.php";
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
                    $redirect_url = "admin.php";
                    $login_success = true;
                } else {
                    $login_error = "Invalid Email or Password!";
                }
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

<script>
    var targetUrl = "<?php echo $redirect_url; ?>";

    function redirectUser() {
        if(targetUrl) {
            window.location.href = targetUrl;
        }
    }

    <?php if($login_success): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var popup = document.getElementById('custom-popup');
        var msg = document.getElementById('popup-msg');
        msg.innerText = 'Welcome <?php echo $user_name; ?> 👋';
        popup.style.display = 'flex';

        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                redirectUser();
            }
        });
    });
    <?php endif; ?>
</script>

</body>
</html>