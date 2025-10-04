# Repository Authentication System

This project now has a working authentication system based on LavaLust-2025.

## Setup Instructions

1. **Database Setup**:
   - Create a database named `users_db` in your MySQL server
   - Run the SQL script: `database/create_users_table.sql`
   - This will create the `users` table with sample data

2. **Configuration**:
   - Database settings are in `app/config/database.php`
   - Default settings: localhost, root user, no password, database: `users_db`

3. **How to Use**:
   - Start your web server (XAMPP, WAMP, or `php -S localhost:8000`)
   - Navigate to `http://localhost:8000` (or your server URL)
   - You'll see the login form first
   - Use any email from the database (e.g., `john.doe@example.com`) with any password
   - After login, you'll see the user list (View.php)

## Features

- **Login Form**: Modern, luxury-styled login interface
- **Authentication**: Session-based authentication
- **Protected Routes**: All main routes require login
- **User Management**: View, create, edit, delete users
- **Logout**: Clean session termination

## Database Structure

The `users` table contains:
- `id` (Primary Key)
- `name` (User's full name)
- `email` (Unique email address)
- `number` (Phone number)
- `password` (Currently empty - can be set later)

## Security Notes

- Passwords are currently stored as plain text (empty in sample data)
- For production, implement proper password hashing
- Consider adding CSRF protection
- Add input validation and sanitization

## Routes

- `/` - Login form (default)
- `/auth/login` - Login form
- `/auth/logout` - Logout
- `/author` - User list (protected)
- `/create` - Create user (protected)
- `/edit/{id}` - Edit user (protected)
- `/delete/{id}` - Delete user (protected)
