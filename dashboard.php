<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/assets/styles/main.css">
    <link rel="stylesheet" href="src/assets/styles/components.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'src/components/navbar.php'; ?>

    <main class="container">
        <section class="dashboard">
            <div class="dashboard-header">
                <div class="user-welcome">
                    <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
                        <img src="uploads/profile_pictures/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>"
                             alt="Profile Picture"
                             class="dashboard-profile-picture"
                             onerror="this.src='src/assets/images/default-avatar.png'">
                    <?php else: ?>
                        <img src="src/assets/images/default-avatar.png"
                             alt="Default Profile Picture"
                             class="dashboard-profile-picture">
                    <?php endif; ?>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                </div>
            </div>
            <div class="dashboard-content">
            </div>
        </section>
    </main>

    <script src="src/js/main.js"></script>
</body>
</html> 