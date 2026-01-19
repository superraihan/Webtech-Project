<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$msg = "";
$error = "";

if (!is_dir('uploads')) {
    mkdir('uploads');
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM pets WHERE id=$id");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $conn->query("DELETE FROM users WHERE id=$user_id");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['approve_request'])) {
    $request_id = $_GET['approve_request'];
    
    $req_qry = $conn->query("SELECT * FROM adoption_request WHERE id='$request_id'");
    
    if ($req_qry && $req_qry->num_rows > 0) {
        $request = $req_qry->fetch_assoc();
        $pet_id = $request['pet_id'];
        $user_id = $request['user_id'];
        
        if (!$conn->query("UPDATE adoption_request SET status='approved' WHERE id='$request_id'")) {
            die("Error updating request status: " . $conn->error);
        }
        
        if (!$conn->query("UPDATE pets SET status='adopted' WHERE id='$pet_id'")) {
            die("Error updating pet status: " . $conn->error);
        }
        
        $check_adoption = $conn->query("SELECT * FROM adoption WHERE pet_id='$pet_id'");
        if ($check_adoption->num_rows == 0) {
            $insert_query = "INSERT INTO adoption (user_id, pet_id) VALUES ('$user_id', '$pet_id')";
            if (!$conn->query($insert_query)) {
                die("Error inserting into adoption table: " . $conn->error);
            }
        }
        
        $conn->query("UPDATE adoption_request SET status='rejected' WHERE pet_id='$pet_id' AND id!='$request_id' AND status='pending'");
    }
    
    header("Location: admin.php");
    exit();
}

