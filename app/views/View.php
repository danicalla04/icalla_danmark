<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>

</head>
<body>
    <div>
        <form action="<?=site_url('/');?>" method="get" class="col-sm-4 float-end d-flex">
		<?php
		$q = '';
		if(isset($_GET['q'])) {
			$q = $_GET['q'];
		}
		?>
        <input class="form-control me-2" name="q" type="text" placeholder="Search" value="<?=html_escape($q);?>">
        <button type="submit" class="btn btn-primary" type="button">Search</button>
	</form>
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
    <?php echo $page;?>
    <a href="<?= site_url('/create'); ?>">Create New User</a>
    </div>
</body>
</html>