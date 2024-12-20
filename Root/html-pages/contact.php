<?php
// profile-dash.php

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
    <title>Contact Us - Roomify</title>
    <script src="../assets/js/theme.js"></script>
    <!-- CSS -->
<link rel="stylesheet" href="../assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style-dash.css">
    <link rel="stylesheet" href="../assets/css/nav-bar.css">
    <link rel="stylesheet" href="../assets/css/background.css">

    <script src="../assets/js/load_user.js" defer></script>
</head>
<body>
    <nav class="top-nav">
        <div class="logonav">
            <h1><a href="./profile-dash.php">Roomify</a></h1>
        </div>
        <div class="nav-items">
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

    <main class="main-container">
        <h2>Contact Us</h2>
        <form action="#" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" required></textarea>
            </div>
            <button type="submit" class="send-message-button">Submit</button>
        </form>
    </main>

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
</body>
</html>
