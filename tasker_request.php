<?php
require 'db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}


// Fetch all pending verification requests
$sql = "SELECT vr.verification_id, u.first_name, u.last_name, u.email, vr.status, u.is_tasker, vr.applied_at 
        FROM verification_requests vr
        JOIN users u ON vr.users_id = u.users_id
        WHERE vr.status = 'pending'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Tasker Requests</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/tasker_request.css">
</head>

<body>
    <div class="sidebar_logo">
        <img src="elements/Main_Logo.png" alt="Task Connect" class="logo">
    </div>
    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.php">Dashboard</a></li>
                    <li><a href="user.php">Tasker</a></li>
                    <li><a href="tasker_request.php">Tasker<br>Request</a></li>
                    <li><a href="history.php">Job Request</a></li>
                    <li><a href="admin.php">User<br> Management</a></li>
                    <li><a href="view.html">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="container">
        <div class="search-container">
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
        <input type="text" placeholder="Search requests..." class="search-input">
    </div>
    
            <h2>TASKER REQUESTS</h2>

            <!-- Success Message -->
            <?php if (isset($_GET['success'])): ?>
                <p style="color: green;">Action completed successfully.</p>
            <?php endif; ?>

            <div class="admin-header">
                <span>Request ID</span>
                <span>Name</span>
                <span>Email</span>
                <span>Date Applied</span>
                <span>Document</span>
                <span>Status</span>
                <span>Action</span>
            </div>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imageSrc = "get_verification_image.php?verification_id=" . $row['verification_id'];
                    echo "<div class='admin-box'>
                            <span>{$row['verification_id']}</span>
                            <span>{$row['first_name']} {$row['last_name']}</span>
                            <span>{$row['email']}</span>
                            <span>{$row['applied_at']}</span>
                            <span><img src='{$imageSrc}' width='100' height='100'></span>
                            <span class='pend_color'>"
                        . (($row['is_tasker'] == 1) ? "Tasker" : "Pending") .
                        "</span>
                            <form method='POST' action='process_verification.php'>
                                <input type='hidden' name='verification_id' value='{$row['verification_id']}'>
                                <select name='action' required>
                                    <option value='approve'>Approve</option>
                                    <option value='reject'>Reject</option>
                                </select>
                                <button type='submit'>Submit</button>
                            </form>
                          </div>";
                }
            } else {
                echo "<p>No pending requests.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>