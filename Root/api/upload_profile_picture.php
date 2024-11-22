<?php
// upload_profile_picture.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if a file was uploaded
if (!isset($_FILES['profilePicture']) || $_FILES['profilePicture']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error.']);
    exit;
}

// Validate the uploaded file
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$file_type = $_FILES['profilePicture']['type'];

if (!in_array($file_type, $allowed_types)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
    exit;
}

// Save the file to the server (you may want to change the directory)
$upload_dir = 'uploads/profile_pictures/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$file_extension = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
$filename = 'profile_' . $user_id . '.' . $file_extension;
$filepath = $upload_dir . $filename;

if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $filepath)) {
    // Update the profile picture URL in the database
    $profilePictureUrl = '/uploads/profile_pictures/' . $filename;

    try {
        $stmt = $pdo->prepare('UPDATE user_profiles SET profile_picture = ? WHERE user_id = ?');
        $stmt->execute([$profilePictureUrl, $user_id]);

        echo json_encode(['success' => true, 'profilePictureUrl' => $profilePictureUrl]);
    } catch (PDOException $e) {
        // Handle SQL error
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error saving file.']);
}
?>
