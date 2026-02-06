<?php
require_once 'models/db_connect.php';

$filter_type = "";
$message = "";
$message_type = "";

if (isset($_POST['request_adoption']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $pet_id = $_POST['pet_id'];
    $response = [];

    // Check existing request
    $stmt = $conn->prepare("SELECT * FROM adoption_request WHERE user_id=:user_id AND pet_id=:pet_id AND status='pending'");
    $stmt->execute(['user_id' => $user_id, 'pet_id' => $pet_id]);

    if ($stmt->rowCount() > 0) {
        $response['status'] = 'error';
        $response['message'] = "You already have a pending request for this pet.";
    } else {
        $stmt = $conn->prepare("INSERT INTO adoption_request (user_id, pet_id, status) VALUES (:user_id, :pet_id, 'pending')");
        if ($stmt->execute(['user_id' => $user_id, 'pet_id' => $pet_id])) {
            $response['status'] = 'success';
            $response['message'] = "Adoption request submitted successfully! We'll review your application soon.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error submitting request. Please try again.";
        }
    }

    if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
        echo json_encode($response);
        exit;
    } else {
        $message = $response['message'];
        $message_type = $response['status'];
    }
}

$sql = "SELECT * FROM pets WHERE status = 'available'";
$params = [];

if (isset($_SESSION['user_id'])) {
    $sql .= " AND (owner_id IS NULL OR owner_id != :uid)";
    $params['uid'] = $_SESSION['user_id'];
}

// Handle Search and Filter
if (isset($_GET['ajax_filter']) || isset($_GET['type'])) {
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $allowed_types = ['Cat', 'Dog', 'Rabbit'];

    if (!empty($type) && in_array($type, $allowed_types)) {
        $sql .= " AND type = :type";
        $params['type'] = $type;
    }
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return only pet grid for AJAX filter requests
if (isset($_GET['ajax_filter'])) {
    if (!empty($pets)) {
        foreach ($pets as $row) {
            $imagePath = !empty($row['image']) ? "uploads/" . $row['image'] : "views/assets/images/paw.png";
            ?>
            <div class="pet-card">
                <img src="<?php echo $imagePath; ?>" class="pet-img">
                <div class="pet-info">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="type"><?php echo $row['type']; ?></p>
                    <p class="age">Age: <?php echo $row['age']; ?> years</p>
                    <p class="desc"><?php echo $row['description']; ?></p>

                    <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>
                        <form method="POST" class="adopt-form" onsubmit="return requestAdoption(event, this)">
                            <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="request_adoption" value="1">
                            <button type="submit" class="adopt-btn">Request Adoption</button>
                        </form>
                    <?php elseif (!isset($_SESSION['email'])): ?>
                        <a href="index.php?page=login"><button class="adopt-btn">Login to Adopt</button></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='no-pets'>No pets found matching your criteria.</p>";
    }
    exit;
}

require 'views/pets.php';
?>