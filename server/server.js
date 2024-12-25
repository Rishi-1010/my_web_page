const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {
        origin: "http://localhost",
        methods: ["GET", "POST"]
    }
});
const pool = require('./config/database');

// Store connected users and their sockets
const users = new Map();
const userSocketMap = new Map(); // Maps username to socket.id

// Test database connection
async function testDatabaseConnection() {
    try {
        const [rows] = await pool.query('SELECT 1');
        console.log('Database connection successful');
    } catch (err) {
        console.error('Database connection failed:', err);
        process.exit(1);
    }
}

testDatabaseConnection();

io.on('connection', (socket) => {
    console.log('A user connected:', socket.id);

    // Handle user joining
    socket.on('user_join', async (username) => {
        console.log(`${username} attempting to join the chat`);
        
        // Check for existing connections for this username
        for (const [socketId, user] of users.entries()) {
            if (user.username === username) {
                console.log(`Found existing connection for ${username}, cleaning up...`);
                const existingSocket = io.sockets.sockets.get(socketId);
                if (existingSocket) {
                    existingSocket.disconnect(true);
                }
                users.delete(socketId);
                userSocketMap.delete(username);
            }
        }

        // Store new socket connection
        userSocketMap.set(username, socket.id);
        users.set(socket.id, { username, currentChannel: 'general' });
        socket.join('general');
        
        try {
            // Load existing messages from database
            const [messages] = await pool.query(
                'SELECT * FROM messages WHERE type = ? AND target = ? ORDER BY timestamp DESC LIMIT 50',
                ['channel', 'general']
            );
            
            socket.emit('channel_history', {
                channel: 'general',
                messages: messages.reverse()
            });

            // Emit updated users list
            const uniqueUsers = Array.from(new Set(Array.from(users.values()).map(u => u.username)));
            io.emit('user_joined', {
                username: username,
                users: uniqueUsers
            });
        } catch (err) {
            console.error('Error loading messages:', err);
        }
    });

    // Add disconnect handler
    socket.on('disconnect', () => {
        const user = users.get(socket.id);
        if (user) {
            const username = user.username;
            users.delete(socket.id);
            userSocketMap.delete(username);
            
            io.emit('user_left', {
                username: username,
                users: Array.from(users.values()).map(u => u.username)
            });
        }
    });

    // Handle chat messages
    socket.on('send_message', async (data) => {
        console.log('Message received:', data);
        const user = users.get(socket.id);
        
        if (!user) {
            console.log('User not found for socket:', socket.id);
            return;
        }

        try {
            // Test query to check if user exists in users table
            const [userCheck] = await pool.query(
                'SELECT username FROM users WHERE username = ?',
                [user.username]
            );

            if (userCheck.length === 0) {
                throw new Error(`User ${user.username} not found in database`);
            }

            console.log('Attempting database insertion with data:', {
                content: data.content,
                sender_username: user.username,
                type: data.type || 'channel',
                target: data.target || 'general'
            });

            // Insert message with explicit error logging
            const [result] = await pool.query(
                `INSERT INTO messages 
                (content, sender_username, type, target) 
                VALUES (?, ?, ?, ?)`,
                [
                    data.content,
                    user.username,
                    data.type || 'channel',
                    data.target || 'general'
                ]
            );

            console.log('Insert result:', result);

            const messageData = {
                id: result.insertId,
                content: data.content,
                username: user.username,
                timestamp: new Date(),
                type: data.type || 'channel',
                target: data.target || 'general'
            };

            // Log successful storage
            console.log('Message stored in database:', messageData);

            // Broadcast message
            if (data.type === 'direct') {
                const targetSocketId = Array.from(users.entries())
                    .find(([_, u]) => u.username === data.target)?.[0];
                if (targetSocketId) {
                    io.to(targetSocketId).emit('receive_message', messageData);
                    socket.emit('receive_message', messageData);
                }
            } else {
                io.emit('receive_message', messageData);
            }
        } catch (err) {
            console.error('Database error:', {
                message: err.message,
                code: err.code,
                sqlMessage: err.sqlMessage,
                sql: err.sql,
                stack: err.stack
            });
            socket.emit('message_error', { 
                message: 'Failed to store message',
                error: err.message 
            });
        }
    });

    // Rest of your existing socket handlers...
});

const PORT = process.env.PORT || 3000;
http.listen(PORT, () => {
    console.log(`Socket.IO server running on port ${PORT}`);
});
