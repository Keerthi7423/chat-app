<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    $result = $conn->query("SELECT password FROM users WHERE id=$user_id");
    $row = $result->fetch_assoc();

    if (password_verify($current, $row['password'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id=$user_id");
        $message = "Password changed successfully!";
    } else {
        $message = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings - Chatly</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-cog text-secondary"></i> Settings</h3>
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  <div class="card shadow-sm p-4" style="max-width:600px; margin:auto;">
    <?php if ($message): ?>
      <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <h5>Change Password</h5>
    <form method="POST" class="mb-4">
      <div class="mb-3">
        <label>Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Update Password</button>
    </form>

    <hr>
    <h5>Theme</h5>
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="darkModeToggle">
      <label class="form-check-label" for="darkModeToggle">Enable Dark Mode</label>
    </div>

    <script>
      document.getElementById('darkModeToggle').addEventListener('change', function() {
        document.body.classList.toggle('bg-dark');
        document.body.classList.toggle('text-light');
      });
    </script>
  </div>
</div>

</body>
</html>
