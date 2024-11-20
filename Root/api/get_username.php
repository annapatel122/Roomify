<?php
// Assuming you have a session or some form of authentication
session_start();
// Set the content type to JSON
header('Content-Type: application/json');

// Suppress PHP errors for cleaner JSON output (useful for debugging)
error_reporting(0);
ini_set('display_errors', 0);
// Database connection
$servername = "localhost";
$port = 3306;
$dbname = "user_db";
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the user ID from the session
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId) {
        // Fetch username
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