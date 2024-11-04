<?php
// 假设你接收来自注册表单的用户输入
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// 使用 password_hash() 函数加密密码
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 创建数据库连接
$servername = "localhost";
$dbname = "roomify_db";
$db_username = "your_db_username"; // 数据库用户名
$db_password = "your_db_password"; // 数据库密码

// 连接到数据库
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 插入用户数据到数据库
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 关闭数据库连接
$conn->close();
?>
