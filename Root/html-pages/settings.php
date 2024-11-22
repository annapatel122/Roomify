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
    <title>Settings - Roomify</title>
    <!-- External CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style-dash.css">
    <link rel="stylesheet" href="../assets/css/nav-bar.css">
    <link rel="stylesheet" href="../assets/css/background.css">
    <link rel="stylesheet" href="../assets/css/settings.css"> <!-- Excluded inline CSS -->

    <!-- JavaScript -->
    <script src="../assets/js/theme.js" defer></script>
    <script src="../assets/js/settings.js" defer></script>
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
        <h2>Settings</h2>
        <div class="settings-container">
            <section class="settings-section">
                <!-- Account Settings -->
                <div class="settings-category">
                    <h3>Account Settings</h3>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" placeholder="Enter new password">
                    </div>
                    <button class="save-button">Save Changes</button>
                </div>
    
                <!-- Notification Settings -->
                <div class="settings-category">
                    <h3>Notification Settings</h3>
                    <div class="settings-checkbox">
                        <input type="checkbox" id="email-notifications">
                        <label for="email-notifications">Email Notifications</label>
                    </div>
                    <div class="settings-checkbox">
                        <input type="checkbox" id="sms-notifications">
                        <label for="sms-notifications">SMS Notifications</label>
                    </div>
                    <button class="save-button">Update Notifications</button>
                </div>
    
                <!-- Privacy Settings -->
                <div class="settings-category">
                    <h3>Privacy Settings</h3>
                    <div class="settings-checkbox">
                        <input type="checkbox" id="public-profile">
                        <label for="public-profile">Make Profile Public</label>
                    </div>
                    <div class="settings-checkbox">
                        <input type="checkbox" id="share-activity">
                        <label for="share-activity">Share Activity with Matches</label>
                    </div>
                    <button class="save-button">Save Privacy Preferences</button>
                </div>
    
                <!-- Theme Preference -->
                <div class="settings-category">
                    <h3>Theme Preference</h3>
                    <div class="form-group">
                        <label for="theme-preference">Theme:</label>
                        <select id="theme-preference" class="theme-select" onchange="toggleDarkMode()">
                            <option value="light">Light Mode</option>
                            <option value="dark">Dark Mode</option>
                        </select>
                    </div>
                    <button class="save-button">Apply Theme</button>
                </div>
    
                <!-- Language Preference -->
                <div class="settings-category">
                    <h3>Language Settings</h3>
                    <div class="form-group">
                        <label for="language">Language:</label>
                        <select id="language" class="theme-select">
                            <option value="en">English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <!-- Add more languages as needed -->
                        </select>
                    </div>
                    <button class="save-button">Save Preferences</button>
                </div>
            </section>
        </div>
    </main>
    

    <footer class="footer">
        <p>&copy; 2024 Roomify. All rights reserved.</p>
        <div>
            <a href="./about.php">About Us</a> |
            <a href="./privacy.php">Privacy Policy</a> |
            <a href="./contact.php">Contact Us</a>
        </div>
        <div class="social-media">
            <a href="https://www.facebook.com/"><img src="../assets/images/facebook-icon.png" alt="Facebook"></a>
            <a href="https://bsky.app/"><img src="../assets/images/twitter-icon.png" alt="Bluesky"></a>
            <a href="https://www.instagram.com/"><img src="../assets/images/instagram-icon.png" alt="Instagram"></a>
        </div>
    </footer>
</body>
</html>