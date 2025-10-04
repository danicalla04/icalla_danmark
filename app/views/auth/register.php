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

        <form method="post" action="/auth/register" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>Profile Photo (optional):</label>
            <input type="file" name="photo" accept="image/*">
            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="/auth/login">Login</a></p>
    </div>
</body>
</html>
