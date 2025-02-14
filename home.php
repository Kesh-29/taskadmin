<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch admin details (if needed)
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT first_name, last_name, email FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Home</title>
    <link rel="stylesheet" href="home.css">

    <!-- Prevent Back Navigation After Logout -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body>
    <header class="navbar">
        <img src="Main_Logo.png" alt="Task Connect Logo" class="logo">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="hero">
            <h1>Welcome, <?= htmlspecialchars($admin['first_name']) ?>!</h1>
            <p>Connecting People Together</p>
        </div>
        <section class="content">
            <p>The gig economy has become a vital source of income for many Filipinos...</p>
            <p>Task Connect is poised to revolutionize short-term work connections...</p>
        </section>
    </main>
</body>

</html>