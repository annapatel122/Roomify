<?php
// get_profiles.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get IDs of users the current user has already swiped on
    $stmt = $pdo->prepare('SELECT swiped_user_id FROM user_swipes WHERE swiper_user_id = ?');
    $stmt->execute([$user_id]);
    $swiped_user_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Initialize parameters array with the current user ID
    $params = [$user_id];

    // Build the base SQL query
    $sql = 'SELECT u.id AS user_id, p.full_name, p.age, p.occupation, p.location, p.gender, p.move_in_date, p.budget, p.bio, p.profile_picture
            FROM users u
            INNER JOIN user_profiles p ON u.id = p.user_id
            WHERE u.id != ?';

    // Add swiped user IDs to the query if any
    if (!empty($swiped_user_ids)) {
        // Add placeholders for each swiped user ID
        $placeholders = implode(',', array_fill(0, count($swiped_user_ids), '?'));
        $sql .= ' AND u.id NOT IN (' . $placeholders . ')';
        // Merge swiped user IDs into the parameters array
        $params = array_merge($params, $swiped_user_ids);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'profiles' => $profiles]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    error_log("Database error in get_profiles.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
