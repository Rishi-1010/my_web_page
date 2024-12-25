<?php
require_once '../../../password_reset/database.php';  // Include your database connection
require '../../../vendor/autoload.php';  // Include the PHPMailer autoloader

// Set the time zone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);  // Get the email from the form

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if the email exists in the users table
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the email doesn't exist in the database
    if ($result->num_rows === 0) {
        echo "<script>alert('No email found. Please register first.'); window.location.href='passwordreset.html';</script>";
        exit;
    }

    // Generate OTP
    $otp = random_int(100000, 999999);  // Generate a 6-digit OTP
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Insert OTP and expiry time into the password_reset table
    $query = "INSERT INTO password_reset (otp, expires_at) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $otp, $expiry);
    if (!$stmt->execute()) {
        die("Error saving OTP to database.");
    }

    // Send OTP via email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'rishi.bardoliya@gmail.com';  // Your Gmail address
        $mail->Password = 'wahttezqfhguywph';  // Use your app-specific password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  // Gmail SMTP port

        // Email settings
        $mail->setFrom('rishi.bardoliya@gmail.com', 'Web Application');
        $mail->addAddress($email);  // Recipient's email
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "Your OTP is: $otp. It is valid for 10 minutes.";

        // Send the email
        if ($mail->send()) {
            // If OTP is successfully sent, store email in session and redirect to the next page with email
            session_start();
            $_SESSION['email'] = $email;
            header("Location: ../../../password_reset/verifyotp.html?email=" . urlencode($email));
            exit;
        } else {
            die("Failed to send OTP.");
        }
    } catch (Exception $e) {
        // Catch PHPMailer errors
        die("Failed to send OTP: " . $mail->ErrorInfo);
    }
} else {
    die("Invalid request.");
}
?>
