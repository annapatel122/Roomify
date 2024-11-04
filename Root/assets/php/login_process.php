<?php
session_start();

// 获取用户输入的用户名和密码
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 数据库连接配置
$host = 'localhost';
$port = 3306;
$dbname = 'user_db'; // 确保这里是 'user_db'
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

    // 查询用户信息
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 检查是否查询到用户
    if ($user) {
        echo 'User found: ';
        var_dump($user);  // 输出用户数据，确认查询结果

        // 验证用户输入的密码是否与数据库中的哈希密码匹配
        if (password_verify($password, $user['password'])) {
            echo 'Password verified successfully';
            $_SESSION['username'] = $user['username'];
            header("Location: login_success.html"); // 登录成功，跳转到成功页面
            exit();
        } else {
            echo 'Password verification failed'; // 密码验证失败
            header("Location: login-page.html?error=1");
            exit();
        }
    } else {
        echo 'User not found'; // 未找到用户
        header("Location: login-page.html?error=2");
        exit();
    }
} catch (PDOException $e) {
    // 处理数据库连接或查询错误
    error_log("Database Error: " . $e->getMessage(), 0);
    echo "A database error occurred. Please try again later.";
}
?>



