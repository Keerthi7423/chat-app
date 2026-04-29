========================================
🚀 CHAT APP DEPLOYMENT GUIDE (Render PHP)
========================================

🧠 PROJECT OVERVIEW
-------------------
A real-time PHP + MySQL Chat App with login/register, 
chat dashboard, image + text messaging, and user status.

💾 Tech Stack:
- Frontend: Bootstrap 5
- Backend: PHP 8
- Database: MySQL
- Hosting: Render
- Optional CDN: Bootstrap, Font Awesome

----------------------------------------
🌐 VISUAL FLOW OF PROJECT
----------------------------------------
                ┌──────────────────────┐
                │   Login/Register     │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │   Dashboard (Users)  │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ Chat Window (sender  │
                │   ↔ receiver)        │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ Messages (DB table)  │
                └──────────────────────┘

----------------------------------------
🧩 DATABASE STRUCTURE
----------------------------------------

1️⃣ users table
----------------
id (INT, AUTO_INCREMENT, PRIMARY KEY)
username (VARCHAR 100)
email (VARCHAR 100)
password (VARCHAR 255)
profile_img (VARCHAR 255)
status (ENUM: 'online','offline')
created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)

2️⃣ messages table
------------------
id (INT, AUTO_INCREMENT, PRIMARY KEY)
sender_id (INT)
receiver_id (INT)
message (TEXT)
type (ENUM: 'text','image')
created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)

----------------------------------------
⚙️ STEP-BY-STEP DEPLOYMENT ON RENDER
----------------------------------------

1️⃣ PREPARE YOUR FILES
----------------------
- Project folder structure:
  📁 chat-app/
     ┣ 📁 auth/
     ┣ 📁 chat/
     ┣ 📁 config/
     ┣ 📁 assets/
     ┣ 📄 index.php
     ┣ 📄 README.md
     ┗ 📄 .htaccess (optional for routing)

- Make sure `config/db.php` looks like this:
  ```php
  <?php
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "chat_app";
  $conn = new mysqli($host, $user, $pass, $dbname);
  if($conn->connect_error){
      die("Connection failed: " . $conn->connect_error);
  }
  ?>
