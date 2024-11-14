<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1>Add New <?php echo $title;?></h1>
    <form action="<?php echo base_url('/adminpanel/majorgrup/store');?>" method="post">
    <?= csrf_field() ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="number" name="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo base_url('/adminpanel/majorgrup')?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>