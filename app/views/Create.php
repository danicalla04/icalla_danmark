<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/style.css">
    <title>Create</title>
</head>
<body>

    <form action="" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="number">Position:</label><br>
        <select id="number" name="number" required style="width: 100%; padding: 10px 12px; border: 1.5px solid #cfcfcf; border-radius: 6px; background: #ffffff; color: #222; font-size: 16px; height: 42px; box-sizing: border-box; margin-bottom: 12px;">
            <option value="">Select Position</option>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select><br>

        <input type="submit" value="Create User">    
    </form>
</body>
</html>