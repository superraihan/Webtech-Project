<?php
include 'db_connect.php';

$conn->query("UPDATE pets SET status='available' WHERE status IS NULL OR status=''");

$conn->query("UPDATE pets p 
              INNER JOIN adoption_request ar ON p.id = ar.pet_id 
              SET p.status = 'adopted' 
              WHERE ar.status = 'approved'");

echo "Fixed! Pet statuses have been synced with approved adoption requests.";
echo "<br><a href='admin.php'>Go back to Admin Panel</a>";
?>
