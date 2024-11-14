<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h1>Edit <?php echo $title;?></h1>
    <form action="<?php echo base_url('/adminpanel/subtaskratting/update/'.$subtaskratting['id']);?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
            <label for="subtask_id" class="form-label">Subtask</label>
            <select name="subtask_id" class="form-select" required>
                <option value="">Select Subtask</option>
                <?php foreach ($subtasks as $subtask): ?>
                    <option value="<?= $subtask['id'] ?>" <?= $subtask['id'] == $subtaskratting['subtask_id'] ? 'selected' : '' ?>>
                        <?= $subtask['code'] ?>:: 
                        <?= $subtask['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= $subtaskratting['name'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?php echo base_url('/adminpanel/subtaskratting')?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>