<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
        <link rel="stylesheet" href="<?= base_url(); ?>public/style.css">
</head>
<body>

        <form action="" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?=html_escape($user['name']);?>"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?=html_escape($user['email']);?>"><br>

        <label for="number">Number:</label><br>
        <input type="text" id="number" name="number" value="<?=html_escape($user['number']);?>"><br>

        <input type="submit" value="Update User">    
    </form>
</body>
</html>