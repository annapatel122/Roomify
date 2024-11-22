<?php
// update_profile.php

session_start();
require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize input
$required_fields = ['name', 'age', 'occupation', 'location', 'gender', 'moveInDate', 'budget', 'bio'];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => "Field '$field' is required."]);
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
        $sql = 'UPDATE user_profiles SET full_name = ?, age = ?, occupation = ?, location = ?, gender = ?, move_in_date = ?, budget = ?, bio = ? WHERE user_id = ?';
        $params = [
            $data['name'],
            $data['age'],
            $data['occupation'],
            $data['location'],
            $data['gender'],
            $data['moveInDate'],
            $data['budget'],
            $data['bio'],
            $user_id
        ];
    } else {
        // Insert new profile
        $sql = 'INSERT INTO user_profiles (user_id, full_name, age, occupation, location, gender, move_in_date, budget, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = [
            $user_id,
            $data['name'],
            $data['age'],
            $data['occupation'],
            $data['location'],
            $data['gender'],
            $data['moveInDate'],
            $data['budget'],
            $data['bio']
        ];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} catch (PDOException $e) {
    // Handle SQL error
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
