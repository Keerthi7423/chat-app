<?php
session_start();
require_once "../config/db.php";

header('Content-Type: application/json'); // Send JSON responses

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in to send messages."]);
    exit;
}

$outgoing_id = $_SESSION['user_id'];
$incoming_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');
$type = "text";
$finalMessage = "";

// ✅ Validate
if (!$incoming_id) {
    echo json_encode(["status" => "error", "message" => "Receiver not found."]);
    exit;
}

// 🖼️ Handle image upload (optional)
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = "../uploads/chat_images/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type."]);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $type = "image";
        $finalMessage = "uploads/chat_images/" . $fileName; // relative path (for display)
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload image."]);
        exit;
    }
} else {
    // ✅ Text message validation
    if (empty($message)) {
        echo json_encode(["status" => "error", "message" => "Message cannot be empty."]);
        exit;
    }
    $finalMessage = $message;
}

// 💾 Insert into database
$sql = "INSERT INTO messages (sender_id, receiver_id, message, type, created_at) 
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $outgoing_id, $incoming_id, $finalMessage, $type);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Message sent successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error."]);
}

$stmt->close();
$conn->close();
?>
