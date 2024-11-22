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

// Include the database connection
require '../api/db_connect.php';

// Fetch user's profile data from the database
try {
    $stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();

    if (!$profile) {
        // Profile doesn't exist; set default values
        $profile = [
            'full_name' => "Click 'Edit Profile' to add your information",
            'age' => '',
            'occupation' => 'Occupation',
            'location' => 'Location',
            'gender' => 'Gender',
            'move_in_date' => 'Move-in Date',
            'budget' => 'Budget',
            'bio' => 'Tell us about yourself...',
            'profile_picture' => '../assets/images/default-profile.png',
        ];
    } else {
        // If profile picture is not set, use default
        if (empty($profile['profile_picture'])) {
            $profile['profile_picture'] = '../assets/images/default-profile.png';
        }
    }
} catch (PDOException $e) {
    // Handle database error
    echo "Error fetching profile data.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify - Profile</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style-dash.css">
    <link rel="stylesheet" href="../assets/css/background.css">
    <link rel="stylesheet" href="../assets/css/nav-bar.css">

    <!-- JavaScript -->
    <script src="../assets/js/theme.js" defer></script>
    <script src="../assets/js/load_user.js" defer></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="top-nav">
        <div class="logonav">
            <h1><a href="./profile-dash.html">Roomify</a></h1>
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

    <!-- Main Profile Section -->
    <main class="main-container">
        <section class="profile-section">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture" id="profile-pic">
                <button class="edit-photo-button" onclick="openEditModal()">Edit Profile</button>
            </div>
            <div class="profile-info">
                <h2 id="user-fullname">
                    <?php
                    echo htmlspecialchars($profile['full_name']);
                    if (!empty($profile['age'])) {
                        echo ', ' . htmlspecialchars($profile['age']);
                    }
                    ?>
                </h2>
                <p><strong>Occupation:</strong> <span id="user-occupation"><?php echo htmlspecialchars($profile['occupation']); ?></span></p>
                <p><strong>Location:</strong> <span id="user-location"><?php echo htmlspecialchars($profile['location']); ?></span></p>
                <p><strong>Gender:</strong> <span id="user-gender"><?php echo htmlspecialchars($profile['gender']); ?></span></p>
                <p><strong>Move-in Date:</strong> <span id="user-movein-date"><?php echo htmlspecialchars($profile['move_in_date']); ?></span></p>
                <p><strong>Budget:</strong> $<span id="user-budget"><?php echo htmlspecialchars($profile['budget']); ?></span></p>
                <p><strong>About Me:</strong> <span id="user-bio"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></span></p>
                <button class="edit-profile-button" onclick="openEditModal()">Edit Profile</button>
            </div>
        </section>

        <div class="find-roommate">
            <a href="./swipe-interface.php" class="find-roommate-button">Go Find Your Roommate!</a>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="edit-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2>Edit Profile</h2>
            <form id="profileForm" action="/Roomify/Root/api/update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profilePicture">Profile Picture:</label>
                    <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" min="18" max="100" value="<?php echo htmlspecialchars($profile['age']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="occupation">Occupation:</label>
                    <input type="text" id="occupation" name="occupation" value="<?php echo htmlspecialchars($profile['occupation']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($profile['location']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" <?php if ($profile['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($profile['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Non-binary" <?php if ($profile['gender'] == 'Non-binary') echo 'selected'; ?>>Non-binary</option>
                        <option value="Other" <?php if ($profile['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                        <option value="Prefer not to say" <?php if ($profile['gender'] == 'Prefer not to say') echo 'selected'; ?>>Prefer not to say</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="moveInDate">Move-in Date:</label>
                    <input type="date" id="moveInDate" name="moveInDate" value="<?php echo htmlspecialchars($profile['move_in_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="budget">Budget ($):</label>
                    <input type="number" id="budget" name="budget" min="0" value="<?php echo htmlspecialchars($profile['budget']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">About Me:</label>
                    <textarea id="bio" name="bio" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                </div>
                <button type="submit" class="save-button">Save Profile</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Roomify. All rights reserved.</p>
        <a href="./about.php">About Us</a> | 
        <a href="./privacy.php">Privacy Policy</a> | 
        <a href="./contact.php">Contact Us</a>
        <div class="social-media">
            <a href="https://www.facebook.com/"><img src="../assets/images/facebook-icon.png" alt="Facebook"></a>
            <a href="https://bsky.app/"><img src="../assets/images/twitter-icon.png" alt="Bluesky"></a>
            <a href="https://www.instagram.com/"><img src="../assets/images/instagram-icon.png" alt="Instagram"></a>
        </div>
    </footer>

    <script>
            // Event listeners for DOM content loaded and keyboard events
            document.addEventListener('DOMContentLoaded', function() {
                loadUserData();
                initializeOnlineStatus();
            });
        
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeEditModal();
                }
            });
        
            // Initialize online status indicator
            function initializeOnlineStatus() {
                const onlineIndicator = document.getElementById('online-indicator');
                onlineIndicator.classList.add(navigator.onLine ? 'online' : 'offline');
            }
        
            // Load user data from localStorage
            function loadUserData() {
                // Update navigation username
                document.getElementById('nav-username').textContent = userData.name?.split(' ')[0] || 'Click to add name';
    
            }
        
            // Update a field in the profile section
            function updateField(id, value) {
                const element = document.getElementById(`user-${id}`);
                if (element) {
                    element.textContent = value || 'Unknown';
                    element.classList.toggle('empty-field', !value);
                }
            }
        
            // Open and close the edit profile modal
            function openEditModal() {
                document.getElementById('editModal').style.display = 'flex';
            }
        
            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }
        
            // Save changes to user data
            function saveProfile(event) {
                event.preventDefault();
            
                const userData = {
                    name: document.getElementById('name').value,
                    age: document.getElementById('age').value,
                    occupation: document.getElementById('occupation').value,
                    location: document.getElementById('location').value,
                    gender: document.getElementById('gender').value,
                    moveInDate: document.getElementById('moveInDate').value,
                    budget: document.getElementById('budget').value,
                    bio: document.getElementById('bio').value,
                };
            
                // Send data to server
                fetch('/Roomify/Root/api/update_profile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(userData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI with new data
                        loadUserData();
                        closeEditModal();
                    } else {
                        alert('Error updating profile.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
            
        
            // Redirect to dashboard after saving changes
            function saveChanges(event) {
                saveProfile(event);
                window.location.href = '/Roomify/Root/html-pages/profile-dash.php';
            }
        
            // Handle photo upload functionality
            function handlePhotoUpload() {
                document.getElementById('photo-upload').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const formData = new FormData();
                        formData.append('profilePicture', file);
                
                        fetch('/Roomify/Root/api/upload_profile_picture.php', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('profile-pic').src = data.profilePictureUrl;
                            } else {
                                alert('Error uploading profile picture.');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                    }
                });
                
            };
        
            // Logout functionality
            function logout() {
                localStorage.removeItem('userData');
                localStorage.removeItem('profilePicture');
                window.location.href = '/Roomify/Root/html-pages/login-page.html';
            }        
    </script>
</body>
</html>
