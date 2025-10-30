<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ../auth/login.php");
    exit;
}

$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Video Call</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #0d1117; color: white; text-align: center; }
    video { width: 45%; border-radius: 10px; margin: 10px; background: black; }
    .controls { margin-top: 15px; }
  </style>
</head>
<body>
  <h3 class="mt-3">Video Call</h3>
  <div>
    <video id="localVideo" autoplay muted></video>
    <video id="remoteVideo" autoplay></video>
  </div>
  <div class="controls">
    <button id="endCall" class="btn btn-danger">End Call</button>
  </div>

  <script>
    const localVideo = document.getElementById("localVideo");
    const remoteVideo = document.getElementById("remoteVideo");
    const endCall = document.getElementById("endCall");

    async function startVideo() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = stream;
        // (For now this only shows your camera locally — signaling via WebRTC will be added later)
      } catch (err) {
        alert("Error accessing camera or microphone: " + err.message);
      }
    }

    endCall.addEventListener("click", () => {
      window.close();
    });

    startVideo();
  </script>
</body>
</html>
