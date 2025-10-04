<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url(); ?>public/style.css">
    <title>View</title>

</head>
<body>  

    <div class="container mt-3">
    <!-- User info and logout -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h4>Welcome, <?= htmlspecialchars(isset($user_name) ? $user_name : 'User') ?>!</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
    
	<form action="<?=site_url('author');?>" method="get" class="col-sm-4 float-end d-flex">
		<?php
		$q = '';
		if(isset($_GET['q'])) {
			$q = $_GET['q'];
		}
		?>
        <input class="form-control me-2" name="q" type="text" placeholder="Search" value="<?=html_escape($q);?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
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
    <div style="text-align: center; margin-top: 20px;">
        <a href="<?= site_url('/create'); ?>">Create New User</a>
    </div>
    </div>
</body>
</html>