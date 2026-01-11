<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="nav">
    <?php if (isset($_SESSION['email'])): ?>
        <a href="user.php" class="login" style="background: transparent; border: 1px solid #ff9100; color: #ff9100;">My Profile</a>
        <a href="logout.php" class="register" style="background-color: #d9534f; border: none;">Logout</a>
    <?php else: ?>
        <a href="login.php" class="login">Login</a>
        <a href="register.php" class="register">Register</a>
    <?php endif; ?>
</div>