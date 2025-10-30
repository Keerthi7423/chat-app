<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch logged-in user details
$stmt = $conn->prepare("SELECT username, email, profile_pic, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ✅ Set safe defaults
$userName = htmlspecialchars($user['username'] ?? 'User');
$userAvatar = "../" . (!empty($user['profile_pic']) 
                ? "assets/uploads/profile_pics/" . htmlspecialchars(basename($user['profile_pic'])) 
                : "assets/uploads/profile_pics/default.png");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <style>
    body { transition: background 0.3s, color 0.3s; }
    .sidebar { height: 100vh; background: #fff; border-right: 1px solid #dee2e6; padding-top: 20px; }
    .sidebar a { display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none; transition: all 0.2s; }
    .sidebar a:hover { background: #f1f1f1; border-radius: 8px; }
    .sidebar i { margin-right: 12px; }
    .card-online { border-left: 5px solid #28a745; }
    .toggle-mode { cursor: pointer; }
    .dark-mode { background: #121212; color: #e0e0e0; }
    .dark-mode .sidebar { background: #1e1e1e; border-right: 1px solid #333; }
    .dark-mode .sidebar a { color: #e0e0e0; }
    .dark-mode .sidebar a:hover { background: #333; }
    .dark-mode .card { background: #1e1e1e; border-color: #333; }
  </style>
</head>
<body class="bg-light">

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column flex-shrink-0 p-3">
    <a href="profile.php" class="mb-4 text-center">
      <img src="<?= $userAvatar; ?>" width="60" height="60" class="rounded-circle mb-2" alt="Profile">
      <div><?= $userName; ?></div>
    </a>
    <hr>
    <a href="index.php"><i class="fas fa-comments"></i> Chats</a>
    <a href="users.php"><i class="fas fa-users"></i> Users</a>
    <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    <div class="mt-auto">
      <a class="toggle-mode"><i class="fas fa-moon"></i> Dark Mode</a>
      <a href="../auth/logout.php" class="text-danger mt-2"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Welcome, <?= $userName; ?> 👋</h2>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card p-3 shadow-sm">
          <i class="fas fa-comments fa-2x text-primary"></i>
          <h5 class="mt-2">Total Chats</h5>
          <p class="mb-0 fs-5">
            <?php
            $total_chats = $conn->query("SELECT COUNT(*) AS total FROM messages")->fetch_assoc()['total'] ?? 0;
            echo $total_chats;
            ?>
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 shadow-sm card-online">
          <i class="fas fa-user-check fa-2x text-success"></i>
          <h5 class="mt-2">Online Users</h5>
          <p class="mb-0 fs-5">
            <?php
            $online_users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status='online' AND id != $user_id")->fetch_assoc()['total'] ?? 0;
            echo $online_users;
            ?>
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 shadow-sm">
          <i class="fas fa-bell fa-2x text-danger"></i>
          <h5 class="mt-2">Notifications</h5>
          <p class="mb-0 fs-5">0</p>
        </div>
      </div>
    </div>

    <!-- Users Online -->
    <h4>Users Online</h4>
    <div class="row g-3">
      <?php
      $users = $conn->query("SELECT username, profile_pic, status FROM users WHERE id != $user_id LIMIT 6");
      if ($users && $users->num_rows > 0):
          while ($u = $users->fetch_assoc()):
              $pic = "../assets/uploads/profile_pics/" . (!empty($u['profile_pic']) ? htmlspecialchars(basename($u['profile_pic'])) : "default.png");
      ?>
      <div class="col-md-2 col-4">
        <div class="card p-2 text-center shadow-sm <?= strtolower(trim($u['status'])) === 'online' ? 'card-online' : ''; ?>">
          <img src="<?= $pic; ?>" width="60" height="60" class="rounded-circle mb-2" alt="User">
          <div><?= htmlspecialchars($u['username']); ?></div>
          <small class="<?= strtolower(trim($u['status'])) === 'online' ? 'text-success' : 'text-muted'; ?>">
            <?= ucfirst($u['status']); ?>
          </small>
        </div>
      </div>
      <?php
          endwhile;
      else:
          echo "<p class='text-muted'>No users found.</p>";
      endif;
      ?>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.toggle-mode').on('click', function(){
    $('body').toggleClass('dark-mode');
    $(this).find('i').toggleClass('fa-moon fa-sun');
});
</script>

</body>
</html>
