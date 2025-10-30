<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['email'] ?? '';
$uploadDir = "../assets/uploads/profile_pics/";

// ✅ Fetch user details
$stmt = $conn->prepare("SELECT username, email, bio, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $bio, $profile_pic);
$stmt->fetch();
$stmt->close();

// ✅ Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];

    if ($file['error'] === 0) {
        // Ensure upload folder exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Rename file to avoid conflicts
        $fileName = time() . "_" . basename($file['name']);
        $targetFile = $uploadDir . $fileName;

        // Move file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Update DB with new filename
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("si", $fileName, $user_id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['success'] = "Profile picture updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to upload file.";
        }
    } else {
        $_SESSION['error'] = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center mb-4">My Profile</h2>

        <!-- ✅ Show alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-3">
            <?php 
$displayPic = "../assets/uploads/profile_pics/" . (!empty($profile_pic) ? $profile_pic : "default.png");
            ?>
            <img src="<?= htmlspecialchars($displayPic); ?>" 
                 alt="Profile Picture" 
                 class="profile-img mb-3">
        </div>

        <!-- ✅ Upload form -->
        <form method="POST" enctype="multipart/form-data" class="text-center mb-4">
            <div class="mb-3 w-50 mx-auto">
                <input type="file" name="profile_pic" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload New Picture</button>
        </form>

        <div class="text-center">
            <h4>Welcome, <?= htmlspecialchars($username); ?> 👋</h4>
            <p class="text-muted"><?= htmlspecialchars($email); ?></p>
            <?php if (!empty($bio)): ?>
                <p class="fst-italic">"<?= htmlspecialchars($bio); ?>"</p>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-success me-2">Back to Dashboard</a>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
