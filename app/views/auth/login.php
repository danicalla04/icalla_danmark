<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="/auth/login">
            <input type="email" name="email" placeholder="👨‍🎓 Email" required>
            <div class="input-group">
                <input type="password" name="password" placeholder="🔐 Password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="/auth/register">Register</a></p>
    </div>
 
</body>
</html>
