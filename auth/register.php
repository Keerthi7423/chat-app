<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");
require '../vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // ✅ Check for existing email
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // ✅ Handle profile picture upload
    $profilePic = "";
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $uploadDir = "../assets/uploads/profile_pics/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath);
        $profilePic = $fileName;
    }

    // ✅ Generate OTP and expiry
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // ✅ Temporarily store data in session (insert only after verification)
    $_SESSION['register_user'] = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'profile_pic' => $profilePic,
        'otp' => $otp,
        'otp_expires_at' => $expires
    ];

    // ✅ Send OTP Email via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'itkeerthi74@gmail.com'; // your Gmail
        $mail->Password = 'svgw lwmt khbj vadh';   // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('itkeerthi74@gmail.com', 'Chat App Verification');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Chat App - Email Verification';
        $mail->Body = "
            <p>Hi <b>$username</b>,</p>
            <p>Your 6-digit OTP code is: <b>$otp</b></p>
            <p>This code will expire in 10 minutes.</p>
            <br>
            <p>Regards,<br>Chat App Team</p>
        ";

        $mail->send();

        $_SESSION['email'] = $email;
        header("Location: verify_otp.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: register.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Chat App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4 shadow">
        <h3 class="text-center mb-4">Create Account</h3>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control">
          </div>
          <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3">
          Already have an account? <a href="login.php">Login here</a>
        </p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
