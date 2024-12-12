// DOM Elements
const button = document.querySelector('button');
const container = document.querySelector('.container');

// Counter for tracking clicks
let clickCount = 0;

// Main button click function
function showMessage() {
    clickCount++;
    
    // Create a new message with animation
    const message = document.createElement('div');
    message.className = 'message';
    message.textContent = `Click #${clickCount}: Hello! This is my first Git project!`;
    
    // Add message to container
    container.appendChild(message);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        message.style.opacity = '0';
        setTimeout(() => {
            container.removeChild(message);
        }, 500);
    }, 3000);
}

// Add hover effect to button
button.addEventListener('mouseover', () => {
    button.style.transform = 'scale(1.1)';
});

button.addEventListener('mouseout', () => {
    button.style.transform = 'scale(1)';
});

// Initial console message
console.log('JavaScript loaded successfully!');
