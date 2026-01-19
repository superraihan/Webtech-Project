<!DOCTYPE html>
<html>

<head>
    <title>Login - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/log_regi.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <div id="custom-popup" class="popup-overlay">
        <div class="popup-content">
            <div class="congrats-icon">🎉</div>
            <h3>Login Successful!</h3>
            <p id="popup-msg">Welcome back!</p>
            <button class="popup-btn">Go to Dashboard</button>
        </div>
    </div>

    <section class="log-regi-section">
        <div class="log-regi-container">
            <h2>Login</h2>

            <?php if ($login_error) {
                echo "<div class='php-error'>$login_error</div>";
            } ?>
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
                        <span id="icon_login" class="toggle-password"
                            onclick="togglePassword('login_pass', 'icon_login')">🔒</span>
                    </div>
                    <a href="index.php?page=forgot_pass" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <p class="link-text">Don't have an account? <a href="index.php?page=register">Register here</a></p>
        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>

    <?php if ($login_success): ?>
        <div id="login-success-data" data-redirect="<?php echo $redirect_url; ?>" data-username="<?php echo $user_name; ?>"
            class="hidden"></div>
    <?php endif; ?>

    <script src="views/assets/js/log_regi.js?v=4"></script>

</body>

</html>