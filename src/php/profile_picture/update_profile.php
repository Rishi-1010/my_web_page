<?php
session_start();
require_once __DIR__ . '/../../../src/php/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login.html');
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $updates = [];
    $params = [];

    // Handle email update
    if (!empty($_POST['email'])) {
        $updates[] = "email = ?";
        $params[] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    }

    // Handle password update
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $updates[] = "password = ?";
            $params[] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        } else {
            $_SESSION['error'] = "Passwords do not match!";
            header('Location: ../../../profile-settings.php');
            exit();
        }
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $upload_dir = '../../../uploads/profile_pictures/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = $user_id . '_' . time() . '.' . $ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                $updates[] = "profile_picture = ?";
                $params[] = $new_filename;
                $_SESSION['profile_picture'] = $new_filename;
            }
        }
    }

    // Only proceed if there are updates to make
    if (!empty($updates)) {
        $params[] = $user_id; // Add user_id for WHERE clause
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($params)) {
            $_SESSION['success'] = "Profile updated successfully!";
            
            // Update session email if it was changed
            if (!empty($_POST['email'])) {
                $_SESSION['email'] = $_POST['email'];
            }
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

header('Location: ../../../profile-settings.php');
exit();