if (isset($_GET['reject_request'])) {
    $request_id = $_GET['reject_request'];
    $conn->query("UPDATE adoption_request SET status='rejected' WHERE id='$request_id'");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_request'])) {
    $request_id = $_GET['delete_request'];
    $conn->query("DELETE FROM adoption_request WHERE id='$request_id'");
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['sync_db'])) {
        $conn->query("UPDATE pets SET status='available' WHERE status IS NULL OR status=''");
        
        $conn->query("UPDATE pets p 
                      INNER JOIN adoption_request ar ON p.id = ar.pet_id 
                      SET p.status = 'adopted' 
                      WHERE ar.status = 'approved'");
        
        $approved_reqs = $conn->query("SELECT * FROM adoption_request WHERE status='approved'");
        while($row = $approved_reqs->fetch_assoc()) {
            $u_id = $row['user_id'];
            $p_id = $row['pet_id'];
            
            $chk = $conn->query("SELECT * FROM adoption WHERE pet_id='$p_id'");
            if($chk->num_rows == 0) {
                if(!$conn->query("INSERT INTO adoption (user_id, pet_id) VALUES ('$u_id', '$p_id')")){
                    $error = "Error syncing id $p_id: " . $conn->error;
                }
            }
        }
        if(!$error) {
            $msg = "Database synchronized successfully!";
        }
    }

    if (isset($_POST['add_pet'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];
        $status = $_POST['status'];
        
        if (empty($name) || empty($type) || $age < 0) {
            $error = "Please fill all fields correctly.";
        } else {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);

            $sql = "INSERT INTO pets (name, type, age, description, image, status) VALUES ('$name', '$type', '$age', '$desc', '$image', '$status')";
            if($conn->query($sql)) {
                $msg = "Pet added successfully!";
            } else {
                $error = "Error adding pet: " . $conn->error;
            }
        }
    }

    if (isset($_POST['update_pet'])) {
        $id = $_POST['pet_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $age = $_POST['age'];
        $desc = $_POST['description'];
        $status = $_POST['status'];

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $sql = "UPDATE pets SET name='$name', type='$type', age='$age', description='$desc', image='$image', status='$status' WHERE id=$id";
        } else {
            $sql = "UPDATE pets SET name='$name', type='$type', age='$age', description='$desc', status='$status' WHERE id=$id";
        }
        
        if($conn->query($sql)) {
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } else {
            $error = "Error updating pet: " . $conn->error;
        }
    }

    if (isset($_POST['update_admin'])) {
        $new_name = $_POST['admin_name'];
        $new_password = $_POST['admin_password'];
        $admin_email = $_SESSION['email'];
        
        if(empty($new_name) || empty($new_password)) {
            $error = "Name and Password cannot be empty.";
        } else {
            $sql = "UPDATE admins SET name='$new_name', password='$new_password' WHERE email='$admin_email'";
            if($conn->query($sql)) {
                $_SESSION['admin_name'] = $new_name;
                $msg = "Profile updated!";
            } else {
                $error = "Error updating profile.";
            }
        }
    }

    if (isset($_POST['add_admin'])) {
        $new_admin_name = $_POST['new_admin_name'];
        $new_admin_email = $_POST['new_admin_email'];
        $new_admin_password = $_POST['new_admin_password'];
        
        if(empty($new_admin_name) || empty($new_admin_email) || empty($new_admin_password)) {
            $error = "All fields are required.";
        } else {
            $check = $conn->query("SELECT * FROM admins WHERE email='$new_admin_email'");
            if ($check->num_rows == 0) {
                $sql = "INSERT INTO admins (name, email, password) VALUES ('$new_admin_name', '$new_admin_email', '$new_admin_password')";
                if($conn->query($sql)) {
                    $msg = "New Admin Added!";
                } else {
                    $error = "Error adding admin.";
                }
            } else {
                $error = "Email already exists.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="admin.css?v=23">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include 'header.php'; ?>

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
            <a href="logout.php" class="logout-link"><button class="sidebar-logout-btn">Logout</button></a>
        </div>

        <div class="main-content">
            
            <?php if($msg) echo "<div class='msg-box msg-success'>$msg</div>"; ?>
            <?php if($error) echo "<div class='msg-box msg-error'>$error</div>"; ?>

            <div id="dashboard" class="section-content active">
                <h2>Admin Overview</h2>
                <p>Welcome, <strong><?php echo $admin_name; ?></strong>!</p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $conn->query("SELECT * FROM admins")->num_rows; ?></h3>
                        <p>Total Admins</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $conn->query("SELECT * FROM pets WHERE status='available'")->num_rows; ?></h3>
                        <p>Available Pets</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $conn->query("SELECT * FROM adoption_request WHERE status='pending'")->num_rows; ?></h3>
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
                        $sql = "SELECT * FROM users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>".$row["id"]."</td>
                                    <td>".$row["name"]."</td>
                                    <td>".$row["email"]."</td>
                                    <td><a href='javascript:void(0)' onclick=\"confirmAction('admin.php?delete_user_id=".$row['id']."', 'Are you sure you want to delete this user? This cannot be undone.', 'delete')\"><button class='btn-delete'>Delete</button></a></td>
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
                        $sql = "SELECT * FROM pets";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status = strtolower(trim($row['status']));
                                if ($status == 'available') $statusClass = 'status-available';
                                elseif ($status == 'pending') $statusClass = 'status-pending';
                                elseif ($status == 'adopted') $statusClass = 'status-adopted';
                                else $statusClass = 'status-unknown';
                                
                                $statusText = !empty($status) ? ucfirst($status) : 'Unknown';
                                
                                echo "<tr>
                                    <td><img src='uploads/".$row['image']."' class='pet-thumb'></td>
                                    <td>".$row['name']."</td>
                                    <td>".$row['type']."</td>
                                    <td>".$row['age']." yrs</td>
                                    <td><span class='status-badge ".$statusClass."'>".$statusText."</span></td>
                                    <td>
                                        <button class='btn-edit' onclick='editPet(".json_encode($row).")'>Edit</button>
                                        <a href='javascript:void(0)' onclick=\"confirmAction('admin.php?delete_id=".$row['id']."', 'Are you sure you want to delete this pet?', 'delete')\"><button class='btn-delete'>Delete</button></a>
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
                        $sql = "SELECT ar.*, u.name as user_name, u.email as user_email, p.name as pet_name, p.type as pet_type 
                                FROM adoption_request ar 
                                JOIN users u ON ar.user_id = u.id 
                                JOIN pets p ON ar.pet_id = p.id 
                                ORDER BY ar.request_at DESC";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                if ($row['status'] == 'pending') $statusClass = 'status-pending';
                                elseif ($row['status'] == 'approved') $statusClass = 'status-available';
                                else $statusClass = 'status-rejected';
                                
                                echo "<tr data-status='".$row['status']."'>
                                    <td>".$row['id']."</td>
                                    <td>".$row['user_name']."<br><small class='sub-text'>".$row['user_email']."</small></td>
                                    <td>".$row['pet_name']." <small class='sub-text'>(".$row['pet_type'].")</small></td>
                                    <td>".date('M d, Y', strtotime($row['request_at']))."</td>
                                    <td><span class='status-badge ".$statusClass."'>".ucfirst($row['status'])."</span></td>
                                    <td>";
                                
                                if ($row['status'] == 'pending') {
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('admin.php?approve_request=".$row['id']."', 'Do you want to Approve this request?', 'approve')\" class='btn-approve'>Approve</a> ";
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('admin.php?reject_request=".$row['id']."', 'Do you want to Reject this request?', 'reject')\" class='btn-reject'>Reject</a>";
                                } else {
                                    echo "<a href='javascript:void(0)' onclick=\"confirmAction('admin.php?delete_request=".$row['id']."', 'Delete this request record?', 'delete')\" class='btn-delete-small'>Delete</a>";
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
                <?php
                $admin_email = $_SESSION['email'];
                $admin_data = $conn->query("SELECT * FROM admins WHERE email='$admin_email'")->fetch_assoc();
                ?>
                <div class="settings-container">
                    <div class="settings-box">
                        <h3>Update Your Profile</h3>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Your Name</label>
                                <input type="text" name="admin_name" value="<?php echo $admin_data['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Your Password</label>
                                <input type="text" name="admin_password" value="<?php echo $admin_data['password']; ?>" required>
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
                                <input type="email" name="new_admin_email" id="new_admin_email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label>Admin Password</label>
                                <input type="text" name="new_admin_password" id="new_admin_password" placeholder="Enter password">
                            </div>
                            <p id="admin-error" class="error-text"></p>
                            <button type="submit" name="add_admin" class="btn-update">Add Admin</button>
                        </form>
                    </div>
                </div>

                <div class="settings-box maintenance-box">
                    <h3 class="maintenance-title">Maintenance</h3>
                    <p class="maintenance-text">If adoption statuses are not showing correctly in the dashboard, click this button to sync database.</p>
                    <form action="" method="POST">
                        <button type="submit" name="sync_db" class="btn-update btn-sync">Fix Database Issues</button>
                    </form>
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
                    <textarea name="description" id="p_desc" rows="3" placeholder="Describe the pet's personality..."></textarea>
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

    <script src="admin.js?v=8"></script>
</body>
</html>