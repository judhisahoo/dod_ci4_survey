<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
    <div class="container mt-5">
    <a href="<?php echo base_url('/adminpanel/majorgrup/create');?>" class="btn btn-primary mb-3">Add New <?php echo $title;?></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl Number</th>
                <th>Name</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($majorgrups as $majorgrup): ?>
            <tr>
                <td><?= $majorgrup['id'] ?></td>
                <td><?= $majorgrup['name'] ?></td>
                <td><?= $majorgrup['code'] ?></td>
                <td>
                    <a href="<?php echo base_url('/adminpanel/majorgrup/edit/'.$majorgrup['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo base_url('/adminpanel/majorgrup/delete/'.$majorgrup['id']); ?>" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>