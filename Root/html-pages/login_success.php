<?php
// login_success.php

session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in; redirect to login page
    header('Location: /Roomify/Root/html-pages/login-page.html');
    exit();
}

// Proceed to display the profile dashboard
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful - Roomify</title>
    <script src="../assets/js/theme.js"></script>
    <!-- CSS -->
<link rel="stylesheet" href="../assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login_success.css">
    <link rel="stylesheet" href="../assets/css/background.css">
</head>

<body>
    <div class="success-container">
        <h2>Login Successful!</h2>
        <p>Welcome back to Roomify. You have been logged in successfully.</p>
        <a href="./profile-dash.php" class="login-button" onclick="navigateToDashboard(event)">Go to Dashboard</a>
        <div class="back-to-home">
            <a href="../home-page.html">Back to Home</a>
        </div>
    </div>
    <script>
    // Store the logged-in user's username
    const urlParams = new URLSearchParams(window.location.search);
    const username = urlParams.get('username');
    if (username) {
        localStorage.setItem('username', username);
        sessionStorage.setItem('username', username);
        console.log('Username stored:', username);
    }

    function navigateToDashboard(event) {
        event.preventDefault();
        const storedUsername = localStorage.getItem('username') || sessionStorage.getItem('username');
        console.log('Attempting to navigate with username:', storedUsername);

        if (storedUsername) {
            window.location.href = '/Roomify/Root/html-pages/profile-dash.php?username=' + encodeURIComponent(storedUsername);
        } else {
            console.log('No username found, redirecting to login...');
            window.location.href = '/Roomify/Root/html-pages/login-page.html';
        }
    }

        // 添加错误处理和调试信息
        window.onerror = function(msg, url, line) {
            console.log('Error: ' + msg);
            console.log('URL: ' + url);
            console.log('Line: ' + line);
            return false;
        };
        
    </script>
</body>
</html>