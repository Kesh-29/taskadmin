<?php
require 'db_connection.php';

// Fetch verification requests
$sql = "SELECT vr.verification_id, u.first_name, u.last_name, vr.status, vr.applied_at 
        FROM verification_requests vr
        JOIN users u ON vr.users_id = u.users_id
        ORDER BY vr.applied_at DESC";

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
                    <li><a href="admindash.html">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="admin.php">Admin</a></li>
                    <li><a href="tasker_request.php">Tasker Request</a></li>
                    <li><a href="admin_user_profile.html">User Profile</a></li>
                    <li><a href="history.php">History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="container">
            <h2>TASKER REQUESTS</h2>

            <div class="admin-header">
                <span>Request ID</span>
                <span>Name</span>
                <span>Date</span>
                <span>Status</span>
                <span>Action</span>
            </div>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusClass = "";

                    // Assign CSS class based on status
                    if ($row['status'] == 'pending') {
                        $statusClass = "pend_color";
                    } elseif ($row['status'] == 'approved') {
                        $statusClass = "comp_color";
                    } elseif ($row['status'] == 'rejected') {
                        $statusClass = "dec_color";
                    }

                    echo "<div class='admin-box'>
                            <span>#{$row['verification_id']}</span>
                            <span><span class='profile-initial'>" . strtoupper(substr($row['first_name'], 0, 1)) . "</span> {$row['first_name']} {$row['last_name']}</span>
                            <span class='Date'>" . date('m/d/Y', strtotime($row['applied_at'])) . "</span>
                            <span class='{$statusClass}'>" . ucfirst($row['status']) . "</span>
                            <select class='dropdown' onchange='updateStatus(this, \"{$row['verification_id']}\")'>
                                <option value='' disabled selected></option>
                                <option value='approved'>Accept</option>
                                <option value='rejected'>Decline</option>
                            </select>
                          </div>";
                }
            } else {
                echo "<p>No tasker requests found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function updateStatus(selectElement, verificationId) {
            var status = selectElement.value;
            if (status) {
                fetch('update_tasker_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'verification_id=' + verificationId + '&status=' + status
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            location.reload();
                        } else {
                            alert('Error updating status');
                        }
                    });
            }
        }
    </script>

</body>

</html>