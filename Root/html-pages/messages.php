<?php
// messages.php

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
    <title>Messages - Roomify</title>
    <script src="../assets/js/theme.js"></script>
    <!-- CSS -->
<link rel="stylesheet" href="../assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/messages.css">
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
        <h2>Your Messages</h2>
        <div class="profile-header">
            <button class="match-switch" id="prev-match">&lt;</button>
            <div class="match-profile">
                <img id="match-pic" src="../assets/images/default-profile.png" alt="Profile Pic" />
                <span id="match-username">User Name</span>
            </div>
            <button class="match-switch" id="next-match">&gt;</button>
        </div>
        <div class="messages-section">
            <div class="message-thread">
                <div class="chat-box" id="chat-box">
                    <!-- Dynamic Messages -->
                </div>
                <div class="message-input-container">
                    <input type="text" id="message-input" class="message-input" placeholder="Type your message...">
                    <button id="send-message" class="send-message-button">Send</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const matchUserId = urlParams.get('match_id');
        
            // Fetch messages
            fetch(`/api/get_messages?user_id=${matchUserId}`)
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = '';
                    data.messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message', message.sender_id === currentUserId ? 'sent' : 'received');
                        messageElement.innerHTML = `<p><strong>${message.sender_name}:</strong> ${message.message_text}</p>`;
                        chatBox.appendChild(messageElement);
                    });
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        
            // Send message
            document.getElementById('send-message').addEventListener('click', () => {
                const messageText = document.getElementById('message-input').value.trim();
                if (!messageText) return;
        
                fetch('/Roomify/Root/api/send_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ receiver_id: matchUserId, message_text: messageText }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Append the new message to the chat
                        const chatBox = document.getElementById('chat-box');
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message', 'sent');
                        messageElement.innerHTML = `<p><strong>You:</strong> ${messageText}</p>`;
                        chatBox.appendChild(messageElement);
                        document.getElementById('message-input').value = '';
                        chatBox.scrollTop = chatBox.scrollHeight;
                    } else {
                        alert('Error sending message.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
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
