<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once("../config/db.php");

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 🧩 Check if both fields are filled
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in both email and password.";
        header("Location: login.php");
        exit();
    }

    // 🔍 Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Verify password
        if (password_verify($password, $row['password'])) {
            // ✅ Store user info in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // ✅ Update status to 'online'
            $update = $conn->prepare("UPDATE users SET status = 'online' WHERE id = ?");
            $update->bind_param("i", $row['id']);
            $update->execute();

            // ✅ Redirect to dashboard
            header("Location: ../chat/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "No account found with that email!";
    }

    // 🔁 Redirect back to login with error
    header("Location: login.php");
    exit();
} else {
    // 🚫 If accessed directly
    header("Location: login.php");
    exit();
}
?>
