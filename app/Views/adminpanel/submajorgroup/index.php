<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1><?php echo $title;?></h1>
    <a href="<?php echo base_url('/adminpanel/submajorgroup/create')?>" class="btn btn-primary mb-3">Add New <?php echo $title;?></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <!--<th>Major Group ID</th>-->
                <th>Name</th>
                <th>Code</th>
                <!--<th>Status</th>-->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submajorgroups as $submajorgroup): ?>
            <tr>
                <td><?= $pageSlNo ?></td>
                <?php /*<td><?= $submajorgroup['majorgroup_id'] ?></td>*/ ?>
                <td><?= $submajorgroup['name'] ?></td>
                <td><?= $submajorgroup['code'] ?></td>
                <?php /*<td><?= $submajorgroup['status'] ?></td>*/ ?>
                <td>
                    <a href="<?php echo base_url('/adminpanel/submajorgroup/edit/'.$submajorgroup['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo base_url('/adminpanel/submajorgroup/delete/'.$submajorgroup['id']); ?>" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php $pageSlNo++;?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row">
    <?= $pager->links('default','full_pagination'); ?>
    </div>
</div>
<?= $this->endSection() ?>