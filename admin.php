<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$show_deleted = isset($_GET['show']) && $_GET['show'] === 'deleted';
$query = $show_deleted
    ? "SELECT admin_id, first_name, last_name, username, mobile_no, email, is_deleted FROM admins WHERE is_deleted = 1"
    : "SELECT admin_id, first_name, last_name, username, mobile_no, email, is_deleted FROM admins WHERE is_deleted = 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/admin.css">
</head>

<body>
    <div class="sidebar-logo">
        <img src="elements/Main_Logo.png" alt="Task Connect Logo" class="logo">
    </div>

    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.php">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="tasker_request.php">Tasker<br>Request</a></li>
                    <li><a href="history.php">Req History</a></li>
                    <li><a href="admin.php">Admin<br>Management</a></li>
                    <li><a href="admin_profile.php">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="container">
            <div class="admin-tabs">
                <h2 class="<?= !$show_deleted ? 'active' : '' ?>" onclick="window.location.href='admin.php'">ACTIVE
                    ADMINS</h2>
                <h2 class="<?= $show_deleted ? 'active' : '' ?>"
                    onclick="window.location.href='admin.php?show=deleted'">DELETED ADMINS</h2>
            </div>

            <div class="admin-top-bar">
                <?php if (!$show_deleted): ?>
                    <button class="add-admin-btn" onclick="openModal()">Add Admin</button>
                <?php endif; ?>
            </div>

            <div class="admin-header">
                <span>ID</span>
                <span>First Name</span>
                <span>Last Name</span>
                <span>Username</span>
                <span>Number</span>
                <span>Email</span>
                <span>Action</span>
            </div>

            <div class="admin-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="admin-box">
                        <span><?= htmlspecialchars($row['admin_id']) ?></span>
                        <span><?= htmlspecialchars($row['first_name']) ?></span>
                        <span><?= htmlspecialchars($row['last_name']) ?></span>
                        <span><?= htmlspecialchars($row['username']) ?></span>
                        <span><?= htmlspecialchars($row['mobile_no']) ?></span>
                        <span><?= htmlspecialchars($row['email']) ?></span>

                        <div class="action-container">
                            <div class="dropdown-container">
                                <select class="dropdown" onchange="toggleSaveButton(this, <?= $row['admin_id'] ?>)">
                                    <option value="" selected></option>
                                    <?php if ($show_deleted): ?>
                                        <option value="activate">Activate</option>
                                    <?php else: ?>
                                        <option value="delete">Delete</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <button class="save-btn" id="save-btn-<?= $row['admin_id'] ?>"
                                onclick="saveAction(<?= $row['admin_id'] ?>)" style="display: none;">
                                Save
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal (only for active admins view) -->
    <?php if (!$show_deleted): ?>
        <div id="adminModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add New Admin</h2>
                <form id="adminForm">
                    <input type="text" id="username" placeholder="Username" required>
                    <input type="password" id="password" placeholder="Password" required>
                    <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
                    <input type="text" id="position" placeholder="Position" required>
                    <button type="button" onclick="addAdmin()">Add Admin</button>
                    <p id="errorMessage" class="error-message" style="display: none; color: red;"></p>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/admin.js"></script>
    <script>
        function toggleSaveButton(dropdown, adminId) {
            const saveBtn = document.getElementById(`save-btn-${adminId}`);
            saveBtn.style.display = dropdown.value ? "inline-block" : "none";
        }

        function saveAction(adminId) {
            const dropdown = document.querySelector(`#save-btn-${adminId}`).previousElementSibling.querySelector('.dropdown');
            const action = dropdown.value;
            const isDeletedView = window.location.href.includes('show=deleted');

            if (!action) return;

            fetch("delete_admin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    admin_id: adminId,
                    action: action,
                    is_deleted_view: isDeletedView
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Admin ${action}d successfully!`);
                        location.reload();
                    } else {
                        alert(`Error: ${data.error}`);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred");
                });
        }

        // Modal functions (only needed for active admins view)
        <?php if (!$show_deleted): ?>
            function openModal() {
                document.getElementById("adminModal").style.display = "flex";
                document.getElementById("adminForm").reset();
            }

            function closeModal() {
                document.getElementById("adminModal").style.display = "none";
                document.getElementById("errorMessage").style.display = "none";
            }

            function addAdmin() {
                const username = document.getElementById("username").value.trim();
                const password = document.getElementById("password").value.trim();
                const confirmPassword = document.getElementById("confirmPassword").value.trim();
                const position = document.getElementById("position").value.trim();
                const errorMessage = document.getElementById("errorMessage");

                if (!username || !password || !position) {
                    errorMessage.textContent = "All fields are required!";
                    errorMessage.style.display = "block";
                    return;
                }

                if (password !== confirmPassword) {
                    errorMessage.textContent = "Passwords don't match!";
                    errorMessage.style.display = "block";
                    return;
                }

                fetch("add_admin.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ username, password, position })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Admin added successfully!");
                            closeModal();
                            location.reload();
                        } else {
                            errorMessage.textContent = data.error;
                            errorMessage.style.display = "block";
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        errorMessage.textContent = "An error occurred";
                        errorMessage.style.display = "block";
                    });
            }
        <?php endif; ?>
    </script>
</body>

</html>