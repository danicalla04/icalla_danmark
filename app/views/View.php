<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>

</head>
<body>  

    <div class="container mt-3 ">
	<form action="<?=site_url('author');?>" method="get" class="col-sm-4 float-end d-flex">
		<?php
		$q = '';
		if(isset($_GET['q'])) {
			$q = $_GET['q'];
		}
		?>
        <input class="form-control me-2" name="q" type="text" placeholder="Search" value="<?=html_escape($q);?>">
        <button type="submit" class="btn btn-primary" type="button">Search</button>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>Actions</th>
        </tr>
        <?php $count = 1; ?>
        <?php foreach(html_escape($all) as $user):?>
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
    <?php
	echo $page;?>
    <a href="<?= site_url('/create'); ?>">Create New User</a>
    </div>
</body>
</html>