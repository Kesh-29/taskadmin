<?php
include 'db_connection.php';
header("Content-Type: application/json"); // Set response type

$error_message = "";
$response = ["success" => false, "message" => ""];

// Detect JSON request
$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle both form and JSON input
    $username = $inputData['username'] ?? $_POST['username'] ?? null;
    $email = $inputData['email'] ?? $_POST['email'] ?? null;
    $password = $inputData['password'] ?? $_POST['password'] ?? null;
    $user_type = $inputData['user_type'] ?? $_POST['user_type'] ?? 'admins'; // Default to 'admins'

    // Validate required fields
    if (!$username || !$email || !$password) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists in the selected table
    $stmt = $conn->prepare("SELECT id FROM $user_type WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response["message"] = "Username or Email already exists.";
    } else {
        // Insert into selected table (users or admins)
        $stmt = $conn->prepare("INSERT INTO $user_type (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Registration successful.";

            // Web redirect (only for form submission)
            if (!isset($inputData)) {
                header("Location: login.php?success=1");
                exit();
            }
        } else {
            $response["message"] = "Registration failed. Try again.";
        }
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response); // Return JSON response
?>