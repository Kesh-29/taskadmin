<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="logo">
        <img src="task_connect.png" alt="Task Connect Logo">
    </div>

    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            <input type="email" id="email" name="email" placeholder="Email:" required>
            <input type="password" id="password" name="password" placeholder="Password:" required>
            <div class="button-container">
                <button type="submit" class="login-btn">Login</button>
            </div>
        </form>
        <p id="error-message" class="error"></p>


    </div>
</body>

</html>