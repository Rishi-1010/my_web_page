<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Project</title>
    <link rel="stylesheet" href="src/assets/styles/main.css">
    <link rel="stylesheet" href="src/assets/styles/components.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">WebApp</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="src/php/auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="register.html">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container">
        <?php if ($isLoggedIn): ?>
            <!-- Content for logged-in users -->
            <section id="dashboard" class="dashboard">
                <h1>Welcome Back, <?php echo htmlspecialchars($username); ?>!</h1>
                <div class="dashboard-content">
                    <div class="feature-grid">
                        <div class="feature-card">
                            <h3>Your Dashboard</h3>
                            <p>Access your personalized features and settings.</p>
                        </div>
                        <!-- Add more feature cards for logged-in users -->
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Content for non-logged-in users -->
            <section id="home" class="hero">
                <h1>Welcome to Our Web App</h1>
                <p>A modern web application built with HTML, CSS, PHP, and JavaScript</p>
                <div class="cta-buttons">
                    <a href="register.html" class="btn primary">Get Started</a>
                    <a href="login.html" class="btn secondary">Already have an account?</a>
                </div>
            </section>

            <section id="benefits" class="benefits">
                <!-- Your existing benefits section -->
            </section>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <!-- Your existing footer content -->
    </footer>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="src/js/main.js"></script>
</body>
</html> 