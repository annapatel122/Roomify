<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$host = 'localhost';
$port = 3306;
$dbname = 'roomify_db';
$db_username = 'root';
$db_password = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $db_username, $db_password, $options);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        echo 'User found: ';
        var_dump($user);  

        if (password_verify($password, $user['password'])) {
            echo 'Password verified successfully';
            $_SESSION['username'] = $user['username'];
            header("Location: login_success.html"); 
            exit();
        } else {
            echo 'Password verification failed'; 
            header("Location: login-page.html?error=1");
            exit();
        }
    } else {
        echo 'User not found'; 
        header("Location: login-page.html?error=2");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage(), 0);
    echo "A database error occurred. Please try again later.";
}
?>



