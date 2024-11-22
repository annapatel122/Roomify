<?php
// update_profile.php

session_start();
require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo "User not authenticated.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $required_fields = ['name', 'age', 'occupation', 'location', 'gender', 'moveInDate', 'budget', 'bio'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo "Field '$field' is required.";
            exit;
        }
        // Sanitize input
        $$field = htmlspecialchars(trim($_POST[$field]));
    }

    // Handle profile picture upload if included
    $profilePictureUrl = null;
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        // Validate the uploaded file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['profilePicture']['type'];

        if (!in_array($file_type, $allowed_types)) {
            echo "Invalid file type.";
            exit;
        }

        // Save the file to the server
        $upload_dir = '../uploads/profile_pictures/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $user_id . '.' . $file_extension;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $filepath)) {
            // File uploaded successfully
            $profilePictureUrl = '/Roomify/Root/uploads/profile_pictures/' . $filename;
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // Prepare and execute the query
    try {
        // Check if profile exists
        $stmt = $pdo->prepare('SELECT user_id FROM user_profiles WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $exists = $stmt->fetch();

        if ($exists) {
            // Update existing profile
            $sql = 'UPDATE user_profiles SET full_name = ?, age = ?, occupation = ?, location = ?, gender = ?, move_in_date = ?, budget = ?, bio = ?';
            $params = [
                $name,
                $age,
                $occupation,
                $location,
                $gender,
                $moveInDate,
                $budget,
                $bio,
            ];

            if ($profilePictureUrl) {
                $sql .= ', profile_picture = ?';
                $params[] = $profilePictureUrl;
            }

            $sql .= ' WHERE user_id = ?';
            $params[] = $user_id;
        } else {
            // Insert new profile
            $sql = 'INSERT INTO user_profiles (user_id, full_name, age, occupation, location, gender, move_in_date, budget, bio, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $params = [
                $user_id,
                $name,
                $age,
                $occupation,
                $location,
                $gender,
                $moveInDate,
                $budget,
                $bio,
                $profilePictureUrl,
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Redirect back to profile-dash.php
        header('Location: /Roomify/Root/html-pages/profile-dash.php');
        exit();
    } catch (PDOException $e) {
        // Handle SQL error
        echo "Database error.";
        exit;
    }
} else {
    // Invalid request method
    echo "Invalid request.";
    exit;
}
?>
