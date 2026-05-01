# 🌟 Chatly - Real-Time Hybrid Chat App

**Chatly** is a high-performance, full-stack chat application built with a hybrid architecture. It leverages the reliability of **PHP 8** for core business logic and the speed of **Node.js (Socket.IO)** for real-time interactivity, presence tracking, and video calling.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 |
| **Backend (Core)** | PHP 8.x |
| **Real-time Server** | Node.js, Socket.IO |
| **Database** | MySQL |
| **Communication** | WebRTC (Video/Audio Signaling) |

---

## ✨ Key Features

- **Instant Messaging**: Zero-latency text and image messaging powered by Socket.IO.
- **User Presence**: Dynamic online/offline status tracking.
- **Multimedia Support**: Seamless image sharing within chat threads.
- **Video Calling**: Peer-to-peer video communication using WebRTC.
- **Typing Indicators**: Real-time feedback when users are composing messages.
- **Secure Auth**: Robust login/registration system with profile image management.
- **Responsive UI**: Optimized for all screen sizes using Bootstrap 5.

---

## 🏗️ Architecture Overview

The project uses a **Dual-Server Architecture**:
1.  **PHP Server**: Manages persistent data, sessions, and initial page loads.
2.  **Node.js Server**: Acts as a high-speed signaling layer for WebRTC and a broadcast medium for chat events.
3.  **Unified Database**: Shared MySQL database to ensure data consistency.

---

## ⚙️ Step-by-Step Deployment (Render)

### 1️⃣ Database Setup
Since Render uses PostgreSQL by default, use an external MySQL provider like **Aiven** or **TiDB Cloud**.
- Export your local `chatly_db` and import it into the cloud instance.
- Update your environment variables with the new credentials.

### 2️⃣ PHP Backend Deployment
- Connect your repo to Render as a **Web Service**.
- Set the Environment to **PHP**.
- Add the following **Environment Variables**:
  - `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`

### 3️⃣ Node.js Server Deployment
- Create a second **Web Service** on Render.
- Set the **Root Directory** to `node_server`.
- Set the Environment to **Node**.
- Use `npm install` and `npm start`.

---

## 🧩 Database Structure

### `users` table
- `id`: INT (Primary Key)
- `username`: VARCHAR(100)
- `email`: VARCHAR(100)
- `password`: VARCHAR(255)
- `profile_img`: VARCHAR(255)
- `status`: ENUM ('online', 'offline')

### `messages` table
- `id`: INT (Primary Key)
- `sender_id`: INT
- `receiver_id`: INT
- `message`: TEXT
- `type`: ENUM ('text', 'image')

---

## 📁 Project Directory
```text
┣ 📁 auth/          # Login & Registration logic
┣ 📁 chat/          # Main Chat UI & WebRTC logic
┣ 📁 config/        # Database & App configurations
┣ 📁 assets/        # CSS, JS, and Images
┣ 📁 node_server/   # Socket.IO & Signaling server
┣ 📄 index.php      # Entry point
┗ 📄 README.md      # Project Documentation
```
