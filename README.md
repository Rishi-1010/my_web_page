# My Web Page Project

A modern web application with user authentication and interactive features.

![Project Preview](src/assets/img/image.png)

## Features

### User Authentication & Management
- User Registration
- User Login/Logout
- Session Management
- Secure Password Hashing
- Profile Settings Management
  - Custom Profile Picture Upload
  - Automatic Random Profile Pictures
  - Profile Picture Removal/Reset
- User Dashboard

### Chat Platform (In Progress) 🚧
- Real-time Chat Interface
- Message History
- User Online Status
- Direct Messaging
- Channel-based Communication

Current Implementation:
- [x] Chat UI Design
- [x] Basic Message Display
- [x] Message Input Handling
- [ ] WebSocket Integration
- [ ] Real-time Message Updates
- [ ] User Online Status
- [ ] Channel Management
- [ ] Direct Messaging
- [ ] Message History Storage

### Interactive UI
- Responsive Design
- Dynamic Navigation
- Feature Cards
- Interactive Dashboard
- Profile Management Interface
- Modern CSS Animations
- Dynamic Profile Picture Updates

## Database Documentation

### Database Name: webapp_db

### Table Structure

#### users
| Column Name      | Data Type      | Constraints                | Description |
|-----------------|----------------|---------------------------|-------------|
| id              | INT(11)        | PRIMARY KEY, AUTO_INCREMENT| Unique identifier for each user |
| username        | VARCHAR(50)    | NOT NULL                  | User's display name |
| email           | VARCHAR(100)   | NOT NULL, INDEX           | User's email address |
| password        | VARCHAR(255)   | NOT NULL                  | Hashed password |
| created_at      | TIMESTAMP      | DEFAULT CURRENT_TIMESTAMP | Account creation time |
| profile_picture | VARCHAR(255)   | DEFAULT NULL              | Profile picture filename |

### Database Setup
1. Create and populate the database using the provided SQL file:
```bash
mysql -u root -p < database.sql
```

### Database Connection
The database connection is configured in `src/php/config/database.php`:
```php
$host = 'localhost';
$dbname = 'webapp_db';
$username = 'root';
$password = '';
```

### Features
- Single users table with essential user information
- Indexed email field for optimized queries
- Automatic timestamp for user creation tracking
- Support for profile picture storage
- Secure password storage with hashing

## Features in Development

- [x] User Profile Management
- [x] User Dashboard
- [x] Profile Picture System
- [ ] Password Reset Functionality
- [ ] Email Verification
- [ ] Admin Dashboard
- [ ] User Activity Logging

## Tech Stack

- **Frontend:**
  - HTML5
  - CSS3
  - JavaScript
  - Bootstrap
  - Font Awesome Icons

- **Backend:**
  - PHP
  - MySQL
  - PDO Database Connection

- **Development Tools:**
  - Git
  - XAMPP
  - Visual Studio Code

## Project Structure 🗂️

```
my_web_page/
├── chat/
│   └── index.php                     # Chat interface
├── password_reset/
│   └── password-reset.html
├── server/
│   └──config.php
│       └── database.php
├── src/
│   ├── assets/
│   │   ├── img/                      # Folder for images
│   │   │   ├── default-avatar.png
│   │   │   └── picture.png
│   │   └── styles/
│   │       ├── components.css        # Component-specific styles
│   │       ├── main.css              # Main styling
│   │       └── chat.css             # Chat styling
│   ├── components/
│   │   └── navbar.php                # Navigation bar component
│   ├── js/
│   │   ├── main.js                   # Main JavaScript functionality
│   │   ├── profile-settings.js       # Profile settings specific JS
│   │   └── chat.js                  # Chat functionality
│   ├── php/
│   │   ├── auth/                     # Authentication functionalities
│   │   │   ├── login.php             # Login processing
│   │   │   ├── logout.php            # Logout handling
│   │   │   ├── password_reset.php    # Password_reset Functionality
│   │   │   ├── register.php          # Registration processing
│   │   │   └── update-profile.php    # Profile updates
│   │   ├── config/                   # Configuration files
│   │   │   └── database.php          # Database configuration
│   │   ├── profile_picture/          # Profile picture functionalities
│   │   │   └── update_profile.php    # Handles profile picture updates
│   │   ├── utils/                    # Utility functions
│   │   │   └── profile_utils.php     # Profile picture utility functions
│   │   └── chat/                    # Chat functionalities
│   │       └── messages.php         # Message handling
├── index.php                         # Main entry point
├── login.html                        # Login page
├── register.html                     # Registration page
├── dashboard.php                     # User dashboard
├── profile-settings.php              # Profile management
├── README.md                         # Project documentation
└── LICENSE                          # License file
```

## How to Run

1. Clone this repository
2. Open `index.html` in your web browser

## Features

- Responsive design
- Interactive button
- Custom styling

## Author

Rishi Bardoliya

## License

This project is open source and available under the [MIT License](LICENSE).

## Setup Instructions

1. **Prerequisites:**
   - XAMPP installed
   - Git installed
   - Web browser

2. **Installation:**
   ```bash
   # Clone the repository
   git clone https://github.com/Rishi-1010/my_web_page.git

   # Move to project directory
   cd my_web_page
   ```

3. **Database Setup:**
   - Start XAMPP Apache and MySQL services
   - Create a new database named 'webapp_db'
   - Import the database structure from `database.sql`

4. **Configuration:**
   - Update database credentials in `src/php/config/database.php`
   - Ensure proper file permissions

5. **Access the Application:**
   - Open your browser
   - Navigate to: `http://localhost/GIT/my_web_page/`

## Features in Development

- [x] User Profile Management
- [x] User Dashboard
- [ ] Password Reset Functionality
- [ ] Email Verification
- [ ] Admin Dashboard
- [ ] User Activity Logging

## Git Branches

- `main` - Production ready code
- `feature/user-authentication` - Authentication system
- `feature/dashboard` - User dashboard implementation
- `feature/profile-settings` - Profile management system
- `development` - Latest development changes

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Bootstrap Documentation
- PHP Documentation
- Font Awesome Icons
- XAMPP Team

## Contact

Rishi Bardoliya - rishibardoliya@gmail.com
