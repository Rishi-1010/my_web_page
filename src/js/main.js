// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log("Main.js is loaded successfully!");

    // Get the feature grid element
    const featureGrid = document.querySelector('.feature-grid');
    
    if (!featureGrid) {
        console.error('Feature grid element not found!');
        return;
    }

    const features = [
        {
            title: 'Counter',
            content: `
                <div class="counter-container">
                    <h3>Count: <span id="counter">0</span></h3>
                    <button class="btn" id="increment">+</button>
                    <button class="btn" id="decrement">-</button>
                </div>
            `
        },
        {
            title: 'Background Changer',
            content: `
                <button class="btn" id="colorChange">Change Background</button>
            `
        },
        {
            title: 'Greeting',
            content: `
                <div class="greeting-container">
                    <input type="text" id="nameInput" placeholder="Enter your name">
                    <button class="btn" id="greet">Greet Me!</button>
                    <p id="greetingText"></p>
                </div>
            `
        }
    ];

    // Add feature cards to the grid
    features.forEach(feature => {
        const card = document.createElement('div');
        card.className = 'feature-card';
        card.innerHTML = `
            <h3>${feature.title}</h3>
            ${feature.content}
        `;
        featureGrid.appendChild(card);
    });

    // Set up event listeners after the cards are added
    setTimeout(() => {
        // Counter functionality
        const counterDisplay = document.getElementById('counter');
        const incrementBtn = document.getElementById('increment');
        const decrementBtn = document.getElementById('decrement');
        let count = 0;

        if (incrementBtn && decrementBtn && counterDisplay) {
            incrementBtn.addEventListener('click', () => {
                count++;
                counterDisplay.textContent = count;
            });

            decrementBtn.addEventListener('click', () => {
                count--;
                counterDisplay.textContent = count;
            });
        }

        // Background color changer
        const colorChangeBtn = document.getElementById('colorChange');
        if (colorChangeBtn) {
            colorChangeBtn.addEventListener('click', () => {
                const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
                document.body.style.backgroundColor = randomColor;
            });
        }

        // Greeting functionality
        const greetBtn = document.getElementById('greet');
        const nameInput = document.getElementById('nameInput');
        const greetingText = document.getElementById('greetingText');

        if (greetBtn && nameInput && greetingText) {
            greetBtn.addEventListener('click', () => {
                const name = nameInput.value;
                if (name.trim() !== '') {
                    greetingText.textContent = `Hello, ${name}! Welcome to our site!`;
                } else {
                    greetingText.textContent = 'Please enter your name!';
                }
            });
        }
    }, 100);
});
