<?php
session_start();
include 'db_connection.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

// Prevent back button from accessing the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Get admin username
$admin_id = $_SESSION['admin_id'];
$adminQuery = "SELECT username FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($adminQuery);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$adminResult = $stmt->get_result();
$adminData = $adminResult->fetch_assoc();
$adminUsername = $adminData['username'];

// Get total users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = ($totalUsersResult) ? $totalUsersResult->fetch_assoc()['total_users'] : 0;

// Get total civilians (is_tasker = 0)
$civiliansQuery = "SELECT COUNT(*) AS total_civilians FROM users WHERE is_tasker = 0";
$civiliansResult = $conn->query($civiliansQuery);
$totalCivilians = ($civiliansResult) ? $civiliansResult->fetch_assoc()['total_civilians'] : 0;

// Get total taskers (is_tasker = 1)
$taskersQuery = "SELECT COUNT(*) AS total_taskers FROM users WHERE is_tasker = 1";
$taskersResult = $conn->query($taskersQuery);
$totalTaskers = ($taskersResult) ? $taskersResult->fetch_assoc()['total_taskers'] : 0;

// Get total pending verification requests
$pendingRequestsQuery = "SELECT COUNT(*) AS total_pending FROM verification_requests WHERE status = 'pending'";
$pendingRequestsResult = $conn->query($pendingRequestsQuery);
$totalPendingRequests = ($pendingRequestsResult) ? $pendingRequestsResult->fetch_assoc()['total_pending'] : 0;

// Calculate percentage for pie chart
$total = $totalCivilians + $totalTaskers;
$civiliansPercentage = ($total > 0) ? round(($totalCivilians / $total) * 100) : 0;
$taskersPercentage = ($total > 0) ? round(($totalTaskers / $total) * 100) : 0;

// Get civilian verification request counts
$verificationQuery = "
    SELECT status, COUNT(*) AS count 
    FROM verification_requests 
    INNER JOIN users ON verification_requests.users_id = users.users_id 
    WHERE users.is_tasker = 0 
    GROUP BY status";


$verificationResult = $conn->query($verificationQuery);

// Initialize variables
$pendingCivilians = 0;
$rejectedCivilians = 0;
$approvedCivilians = 0;

// Process the results
while ($row = $verificationResult->fetch_assoc()) {
    if ($row['status'] == 'pending') {
        $pendingCivilians = $row['count'];
    } elseif ($row['status'] == 'rejected') {
        $rejectedCivilians = $row['count'];
    } elseif ($row['status'] == 'approved') {
        $approvedCivilians = $row['count'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/admindash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
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
                    <li><a href="admin.php">User <br> Management</a></li>
                    <li><a href="view.html">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div> 

        <main class="main-content">
            <div class="welcome-section">
                <h1>Welcome, <?= htmlspecialchars($adminUsername) ?>!</h1>
                <p>Here is an overview of our progress</p>
            </div>

            <div class="stats-container">
                <a href="user.php" class="stats-card total-users"> <!-- Clickable Card -->
                    <div>
                        <h2><?= $totalUsers ?></h2>
                        <p>Total Users</p>
                    </div>
                </a>
                <div class="stats-card total-civilians">
                    <h2><?= $totalCivilians ?></h2>
                    <p>Total Citizens</p>
                </div>
                <div class="stats-card total-taskers">
                    <h2><?= $totalTaskers ?></h2>
                    <p>Total Taskers</p>
                </div>
                <a href="tasker_request.php" class="stats-card pending-requests"> <!-- Clickable Card -->
                    <div>
                        <h2><?= $totalPendingRequests ?></h2>
                        <p>Pending Requests</p>
                    </div>
                </a>
            </div>




            <!-- Pie Chart Section -->
            <div class="chart-container">
                <canvas id="userPieChart"></canvas>
            </div>

            <!-- Bar Chart Section -->
            <div class="bar-chart-container">
                <h2>Verification Requests</h2>
                <canvas id="verificationBarChart"></canvas>
            </div>



    </div>
    </main>
    </div>

    <script src="scripts/chart.js"></script> <!-- Separate JS file for the chart -->
    <script>
        const civiliansPercentage = <?= $civiliansPercentage ?>;
        const taskersPercentage = <?= $taskersPercentage ?>;
        const pendingCivilians = <?= $pendingCivilians ?>;
        const rejectedCivilians = <?= $rejectedCivilians ?>;
        const approvedCivilians = <?= $approvedCivilians ?>;
    </script>


</body>

</html>