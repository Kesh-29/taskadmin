<?php
session_start(); // Start session to track login status
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect - Home</title>
    <link rel="stylesheet" href="home.css">

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body>
    <header class="navbar">
        <img src="elements\mini_Logo.png" alt="Task Connect Logo" class="logo">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <!-- If logged in, show Logout -->
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- If not logged in, show Login -->
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="hero">
            <h1>Welcome to TaskConnect!</h1>
            <p>Connecting People Together</p>
        </div>
        <section class="content">
            <p>The gig economy has become a vital source of income for many Filipinos, offering flexibility and
                opportunities to earn through short-term jobs or "mini jobs."
                Despite this, many individuals and businesses struggle to connectwith reliable workers for small tasks.
                This proposal introduces Task Connect, a mobile app
                designed to bridge this gap by connecting job seekers and employers efficiently.</p>
            <p>Task Connect is poised to revolutionize the way people in the Philippines find and offer short-term work.
                By offering a flexible, mobile-based platform that simplifies job matching, Task Connect will provide
                job seekers with new income opportunities while helping employers
                find reliable, on-demand assistance for their tasks. This project has the potential to enhance economic
                mobility and improve the livelihoods of thousands of Filipinos
                across the country.</p>
        </section>
    </main>
</body>

</html>