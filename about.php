<?php
session_start(); // Start session to track login status
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Connect</title>
    <link rel="stylesheet" href="about.css">
    <link href="https://fonts.cdnfonts.com/css/made-tommy-outline" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

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

    <!-- About Us Section -->
    <section class="about-container">
        <h2>About Us</h2>
        <p>
            Founded in 2025, Task Connect is more than just a job-seeking platform—it's a bridge between talent and
            opportunity, whether online or in person. Our mission is to empower job seekers and employers by making the
            hiring process seamless, efficient, and accessible to everyone.
        </p> <br>
        <p>
            Task Connect was born from a simple idea: Finding a job shouldn't be complicated. Whether you're searching
            for remote gigs, freelance projects, or in-person roles, we provide the tools and connections you need to
            take control of your career. No more endless job searches or missed opportunities, Task Connect puts you in
            charge of your professional journey.
        </p><br>
        <p>
            At Task Connect, we believe in redefining job-seeking by offering smart matching, real-time updates, and
            secure communication. Our goal is to make finding work easier, faster, and more effective for everyone.
        </p><br>
        <p>
            Take the next step in your career with Task Connect, where opportunities meet talent.
        </p>
        <img src="elements\Setting.png" alt="">
    </section>

    <div class="container">
        <div class="title"><span>The</span> Developers</div>
        <div class="developer">
            <div class="developer_kresner">
                <img src="elements\kresner_photo.png" alt="">
                <p class="name"><span>Kresner</span> Leonardo</p>
                <p class="position">Project Manager</p>
                <p class="position"> Android Developer, and UI Designer, and Professional Mayabang</p>
            </div>

            <div class="developer_venus">
                <p class="name"><span>Venus</span> Sison</p>
                <p class="position">Android Developer</p>
            </div>

            <div class="developer_patrick">
                <img src="elements\patrick_photo.png" alt="">
                <p class="name"><span>Patrick</span> Cruz</p>
                <p class="position">Backend Developer</p>
            </div>

            <div class="developer_brent">
                <p class="name"><span>Brent</span> Espinoza</p>
                <p class="position">Web Developer</p>
            </div>

            <div class="developer_brian">
                <img src="elements\brian_photo.png" alt="">
                <p class="name"><span>Brian</span> Layno</p>
                <p class="position">Web Developer</p>
            </div>
        </div>
    </div>

</body>

</html>