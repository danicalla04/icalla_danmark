<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/public/css/register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="/auth/register">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>Position:</label>
            <select name="number" required style="width: calc(100% - 28px); padding: 12px 14px; margin: 8px 0 12px 0; border: 1px solid #555555; border-radius: 8px; background: #333333; color: #ffffff; outline: none;">
                <option value="">Select Position</option>
                <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select>
            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="/auth/login">Login</a></p>
    </div>
</body>
</html>
