<?php
require_once 'models/db_connect.php';

$sql = "SELECT * FROM pets WHERE status = 'available' ORDER BY id DESC LIMIT 8";
$result = $conn->query($sql);
$pets = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
}

require 'views/home.php';
?>