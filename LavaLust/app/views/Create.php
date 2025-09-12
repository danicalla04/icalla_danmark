<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url(); ?>public/style.css">
    <title>Create</title>
</head>
<body>

    <form action="" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="number">Number:</label><br>
        <input type="text" id="number" name="number"><br>

        <input type="submit" value="Create User">    
    </form>
</body>
</html>