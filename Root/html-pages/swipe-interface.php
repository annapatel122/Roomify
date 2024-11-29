<?php
// swipe-interface.php

session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in; redirect to login page
    header('Location: /Roomify/Root/html-pages/login-page.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
// Proceed to display the profile dashboard
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify - Find Your Roommate</title>
        <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style-swipe.css">
    <link rel="stylesheet" href="../assets/css/background.css">
    <link rel="stylesheet" href="../assets/css/nav-bar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="top-nav">
        <div class="logonav">
            <h1><a href="./profile-dash.php">Roomify</a></h1>
        </div>
        <div class="nav-items">
<!--Make the find roommate button here a small rounded button -->
            <a href="./swipe-interface.php">Find your Roommate!</a>
            <a href="#"> | </a>
            <a href="./matches.php">Matches</a>
            <a href="./messages.php">Messages</a>
            <a href="./settings.php">Settings</a>
            <a href="#"> | </a>
            <div id="online-indicator" class="online-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Online</span>
            </div>
            <span class="username">Hello, <span id="nav-username">Click to add name</span></span>
            <a href="./login-page.html" class="logout" onclick="logout()">Logout</a>
        </div>
    </nav>

    <div class="swipe-container" id="swipe-container">
        <div class="arrow left-arrow" id="left-arrow">❮</div>
    
        <div class="card" id="profile-card">
            <img src="../assets/images/default-profile.png" alt="Profile Picture">
            <h2></h2>
            <p><strong>Occupation:</strong> <span id="profile-occupation"></span></p>
            <p><strong>Location:</strong> <span id="profile-location"></span></p>
            <p><strong>Gender:</strong> <span id="profile-gender"></span></p>
            <p><strong>Move-in Date:</strong> <span id="profile-movein-date"></span></p>
            <p><strong>Budget:</strong> $<span id="profile-budget"></span></p>
            <p><strong>About Me:</strong> <span id="profile-bio"></span></p>
        </div>

        <div class="arrow right-arrow" id="right-arrow">❯</div>
    </div>


    <footer class="footer">
        <p>&copy; 2024 Roomify. All rights reserved.</p>
        <a href="./about.php">About Us</a> | 
        <a href="./privacy.php">Privacy Policy</a> | 
        <a href="./contact.php">Contact Us</a>
        <div class="social-media">
            <a href="https://www.facebook.com/"><img src="../assets/images/facebook-icon.png" alt="Facebook"></a>
            <!-- CHange link from twitter png to bluesky -->
            <a href="https://bsky.app/"><img src="../assets/images/twitter-icon.png" alt="Bluesky"></a>
            <a href="https://www.instagram.com/"><img src="../assets/images/instagram-icon.png" alt="Instagram"></a>
        </div>
    </footer>

                <!-- JavaScript -->
                <script src="../assets/js/swipe.js"></script>
                <script src="../assets/js/load_user.js" defer></script>
                <script src="../assets/js/theme.js"></script>
</body>
</html>
