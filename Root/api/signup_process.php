<?php

// signup_process.php

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';
$port = 3306;
$dbname = 'roomify_db'; // 将 'user_db' 改为 'roomify_db'
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

        // Check if user already exists
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $check_stmt->execute([$username, $email]);
        $user_exists = $check_stmt->fetchColumn();

        if ($user_exists) {
            // User already exists, redirect to account_exists.html
            header("Location: account_exists.html");
            exit();
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
        // Username or email already exists, redirect to account_exists.html
        header("Location: account_exists.html");
        exit();
    } else {
        // Log the error for later review
        error_log("Database Error: " . $e->getMessage(), 0);
        echo "A database error occurred. Please try again later.";
    }
} catch (Exception $e) {
    // Handle other errors
    // Log the error for later review
    error_log("Error: " . $e->getMessage(), 0);
    echo "An error occurred: " . $e->getMessage();
}

?>
