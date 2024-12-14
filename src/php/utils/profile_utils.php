<?php
function getRandomProfilePicture() {
    // Array of placeholder image services (using DiceBear API)
    $avatarStyles = ['adventurer', 'avataaars', 'bottts', 'initials', 'micah'];
    $style = $avatarStyles[array_rand($avatarStyles)];
    
    // Generate a random seed
    $seed = uniqid();
    
    // Generate the URL
    $avatarUrl = "https://api.dicebear.com/6.x/{$style}/png?seed={$seed}";
    
    // Get the image content
    $imageContent = file_get_contents($avatarUrl);
    
    if ($imageContent) {
        // Create uploads directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../../uploads/profile_pictures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate filename
        $filename = 'random_' . time() . '_' . $seed . '.png';
        $filepath = $uploadDir . $filename;
        
        // Save the file
        file_put_contents($filepath, $imageContent);
        
        return $filename;
    }
    
    return null;
}
?> 