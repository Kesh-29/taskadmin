<?php
session_start();
include 'db_connection.php'; // Include database connection

$error_message = ""; // Initialize an empty error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailOrUsername = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, email, password FROM admins WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Use password_verify() to check hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];

            header("Location: user.html");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style/login.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="container" id="container">
        <!-- REGISTER FORM -->
        <div class="form-container sign-up-container">
            <form id="registerForm">
                <h1>Create Account</h1>
                <span>or use your email for registration</span>
                <input type="text" id="reg_username" placeholder="Username" required />
                <input type="email" id="reg_email" placeholder="Email" required />
                <input type="password" id="reg_password" placeholder="Password" required />
                <p id="register_error" style="color: red;"></p>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <!-- LOGIN FORM (FIXED & DATABASE COMPATIBLE) -->
        <div class="form-container sign-in-container">
            <form action="index.php" method="POST">
                <h1>Login</h1>
                <span>or use your account</span>

                <input type="text" name="email" placeholder="Email or Username" required />
                <input type="password" name="password" placeholder="Password" required />

                <?php if (!empty($error_message)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
                <?php endif; ?>

                <button type="submit">Login</button>
            </form>
        </div>

        <!-- OVERLAY CONTAINER (UNCHANGED) -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", function (e) {
            e.preventDefault();

            let username = document.getElementById("reg_username").value;
            let email = document.getElementById("reg_email").value;
            let password = document.getElementById("reg_password").value;
            let errorMsg = document.getElementById("register_error");

            fetch("register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, email, password })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Registration successful!");
                        location.reload(); // Refresh the page
                    } else {
                        errorMsg.innerText = data.message; // Display error message
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    </script>
    <script src="scripts/script.js"></script>

</body>

</html>