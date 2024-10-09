<?php
// signup_process.php

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';
$port = 3306;
$dbname = 'user_db';
$username = 'root';
$password = '';

// Main program logic
try {
    // Connect to the database
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize input
        $username = trim($_POST['username'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("All fields are required.");
        }

        // Additional username validation
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new Exception("Username can only contain letters, numbers, and underscores.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        // Execute the statement
        if ($stmt->execute([$username, $email, $hashed_password])) {
            // User registered successfully, redirect to success page
            header("Location: signup_success.html");
            exit();
        } else {
            throw new Exception("Error occurred while registering user.");
        }
    }
} catch (PDOException $e) {
    // Handle database errors
    if ($e->getCode() == '23000') {
        echo "Error: Username or email already exists.";
    } else {
        echo "Database Error: " . $e->getMessage();
    }
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage();
}
?>
