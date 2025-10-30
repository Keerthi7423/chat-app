<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

// ✅ If user is logged in, set them offline
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("UPDATE users SET status = 'offline' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// ✅ Destroy session safely
session_unset();
session_destroy();

// ✅ Redirect to login page
header("Location: login.php");
exit();
?>
