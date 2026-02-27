<?php
require_once 'models/db_connect.php';

$sql = "ALTER TABLE pets ADD COLUMN owner_id INT NULL DEFAULT NULL AFTER id";

if ($mysqli->query($sql) === TRUE) {
    echo "Column 'owner_id' added successfully.<br>";

    // Add foreign key constraint
    $fk_sql = "ALTER TABLE pets ADD CONSTRAINT fk_pets_owner FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE SET NULL";
    if ($mysqli->query($fk_sql) === TRUE) {
        echo "Foreign key constraint added successfully.<br>";
    }
    else {
        echo "Error adding foreign key constraint: " . $mysqli->error . "<br>";
    }
}
else {
    echo "Error adding column or it already exists: " . $mysqli->error . "<br>";
}
?>
