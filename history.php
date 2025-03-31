<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

// Determine sort order
$sort = $_GET['sort'] ?? 'accepted_desc';
$orderBy = '';
switch ($sort) {
    case 'accepted_asc':
        $orderBy = 'vr.accepted_on ASC';
        break;
    case 'accepted_desc':
    default:
        $orderBy = 'vr.accepted_on DESC';
        break;
}

// Fetch approved verification requests
$query = "SELECT vr.verification_id, vr.users_id, u.first_name, u.last_name, 
          vr.status, vr.created_at, vr.accepted_on, vr.description,
          a.username as reviewed_by_admin
          FROM verification_requests vr
          JOIN users u ON vr.users_id = u.users_id
          LEFT JOIN admins a ON vr.reviewed_by = a.admin_id
          WHERE vr.status = 'approved'
          ORDER BY $orderBy";
$result = $conn->query($query);

$hasApprovedRequests = $result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Verification History</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="style/history.css">
</head>

<body>
    <div class="sidebar-logo">
        <img src="elements/Main_Logo.png" alt="Task Connect" class="logo">
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
            <div class="history-header">
                <h2>VERIFICATION REQUEST HISTORY</h2>
                <div class="sort-container">
                    <label for="sort">Sort By:</label>
                    <select id="sort" onchange="window.location.href='history.php?sort='+this.value">
                        <option value="accepted_desc" <?= $sort === 'accepted_desc' ? 'selected' : '' ?>>Acceptance Date
                            (Newest First)</option>
                        <option value="accepted_asc" <?= $sort === 'accepted_asc' ? 'selected' : '' ?>>Acceptance Date
                            (Oldest First)</option>
                    </select>
                </div>
            </div>

            <div class="admin-header">
                <span>Request ID</span>
                <span>User</span>
                <span>Accepted Date</span>
                <span>Status</span>
            </div>

            <?php if ($hasApprovedRequests): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="admin-box">
                        <span title="<?= htmlspecialchars($row['verification_id']) ?>">
                            <?= substr(htmlspecialchars($row['verification_id']), 0, 8) ?>...
                        </span>
                        <span><?= htmlspecialchars($row['first_name']) ?>         <?= htmlspecialchars($row['last_name']) ?></span>
                        <span><?= date('M d, Y h:i A', strtotime($row['accepted_on'])) ?></span>
                        <span class="comp_color"><?= htmlspecialchars(ucfirst($row['status'])) ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-requests">
                    No approved verification requests found.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>