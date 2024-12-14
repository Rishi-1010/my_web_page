document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.profile-form form');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    form.addEventListener('submit', function(e) {
        // Reset any previous error messages
        clearErrors();

        // Validate passwords if either password field is filled
        if (newPassword.value || confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                showError(confirmPassword, 'Passwords do not match');
                return;
            }

            if (newPassword.value.length < 8) {
                e.preventDefault();
                showError(newPassword, 'Password must be at least 8 characters long');
                return;
            }
        }

        // Validate email
        const email = document.getElementById('email');
        if (email.value && !isValidEmail(email.value)) {
            e.preventDefault();
            showError(email, 'Please enter a valid email address');
            return;
        }
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '0.25rem';
        element.parentNode.appendChild(errorDiv);
        element.style.borderColor = 'red';
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
        document.querySelectorAll('.form-group input').forEach(input => {
            input.style.borderColor = '';
        });
    }

    // Add remove profile picture functionality
    const removeProfilePictureBtn = document.getElementById('removeProfilePicture');
    if (removeProfilePictureBtn) {
        removeProfilePictureBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch('src/php/profile_picture/remove_profile_picture.php', {
                    method: 'POST'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update profile picture display
                    const profilePictures = document.querySelectorAll('img[alt="Current Profile Picture"]');
                    profilePictures.forEach(img => {
                        img.src = `uploads/profile_pictures/${data.new_picture}`;
                    });
                    
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'alert success';
                    successDiv.textContent = 'Profile picture removed and replaced with a random one';
                    document.querySelector('.profile-settings').insertBefore(successDiv, document.querySelector('.profile-form'));
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => successDiv.remove(), 3000);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
}); 