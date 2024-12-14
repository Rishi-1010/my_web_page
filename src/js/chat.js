document.addEventListener('DOMContentLoaded', () => {
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendMessage');
    const messagesContainer = document.getElementById('messages');

    // Sample messages array to store chat history
    const messages = [];

    // Function to display a message
    function displayMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = `message ${message.isCurrentUser ? 'message-own' : 'message-other'}`;
        
        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-header">
                    <span class="message-username">${message.username}</span>
                    <span class="message-time">${formatTime(message.timestamp)}</span>
                </div>
                <div class="message-text">${message.content}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Format timestamp
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Send message function
    function sendMessage() {
        const content = messageInput.value.trim();
        if (content) {
            const message = {
                content: content,
                username: 'You', // This would normally come from the session
                timestamp: new Date(),
                isCurrentUser: true
            };
            
            // Add message to array and display it
            messages.push(message);
            displayMessage(message);
            
            // Clear input
            messageInput.value = '';

            // Simulate received message (for testing)
            setTimeout(() => {
                const response = {
                    content: 'This is a sample response',
                    username: 'Bot',
                    timestamp: new Date(),
                    isCurrentUser: false
                };
                messages.push(response);
                displayMessage(response);
            }, 1000);
        }
    }

    // Event listeners
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Add some sample messages
    displayMessage({
        content: 'Welcome to the chat!',
        username: 'System',
        timestamp: new Date(),
        isCurrentUser: false
    });
});
