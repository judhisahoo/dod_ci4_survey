<?= $this->extend('layouts/admin-report') ?>
<?= $this->section('content') ?>
<style>
    .overlay {
        position: fixed;
        /* Fixed position to overlay on top of everything */
        top: 0;
        /* Align to the top */
        left: 0;
        /* Align to the left */
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent background */
        z-index: 999;
        /* High z-index to sit on top of other elements */
        display: flex;
        /* Use flexbox for centering */
        justify-content: center;
        /* Center horizontally */
        align-items: center;
        /* Center vertically */
        color: white;
        /* Text color */
        font-size: 2rem;
        /* Text size */
        text-align: center;
        /* Center text */
        pointer-events: none;
        /* Allow clicks to pass through the overlay */
    }
    .hidden {
 list-style-type: none;
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
    <?php if(is_array($errors) && !empty($error)) :?>
    <div class="row">
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
        <?php foreach ($errors as $error): ?>
            <ul class="hidden">
            <li><i class="bi bi-shield-exclamation" style="color:#FFFFFF; font-size:30px"></i> &nbsp; &nbsp; <?= esc($error) ?></li>
            </ul>
        <?php endforeach ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="row" style="height: 30px;">&nbsp;</div>
    <?php endif;?>
    <div class="row">

        <div class="card">
            <div class="card-body">
                <!--<form action="<?php // base_url('/adminpanel/generate-ai-report'); 
                                    ?>" enctype="multipart/form-data" method="post"> -->
                <?php echo form_open_multipart('/adminpanel/ai-report', ['onsubmit' => 'return checkSubmit();']) ?>
                <?= csrf_field() ?>
                <div class="row" style="height: 50px;">
                    &nbsp;
                </div>
                <div class="row mb-3" style="height: 150px;">
                    <div class="col-12 p-3">
                        <label for="inputNumber" class="col-sm-4 col-form-label">Upload Table Image Report</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="file" id="aiDataFile" name="aiDataFile" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script>
    function checkSubmit() {
        $(".overlay").fadeIn();
        return true;
    }
</script>
<?= $this->endSection() ?>