<?php
require_once 'models/db_connect.php';

echo "<h2>Starting Password Hashing Migration...</h2>";

// Hash Admin Passwords
echo "<h3>Processing Admins</h3>";
$admins = $conn->query("SELECT id, password FROM admins")->fetchAll();
$admin_updated = 0;

foreach ($admins as $admin) {
    $current_pass = $admin['password'];
    // Check if it's already a hash (bcrypt hashes usually start with $2y$)
    if (strpos($current_pass, '$2y$') !== 0) {
        $hashed = password_hash($current_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET password = :hash WHERE id = :id");
        $stmt->execute(['hash' => $hashed, 'id' => $admin['id']]);
        $admin_updated++;
    }
}
echo "<p>Successfully hashed $admin_updated admin passwords.</p>";

// Hash User Passwords
echo "<h3>Processing Users</h3>";
$users = $conn->query("SELECT id, password FROM users")->fetchAll();
$user_updated = 0;

foreach ($users as $user) {
    $current_pass = $user['password'];
    // Check if it's already a hash (bcrypt hashes usually start with $2y$)
    if (strpos($current_pass, '$2y$') !== 0) {
        $hashed = password_hash($current_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = :hash WHERE id = :id");
        $stmt->execute(['hash' => $hashed, 'id' => $user['id']]);
        $user_updated++;
    }
}
echo "<p>Successfully hashed $user_updated user passwords.</p>";

echo "<h2>✅ Migration Complete! You may now safely delete this file.</h2>";

?>
