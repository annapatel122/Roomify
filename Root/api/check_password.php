<?php
session_start();

// 数据库连接配置
$host = 'localhost';
$dbname = 'user_db';  // 确保数据库名称与实际一致
$db_username = 'root';
$db_password = '';

try {
    // 创建 PDO 连接
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $db_username, $db_password, $options);

    // 获取用户输入的用户名和密码
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 查询数据库中是否存在该用户名
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // 使用 password_verify 验证密码
        if (password_verify($password, $user['password'])) {
            // 如果密码正确，存储用户名到 session 并跳转到成功页面
            $_SESSION['username'] = $user['username'];
            header('Location: login_success.html?username=' . urlencode($username));
            exit();
        } else {
            // 密码错误
            header('Location: login-page.html?error=1');
            exit();
        }
    } else {
        // 用户名不存在
        header('Location: login-page.html?error=2');
        exit();
    }
} catch (PDOException $e) {
    // 处理数据库连接或查询错误
    error_log("Database Error: " . $e->getMessage(), 0);
    echo "A database error occurred. Please try again later.";
}
?>

