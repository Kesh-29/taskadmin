<?php
include 'db_connection.php';
header("Content-Type: application/json"); // Set response type

$response = ["success" => false, "message" => ""];

// Detect JSON request
$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle both form and JSON input
    $first_name = $inputData['first_name'] ?? $_POST['first_name'] ?? null;
    $last_name = $inputData['last_name'] ?? $_POST['last_name'] ?? null;
    $username = $inputData['username'] ?? $_POST['username'] ?? null;
    $email = $inputData['email'] ?? $_POST['email'] ?? null;
    $mobile_no = $inputData['mobile_no'] ?? $_POST['mobile_no'] ?? null;
    $password = $inputData['password'] ?? $_POST['password'] ?? null;

    // Validate required fields
    if (!$first_name || !$last_name || !$username || !$email || !$mobile_no || !$password) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username, email, or mobile number already exists
    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ? OR mobile_no = ?");
    $stmt->bind_param("sss", $username, $email, $mobile_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response["message"] = "Username, Email, or Mobile Number already exists.";
    } else {
        // Insert into the admins table
        $stmt = $conn->prepare("INSERT INTO admins (first_name, last_name, username, email, mobile_no, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $mobile_no, $hashed_password);

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