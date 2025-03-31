<?php
session_start();
require_once 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Map JS field names to actual database column names
$fieldMappings = [
    'firstName' => 'first_name',
    'lastName' => 'last_name',
    'email' => 'email',
    'phone' => 'mobile_no',
    'birthDate' => 'birth_date',
    'country' => 'country',
    'city' => 'city',
    'postalCode' => 'postal_code'
];

$updateFields = [];
$params = [];
$paramTypes = "";

foreach ($fieldMappings as $jsField => $dbColumn) {
    if (isset($_POST[$jsField])) {
        $updateFields[$dbColumn] = trim($_POST[$jsField]);
    }
}

// If no valid data provided
if (empty($updateFields)) {
    echo json_encode(["success" => false, "message" => "No data provided."]);
    exit();
}

// Build SQL query dynamically
$sqlParts = [];
foreach ($updateFields as $column => $value) {
    $sqlParts[] = "`$column` = ?";
    $params[] = $value;
    $paramTypes .= "s";
}

$params[] = $admin_id;
$paramTypes .= "i";

$query = "UPDATE admins SET " . implode(", ", $sqlParts) . " WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param($paramTypes, ...$params);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>