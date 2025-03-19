<?php
session_start();
include 'db_connection.php'; // Database connection

if (!isset($_SESSION['admin_id'])) { // Check if user is NOT logged in
    header("Location: login2.php");
    exit();
}

// Prevent back button from accessing the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Fetch all admins from the database
$query = "SELECT admin_id, first_name, last_name, username, mobile_no, email FROM admins";
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
    <div class="logo-container">
        <img src="elements/Main_Logo.png" alt="Task Connect Logo" class="logo">
    </div>

    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.php">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="admin.php">Admin List</a></li>
                    <li><a href="tasker_request.php">Tasker Request</a></li>
                    <li><a href="admin_user_profile.html">User Profile</a></li>
                    <li><a href="history.php">History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="container">
            <h2>ADMIN</h2>

            <!-- Header Row -->
            <div class="admin-header">
                <span>ID</span>
                <span>First Name</span>
                <span>Last Name</span>
                <span>Username</span>
                <span>Number</span>
                <span>Email</span>
                <span>Action</span>
            </div>

            <!-- Fetch and display admins -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="admin-box">
                    <span><?= htmlspecialchars($row['admin_id']) ?></span>
                    <span><?= htmlspecialchars($row['first_name']) ?></span>
                    <span><?= htmlspecialchars($row['last_name']) ?></span>
                    <span><?= htmlspecialchars($row['username']) ?></span>
                    <span><?= htmlspecialchars($row['mobile_no']) ?></span>
                    <span><?= htmlspecialchars($row['email']) ?></span>
                    <select class="dropdown">
                        <option value="" disabled selected></option>
                        <option value="accept">Accept</option>
                        <option value="decline">Decline</option>
                    </select>
                </div>
            <?php endwhile; ?>

        </div>
    </div>
</body>

</html>