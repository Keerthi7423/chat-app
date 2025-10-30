<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;

if ($receiver_id <= 0) {
    echo "Invalid receiver.";
    exit;
}

if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
    $img_name = $_FILES['image']['name'];
    $img_tmp = $_FILES['image']['tmp_name'];
    $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($img_ext, $allowed)) {
        $new_name = time() . "_" . uniqid() . "." . $img_ext;
        $upload_path = "../uploads/" . $new_name;

        if (move_uploaded_file($img_tmp, $upload_path)) {
            $sql = "INSERT INTO messages (sender_id, receiver_id, message, created_at, type)
                    VALUES (?, ?, ?, NOW(), 'image')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $sender_id, $receiver_id, $new_name);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Database insert failed: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid file type. Allowed: jpg, jpeg, png, gif.";
    }
} else {
    echo "No image selected.";
}
?>
