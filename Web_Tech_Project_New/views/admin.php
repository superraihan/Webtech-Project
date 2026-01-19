<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/admin.css?v=23">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <div class="sidebar-nav">
                <button onclick="showSection('dashboard')" class="nav-btn active">Dashboard</button>
                <button onclick="showSection('users')" class="nav-btn">Users List</button>
                <button onclick="showSection('manage_pets')" class="nav-btn">Manage Pets</button>
                <button onclick="showSection('adoptions')" class="nav-btn">Adoptions</button>
                <button onclick="showSection('settings')" class="nav-btn">Settings</button>
            </div>
            <a href="index.php?page=logout" class="logout-link"><button class="sidebar-logout-btn">Logout</button></a>
        </div>

        <div class="main-content">

            <?php if ($msg)
                echo "<div class='msg-box msg-success'>$msg</div>"; ?>
            <?php if ($error)
                echo "<div class='msg-box msg-error'>$error</div>"; ?>

            <div id="dashboard" class="section-content active">
                <h2>Admin Overview</h2>
                <p>Welcome, <strong>
                        <?php echo $admin_name; ?>
                    </strong>!</p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>
                            <?php echo $total_users; ?>
                        </h3>
                        <p>Total Users</p>
                    </div>
                    <div class="stat-card">
                        <h3>
                            <?php echo $total_admins; ?>
                        </h3>
                        <p>Total Admins</p>
                    </div>
                    <div class="stat-card">
                        <h3>
                            <?php echo $available_pets_count; ?>
                        </h3>
                        <p>Available Pets</p>
                    </div>
                    <div class="stat-card">
                        <h3>
                            <?php echo $pending_requests_count; ?>
                        </h3>
                        <p>Pending Adoptions</p>
                    </div>
                </div>
            </div>

            <div id="users" class="section-content">
                <h2>All Registered Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($users_list)) {
                            foreach ($users_list as $row) {
                                echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["email"] . "</td>
                                    <td><a href='javascript:void(0)' onclick=\"confirmAction('index.php?page=admin&delete_user_id=" . $row['id'] . "', 'Are you sure you want to delete this user? This cannot be undone.', 'delete')\"><button class='btn-delete'>Delete</button></a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="manage_pets" class="section-content">
                <h2>Manage Pets</h2>
                <button class="btn-update" onclick="openModal()">➕ Add New Pet</button>

                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($pets_list)) {
                            foreach ($pets_list as $row) {
                                $status = strtolower(trim($row['status']));
                                if ($status == 'available')
                                    $statusClass = 'status-available';
                                elseif ($status == 'pending')
                                    $statusClass = 'status-pending';
                                elseif ($status == 'adopted')
                                    $statusClass = 'status-adopted';
                                else
                                    $statusClass = 'status-unknown';

                                $statusText = !empty($status) ? ucfirst($status) : 'Unknown';

                                echo "<tr>
                                    <td><img src='uploads/" . $row['image'] . "' class='pet-thumb'></td>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $row['type'] . "</td>
                                    <td>" . $row['age'] . " yrs</td>
                                    <td><span class='status-badge " . $statusClass . "'>" . $statusText . "</span></td>
                                    <td>
                                        <button class='btn-edit' onclick='editPet(" . json_encode($row) . ")'>Edit</button>
                                        <a href='javascript:void(0)' onclick=\"confirmAction('index.php?page=admin&delete_id=" . $row['id'] . "', 'Are you sure you want to delete this pet?', 'delete')\"><button class='btn-delete'>Delete</button></a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No pets added yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="adoptions" class="section-content">
                <h2>Adoption Requests</h2>

                <div class="filter-tabs">
                    <button class="filter-btn active" onclick="filterRequests('all')">All</button>
                    <button class="filter-btn" onclick="filterRequests('pending')">Pending</button>
                    <button class="filter-btn" onclick="filterRequests('approved')">Approved</button>
                    <button class="filter-btn" onclick="filterRequests('rejected')">Rejected</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Pet</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($requests_list)) {
                            foreach ($requests_list as $row) {
                                $statusClass = '';
                                if ($row['status'] == 'pending')
                                    $statusClass = 'status-pending';
                                elseif ($row['status'] == 'approved')
                                    $statusClass = 'status-available';
                                else
                                    $statusClass = 'status-rejected';

                                echo "<tr data-status='" . $row['status'] . "'>
                                    <td>" . $row['id'] . "</td>
                                    <td>" . $row['user_name'] . "<br><small class='sub-text'>" . $row['user_email'] . "</small></td>
                                    <td>" . $row['pet_name'] . " <small class='sub-text'>(" . $row['pet_type'] . ")</small></td>
                                    <td>" . date('M d, Y', strtotime($row['request_at'])) . "</td>
                                    <td><span class='status-badge " . $statusClass . "'>" . ucfirst($row['status']) . "</span></td>
                                    <td>";

                                if ($row['status'] == 'pending') {
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('index.php?page=admin&approve_request=" . $row['id'] . "', 'Do you want to Approve this request?', 'approve')\" class='btn-approve'>Approve</a> ";
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('index.php?page=admin&reject_request=" . $row['id'] . "', 'Do you want to Reject this request?', 'reject')\" class='btn-reject'>Reject</a>";
                                } else {
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('index.php?page=admin&delete_request=" . $row['id'] . "', 'Delete this request record?', 'delete')\" class='btn-delete-small'>Delete</a>";
                                }

                                echo "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='no-data'>No adoption requests yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="settings" class="section-content">
                <h2>Admin Settings</h2>
                <div class="settings-container">
                    <div class="settings-box">
                        <h3>Update Your Profile</h3>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Your Name</label>
                                <input type="text" name="admin_name" value="<?php echo $admin_data['name']; ?>"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Your Password</label>
                                <input type="text" name="admin_password" value="<?php echo $admin_data['password']; ?>"
                                    required>
                            </div>
                            <button type="submit" name="update_admin" class="btn-update">Update Profile</button>
                        </form>
                    </div>

                    <div class="settings-box">
                        <h3>Add New Admin</h3>
                        <form action="" method="POST" onsubmit="return validateAdminForm()">
                            <div class="form-group">
                                <label>Admin Name</label>
                                <input type="text" name="new_admin_name" id="new_admin_name" placeholder="Enter name">
                            </div>
                            <div class="form-group">
                                <label>Admin Email</label>
                                <input type="email" name="new_admin_email" id="new_admin_email"
                                    placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label>Admin Password</label>
                                <input type="text" name="new_admin_password" id="new_admin_password"
                                    placeholder="Enter password">
                            </div>
                            <p id="admin-error" class="error-text"></p>
                            <button type="submit" name="add_admin" class="btn-update">Add Admin</button>
                        </form>
                    </div>
                </div>



            </div>

        </div>
    </div>

    <div id="petModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Add New Pet</h3>
            <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validatePetForm()">
                <input type="hidden" name="pet_id" id="pet_id">
                <div class="form-group">
                    <label>Pet Name</label>
                    <input type="text" name="name" id="p_name" placeholder="Enter pet name">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" id="p_type" placeholder="Dog, Cat, Bird, etc.">
                </div>
                <div class="form-group">
                    <label>Age (Years)</label>
                    <input type="number" name="age" id="p_age" placeholder="Enter age in years" min="0">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="p_desc" rows="3"
                        placeholder="Describe the pet's personality..."></textarea>
                </div>
                <div class="form-group">
                    <label>Pet Image</label>
                    <input type="file" name="image" id="p_image" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="p_status">
                        <option value="available">Available</option>
                        <option value="pending">Pending</option>
                        <option value="adopted">Adopted</option>
                    </select>
                </div>
                <p id="pet-error" class="error-text"></p>
                <button type="submit" name="add_pet" id="addBtn" class="btn-update">Add Pet</button>
                <button type="submit" name="update_pet" id="updateBtn" class="btn-update hidden">Update Pet</button>
            </form>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeConfirmModal()">&times;</span>
            <h3>Confirm Action</h3>
            <p id="confirmText" class="confirm-text">Are you sure?</p>
            <div class="confirm-actions">
                <a id="confirmBtnLink" href="#" class="btn-confirm-yes">Yes, I'm Sure</a>
                <button onclick="closeConfirmModal()" class="btn-confirm-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script src="views/assets/js/admin.js?v=8"></script>
</body>

</html>