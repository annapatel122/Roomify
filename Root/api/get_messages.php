<?php
// get_messages.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (empty($_GET['user_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Matched user ID is required.']);
    exit;
}

$matched_user_id = $_GET['user_id'];

try {
    // Check if the users are matched
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM user_matches WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)');
    $stmt->execute([$user_id, $matched_user_id, $matched_user_id, $user_id]);
    $is_matched = $stmt->fetchColumn();

    if (!$is_matched) {
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'message' => 'You are not matched with this user.']);
        exit;
    }

    // Retrieve messages
    $stmt = $pdo->prepare('SELECT m.*, u.full_name AS sender_name
                           FROM user_messages m
                           INNER JOIN users u ON m.sender_id = u.id
                           WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                           ORDER BY sent_at ASC');
    $stmt->execute([$user_id, $matched_user_id, $matched_user_id, $user_id]);
    $messages = $stmt->fetchAll();

    echo json_encode(['success' => true, 'messages' => $messages]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
