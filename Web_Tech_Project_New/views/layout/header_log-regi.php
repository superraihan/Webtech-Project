<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="nav">
    <?php if (isset($_SESSION['email'])): ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="index.php?page=admin" class="login profile-btn">My Profile</a>
        <?php else: ?>
            <a href="index.php?page=user" class="login profile-btn">My Profile</a>
        <?php endif; ?>
        <a href="index.php?page=logout" class="register logout-btn">Logout</a>
    <?php else: ?>
        <a href="index.php?page=login" class="login">Login</a>
        <a href="index.php?page=register" class="register">Register</a>
    <?php endif; ?>
</div>