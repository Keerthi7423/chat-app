<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require '../config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP and email in session for verification
    $_SESSION['email'] = $email;
    $_SESSION['otp'] = $otp;

    // Send email
    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'itkeerthi74@gmail.com';
        $mail->Password = 'svgw lwmt khbj vadh'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('itkeerthi74@gmail.com', 'HealthHive Verification');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code - HealthHive';
        $mail->Body = "
            <div style='font-family:Arial,sans-serif;padding:20px;'>
                <h2 style='color:#2E86C1;'>HealthHive Email Verification</h2>
                <p>Your OTP code is:</p>
                <h3 style='color:#27AE60;'>$otp</h3>
                <p>This code will expire in 5 minutes.</p>
                <br><p>Thank you,<br>HealthHive Team</p>
            </div>
        ";

        // Send
        $mail->send();

        // Redirect to OTP page
        header("Location: verify_otp.php");
        exit();
    } catch (Exception $e) {
        echo "<div style='color:red;'>Mailer Error: {$mail->ErrorInfo}</div>";
    }
} else {
    echo "<div style='color:red;'>Invalid request</div>";
}
?>
