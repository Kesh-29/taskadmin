<?php
include 'db_connection.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit();
}

// Get JSON input (now compatible with frontend)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate required fields
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;
$position = $data['position'] ?? null;

if (!$username || !$password || !$position) {
    echo json_encode(["success" => false, "error" => "All fields are required"]);
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if username exists
$stmt = $conn->prepare("SELECT admin_id FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "Username already exists"]);
    exit();
}

// Insert new admin (with default values for NOT NULL fields)
$stmt = $conn->prepare("INSERT INTO admins (username, password, position, first_name, last_name) VALUES (?, ?, ?, 'Default', 'User')");
$stmt->bind_param("sss", $username, $hashed_password, $position);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Admin added successfully"]);
} else {
    echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>