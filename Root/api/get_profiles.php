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

    // Prepare placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($swiped_user_ids), '?'));

    // Get profiles excluding the current user and already swiped users
    $sql = 'SELECT u.id AS user_id, p.full_name, p.age, p.occupation, p.location, p.gender, p.move_in_date, p.budget, p.bio, p.profile_picture
            FROM users u
            INNER JOIN user_profiles p ON u.id = p.user_id
            WHERE u.id != ?';

    if (!empty($swiped_user_ids)) {
        $sql .= ' AND u.id NOT IN (' . $placeholders . ')';
        $params = array_merge([$user_id], $swiped_user_ids);
    } else {
        $params = [$user_id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $profiles = $stmt->fetchAll();

    echo json_encode(['success' => true, 'profiles' => $profiles]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
