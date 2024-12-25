<?php
require_once '../../../password_reset/database.php';

// Set the time zone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    
    // Validate the OTP format (must be 6 digits)
    if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
        die("Invalid OTP format.");
    }

    // Print the OTP and current time for debugging
    error_log("OTP: $otp");
    error_log("Current time: " . date("Y-m-d H:i:s"));
    
    // Check if the OTP exists and has not expired
    $query = "SELECT * FROM password_reset WHERE otp = ? AND expires_at > NOW()";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $otp); // Only bind OTP for this second query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid
        session_start();
        $_SESSION['otp_verified'] = true; // Store verification flag in session
        $_SESSION['otp'] = $otp; // Optionally store OTP in session if needed

        // Redirect to password update page
        header("Location: ../../../password_reset/Updatepassword.html");
        exit;
    } else {
        // Log the error and the query
        error_log("Invalid or expired OTP. Query: SELECT * FROM password_reset WHERE otp = '$otp' AND expires_at > NOW()");
        die("Invalid or expired OTP.");
    }
}
?>
