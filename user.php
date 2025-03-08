<?php
session_start();
include 'db_connection.php'; // Database connection

// Fetch all users from the database
$query = "SELECT id, first_name, last_name, username, mobile_no, email FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/user.css">
</head>

<body>
    <div class="logo-container">
        <img src="elements/Main_Logo.png" alt="Task Connect Logo" class="logo">
    </div>
    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.html">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="admin.php">Admin</a></li>
                    <li><a href="tasker_request.html">Tasker Request</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="container">
            <h2>TASKER</h2>
            <div class="User-header">
                <span>ID</span>
                <span>First Name</span>
                <span>Last Name</span>
                <span>Username</span>
                <span>Number</span>
                <span>Email</span>
                <span>Action</span>
            </div>

            <!-- Fetch and display users dynamically -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="user-box">
                    <span><?= htmlspecialchars($row['id']) ?></span>
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
    </div>
</body>

</html>