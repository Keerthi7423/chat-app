<?php
session_start();
require_once "../config/db.php"; // adjust path if needed

// ✅ Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch all other users (limit optional)
$sql = "SELECT id, username, profile_pic, status FROM users WHERE id != ? ORDER BY status DESC, username ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .user-card {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 10px;
            transition: 0.3s ease;
        }
        .user-card:hover {
            transform: translateY(-3px);
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #ddd;
        }
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .status-online {
            background-color: #28a745;
        }
        .status-offline {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h3 class="mb-4 text-center">Users Online</h3>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): 
                    $profilePic = "../assets/uploads/profile_pics/" . (!empty($user['profile_pic']) ? htmlspecialchars(basename($user['profile_pic'])) : "default.png");
                    $statusClass = ($user['status'] === 'online') ? 'status-online' : 'status-offline';
                ?>
                    <div class="user-card">
                        <img src="<?= $profilePic ?>" alt="Profile" class="profile-pic">
                        <div>
                            <strong><?= htmlspecialchars($user['username']) ?></strong><br>
                            <span class="status-dot <?= $statusClass ?>"></span>
                            <?= htmlspecialchars(ucfirst($user['status'])) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">No other users found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
