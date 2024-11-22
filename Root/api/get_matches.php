<?php
// get_matches.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get matched user IDs
    $stmt = $pdo->prepare('SELECT CASE WHEN user1_id = ? THEN user2_id ELSE user1_id END AS matched_user_id
                           FROM user_matches
                           WHERE user1_id = ? OR user2_id = ?');
    $stmt->execute([$user_id, $user_id, $user_id]);
    $matched_user_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($matched_user_ids)) {
        echo json_encode(['success' => true, 'matches' => []]);
        exit;
    }

    // Get profile information for matched users
    $placeholders = implode(',', array_fill(0, count($matched_user_ids), '?'));
    $sql = 'SELECT u.id AS user_id, p.full_name, p.age, p.occupation, p.location, p.gender, p.move_in_date, p.budget, p.bio, p.profile_picture
            FROM users u
            INNER JOIN user_profiles p ON u.id = p.user_id
            WHERE u.id IN (' . $placeholders . ')';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($matched_user_ids);
    $matches = $stmt->fetchAll();

    echo json_encode(['success' => true, 'matches' => $matches]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
