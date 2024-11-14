<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo @$title?'Admin Panel :: '.$title:'Admin Panel Page' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <div><?php //echo @$title; ?></div>
    <nav>
        <a href="<?php echo base_url('/adminpanel');?>" >Dashboard</a> &nbsp; &nbsp;
        <a href="<?php echo base_url('/adminpanel/majorgrup');?>">Manage Top Group</a> &nbsp; &nbsp;
        <a href="<?php echo base_url('/adminpanel/submajorgroup');?>">Manage Major Group</a> &nbsp; &nbsp;
        <a href="<?php echo base_url('/adminpanel/task');?>">Manage Sub Major Group</a> &nbsp; &nbsp;
        <a href="<?php echo base_url('/adminpanel/subtask');?>">Manage Manor Group</a> &nbsp; &nbsp;
        <a href="<?php echo base_url('/adminpanel/subtaskratting');?>">Manage Ratting Topic</a> &nbsp; &nbsp;
    </nav>
</header>
<main>
    <?= $this->renderSection('content') ?>
</main>
<footer>
    Site Footer
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>