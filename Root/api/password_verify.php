<?php
// 假设你接收来自登录表单的用户名和密码
$username = $_POST['username'];
$password = $_POST['password'];

// 创建数据库连接
$servername = "localhost";
$port = 3306;
$dbname = "user_db";
$db_username = "your_db_username"; // 数据库用户名
$db_password = "your_db_password"; // 数据库密码

// 连接到数据库
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 查询数据库，获取该用户的哈希密码
$sql = "SELECT password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 获取存储的哈希密码
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // 使用 password_verify() 验证密码
    if (password_verify($password, $hashed_password)) {
        echo "Login successful";
    } else {
        echo "Invalid username or password";
    }
} else {
    echo "Invalid username or password";
}

// 关闭数据库连接
$conn->close();
?>
