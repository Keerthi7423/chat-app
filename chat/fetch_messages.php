<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center text-danger mt-3'>Please log in to view messages.</p>";
    exit;
}

$incoming_id = $_POST['incoming_id'];
$outgoing_id = $_SESSION['user_id'];

$output = "";

// Fetch all messages between the two users
$sql = "SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Add inline styles (or move to CSS file)
$output .= '
<style>
.chat-box {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 10px;
}
.chat {
    display: flex;
    align-items: flex-start;
    margin-bottom: 8px;
}
.chat.incoming .message-bubble {
    background-color: #f1f0f0;
    color: #000;
    border-radius: 15px 15px 15px 0;
    padding: 8px 12px;
    max-width: 70%;
}
.chat.outgoing {
    justify-content: flex-end;
}
.chat.outgoing .message-bubble {
    background-color: #007bff;
    color: #fff;
    border-radius: 15px 15px 0 15px;
    padding: 8px 12px;
    max-width: 70%;
}
.message-bubble img {
    max-width: 220px;
    border-radius: 10px;
}
.message-bubble video {
    max-width: 250px;
    border-radius: 10px;
}
.message-time {
    font-size: 11px;
    color: #888;
    margin-top: 2px;
    text-align: right;
}
</style>
';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $msgType = $row['type'];
        $message = htmlspecialchars($row['message']);
        $time = date("H:i", strtotime($row['created_at']));

        if ($row['sender_id'] == $outgoing_id) {
            $output .= '<div class="chat outgoing">';
        } else {
            $output .= '<div class="chat incoming">';
        }

        // Message bubble
        $output .= '<div class="message-bubble">';
        if ($msgType === 'image') {
            $output .= '<img src="../' . $message . '" alt="image">';
        } elseif ($msgType === 'video') {
            $output .= '<video controls><source src="../' . $message . '" type="video/mp4"></video>';
        } else {
            $output .= nl2br($message);
        }
        $output .= '<div class="message-time">' . $time . '</div>';
        $output .= '</div></div>';
    }
} else {
    $output .= '<p class="text-center text-muted mt-3">No messages yet</p>';
}

echo $output;
?>
