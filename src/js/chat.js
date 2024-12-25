document.addEventListener('DOMContentLoaded', () => {
    const socket = io('http://localhost:3000', {
        reconnection: true,
        reconnectionDelay: 1000,
        reconnectionDelayMax: 5000,
        reconnectionAttempts: 5
    });
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendMessage');
    const messagesContainer = document.getElementById('messages');
    const channelsList = document.getElementById('channelsList');
    const currentChatTitle = document.getElementById('currentChat');
    const onlineUsersList = document.getElementById('onlineUsersList');
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    let currentChat = { type: 'channel', target: 'general' };
    let directChats = new Set(); // Store active DM conversations
    let onlineUsers = new Set(); // Store online users

    // Handle tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            button.classList.remove('has-notification');

            const tabType = button.getAttribute('data-tab');
            updateChatList(tabType);
        });
    });

    function updateChatList(tabType) {
        channelsList.innerHTML = '';

        if (tabType === 'direct') {
            const newChatBtn = document.createElement('button');
            newChatBtn.className = 'new-chat-btn';
            newChatBtn.innerHTML = '<i class="fas fa-plus"></i> Start New Chat';
            newChatBtn.onclick = showOnlineUsers;
            channelsList.appendChild(newChatBtn);

            Array.from(directChats).forEach(username => {
                if (!document.querySelector(`.chat-item[data-username="${username}"]`)) {
                    addDirectChatItem(username);
                }
            });
        } else {
            const generalChannel = document.createElement('div');
            generalChannel.className = 'chat-item active';
            generalChannel.innerHTML = `
                <span class="channel-hash">#</span>
                <span class="chat-name">general</span>
            `;
            generalChannel.onclick = () => {
                currentChat = { type: 'channel', target: 'general' };
                currentChatTitle.textContent = '# general';
                messagesContainer.innerHTML = '';
                document.querySelectorAll('.chat-item').forEach(item => {
                    item.classList.remove('active');
                });
                generalChannel.classList.add('active');
            };
            channelsList.appendChild(generalChannel);
        }
    }

    function addDirectChatItem(username) {
        // Check if chat item already exists
        if (document.querySelector(`.chat-item[data-username="${username}"]`)) {
            return; // Exit if already exists
        }

        const chatItem = document.createElement('div');
        chatItem.className = 'chat-item';
        chatItem.dataset.username = username; // Add data attribute for identification
        chatItem.innerHTML = `
            <span class="user-status">●</span>
            <span class="chat-name">${username}</span>
        `;
        chatItem.onclick = () => startDirectChat(username);
        channelsList.appendChild(chatItem);
    }

    function startDirectChat(username) {
        if (!username || username === currentUsername) return;

        currentChat = { type: 'direct', target: username };
        currentChatTitle.textContent = `Chat with @${username}`;
        directChats.add(username);
        
        // Clear messages container
        messagesContainer.innerHTML = '';
        
        // Update chat list
        updateChatList('direct');
        
        // Highlight the current chat
        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.username === username) {
                item.classList.add('active');
            }
        });
    }

    function showOnlineUsers() {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <h3>Start New Chat</h3>
                <div class="online-users-list">
                    ${Array.from(onlineUsers)
                        .filter(user => user !== currentUsername)
                        .map(user => `
                            <div class="online-user-select">
                                <label class="user-select-label">
                                    <input type="radio" name="selected-user" value="${user}">
                                    <span class="user-status">●</span>
                                    <span class="user-name">${user}</span>
                                </label>
                            </div>
                        `).join('')}
                </div>
                <button class="start-chat-btn" disabled>Start Chat</button>
                <button class="close-modal">Cancel</button>
            </div>
        `;
        document.body.appendChild(modal);

        // Handle user selection
        const radioButtons = modal.querySelectorAll('input[type="radio"]');
        const startChatBtn = modal.querySelector('.start-chat-btn');

        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                startChatBtn.disabled = false;
            });
        });

        // Handle start chat button
        startChatBtn.addEventListener('click', () => {
            const selectedUser = modal.querySelector('input[type="radio"]:checked').value;
            startDirectChat(selectedUser);
            modal.remove();
        });

        // Handle close button
        modal.querySelector('.close-modal').onclick = () => modal.remove();
    }

    // Add notification container
    const notificationContainer = document.createElement('div');
    notificationContainer.className = 'notification-container';
    document.body.appendChild(notificationContainer);

    console.log('Chat elements:', {
        messageInput,
        sendButton,
        messagesContainer,
        channelsList,
        currentChatTitle
    });

    // Join chat
    socket.emit('user_join', currentUsername);
    console.log('Joining chat as:', currentUsername);

    // Handle received messages
    socket.on('receive_message', (message) => {
        console.log('Received message:', message);
        displayMessage(message);
    });

    function displayMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = `message ${message.username === currentUsername ? 'message-own' : ''}`;
        
        const time = new Date(message.timestamp).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });

        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-header">
                    <span class="message-username">${message.username}</span>
                    <span class="message-time">${time}</span>
                </div>
                <div class="message-text">${message.content}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageElement);
        // Auto scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function sendMessage() {
        const content = messageInput.value.trim();
        if (content) {
            const messageData = {
                content: content,
                type: currentChat.type,
                target: currentChat.target || 'general'
            };
            
            socket.emit('send_message', messageData);
            messageInput.value = '';
        }
    }

    // Event listeners
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Handle user updates
    socket.on('user_joined', (data) => {
        console.log('User joined:', data);
        showNotification(`${data.username} joined`);
        // Update online users
        onlineUsers = new Set(data.users);
        updateOnlineUsers(data.users);
    });

    socket.on('user_left', (data) => {
        console.log('User left:', data);
        showNotification(`${data.username} left`);
        // Update online users
        onlineUsers = new Set(data.users);
        updateOnlineUsers(data.users);
    });

    function updateOnlineUsers(users) {
        if (!onlineUsersList) return;
        
        onlineUsersList.innerHTML = users
            .map(username => `
                <div class="online-user">
                    <span class="user-status">●</span>
                    <span class="user-name">${username}</span>
                </div>
            `)
            .join('');
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-envelope"></i>
                <span>${message}</span>
            </div>
        `;
        
        notificationContainer.appendChild(notification);

        // Remove notification after 5 seconds (increased from 3)
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }

    function formatTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString([], { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    // Add new socket event listeners for DM notifications
    socket.on('dm_request', (data) => {
        console.log('DM request received:', data);
        
        // Show notification
        showNotification(data.message || `${data.username} wants to start a chat with you`);
        
        // Add to direct chats
        directChats.add(data.username);
        
        // If in direct messages tab, update the list
        const directTab = document.querySelector('.tab-btn[data-tab="direct"]');
        if (directTab.classList.contains('active')) {
            updateChatList('direct');
        } else {
            // Add notification dot to direct messages tab
            directTab.classList.add('has-notification');
        }
    });

    socket.on('add_dm_chat', (data) => {
        console.log('Adding new DM chat:', data);
        directChats.add(data.username);
        
        // If in direct messages tab, update immediately
        const directTab = document.querySelector('.tab-btn[data-tab="direct"]');
        if (directTab.classList.contains('active')) {
            updateChatList('direct');
        }
    });

    // Add CSS classes for notifications
    const style = document.createElement('style');
    style.textContent = `
        .notification-dot {
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .tab-btn.has-notification::after {
            content: '';
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            position: absolute;
            right: -4px;
            top: -4px;
        }
        
        .chat-item {
            position: relative;
        }
    `;
    document.head.appendChild(style);

    socket.on('channel_history', (data) => {
        console.log('Received channel history:', data);
        messagesContainer.innerHTML = '';
        data.messages.forEach(message => {
            displayMessage({
                content: message.content,
                username: message.sender_username,
                timestamp: new Date(message.timestamp),
                type: message.type,
                target: message.target
            });
        });
    });

    socket.on('dm_history', (data) => {
        console.log('Received DM history:', data);
        data.messages.forEach(message => {
            if (message.sender_username !== currentUsername) {
                directChats.add(message.sender_username);
            } else {
                directChats.add(message.target);
            }
        });
        updateChatList('direct');
    });

    // Add connection event handlers
    socket.on('connect', () => {
        console.log('Connected to server');
        // Only emit user_join if we have a username
        if (currentUsername) {
            socket.emit('user_join', currentUsername);
        }
    });

    socket.on('disconnect', () => {
        console.log('Disconnected from server');
    });

    socket.on('connect_error', (error) => {
        console.error('Connection error:', error);
    });
});
