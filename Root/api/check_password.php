<?php 
session_start();

// Database connection configuration
$host = 'localhost';
$port = 3306;
$dbname = 'roomify_db';  // Ensure the database name matches the actual database
$db_username = 'root';
$db_password = '';

try {
    // Create a PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $db_username, $db_password, $options);

    // Retrieve and sanitize user input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: login-page.html?error=3'); // Error code 3: Fields cannot be empty
        exit();
    }

    // Query the database to check if the username exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // If password is correct, store the username in the session and redirect
            $_SESSION['username'] = $user['username'];
            header('Location: login_success.html?username=' . urlencode($username));
            exit();
        } else {
            // Password is incorrect
            header('Location: login-page.html?error=1'); // Error code 1: Incorrect password
            exit();
        }
    } else {
        // Username does not exist
        header('Location: login-page.html?error=2'); // Error code 2: Username not found
        exit();
    }
} catch (PDOException $e) {
    // Log the database error and show a generic error message
    error_log("Database Error: " . $e->getMessage(), 0);
    header('Location: login-page.html?error=4'); // Error code 4: Database error
    exit();
}
?>
