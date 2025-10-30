// ---------------------------
// chat.js (FINAL UPDATED VERSION)
// ---------------------------

// Track selected user
let selectedUserId = null;

// Cached DOM elements
const chatBox = document.getElementById("chat-box");
const messageInput = document.getElementById("messageInput");
const sendForm = document.getElementById("sendForm");
const chatHeader = document.getElementById("chat-header");
const receiverInput = document.getElementById("receiver_id");
const videoBtn = document.getElementById("video-btn");

// ---------------------------
// Select a user to chat with
// ---------------------------
document.querySelectorAll(".user-item").forEach((item) => {
  item.addEventListener("click", function () {
    // Highlight selected user
    document.querySelectorAll(".user-item").forEach((i) => i.classList.remove("active"));
    this.classList.add("active");

    // Set selected user ID
    selectedUserId = this.dataset.userId;
    receiverInput.value = selectedUserId;

    // Update chat header
    const username = this.querySelector("strong").textContent;
    chatHeader.textContent = "Chatting with " + username;

    // Reset chat box
    chatBox.innerHTML = '<p class="text-center text-muted mt-4">Loading messages...</p>';

    // Load chat history
    loadMessages(true);
  });
});

// ---------------------------
// Load messages for selected user
// ---------------------------
function loadMessages(scrollToBottom = false) {
  if (!selectedUserId) return;

  const formData = new FormData();
  formData.append("incoming_id", selectedUserId);

  fetch("fetch_messages.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((data) => {
      const trimmed = data.trim();
      chatBox.innerHTML = trimmed || '<p class="text-center text-muted mt-5">No messages yet</p>';

      // Scroll to bottom when new chat selected or after send
      if (scrollToBottom) {
        chatBox.scrollTop = chatBox.scrollHeight;
      } else {
        const shouldAutoScroll = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 100;
        if (shouldAutoScroll) chatBox.scrollTop = chatBox.scrollHeight;
      }
    })
    .catch((err) => console.error("Error loading messages:", err));
}

// ---------------------------
// Send text message
// ---------------------------
sendForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const message = messageInput.value.trim();

  if (!selectedUserId) {
    alert("Please select a user first!");
    return;
  }

  if (message === "") return;

  // Disable input briefly for UX
  messageInput.disabled = true;

  const formData = new FormData();
  formData.append("receiver_id", selectedUserId);
  formData.append("message", message);
  formData.append("type", "text");

  fetch("send_messages.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then(() => {
      messageInput.value = "";
      loadMessages(true);
    })
    .catch((err) => console.error("Error sending message:", err))
    .finally(() => {
      messageInput.disabled = false;
      messageInput.focus();
    });
});

// ---------------------------
// Auto-refresh chat every 3 seconds
// ---------------------------
setInterval(() => {
  if (selectedUserId) loadMessages(false);
}, 3000);

// ---------------------------
// Send on Enter (Shift+Enter = newline)
// ---------------------------
messageInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    sendForm.dispatchEvent(new Event("submit"));
  }
});

// ---------------------------
// Video Call Button
// ---------------------------
if (videoBtn) {
  videoBtn.addEventListener("click", () => {
    if (!selectedUserId) {
      alert("Please select a user to start a video call!");
      return;
    }

    const videoWindow = window.open(
      `video_call.php?receiver_id=${selectedUserId}`,
      "VideoCall",
      "width=850,height=600,scrollbars=no,resizable=yes"
    );

    if (!videoWindow) {
      alert("Please allow popups to start a video call.");
    }
  });
}

// ---------------------------
// Optional: Highlight active chat box scroll behavior
// ---------------------------
chatBox.addEventListener("scroll", () => {
  // Can be used for “New messages below” indicator later
});
