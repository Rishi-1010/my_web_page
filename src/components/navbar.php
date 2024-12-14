<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
?>

<nav class="navbar">
    <div class="container">
        <div class="logo"><a href="/">WebApp</a></div>
        <ul class="nav-links">
            <li><a href="/GIT/my_web_page/">Home</a></li>
            <li><a href="/about.php">About</a></li>
            <li><a href="/contact.php">Contact</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="/dashboard.php">Dashboard</a></li>
                <li>
                    <a href="/GIT/my_web_page/profile-settings.php" class="profile-link" title="Profile Settings">
                        <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
                            <img src="uploads/profile_pictures/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" 
                                 alt="Profile Settings" 
                                 class="profile-picture-small"
                                 onerror="this.src='src/assets/images/default-avatar.png'">
                        <?php else: ?>
                            <img src="src/assets/images/default-avatar.png" 
                                 alt="Profile Settings" 
                                 class="profile-picture-small">
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="/GIT/my_web_page/src/php/auth/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/login.html">Login</a></li>
                <li><a href="/register.html">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav> 