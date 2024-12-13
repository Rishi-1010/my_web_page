<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update username and email
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);

        // Update password if provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
        }

        // Commit transaction
        $pdo->commit();

        // Update session
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        header('Location: ../../../profile-settings.php?success=1');
        exit();

    } catch(PDOException $e) {
        $pdo->rollBack();
        die("Error updating profile: " . $e->getMessage());
    }
}
?>
