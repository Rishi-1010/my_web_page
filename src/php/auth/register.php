<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Update the path to match your project structure
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: Print received data
    echo "Received POST data:<br>";
    print_r($_POST);
    echo "<br><br>";

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        die('All fields are required');
    }

    if ($password !== $confirm_password) {
        die('Passwords do not match');
    }

    try {
        // Debug: Verify database connection
        echo "Checking database connection...<br>";
        if ($pdo) {
            echo "Database connection successful!<br><br>";
        }

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            die('Email already exists');
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$username, $email, $hashed_password]);

        if ($result) {
            echo "Registration successful! User added to database.<br>";
            echo "Redirecting to login page in 3 seconds...";
            header("refresh:3;url=../../../login.html");
        } else {
            echo "Registration failed.<br>";
            echo "PDO Error Info:<br>";
            print_r($stmt->errorInfo());
        }

    } catch(PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    } catch(Exception $e) {
        echo "General Error: " . $e->getMessage();
    }
}
?>
