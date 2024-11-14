<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>


<div class="row">

<div class="mb-3">
        <a href="<?php echo base_url('/adminpanel/majorgrup')?>" class="btn btn-secondary">Manage Top Group</a>
        </div>
        <div class="mb-3">
        <a href="<?php echo base_url('/adminpanel/submajorgroup')?>" class="btn btn-secondary">Manage Major Group</a>
        </div>

        <div class="mb-3">
        <a href="<?php echo base_url('/adminpanel/task')?>" class="btn btn-secondary">Manage Sub Major Group</a>
        </div>

        <div class="mb-3">
        <a href="<?php echo base_url('/adminpanel/subtask')?>" class="btn btn-secondary">Manage Minor Group</a>
        </div>

        <div class="mb-3">
        <a href="<?php echo base_url('/adminpanel/subtaskratting')?>" class="btn btn-secondary">Manage Rating Topic</a>
        </div>

</div>
<?= $this->endSection() ?>
