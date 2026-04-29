import express from "express";
import { createServer } from "http";
import { Server } from "socket.io";
import mysql from "mysql2";
import cors from "cors";

const app = express();
app.use(cors());
const server = createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

// ✅ MySQL connection
const db = mysql.createConnection({
  host: process.env.DB_HOST || "localhost",
  user: process.env.DB_USER || "root",
  password: process.env.DB_PASSWORD || "",
  database: process.env.DB_NAME || "chat_app" 
});

db.connect(err => {
  if (err) console.error("DB connection failed:", err);
  else console.log("✅ Connected to MySQL");
});

// ✅ Socket.IO Events
io.on("connection", socket => {
  console.log("User connected:", socket.id);

  // When user connects with ID
  socket.on("register_user", userId => {
    db.query("UPDATE users SET socket_id=? , status='online' WHERE id=?", [socket.id, userId]);
  });

  // Handle sending message
  socket.on("send_message", data => {
    const { sender_id, receiver_id, message } = data;
    db.query("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)", [sender_id, receiver_id, message]);
    // Emit to receiver only
    db.query("SELECT socket_id FROM users WHERE id=?", [receiver_id], (err, res) => {
      if (!err && res[0]?.socket_id) {
        io.to(res[0].socket_id).emit("receive_message", data);
      }
    });
  });

  // Typing indicator
  socket.on("typing", ({ to }) => {
    db.query("SELECT socket_id FROM users WHERE id=?", [to], (err, res) => {
      if (!err && res[0]?.socket_id) {
        io.to(res[0].socket_id).emit("typing_indicator");
      }
    });
  });

  // WebRTC signaling
  socket.on("offer", data => io.to(data.to).emit("offer", data));
  socket.on("answer", data => io.to(data.to).emit("answer", data));
  socket.on("ice-candidate", data => io.to(data.to).emit("ice-candidate", data));

  socket.on("disconnect", () => {
    db.query("UPDATE users SET status='offline' WHERE socket_id=?", [socket.id]);
    console.log("User disconnected:", socket.id);
  });
});

server.listen(3000, () => console.log("🚀 Socket.IO server running on port 3000"));
