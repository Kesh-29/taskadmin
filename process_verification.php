<?php
require 'db_connection.php';
session_start();

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['verification_id']) || !isset($data['action']) || !isset($data['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

$verificationId = $data['verification_id'];
$action = $data['action'];
$adminId = $data['admin_id'];

// Validate action
if (!in_array($action, ['approve', 'reject'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
    exit();
}

// Determine new status
$status = $action === 'approve' ? 'approved' : 'rejected';

try {
    // Start transaction
    $conn->begin_transaction();

    // Update verification request
    $stmt = $conn->prepare("UPDATE verification_requests 
                           SET status = ?, reviewed_by = ?, accepted_on = IF(? = 'approve', NOW(), NULL)
                           WHERE verification_id = ? AND status = 'pending'");

    $stmt->bind_param("siss", $status, $adminId, $action, $verificationId);
    $stmt->execute();

    // If approved, update user's is_tasker status
    if ($action === 'approve') {
        $updateUser = $conn->prepare("UPDATE users SET is_tasker = 1 
                                     WHERE users_id = (SELECT users_id FROM verification_requests 
                                                     WHERE verification_id = ?)");
        $updateUser->bind_param("s", $verificationId);
        $updateUser->execute();
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>