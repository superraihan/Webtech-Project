<?php
include 'db_connect.php';

$conn->query("UPDATE pets SET status='available' WHERE status IS NULL OR status=''");

echo "Fixed! Empty status values have been updated to 'available'.";
echo "<br><a href='admin.php'>Go back to Admin Panel</a>";
?>
