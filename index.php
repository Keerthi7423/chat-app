<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // If logged in → go to chat dashboard
    header("Location: chat/dashboard.php");
} else {
    // If not logged in → go to login page
    header("Location: auth/login.php");
}
exit();
?>
