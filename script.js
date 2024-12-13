// // Wait for DOM to be fully loaded
// document.addEventListener('DOMContentLoaded', () => {
//     console.log("Main.js is loaded successfully!");

//     // Add feature cards dynamically
//     const featureGrid = document.querySelector('.feature-grid');
//     const features = [
//         {
//             title: 'Counter',
//             content: `
//                 <div class="counter-container">
//                     <h3>Count: <span id="counter">0</span></h3>
//                     <button class="btn" id="increment">+</button>
//                     <button class="btn" id="decrement">-</button>
//                 </div>
//             `
//         },
//         {
//             title: 'Background Changer',
//             content: `
//                 <button class="btn" id="colorChange">Change Background</button>
//             `
//         },
//         {
//             title: 'Greeting',
//             content: `
//                 <div class="greeting-container">
//                     <input type="text" id="nameInput" placeholder="Enter your name">
//                     <button class="btn" id="greet">Greet Me!</button>
//                     <p id="greetingText"></p>
//                 </div>
//             `
//         }
//     ];

//     // Add feature cards to the grid
//     features.forEach(feature => {
//         const card = document.createElement('div');
//         card.className = 'feature-card';
//         card.innerHTML = `
//             <h3>${feature.title}</h3>
//             ${feature.content}
//         `;
//         featureGrid.appendChild(card);
//     });

//     // Counter functionality
//     let count = 0;
//     const counterDisplay = document.getElementById('counter');
    
//     document.getElementById('increment')?.addEventListener('click', () => {
//         count++;
//         counterDisplay.textContent = count;
//     });

//     document.getElementById('decrement')?.addEventListener('click', () => {
//         count--;
//         counterDisplay.textContent = count;
//     });

//     // Background color changer
//     document.getElementById('colorChange')?.addEventListener('click', () => {
//         const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
//         document.body.style.backgroundColor = randomColor;
//     });

//     // Greeting functionality
//     document.getElementById('greet')?.addEventListener('click', () => {
//         const name = document.getElementById('nameInput').value;
//         const greetingText = document.getElementById('greetingText');
        
//         if (name.trim() !== '') {
//             greetingText.textContent = `Hello, ${name}! Welcome to our site!`;
//         } else {
//             greetingText.textContent = 'Please enter your name!';
//         }
//     });

//     // CTA button functionality
//     document.getElementById('cta-button')?.addEventListener('click', () => {
//         alert('Welcome to our WebApp!');
//     });
// });
