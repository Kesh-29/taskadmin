<?php
session_start();
include 'db_connection.php'; // Database connection

// Fetch history data
$query = "SELECT task_id, created_at, updated_at FROM tasks ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - History</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/history.css">
</head>

<body>
    <div class="sidebar_logo">
        <img src="elements/Main_Logo.png" alt="Task Connect" class="logo">
    </div>
    <div class="main-container">
        <div class="sort-box">
            <label for="sort">Sort By:</label>
            <select id="sort">
                <option value="date">Date</option>
                <option value="id">Task ID</option>
            </select>
        </div>

        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.html">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="admin.php">Admin List</a></li>
                    <li><a href="tasker_request.php">Tasker Request</a></li>
                    <li><a href="history.php">History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="container">
            <h2>HISTORY</h2>

            <div class="admin-header">
                <span>Task ID</span>
                <span>Creation Date</span>
                <span>Updated At</span>
            </div>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="admin-box">
                    <span
                        title="<?= htmlspecialchars($row['task_id']) ?>"><?= substr(htmlspecialchars($row['task_id']), 0) ?></span>
                    <span class="Date"><?= htmlspecialchars($row['created_at']) ?></span>
                    <span class="dec_color"><?= htmlspecialchars($row['updated_at']) ?></span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>