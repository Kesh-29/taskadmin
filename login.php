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

        if ($password === $user['password']) { // No hashing
            $_SESSION['admin_id'] = $user['id']; // Store admin ID
            $_SESSION['admin_username'] = $user['username']; // Store username

            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            $error_message = "Invalid password."; // Error message
        }
    } else {
        $error_message = "User not found."; // Error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="navbar">
        <img src="elements\mini_Logo.png" alt="Task Connect Logo" class="nav_logo">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="logo">
        <img src="elements\login_logo.png" alt="Task Connect Logo">
    </div>

    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" id="email" name="email" placeholder="Email or Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div class="button-container">
                <button type="submit" class="login-btn">Login</button>
            </div>
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>

</body>

</html>