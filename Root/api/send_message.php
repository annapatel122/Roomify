<?php
// send_message.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['receiver_id']) || empty($data['message_text'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Receiver ID and message text are required.']);
    exit;
}

$receiver_id = $data['receiver_id'];
$message_text = $data['message_text'];

try {
    // Check if the users are matched
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM user_matches WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)');
    $stmt->execute([$user_id, $receiver_id, $receiver_id, $user_id]);
    $is_matched = $stmt->fetchColumn();

    if (!$is_matched) {
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'message' => 'You are not matched with this user.']);
        exit;
    }

    // Insert the message
    $stmt = $pdo->prepare('INSERT INTO user_messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $receiver_id, $message_text]);

    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
