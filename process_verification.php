<?php
require 'db_connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

$admin_id = $_SESSION['admin_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_id = $_POST['verification_id'];
    $action = $_POST['action'];

    if ($action == "approve") {
        // Approve request and mark user as a tasker
        $stmt = $conn->prepare("UPDATE users 
                                JOIN verification_requests vr ON users.users_id = vr.users_id
                                SET users.verified = 1, users.is_tasker = 1, 
                                    vr.status = 'approved', vr.reviewed_at = NOW(), vr.reviewed_by = ?
                                WHERE vr.verification_id = ?");
        $stmt->bind_param("is", $admin_id, $verification_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == "reject") {
        // Reject request
        $stmt = $conn->prepare("UPDATE verification_requests 
                                SET status = 'rejected', reviewed_at = NOW(), reviewed_by = ?
                                WHERE verification_id = ?");
        $stmt->bind_param("is", $admin_id, $verification_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back to tasker request page
header("Location: tasker_request.php?success=1");
exit();
