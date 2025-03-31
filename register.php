<?php
include 'db_connection.php';
header("Content-Type: application/json"); // Set response type

$response = ["success" => false, "message" => ""];

try {
    // Get input data (works for both form and JSON)
    $inputData = $_SERVER['CONTENT_TYPE'] === 'application/json'
        ? json_decode(file_get_contents("php://input"), true)
        : $_POST;

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Invalid request method");
    }

    // Validate required fields
    $required = ['first_name', 'last_name', 'username', 'email', 'mobile_no', 'password'];
    foreach ($required as $field) {
        if (empty($inputData[$field])) {
            throw new Exception("All fields are required");
        }
    }

    // Sanitize inputs
    $first_name = trim($inputData['first_name']);
    $last_name = trim($inputData['last_name']);
    $username = trim($inputData['username']);
    $email = trim($inputData['email']);
    $mobile_no = trim($inputData['mobile_no']);
    $password = $inputData['password'];

    // Additional validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    if (!preg_match('/^[0-9]{11}$/', $mobile_no)) {
        throw new Exception("Mobile number must be 11 digits");
    }

    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters");
    }

    // Check for existing user
    $stmt = $conn->prepare("SELECT admin_id FROM admins WHERE username = ? OR email = ? OR mobile_no = ?");
    $stmt->bind_param("sss", $username, $email, $mobile_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        throw new Exception("Username, Email, or Mobile Number already exists");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (first_name, last_name, username, email, mobile_no, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $mobile_no, $hashed_password);

    if (!$stmt->execute()) {
        throw new Exception("Registration failed. Please try again.");
    }

    $response["success"] = true;
    $response["message"] = "Registration successful";

    // Handle redirect for form submission (non-AJAX)
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: login.php?success=1");
        exit();
    }

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
    http_response_code(400); // Bad request for client errors
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>