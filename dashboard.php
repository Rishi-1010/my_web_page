<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to Dashboard</h1>
    <p>Hello, <?php echo $_SESSION['username']; ?></p>
</body>
</html> 