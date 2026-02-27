<?php
require_once 'models/db_connect.php';

$sql = "SELECT * FROM pets WHERE status = 'available' ORDER BY id DESC LIMIT 8";
$stmt = $conn->query($sql);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Stats (PDO)
$stats = [
    'stats_adopted' => '5,000+',
    'stats_families' => '1,200+',
    'stats_shelters' => '50+'
];

$res = $conn->query("SELECT * FROM site_settings");
if ($res) {
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['setting_key']] = $row['setting_value'];
    }
}

require 'views/home.php';
?>