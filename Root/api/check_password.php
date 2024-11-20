<?php
session_start();


$host = 'localhost';
$port = 3306;
$dbname = 'user_db';  
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

    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        
        if (password_verify($password, $user['password'])) {
           
            $_SESSION['username'] = $user['username'];
            header('Location: /Roomify/Root/html-pages/login_success.html?username=' . urlencode($username));
            exit();
        } else {
          
            header('Location: /Roomify/Root/html-pages/login-page.html?error=1');
            exit();
        }
    } else {
       
        header('Location: /Roomify/Root/html-pages/login-page.html?error=2');
        exit();
    }
} catch (PDOException $e) {
    
    error_log("Database Error: " . $e->getMessage(), 0);
    echo "A database error occurred. Please try again later.";
}
?>

