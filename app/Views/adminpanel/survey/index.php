<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row">
        <div class="col align-self-start">
        <h1><?php echo $title;?></h1>
        </div>
        <div class="col align-self-end text-end">
        <a href="<?php echo base_url('/adminpanel/export-excel'); ?>" class="btn btn-primary">Export to Excel</a>
        </div>
    </div>
    <?php
        if (session()->getFlashdata('status') != '') {
            echo '<div class="row"><div class="alert alert-info">' . session()->getFlashdata('status') . '</div></div>';
        }
        ?>
    <div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Name of Business</th>
                <th>Sector</th>
                <th>Top Group</th>
                <th>Major Group</th>
                <th>Sub Major Group</th>
                <th>Survey Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($surveys as $survey): ?>
            <tr>
                <td><?= $pageSlNo ?></td>
                <td><?= $survey['name'] ?></td>
                <td><?= $survey['email'] ?></td>
                <td><?= $survey['name_of_business'] ?></td>
                <td><?= $survey['sector'] ?></td>
                <td><?= $survey['topGroupName'] ?></td>
                <td><?= $survey['majorGroupName'] ?></td>
                <td><?= $survey['subMajorGroupName'] ?></td>
                <td><?= $survey['survey_date'] ?></td>
            </tr>
            <?php $pageSlNo++;?>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    
    <div class="row">
    <?= $pager->links('default','full_pagination'); ?>
    </div>
</div>
<?= $this->endSection() ?>