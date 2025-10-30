<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'itkeerthi74@gmail.com';
    $mail->Password = 'svgw lwmt khbj vadh';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('itkeerthi74@gmail.com', 'HealthHive Test');
    $mail->addAddress('itkeerthi74@gmail.com'); // send to self

    $mail->isHTML(true);
    $mail->Subject = 'Testing Gmail SMTP';
    $mail->Body    = 'If you receive this, Gmail SMTP is working!';

    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';

    $mail->send();
    echo "✅ Test email sent successfully!";
} catch (Exception $e) {
    echo "❌ Error: {$mail->ErrorInfo}";
}
?>
