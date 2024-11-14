<?php
$username = $_POST['username'];
$password = $_POST['password'];

$servername = "localhost";
$dbname = "roomify_db";
$db_username = "your_db_username"; 
$db_password = "your_db_password"; 

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    
    if (password_verify($password, $hashed_password)) {
        echo "Login successful";
    } else {
        echo "Invalid username or password";
    }
} else {
    echo "Invalid username or password";
}

$conn->close();
?>
