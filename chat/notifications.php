<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notifications for this user
$query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications - Chatly</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
  <div class="container">
    <span class="navbar-brand fw-bold">🔔 Notifications</span>
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">⬅ Back</a>
  </div>
</nav>

<div class="container">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Your Notifications</h5>
      <hr>

      <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
          <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?php echo htmlspecialchars($row['message']); ?>
              <small class="text-muted"><?php echo $row['created_at']; ?></small>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No notifications yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
