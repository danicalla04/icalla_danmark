# Render Deployment Setup

## Environment Variables Required

Your database.php is configured to use environment variables:

```php
$database['main'] = array(
    'driver'   => 'mysql',
    'hostname' => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'database' => getenv('DB_NAME'),
    'charset'  => 'utf8mb4',
    'dbprefix' => '',
    'path'     => ''
);
```

## Required Environment Variables in Render:

1. **DB_HOST** - Your database host (e.g., yourrender-db-host.com)
2. **DB_PORT** - Database port (usually 5432 for PostgreSQL, 3306 for MySQL)
3. **DB_USER** - Database username
4. **DB_PASS** - Database password  
5. **DB_NAME** - Database name

## Steps to Fix:

1. **In your Render Dashboard**:
   - Go to your web service settings
   - Add environment variables for the database connection

2. **Create a Database on Render**:
   - Add a PostgreSQL or MySQL database service
   - Copy the connection details

3. **Set Environment Variables**:
   - Add each variable in your web service environment settings

## Testing:

After setting up the environment variables:
1. Login at: `https://icalla-danmark-e3ri.onrender.com/auth/login`
2. Should redirect to: `https://icalla-danmark-e3ri.onrender.com/author`
3. Should show the View.php table with user data

## Debug Information:

I've added error logging to help debug. Check your Render service logs to see:
- "UserController::show() called"
- Database connection errors
- Query results

## Quick Fix - Fallback Database Config:

If you want to test without a database first, modify database.php to use default values:
```
'hostname' => getenv('DB_HOST') ?: 'localhost',
'username' => getenv('DB_USER') ?: 'root',
// etc.
```
