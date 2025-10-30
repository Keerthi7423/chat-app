<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

// 🔒 Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../chat/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Chat App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4 shadow">
        <h3 class="text-center mb-4">Login</h3>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <form action="verify_user.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <button type="submit" name="login" class="btn btn-success w-100">Login</button>
        </form>

        <p class="text-center mt-3">
          Don’t have an account? <a href="register.php">Register</a>
        </p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
