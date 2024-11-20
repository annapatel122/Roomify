<?php
session_start();
header('Content-Type: application/json');
error_log("User ID: " . print_r($_SESSION['user_id'], true));

$host = 'localhost';
$db = 'user_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$userRecord = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userRecord) {
    echo json_encode(['username' => $userRecord['username']]);
} else {
    echo json_encode(['error' => 'User not found']);
}
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
