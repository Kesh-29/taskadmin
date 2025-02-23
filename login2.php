<?php
session_start();
include 'db_connection.php'; // Include database connection

$error_message = ""; // Initialize an empty error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailOrUsername = $_POST['email'];
    $password = $_POST['password'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $emailOrUsername = $_POST['email'];
        $password = $_POST['password'];

        // DEBUGGING: Display user input
        $stmt = $conn->prepare("SELECT id, username, email, password FROM admins WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // DEBUGGING: Print actual password from database
            echo "Stored Password: " . htmlspecialchars($user['password']) . "<br>";

            if ($password === $user['password']) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];

                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid password.";
                exit(); // Stop further execution
            }
        } else {
            $error_message = "User not found.";
        }
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
        <!-- SIGN UP FORM (UNCHANGED) -->
        <div class="form-container sign-up-container">
            <form action="signup.php" method="POST">
                <h1>Create Account</h1>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <!-- LOGIN FORM (FIXED & DATABASE COMPATIBLE) -->
        <div class="form-container sign-in-container">
            <form action="login2.php" method="POST">
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

    <script src="script.js"></script>
</body>

</html>