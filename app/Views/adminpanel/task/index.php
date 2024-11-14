<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1><?php echo $title;?></h1>
    <a href="<?php echo base_url('/adminpanel/task/create');?>" class="btn btn-primary mb-3">Add New <?php echo $title;?></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <!--<th>SubMajorGroup ID</th> -->
                <th>Name</th>
                <th>Code</th>
                <!--<th>Status</th>-->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <?php /*<td><?= $task['submajorgroup_id'] ?></td>*/ ?>
                <td><?= $task['name'] ?></td>
                <td><?= $task['code'] ?></td>
                <?php /*<td><?= $task['status'] ?></td> */?>
                <td>
                    <a href="<?php echo base_url('/adminpanel/task/edit/'.$task['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo base_url('/adminpanel/task/delete/'.$task['id']); ?>" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>