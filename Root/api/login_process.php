<?php
session_start();
error_log("Session user_id: " . $_SESSION['user_id']);

// Get user input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Database connection config
$host = 'localhost';
$port = 3306;
$dbname = 'user_db'; // Ensure this matches your database name
$db_username = 'root';
$db_password = '';

try {
    // Create PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $db_username, $db_password, $options);

    // Query user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $fetchedUser = $stmt->fetch(); // Changed variable name from $user to $fetchedUser
    
    if ($fetchedUser) {
        if (password_verify($password, $fetchedUser['password'])) {
            $_SESSION['user_id'] = $fetchedUser['id']; // Store user ID
            $_SESSION['username'] = $fetchedUser['username'];
    
            header("Location: /Roomify/Root/html-pages/login_success.html");
            exit();
        } else {
            header("Location: /Roomify/Root/html-pages/login-page.html?error=1");
            exit();
        }
    } else {
        header("Location: /Roomify/Root/html-pages/login-page.html?error=2");
        exit();
    }
} catch (PDOException $e) {
    // Handle database errors
    error_log("Database Error: " . $e->getMessage(), 0);
    echo "A database error occurred. Please try again later.";
}
?>
