<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$msg = "";
$error = "";

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_id = $user['id'];

$my_requests = [];
$req_check = $conn->query("SELECT pet_id FROM adoption_request WHERE user_id='$user_id'");
if ($req_check->num_rows > 0) {
    while($row = $req_check->fetch_assoc()) {
        $my_requests[] = $row['pet_id'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['request_adoption'])) {
        $pet_id = $_POST['pet_id'];
        
        if (in_array($pet_id, $my_requests)) {
        } else {
            $insert_sql = "INSERT INTO adoption_request (user_id, pet_id, status) VALUES ('$user_id', '$pet_id', 'pending')";
            if ($conn->query($insert_sql) === TRUE) {
                $msg = "Adoption request sent successfully!";
                header("Refresh:1");
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        if (empty($name) || empty($phone) || empty($address) || empty($password)) {
            $error = "All fields are required!";
        } elseif (strlen($phone) < 11) {
            $error = "Phone number must be at least 11 digits!";
        } else {
            $update_sql = "UPDATE users SET name='$name', phone='$phone', address='$address', password='$password' WHERE email='$email'";
            
            if ($conn->query($update_sql) === TRUE) {
                $msg = "Profile updated successfully!";
                $_SESSION['user_name'] = $name;
                header("Refresh:1");
            } else {
                $error = "Error updating: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="user.css?v=14">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h3>My Dashboard</h3>
            <button onclick="showSection('dashboard')" class="nav-btn active"> Dashboard</button>
            <button onclick="showSection('pets')" class="nav-btn"> Adopt Pets</button>
            <button onclick="showSection('history')" class="nav-btn"> History</button>
            <button onclick="showSection('profile')" class="nav-btn"> Profile Settings</button>
            <a href="logout.php"><button class="logout-btn"> Logout</button></a>
        </div>

        <div class="main-content">
            
            <div id="dashboard" class="section-content active">
                <h2>Overview</h2>
                <p>Welcome back, <strong><?php echo $user['name']; ?></strong>! Here is your activity summary.</p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php 
                            $adopt_count = $conn->query("SELECT * FROM adoption_request WHERE user_id='$user_id' AND status='approved'");
                            echo $adopt_count->num_rows; 
                        ?></h3>
                        <p>Pets Adopted</p>
                    </div>

                    <div class="stat-card">
                        <h3><?php 
                            $pending_count = $conn->query("SELECT * FROM adoption_request WHERE user_id='$user_id' AND status='pending'");
                            echo $pending_count->num_rows; 
                        ?></h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
            </div>

            <div id="pets" class="section-content">
                <h2>Available for Adoption</h2>
                <p>Find your new best friend here!</p>
                
                <?php if($msg) echo "<p class='success-msg'>$msg</p>"; ?>
                <div class="pets-grid">
                    <?php
                    $pet_sql = "SELECT * FROM pets WHERE status = 'available'";
                    $pet_result = $conn->query($pet_sql);

                    if ($pet_result->num_rows > 0) {
                        while($pet = $pet_result->fetch_assoc()) {
                            $imagePath = !empty($pet['image']) ? "uploads/".$pet['image'] : "paw.png";
                            $is_requested = in_array($pet['id'], $my_requests);
                            ?>
                            <div class="pet-card">
                                <img src="<?php echo $imagePath; ?>" class="pet-card-img">
                                <h4><?php echo $pet['name']; ?></h4>
                                <p>Age: <?php echo $pet['age']; ?></p>
                                <p class="pet-desc"><?php echo $pet['description']; ?></p>
                                
                                <?php if($is_requested): ?>
                                    <button class="btn-adopt" style="background-color: #555; cursor: not-allowed;" disabled>Request Pending</button>
                                <?php else: ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                        <button type="submit" name="request_adoption" class="btn-adopt">Request Adoption</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No pets available for adoption right now.</p>";
                    }
                    ?>
                </div>
            </div>

            <div id="history" class="section-content">
                <h2>Adoption History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Pet Name</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $hist_sql = "SELECT adoption_request.*, pets.name as pet_name, pets.image as pet_image 
                                     FROM adoption_request 
                                     JOIN pets ON adoption_request.pet_id = pets.id 
                                     WHERE adoption_request.user_id = '$user_id' 
                                     ORDER BY request_at DESC";
                        
                        $hist_result = $conn->query($hist_sql);

                        if ($hist_result->num_rows > 0) {
                            while($row = $hist_result->fetch_assoc()) {
                                $statusClass = "status-pending";
                                if($row['status'] == 'approved') $statusClass = "status-approved";
                                if($row['status'] == 'rejected') $statusClass = "status-rejected";
                                
                                $petImg = !empty($row['pet_image']) ? "uploads/".$row['pet_image'] : "paw.png";

                                echo "<tr>
                                    <td><img src='$petImg' class='history-img'></td>
                                    <td>".$row['pet_name']."</td>
                                    <td>".date('d M Y', strtotime($row['request_at']))."</td>
                                    <td class='$statusClass'>".ucfirst($row['status'])."</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No adoption history found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="profile" class="section-content">
                <h2>Profile Settings</h2>
                
                <form method="POST" action="" onsubmit="return validateProfile()">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Email (Cannot be changed)</label>
                        <input type="email" value="<?php echo $user['email']; ?>" disabled style="opacity: 0.6;">
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="phone" value="<?php echo $user['phone']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" id="address" value="<?php echo $user['address']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password" id="password" value="<?php echo $user['password']; ?>">
                    </div>

                    <button type="submit" name="update_profile" class="btn-update">Save Changes</button>
                </form>
            </div>

        </div>
    </div>

    <script src="user.js?v=14"></script>
</body>
</html>