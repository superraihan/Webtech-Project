<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/user.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h3>My Dashboard</h3>
            <button onclick="showSection('dashboard')" class="nav-btn active"> Dashboard</button>
            <button onclick="showSection('pets')" class="nav-btn"> Adopt Pets</button>
            <button onclick="showSection('history')" class="nav-btn"> History</button>
            <button onclick="showSection('profile')" class="nav-btn"> Profile Settings</button>
            <a href="index.php?page=logout"><button class="logout-btn"> Logout</button></a>
        </div>

        <div class="main-content">

            <div id="dashboard" class="section-content active">
                <h2>Overview</h2>
                <p>Welcome back, <strong>
                        <?php echo $user['name']; ?></strong>
                    ! Here is your activity summary.</p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>
                            <?php echo $adopt_count; ?>
                        </h3>
                        <p>Pets Adopted</p>
                    </div>

                    <div class="stat-card">
                        <h3>
                            <?php echo $pending_count; ?>
                        </h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
            </div>

            <div id="pets" class="section-content">
                <h2>Available for Adoption</h2>
                <p>Find your new best friend here!</p>

                <?php if ($msg)
                    echo "<p class='success-msg'>$msg</p>"; ?>
                <div class="pets-grid">
                    <?php
                    if (!empty($available_pets)) {
                        foreach ($available_pets as $pet) {
                            $imagePath = !empty($pet['image']) ? "uploads/" . $pet['image'] : "views/assets/images/paw.png";
                            $is_requested = in_array($pet['id'], $my_requests);
                            ?>
                            <div class="pet-card">
                                <img src="<?php echo $imagePath; ?>" class="pet-card-img">
                                <h4>
                                    <?php echo $pet['name']; ?>
                                </h4>
                                <p>Age:
                                    <?php echo $pet['age']; ?>
                                </p>
                                <p class="pet-desc">
                                    <?php echo $pet['description']; ?>
                                </p>

                                <?php if ($is_requested): ?>
                                    <button class="btn-adopt disabled" disabled>Request
                                        Pending</button>
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
                        if (!empty($history)) {
                            foreach ($history as $row) {
                                $statusClass = "status-pending";
                                if ($row['status'] == 'approved')
                                    $statusClass = "status-approved";
                                if ($row['status'] == 'rejected')
                                    $statusClass = "status-rejected";

                                $petImg = !empty($row['pet_image']) ? "uploads/" . $row['pet_image'] : "views/assets/images/paw.png";

                                echo "<tr>
                                    <td><img src='$petImg' class='history-img'></td>
                                    <td>" . $row['pet_name'] . "</td>
                                    <td>" . date('d M Y', strtotime($row['request_at'])) . "</td>
                                    <td class='$statusClass'>" . ucfirst($row['status']) . "</td>
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
                        <input type="email" value="<?php echo $user['email']; ?>" disabled>
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

    <script src="views/assets/js/user.js?v=14"></script>
</body>

</html>