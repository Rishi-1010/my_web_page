<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/profile_utils.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Get current profile picture
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $current_picture = $stmt->fetchColumn();

    // Delete current profile picture file if it exists and is not a random one
    if ($current_picture && strpos($current_picture, 'random_') === false) {
        $filepath = __DIR__ . '/../../../uploads/profile_pictures/' . $current_picture;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // Generate new random profile picture
    $new_profile_picture = getRandomProfilePicture();

    if (!$new_profile_picture) {
        throw new Exception('Failed to generate new profile picture');
    }

    // Update user's profile picture in database
    $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
    $stmt->execute([$new_profile_picture, $user_id]);

    // Update session
    $_SESSION['profile_picture'] = $new_profile_picture;

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'new_picture' => $new_profile_picture,
        'message' => 'Profile picture updated successfully'
    ]);

} catch(Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?> 