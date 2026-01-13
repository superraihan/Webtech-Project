<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="nav">
    <?php if (isset($_SESSION['email'])): ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="login profile-btn">My Profile</a>
        <?php else: ?>
            <a href="user.php" class="login profile-btn">My Profile</a>
        <?php endif; ?>
        <a href="logout.php" class="register logout-btn">Logout</a>
    <?php else: ?>
        <a href="login.php" class="login">Login</a>
        <a href="register.php" class="register">Register</a>
    <?php endif; ?>
</div>