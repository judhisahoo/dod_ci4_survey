<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1><?php echo $title;?></h1>
    <a href="<?php echo base_url('/adminpanel/subtaskratting/create');?>" class="btn btn-primary mb-3">Add New <?php echo $title;?></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <!--<th>Subtask ID</th> -->
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subtasksrattings as $subtaskratting): ?>
            <tr>
            <td><?= $pageSlNo ?></td>
                <?php /*<td><?= $subtaskratting['subtask_id'] ?></td> */?>
                <td><?= $subtaskratting['name'] ?></td>
                <td>
                    <a href="<?php echo base_url('/adminpanel/subtaskratting/edit/'.$subtaskratting['id']);?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo base_url('/adminpanel/subtaskratting/delete/'.$subtaskratting['id']); ?>" method="post" class="d-inline">
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