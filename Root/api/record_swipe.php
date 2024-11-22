<?php
// record_swipe.php

session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['swipeType']) || empty($data['swipedUserId'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$swipe_type = $data['swipeType']; // 'like' or 'dislike'
$swiped_user_id = $data['swipedUserId'];

if (!in_array($swipe_type, ['like', 'dislike'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid swipe type.']);
    exit;
}

try {
    // Record the swipe
    $stmt = $pdo->prepare('INSERT INTO user_swipes (swiper_user_id, swiped_user_id, swipe_type) VALUES (?, ?, ?)
                           ON DUPLICATE KEY UPDATE swipe_type = ?');
    $stmt->execute([$user_id, $swiped_user_id, $swipe_type, $swipe_type]);

    $is_match = false;

    if ($swipe_type === 'like') {
        // Check if the swiped user has also liked the current user
        $stmt = $pdo->prepare('SELECT swipe_type FROM user_swipes WHERE swiper_user_id = ? AND swiped_user_id = ?');
        $stmt->execute([$swiped_user_id, $user_id]);
        $swiped_user_swipe = $stmt->fetchColumn();

        if ($swiped_user_swipe === 'like') {
            // It's a match!
            // Insert into user_matches
            $stmt = $pdo->prepare('INSERT INTO user_matches (user1_id, user2_id) VALUES (?, ?)');
            // Ensure the lower ID is first for consistency
            $user_ids = [$user_id, $swiped_user_id];
            sort($user_ids);
            $stmt->execute($user_ids);

            $is_match = true;
        }
    }

    echo json_encode(['success' => true, 'match' => $is_match]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
