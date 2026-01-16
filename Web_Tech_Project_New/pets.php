<?php
session_start();
include 'db_connect.php';

$filter_type = "";
$message = "";
$message_type = "";


if (isset($_POST['request_adoption']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $pet_id = $_POST['pet_id'];
    
    
    $check = $conn->query("SELECT * FROM adoption_request WHERE user_id=$user_id AND pet_id=$pet_id AND status='pending'");
    if ($check->num_rows > 0) {
        $message = "You already have a pending request for this pet.";
        $message_type = "error";
    } else {
        
        $sql = "INSERT INTO adoption_request (user_id, pet_id, status) VALUES ($user_id, $pet_id, 'pending')";
        if ($conn->query($sql)) {
            $message = "Adoption request submitted successfully! We'll review your application soon.";
            $message_type = "success";
        } else {
            $message = "Error submitting request. Please try again.";
            $message_type = "error";
        }
    }
}

$sql = "SELECT * FROM pets WHERE status = 'available'";

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $allowed_types = ['Cat', 'Dog', 'Rabbit'];
    
    if (in_array($type, $allowed_types)) {
        $filter_type = $type;
        $sql .= " AND type = '$filter_type'";
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Pets - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="pets.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<section class="pets-header">
    <h1>Find Your New <span>Best Friend</span></h1>
    <p>Browse through our available pets by category.</p>
    
    <?php if ($message): ?>
        <div class="alert <?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
</section>

<section class="filter-section">
    <div class="categories">
        <a href="pets.php" class="<?php echo ($filter_type == '') ? 'active' : ''; ?>">All</a>
        <a href="pets.php?type=Cat" class="<?php echo ($filter_type == 'Cat') ? 'active' : ''; ?>">Cats</a>
        <a href="pets.php?type=Dog" class="<?php echo ($filter_type == 'Dog') ? 'active' : ''; ?>">Dogs</a>
        <a href="pets.php?type=Rabbit" class="<?php echo ($filter_type == 'Rabbit') ? 'active' : ''; ?>">Rabbits</a>
    </div>
</section>

<section class="pets-display">
    <div class="pets-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['image']) ? "uploads/".$row['image'] : "paw.png";
                ?>
                <div class="pet-card">
                    <img src="<?php echo $imagePath; ?>" class="pet-img">
                    <div class="pet-info">
                        <h3><?php echo $row['name']; ?></h3>
                        <p class="type"><?php echo $row['type']; ?></p>
                        <p class="age">Age: <?php echo $row['age']; ?> years</p>
                        <p class="desc"><?php echo $row['description']; ?></p>
                        
                        <?php if(isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>
                            <form method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="request_adoption" class="adopt-btn">Request Adoption</button>
                            </form>
                        <?php elseif(!isset($_SESSION['email'])): ?>
                            <a href="login.php"><button class="adopt-btn">Login to Adopt</button></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='no-pets'>No pets found in this category.</p>";
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>