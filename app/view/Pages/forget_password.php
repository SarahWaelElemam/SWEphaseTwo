<?php
require '../../../vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'send_code') {
        $email = $_POST['email'];
        $code = rand(1000, 9999); // Generate 4-digit code
        $_SESSION['verification_code'] = $code;
        $_SESSION['verification_email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sarahwaelelemam@gmail.com'; // Your email
            $mail->Password = 'your-app-password'; // Your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption method
            $mail->Port = 587; // Use port 587 for TLS

            $mail->setFrom('sarahwaelelemam@gmail.com', 'Sarah Wael'); // Your email and name
            $mail->addAddress($email); // Recipient email

            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body = "Your verification code is: <b>$code</b>";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Code sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Mail error: ' . $mail->ErrorInfo]);
        }
    } elseif ($action === 'verify_code') {
        $enteredCode = $_POST['code'];
        if ($_SESSION['verification_code'] == $enteredCode) {
            echo json_encode(['status' => 'success', 'message' => 'Code verified successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect code!']);
        }
    } elseif ($action === 'reset_password') {
        $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = $_SESSION['verification_email'];

        // Replace this with your database update logic
        // Example: Update the user's password in the database
        // $db->query("UPDATE users SET password = '$newPassword' WHERE email = '$email'");

        unset($_SESSION['verification_code']);
        unset($_SESSION['verification_email']);
        echo json_encode(['status' => 'success', 'message' => 'Password reset successfully!']);
    }
}

