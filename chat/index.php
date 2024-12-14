<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp</title>
    <link rel="stylesheet" href="../src/assets/styles/main.css">
    <link rel="stylesheet" href="../src/assets/styles/components.css">
    <link rel="stylesheet" href="../src/assets/styles/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Back to main app button -->
    <a href="../" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="user-profile">
                <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? 'default-avatar.png'); ?>" 
                     alt="Profile" 
                     class="profile-picture">
                <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>

            <div class="chat-tabs">
                <button class="tab-btn active" data-tab="channels">
                    <i class="fas fa-hashtag"></i> Channels
                </button>
                <button class="tab-btn" data-tab="direct">
                    <i class="fas fa-user"></i> Direct
                </button>
            </div>

            <div class="chat-list" id="channelsList"></div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-header">
                <h2 id="currentChat"></h2>
                <div class="chat-actions">
                    <button class="btn-icon" id="videoCall">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="btn-icon" id="voiceCall">
                        <i class="fas fa-phone"></i>
                    </button>
                </div>
            </div>

            <div class="messages-container" id="messages"></div>

            <div class="chat-input-area">
                <input type="text" 
                       id="messageInput" 
                       placeholder="Type a message..." 
                       class="message-input">
                <button class="send-btn" id="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Online Users -->
        <div class="online-users">
            <h3>Online Users</h3>
            <div id="onlineUsersList"></div>
        </div>
    </div>

    <script src="../src/js/chat.js"></script>
</body>
</html>