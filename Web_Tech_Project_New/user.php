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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
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

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="user.css?v=3">
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
                        <h3>0</h3>
                        <p>Pets Adopted</p>
                    </div>
                    <div class="stat-card">
                        <h3>0</h3>
                        <p>Pending Requests</p>
                    </div>
                    <div class="stat-card">
                        <h3>0</h3>
                        <p>Total Adoptions</p>
                    </div>
                </div>
            </div>

            <div id="pets" class="section-content">
                <h2>Available for Adoption</h2>
                <p>Find your new best friend here!</p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <img src="paw.png" width="50">
                        <h4>Golden Retriever</h4>
                        <p>Age: 2 Years</p>
                        <button class="btn-update" style="margin-top: 5px;">Request Adoption</button>
                    </div>
                    <div class="stat-card">
                        <img src="paw.png" width="50">
                        <h4>Persian Cat</h4>
                        <p>Age: 1 Year</p>
                        <button class="btn-update" style="margin-top: 5px;">Request Adoption</button>
                    </div>
                </div>
            </div>

            <div id="history" class="section-content">
                <h2>Adoption History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No history found.</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="profile" class="section-content">
                <h2>Profile Settings</h2>
                
                <?php if($msg) echo "<p class='success-msg'>$msg</p>"; ?>
                <?php if($error) echo "<p class='error-msg'>$error</p>"; ?>
                <p id="js-error" class="error-msg" style="display:none;"></p>

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

    <?php include 'footer.php'; ?>
    <script src="user.js?v=3"></script>
</body>
</html>