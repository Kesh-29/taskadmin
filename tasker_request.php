<?php
require 'db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

// Fetch all pending verification requests
$sql = "SELECT vr.verification_id, vr.users_id, u.email, vr.status, 
               vr.document_path, vr.created_at, vr.description
        FROM verification_requests vr
        LEFT JOIN users u ON vr.users_id = u.users_id
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
    <style>
        /* Add these styles to your existing CSS */
        .container {
            max-height: 80vh;
            overflow-y: auto;
        }

        .action-container {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .dropdown {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            min-width: 100px;
        }

        .save-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.2s;
            display: none;
        }

        .save-btn:hover {
            background-color: #218838;
        }
    </style>
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
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
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
                <span>User ID</span>
                <span>Email</span>
                <span>Date Applied</span>
                <span>Document</span>
                <span>Action</span>
            </div>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $email = $row['email'] ?? 'N/A';
                    $docPath = $row['document_path'] ? '../documents_uploads/' . basename($row['document_path']) : 'N/A';

                    echo "<div class='admin-box'>
                            <span>{$row['verification_id']}</span>
                            <span>{$row['users_id']}</span>
                            <span>{$email}</span>
                            <span>{$row['created_at']}</span>
                            <span>";

                    if ($docPath !== 'N/A') {
                        echo "<a href='{$docPath}' target='_blank'>View Document</a>";
                    } else {
                        echo "N/A";
                    }

                    echo "</span>
                          <div class='action-container'>
                            <select class='dropdown' onchange='toggleSaveButton(this, \"{$row['verification_id']}\")'>
                                <option value='' selected></option>
                                <option value='approve'>Approve</option>
                                <option value='reject'>Reject</option>
                            </select>
                            <button class='save-btn' id='save-btn-{$row['verification_id']}' 
                                    onclick='processVerification(\"{$row['verification_id']}\")'>
                                Submit
                            </button>
                          </div>
                        </div>";
                }
            } else {
                echo "<p>No pending requests.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        function toggleSaveButton(dropdown, verificationId) {
            const saveBtn = document.getElementById(`save-btn-${verificationId}`);
            saveBtn.style.display = dropdown.value ? "inline-block" : "none";
        }

        function processVerification(verificationId) {
            const dropdown = document.querySelector(`#save-btn-${verificationId}`).previousElementSibling;
            const action = dropdown.value;
            const adminId = <?= json_encode($_SESSION['admin_id'] ?? null) ?>;

            if (!action || !adminId) return;

            fetch('process_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    verification_id: verificationId,
                    action: action,
                    admin_id: adminId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Request ${action}d successfully!`);
                        location.reload();
                    } else {
                        alert(`Error: ${data.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>