<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url(); ?>public/style.css">
    <title>View</title>

</head>
<body>  
    <table border=1>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>Actions</th>
        </tr>
        <?php $count = 1; ?>
        <?php foreach(html_escape($users) as $user):?>
            <tr>
                <td><?=$count++; ?></td>
                <td><?=$user['name'];?></td>
                <td><?=$user['email'];?></td>
                <td><?=$user['number'];?></td>
                <td>
                    <a href="<?= site_url('/edit/'.$user['id']); ?>">Edit</a>
                    <a href="<?= site_url('/delete/'.$user['id']); ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach;?>

    </table>
    <a href="<?= site_url('/create'); ?>">Create New User</a>
    </div>
</body>
</html>