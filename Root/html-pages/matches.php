<?php
// matches.php

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
    <title>Matches - Roomify</title>
    <script src="../assets/js/theme.js"></script>
    <!-- CSS -->
<link rel="stylesheet" href="../assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/matches.css">
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
        <h2>Your Matches</h2>
        <div class="matches-wrapper">
            <button class="swipe-button" id="prev-match">&lt;</button>
            <div class="matches-section">
                <!-- Match Card 1 -->
                <div class="match-card">
                    <img src="../assets/images/default-profile.png" alt="Match Profile Picture">
                    <div class="match-info">
                        <h3>User Name</h3>
                        <p><span class="field-title">Location:</span> City</p>
                        <p><span class="field-title">Shared Interests:</span> Cooking, Hiking</p>
                        <p><span class="field-title">Bio:</span> A brief description about the user.</p>
                        <a href="./messages.php" class="message-button">Message</a>
                    </div>
                </div>
                <!-- Match Card 2 -->
                <div class="match-card">
                    <img src="../assets/images/default-profile.png" alt="Match Profile Picture">
                    <div class="match-info">
                        <h3>Jane Doe</h3>
                        <p><span class="field-title">Location:</span> Springfield</p>
                        <p><span class="field-title">Shared Interests:</span> Reading, Traveling</p>
                        <p><span class="field-title">Bio:</span> A brief description about the user.</p>
                        <a href="./messages.php" class="message-button">Message</a>
                    </div>
            </div>
            <div class="match-card">
                <img src="../assets/images/default-profile.png" alt="Match Profile Picture">
                <div class="match-info">
                    <h3>John Doe</h3>
                    <p><span class="field-title">Location:</span> Clarksville</p>
                    <p><span class="field-title">Shared Interests:</span> Reading, Painting</p>
                    <p><span class="field-title">Bio:</span> A brief description about the user.</p>
                    <a href="./messages.php" class="message-button">Message</a>
                </div>
            <button class="swipe-button" id="next-match">&gt;</button>
        </div>
    </main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/Roomify/Root/api/get_matches.php')
            .then(response => response.json())
            .then(data => {
                const matchesSection = document.querySelector('.matches-section');
                matchesSection.innerHTML = ''; // Clear existing matches
    
                data.matches.forEach(match => {
                    const matchCard = document.createElement('div');
                    matchCard.classList.add('match-card');
                    matchCard.innerHTML = `
                        <img src="${match.profile_picture}" alt="Match Profile Picture">
                        <div class="match-info">
                            <h3>${match.full_name}</h3>
                            <p><span class="field-title">Location:</span> ${match.location}</p>
                            <p><span class="field-title">About Me:</span> ${match.bio}</p>
                            <a href="./messages.html?match_id=${match.user_id}" class="message-button">Message</a>
                        </div>
                    `;
                    matchesSection.appendChild(matchCard);
                });
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
    
</script>
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
