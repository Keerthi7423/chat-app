<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['register_user'])) {
    header("Location: register.php");
    exit();
}

$user = $_SESSION['register_user'];

// ✅ Handle OTP submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    $stored_otp = $user['otp'];
    $expires = $user['otp_expires_at'];

    if ($entered_otp == $stored_otp) {
        if (strtotime($expires) > time()) {
            // ✅ OTP valid — Insert user into DB
            $stmt = $conn->prepare("
                INSERT INTO users (username, email, password, profile_pic, status, created_at, is_verified) 
                VALUES (?, ?, ?, ?, 'online', NOW(), 1)
            ");
            $stmt->bind_param("ssss", $user['username'], $user['email'], $user['password'], $user['profile_pic']);

            if ($stmt->execute()) {
                // ✅ Auto login after registration
                $user_id = $stmt->insert_id;

                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                unset($_SESSION['register_user']); // clean up temp data

                header("Location: ../chat/dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Something went wrong while creating your account.";
            }
        } else {
            $_SESSION['error'] = "OTP expired. Please register again.";
            unset($_SESSION['register_user']);
            header("Location: register.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow">
                <h3 class="text-center mb-4">Verify Your Email</h3>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Enter OTP</label>
                        <input type="text" name="otp" class="form-control text-center fs-4" maxlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                </form>

                <p class="text-center mt-3 text-muted">
                    Check your email for the 6-digit OTP.
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
