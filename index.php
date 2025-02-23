<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}
include 'db_connection.php';

// Fetch all admins
$sql = "SELECT id, first_name, last_name, email, mobile_no, username, position, image_path FROM admins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style/dashboard.css">
    <link href="https://fonts.cdnfonts.com/css/made-tommy-outline" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="logo-container">
        <img src="TEST/Main_Logo.png" alt="Task Connect Logo" class="logo">
    </div>
    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="TEST/user.html">Users</a></li>
                    <li><a href="#">Admin</a></li>
                    <li><a href="#">Tasker Request</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>


    <div class="admin-container">
        <?php while ($admin = $result->fetch_assoc()): ?>
            <div class="admin-card">
                <img src="<?= htmlspecialchars($admin['image_path']) ?>" alt="Admin Image" class="admin-image">
                <div class="name_pos">
                    <h2 class="name"><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></h2>
                    <h2 class="position"><?= htmlspecialchars($admin['position']) ?></h2>
                </div>
                <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($admin['mobile_no']) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>