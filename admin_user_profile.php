<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$query = "SELECT first_name, last_name, email, username, mobile_no, image_path FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Set profile image path (default if empty)
$image_path = !empty($admin['image_path']) ? htmlspecialchars($admin['image_path']) : 'elements/default_profile.png';

// Close connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Profile</title>
    <link rel="stylesheet" href="style/admin_user_profile.css">
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
                    <li><a href="history.php">Req History</a></li>
                    <li><a href="admin.php">Admin <br> Managent</a></li>
                    <li><a href="admin_profile.php">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <h1>Admin User Profile</h1>
            <p class="welcome-text">Welcome to Task Connect admin user profile</p>
            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-pic">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile Picture">
                    </div>

                    <div class="profile-info">
                        <p class="profile-name">
                            <span><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></span>
                        </p>
                        <p class="profile-username">
                            <span>@<?= htmlspecialchars($admin['username']) ?></span>
                        </p>
                        <p class="profile-mobile"><strong>Mobile Number</strong>
                            <span><?= htmlspecialchars($admin['mobile_no']) ?></span>
                        </p>
                        <p class="profile-email"><strong>Email</strong>
                            <span><?= htmlspecialchars($admin['email']) ?></span>
                        </p>
                        <div class="profile-buttons">
                            <button class="edit-btn">Edit Profile</button>
                            <button class="save-btn">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>