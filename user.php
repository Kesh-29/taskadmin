<?php
session_start();
include 'db_connection.php'; // Database connection
if (!isset($_SESSION['admin_id'])) { // Check if user is NOT logged in
    header("Location: login2.php");
    exit();
}

// Fetch all users from the database including is_tasker status
$query = "SELECT users_id, first_name, last_name, username, mobile_no, email, is_tasker FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Users</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/user.css">
    <style>
        /* Add this to your existing CSS */
        .container {
            max-height: 80vh;
            overflow-y: auto;
        }

        .tasker-status {
            font-weight: bold;
            text-align: center;
        }

        .tasker-yes {
            color: #28a745;
            /* Green for Yes */
        }

        .tasker-no {
            color: #dc3545;
            /* Red for No */
        }
    </style>
</head>

<body>
    <div class="logo-container">
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
                    <li><a href="admin.php">Admin <br> Management</a></li>
                    <li><a href="admin_profile.php">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="container">
            <h2>USERS</h2>
            <div class="User-header">
                <span>ID</span>
                <span>First Name</span>
                <span>Last Name</span>
                <span>Username</span>
                <span>Number</span>
                <span>Email</span>
                <span>Tasker</span>
            </div>

            <!-- Fetch and display users dynamically -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="user-box">
                    <span><?= htmlspecialchars($row['users_id']) ?></span>
                    <span><?= htmlspecialchars($row['first_name']) ?></span>
                    <span><?= htmlspecialchars($row['last_name']) ?></span>
                    <span><?= htmlspecialchars($row['username']) ?></span>
                    <span><?= htmlspecialchars($row['mobile_no']) ?></span>
                    <span><?= htmlspecialchars($row['email']) ?></span>
                    <span class="tasker-status <?= $row['is_tasker'] ? 'tasker-yes' : 'tasker-no' ?>">
                        <?= $row['is_tasker'] ? 'Yes' : 'No' ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>