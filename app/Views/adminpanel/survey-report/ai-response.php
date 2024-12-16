<?= $this->extend('layouts/admin-report') ?>
<?= $this->section('content') ?>
<style>
 body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        h2 {
            color: #333;
            margin-top: 20px;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
</style>
<div class="container mt-5">
    <div class="overlay" style="display:none"><img src="http://i.imgur.com/KUJoe.gif"></div>
    <div class="row">
        <div class="col align-self-start">
            <h1><?php echo $title; ?></h1>
        </div>
    </div>
    <?php
    if (session()->getFlashdata('status') != '') {
        echo '<div class="row"><div class="alert alert-info">' . session()->getFlashdata('status') . '</div></div>';
    }
    ?>
    <div class="row" style="height: 50px;">
        &nbsp;
    </div>
   
    <div class="row">

        <div class="card">
            <div class="card-body">
                <?php echo $aiResponse;?>
            </div>
            <div class="card-footer">
            <p class="card-text"><a href="<?php echo base_url('/adminpanel/ai-report');?>" class="btn btn-primary">Get a New AI Report</a></p>
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
</div>
<?= $this->endSection() ?>