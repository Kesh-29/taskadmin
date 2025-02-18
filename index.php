<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}
include 'db_connection.php';

// Fetch all admins
$sql = "SELECT id, first_name, last_name, email, mobile_no, username FROM admins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <header class="navbar">
        <img src="elements\mini_Logo.png" alt="Task Connect Logo" class="logo">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <h1>Admin Dashboard</h1>

    <div class="admin-container">
        <?php while ($admin = $result->fetch_assoc()): ?>
            <div class="admin-card">
                <h2><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($admin['mobile_no']) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>