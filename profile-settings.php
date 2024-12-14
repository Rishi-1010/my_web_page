<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="src/assets/styles/main.css">
    <link rel="stylesheet" href="src/assets/styles/components.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'src/components/navbar.php'; ?>

    <main class="container">
        <section class="profile-settings">
            <div class="profile-header">
                <div class="current-profile-picture">
                    <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
                        <img src="uploads/profile_pictures/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" 
                             alt="Current Profile Picture"
                             onerror="this.src='src/assets/images/default-avatar.png'">
                    <?php else: ?>
                        <img src="src/assets/images/default-avatar.png" 
                             alt="Default Profile Picture">
                    <?php endif; ?>
                </div>
                <h1>Profile Settings</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="profile-form">
                <form action="src/php/profile_picture/update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Update Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                        <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
                            <button type="button" class="btn secondary" id="removeProfilePicture">Remove Profile Picture</button>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" class="btn primary">Save Changes</button>
                </form>
            </div>
        </section>
    </main>

    <script src="src/js/profile-settings.js"></script>
</body>
</html>
