
<?php
session_start();

if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
    echo "Request already processed.";
    exit;
}

require_once '../../../server/config/database.php';
require '../../../vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not found.";
        exit;
    }

    // Generate a unique token and expiration time
    try {
        $token = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        echo "Failed to generate a secure token: " . $e->getMessage();
        exit;
    }

    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Insert token into the database
    $query = "INSERT INTO password_reset (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $email, $token, $expiry, $token, $expiry);
    $stmt->execute();

    // Send reset link via email using PHPMailer
    $resetLink = "http://yourwebsite.com/reset-password.php?token=" . $token;
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:\n\n" . $resetLink;

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rishi.bardoliya@gmail.com'; // Your Gmail address
        $mail->Password = 'agdozqfepxrrohsk'; // Your Gmail password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        $mail->setFrom('rishi.bardoliya@gmail.com', 'WebApplication');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo "A password reset link has been sent to your email.";
    } catch (Exception $e) {
        echo "Failed to send reset email: {$mail->ErrorInfo}";
    }
}
?>
