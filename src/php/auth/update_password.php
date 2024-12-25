<?php
require_once '../../../password_reset/database.php';
session_start();  // Start session at the top of the page

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if session has email
    if (!isset($_SESSION['email'])) {
        die("Session expired. Please restart the process.");
    }

    $email = $_SESSION['email'];  // Retrieve email from session

    // Ensure the password is being provided
    if (empty($_POST['password'])) {
        die("Please provide a new password.");
    }

    // Hash the new password
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Update the password in the database
    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $newPassword, $email);

    if ($stmt->execute()) {
        // Clear the session after the password change
        session_unset();  // This will remove the session variables without destroying the session completely
        session_destroy();  // This will completely destroy the session if you want

        echo "<script>alert('Password changed successfully!'); window.location.href='../../../login.html';</script>";
    } else {
        die("Error updating password: " . $stmt->error);
    }
}
?>
