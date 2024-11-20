<?php

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$servername = "localhost";
$port = 3306;
$dbname = "user_db";
$db_username = "root"; 
$db_password = ""; 


$conn = new mysqli($servername, $db_username, $db_password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>
