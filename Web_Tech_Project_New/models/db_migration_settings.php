<?php
require_once 'models/db_connect.php';

// Create Table
$sql = "CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT
)";

if ($conn->query($sql)) {
    echo "Table 'site_settings' checked/created successfully.<br>";

    // Default Values
    $defaults = [
        'stats_adopted' => '5,000+',
        'stats_families' => '1,200+',
        'stats_shelters' => '50+'
    ];

    foreach ($defaults as $key => $val) {
        $check = $conn->prepare("SELECT * FROM site_settings WHERE setting_key=:key");
        $check->execute(['key' => $key]);
        if ($check->rowCount() == 0) {
            $insert_sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :val)";
            $stmt = $conn->prepare($insert_sql);
            if ($stmt->execute(['key' => $key, 'val' => $val])) {
                echo "Inserted default for $key.<br>";
            }
            else {
                echo "Error inserting $key: " . $conn->errorInfo()[2] . "<br>";
            }
        }
        else {
            echo "Setting $key already exists.<br>";
        }
    }
}
else {
    echo "Error creating table: " . $conn->errorInfo()[2];
}
?>