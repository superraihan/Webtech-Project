<!DOCTYPE html>
<html>

<head>
    <title>Reset Password - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/log_regi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <section class="log-regi-section">
        <div class="log-regi-container">

            <?php if ($step == 1): ?>
                <h2>Find Account</h2>
                <p class="info-text">Enter your registered email to search.</p>

                <?php if ($error) {
                    echo "<div class='php-error'>$error</div>";
                } ?>
                <p id="js-forgot-error" class="error-msg"></p>

                <form action="index.php?page=forgot_pass" method="POST" onsubmit="return validateForgot()">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="forgot_email" placeholder="Enter your email">
                    </div>
                    <button type="submit" name="check_email" class="btn-submit">Search</button>
                </form>

                <p class="link-text"><a href="index.php?page=login">← Back to Login</a></p>

            <?php elseif ($step == 2): ?>
                <h2>Reset Password</h2>
                <p class="verified-text">
                    Account found! Please enter your new password below.
                </p>

                <?php if ($error) {
                    echo "<div class='php-error'>$error</div>";
                } ?>
                <p id="js-newpass-error" class="error-msg"></p>

                <form action="index.php?page=forgot_pass" method="POST" onsubmit="return validateNewPass()">
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="new_password" id="new_pass" placeholder="Enter new password">
                            <span id="icon_new" class="toggle-password"
                                onclick="togglePassword('new_pass', 'icon_new')">🔒</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_new_password" id="confirm_new_pass"
                                placeholder="Confirm new password">
                            <span id="icon_confirm_new" class="toggle-password"
                                onclick="togglePassword('confirm_new_pass', 'icon_confirm_new')">🔒</span>
                        </div>
                    </div>

                    <button type="submit" name="change_pass" class="btn-submit">Update Password</button>
                </form>
                <p class="link-text cancel-link">
                    <a href="index.php?page=home">← Back to Home</a>
                </p>
            <?php elseif ($step == 3): ?>
                <h2>Success!</h2>
                <div class="success-icon">🎉</div>
                <div class="success-box">
                    <?php echo $success; ?>
                </div>
                <a href="index.php?page=login" class="btn-submit btn-link">Go to Login</a>
            <?php endif; ?>

        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>
    <script src="views/assets/js/log_regi.js"></script>
</body>

</html>