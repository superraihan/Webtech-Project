<?php
require_once 'models/db_connect.php';

try {
    // Check if column exists
    $check = $conn->query("SHOW COLUMNS FROM pets LIKE 'owner_id'");
    if ($check->rowCount() == 0) {
        // Add column
        $sql = "ALTER TABLE pets ADD COLUMN owner_id INT(11) DEFAULT NULL";
        $conn->exec($sql);
        echo "Column 'owner_id' added successfully.<br>";

        // Add foreign key if strictly enforcing, but for simplicity just indexing/logic checking is often enough in simple projects. 
        // Let's add an index for performance.
        $conn->exec("ALTER TABLE pets ADD INDEX (owner_id)");
        echo "Index added.<br>";
    } else {
        echo "Column 'owner_id' already exists.<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>