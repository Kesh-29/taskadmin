<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_id = $_POST['verification_id'];
    $action = $_POST['action'];

    // Validate action
    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        die("Invalid action.");
    }

    // Update verification request status
    $stmt = $conn->prepare("UPDATE verification_requests SET status = ?, reviewed_at = NOW() WHERE verification_id = ?");
    $stmt->bind_param("ss", $status, $verification_id);

    if ($stmt->execute()) {
        header("Location: tasker_request.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>