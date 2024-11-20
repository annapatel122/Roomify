<?php
// Assuming you have a session or some form of authentication
session_start();

// Database connection
$servername = "localhost";
$dbname = "user_db";
$db_username = "your_db_username"; 
$db_password = "your_db_password"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the user ID from the session
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId) {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['username' => $user['username']]);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        echo json_encode(['error' => 'User not logged in']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
