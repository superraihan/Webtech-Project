<?php 
session_start();
include 'db_connect.php'; 

if (isset($_SESSION['email'])) {
    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="log_regi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            $sql = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                if ($password == $row['password']) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    $_SESSION['email'] = $row['email'];

                    // ✅ SweetAlert2 কোড আপডেট করা হলো
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Login Successful!',
                                text: 'Welcome " . $row['name'] . " 👋',
                                icon: 'success',
                                background: '#222',
                                color: '#fff',
                                confirmButtonColor: '#ff8c00',
                                confirmButtonText: 'Go to Profile',
                                allowOutsideClick: true // বাইরে ক্লিক করার অনুমতি দেওয়া হলো
                            }).then((result) => {
                                // শর্ত সরিয়ে দেওয়া হয়েছে। এখন বাটনে চাপ দিক বা বাইরে ক্লিক করুক—সব ক্ষেত্রেই রিডাইরেক্ট হবে।
                                window.location.href = 'user.php';
                            });
                        });
                    </script>";
                } else {
                    $login_error = "Incorrect Password!";
                }
            } else {
                $login_error = "Email not found! Please Register.";
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