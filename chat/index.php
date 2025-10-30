<link rel="icon" type="image/x-icon" href="../chatapp.png">

<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("location: ../auth/login.php");
    exit;
}

$outgoing_id = $_SESSION['user_id'];
$sql = "SELECT id, username, email, profile_pic, status FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $outgoing_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat | Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f7f9fc;
        }
        .chat-container {
            display: flex;
            height: 90vh;
            margin-top: 30px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .user-list {
            width: 25%;
            background: #fff;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }
        .chat-area {
            width: 75%;
            display: flex;
            flex-direction: column;
            background: #fdfdfd;
        }
        .user-item {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: 0.3s;
        }
        .user-item:hover, .user-item.active {
            background: #e9f0ff;
        }
        .user-item strong {
            font-size: 15px;
        }
        .user-item small {
            font-size: 13px;
        }
        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: #fafafa;
        }
        .chat-input-area {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ddd;
        }
        .chat-input-area input[type="text"] {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px;
            border-radius: 20px;
            background: #f1f1f1;
            margin-right: 10px;
        }
        .chat-input-area button {
            border: none;
            background: none;
            font-size: 1.4rem;
            margin: 0 5px;
            color: #007bff;
            cursor: pointer;
        }
        .outgoing { text-align: right; }
        .incoming { text-align: left; }
        .outgoing .msg {
            display: inline-block;
            background: #007bff;
            color: #fff;
            padding: 8px 15px;
            border-radius: 15px;
        }
        .incoming .msg {
            display: inline-block;
            background: #e1e1e1;
            color: #000;
            padding: 8px 15px;
            border-radius: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="chat-container">
        <!-- Left Sidebar -->
        <div class="user-list">
            <h5 class="text-center p-3 border-bottom">Chats</h5>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="user-item" data-user-id="<?php echo $row['id']; ?>">
                    <div>
                        <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                        <small class="text-<?php echo ($row['status'] === 'online') ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </small>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div id="chat-header" class="text-center text-muted p-2 border-bottom">
                Select a user to start chatting
            </div>

            <div class="chat-box" id="chat-box">
                <p class="text-center text-muted mt-5">No messages yet</p>
            </div>

            <form id="sendForm" class="chat-input-area">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <input type="text" id="messageInput" name="message" placeholder="Type a message..." autocomplete="off">

                <!-- Video (future use) -->
                <button type="button" id="video-btn" title="Video feature coming soon">
                    <i class="bi bi-camera-video"></i>
                </button>

                <!-- Send -->
                <button type="submit" id="send-btn" title="Send message">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="chat.js"></script>
</body>
</html>
