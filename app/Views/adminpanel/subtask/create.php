<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1>Add New <?php echo $title;?></h1>
    <form action="<?php echo base_url('/adminpanel/subtask/store');?>" method="post">
    <?= csrf_field() ?>
        <div class="mb-3">
            <label for="task_id" class="form-label">Task</label>
            <select name="task_id" class="form-select" required>
                <option value="">Select Task</option>
                <?php foreach ($tasks as $task): ?>
                    <option value="<?= $task['id'] ?>"><?= $task['code'] ?> :: <?= $task['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="number" name="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo base_url('/adminpanel/subtask');?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>