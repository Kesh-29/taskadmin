<?php
session_start();
include 'db_connection.php';
header("Content-Type: application/json");

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized access"]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['admin_id']) || !isset($data['action'])) {
    echo json_encode(["success" => false, "error" => "Invalid request data"]);
    exit();
}

$adminId = (int) $data['admin_id'];
$action = $data['action'];
$isDeleted = ($action === 'delete') ? 1 : 0;

try {
    $stmt = $conn->prepare("UPDATE admins SET is_deleted = ? WHERE admin_id = ?");
    $stmt->bind_param("ii", $isDeleted, $adminId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Server error: " . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>