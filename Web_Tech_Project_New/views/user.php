<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/user.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h3>My Dashboard</h3>
            <div class="sidebar-nav">
                <button onclick="showSection('dashboard', this)" class="nav-btn active">Dashboard</button>
                <button onclick="showSection('rehome', this)" class="nav-btn">Rehome a Pet</button>
                <button onclick="showSection('requests', this)" class="nav-btn">Requests</button>
                <button onclick="showSection('pets', this)" class="nav-btn">Adopt Pets</button>
                <button onclick="showSection('history', this)" class="nav-btn">History</button>
                <button onclick="showSection('profile', this)" class="nav-btn">Profile Settings</button>
            </div>
            <a href="index.php?page=logout" class="logout-link"><button class="sidebar-logout-btn">Logout</button></a>
        </div>

        <div class="main-content">

            <div id="requests" class="section-content">
                <h2>Requests</h2>
                <p>Requests from other users to adopt your pets.</p>
                <table>
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Requester</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($incoming_requests)) {
                            foreach ($incoming_requests as $req) {
                                echo "<tr>
                                    <td>" . $req['pet_name'] . "</td>
                                    <td>" . $req['requester_name'] . "</td>
                                    <td>" . $req['requester_email'] . "</td>
                                    <td>" . date('d M Y', strtotime($req['request_at'])) . "</td>
                                    <td>
                                        <div style='display: flex; gap: 10px; justify-content: center;'>
                                            <a href='index.php?page=user&approve_request_user=" . $req['id'] . "'><button class='btn-action approve' style='background: #28a745; color: white; padding: 5px 15px; border: none; border-radius: 5px; cursor: pointer;'>✓ Approve</button></a>
                                            <a href='index.php?page=user&reject_request_user=" . $req['id'] . "'><button class='btn-action reject' style='background: #dc3545; color: white; padding: 5px 15px; border: none; border-radius: 5px; cursor: pointer;'>✕ Reject</button></a>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No pending requests for your pets.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="rehome" class="section-content">
                <h2 id="rehomeTitle">Rehome a Pet</h2>
                <button id="cancelEdit"
                    style="display:none; background: #6c757d; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; margin-bottom: 15px;"
                    onclick="resetRehomeForm()">Cancel Edit</button>

                <form method="POST" action="" enctype="multipart/form-data" id="rehomeForm">
                    <input type="hidden" name="add_pet_user" value="1" id="action_input">
                    <input type="hidden" name="pet_id" id="edit_pet_id">

                    <div class="form-group">
                        <label>Pet Name</label>
                        <input type="text" name="name" id="p_name" required placeholder="e.g. Bella">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" id="p_type" required
                            style="width: 100%; padding: 10px; border-radius: 5px;">
                            <option value="Cat">Cat</option>
                            <option value="Dog">Dog</option>
                            <option value="Rabbit">Rabbit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Age (years)</label>
                        <input type="number" name="age" id="p_age" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="p_desc" rows="3"
                            placeholder="Tell us about the pet..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="image" id="p_image" accept="image/*">
                        <small id="img_hint" style="display:none; color: #aaa;">Leave blank to keep current
                            photo</small>
                    </div>
                    <button type="submit" class="btn-update" id="submitBtn">Submit Pet</button>
                </form>

                <script>
                    function editPetUser(pet) {
                        document.getElementById('rehomeTitle').innerText = "Edit Pet";
                        document.getElementById('action_input').name = "update_pet_user";
                        document.getElementById('edit_pet_id').value = pet.id;

                        document.getElementById('p_name').value = pet.name;
                        document.getElementById('p_type').value = pet.type;
                        document.getElementById('p_age').value = pet.age;
                        document.getElementById('p_desc').value = pet.description;

                        document.getElementById('p_image').required = false;
                        document.getElementById('img_hint').style.display = 'block';
                        document.getElementById('submitBtn').innerText = "Update Pet";
                        document.getElementById('cancelEdit').style.display = 'inline-block';

                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }

                    function resetRehomeForm() {
                        document.getElementById('rehomeTitle').innerText = "Rehome a Pet";
                        document.getElementById('action_input').name = "add_pet_user";
                        document.getElementById('edit_pet_id').value = '';
                        document.getElementById('rehomeForm').reset();

                        document.getElementById('p_image').required = true;
                        document.getElementById('img_hint').style.display = 'none';
                        document.getElementById('submitBtn').innerText = "Submit Pet";
                        document.getElementById('cancelEdit').style.display = 'none';
                    }
                </script>

                <br><br>
                <h2>My Listed Pets</h2>
                <div class="pets-grid">
                    <?php
                    if (!empty($my_pets)) {
                        foreach ($my_pets as $pet) {
                            $imagePath = !empty($pet['image']) ? "uploads/" . $pet['image'] : "views/assets/images/paw.png";
                            ?>
                            <div class="pet-card">
                                <img src="<?php echo $imagePath; ?>" class="pet-card-img">
                                <h4><?php echo $pet['name']; ?></h4>
                                <p>Status: <strong><?php echo ucfirst($pet['status']); ?></strong></p>
                                <div style="margin-top: 10px; display: flex; gap: 10px; justify-content: center;">
                                    <button class="btn-action edit" onclick='editPetUser(<?php echo json_encode($pet); ?>)'
                                        style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Edit</button>
                                    <a href="index.php?page=user&delete_pet_id=<?php echo $pet['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this pet?');"><button
                                            class="btn-action delete"
                                            style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Delete</button></a>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>You haven't listed any pets yet.</p>";
                    }
                    ?>
                </div>
            </div>

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