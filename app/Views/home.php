<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILO Survey Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php base_url();?>public/fe/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';  
  </script>
</head>

<body class="my-5">
<div class="overlay" style="display:none"><img src="http://i.imgur.com/KUJoe.gif"></div>
    <div class="container">

        <?php
        if (session()->getFlashdata('status') != '') {
            echo '<div class="alert alert-info">' . session()->getFlashdata('status') . '</div>';
        }
        ?>
        <?php  //$this->renderSection('content') 
        ?>

        

        <div class="container mt-5">
            <div class="card">
                <img src="<?php base_url();?>public/fe/images/cmi-tool-bg.webp" class="card-img-top" alt="Survey Banner">
                <div class="card-body">
                    <h1 class="card-title text-center textlarge">Survey Portal</h1>
                    <p class="text-center mb-4">Survey Portal: Select Your Occupation</p>

                    <form action="<?php echo base_url('/submit-survey'); ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="name" class="form-label form-label-required">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone">
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label form-label-required">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>


                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="name_of_business" class="form-label form-label-required">Name of Business</label>
                                <input type="text" class="form-control" name="name_of_business" id="name_of_business" placeholder="Enter business name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sector" class="form-label form-label-required">Sector</label>
                                <input type="text" class="form-control" id="sector" name="sector" placeholder="Enter sector" required>
                            </div>
                            <div class="col-md-4">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
                            </div>
                        </div>
                        
                        
                        

                        <div class="mb-3">
                            <label for="major-group" class="form-label form-label-required">Top Group:</label>
                            <select class="form-select" id="major-group" name="major_group_id" style="width: 300px;" required>
                                <option value="">Select Top Group</option>
                                <?php foreach ($majorGroupData as $majorGroup): ?>
                                    <option value="<?php echo $majorGroup['id']; ?>"><?php echo $majorGroup['code']; ?> :: <?php echo $majorGroup['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4 col-md-6" id="sub-major-group-parent" style="display: none;">
                            <select class="form-select w-100" name="submajor_group_id" id="sub-major-group" required>
                                <option selected></option>
                            </select>
                        </div>

                        <div class="mb-4" id="taskId-parent"  style="display: none;">
                            <label for="taskId" class="form-label form-label-required">Select Sub Major Group:</label>
                            <select class="form-select" id="taskId" name="task_id" style="width: 320px;" required>
                                <option selected></option>
                            </select>
                        </div>

                        <div class="mb-4" id="subTaskId-parent"  style="display: none;">
                            <label for="subTaskId" class="form-label">Select Minor Group:</label>
                            <select class="form-select" id="subTaskId" style="width: 320px;">
                                <option selected></option>
                            </select>
                        </div>

                        <div class="row" id="processAllSubTask">

                        </div>

                        <div class="row" id="processSubTaskDetails">
                            
                        </div>




                        

                        <button type="submit" class="btn btn-primary btndarkblue">Submit Survey</button>
                    </form>



                    <!-- Add more tasks as needed -->
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#major-group').change(function() {
                    var majorGroupId = $(this).val();
                    //alert(majorGroupId);
                    if (majorGroupId) {
                        majorGroupChange();
                        $(".overlay").fadeIn();
                        $.ajax({
                            url: "<?php echo base_url('/getSubMajorGroup'); ?>",
                            type: "POST",
                            data: {
                                majorGroupId: majorGroupId,
                                [csrfName]: csrfHash,
                            },
                            dataType: "json",
                            success: function(states) {
                                $(".overlay").fadeOut(2000);
                                $('#sub-major-group-parent').show();
                                $('#sub-major-group').empty();
                                $('#sub-major-group').append('<option value="">Select a Major Group</option>');
                                $.each(states, function(index, state) {
                                    $('#sub-major-group').append('<option value="' + state.id + '">'+ state.code + ' :: ' + state.name + '</option>');
                                });
                                getCSRF();
                            }
                        });
                    } else {
                        $('#sub-major-group').empty();
                        $('#sub-major-group').append('<option value="">Select a Major Group</option>');
                    }
                });

                $('#sub-major-group').change(function() {
                    var subMajorGroupId = $(this).val();
                    //alert(majorGroupId);
                    if (subMajorGroupId) {
                        submajorGroupChange();
                        $(".overlay").fadeIn();
                        $.ajax({
                            url: "<?php echo base_url('/getTask'); ?>",
                            type: "POST",
                            data: {
                                subMajorGroupId: subMajorGroupId,
                                [csrfName]: csrfHash,
                            },
                            dataType: "json",
                            success: function(tasks) {
                                $(".overlay").fadeOut(2000);
                                $('#taskId-parent').show();
                                $('#taskId').empty();
                                $('#taskId').append('<option value="">Select Sub Major Group</option>');
                                $.each(tasks, function(index, task) {
                                    $('#taskId').append('<option value="' + task.id + '">' + task.code + ' :: ' + task.name + '</option>');
                                });
                                getCSRF();
                            }
                        });
                    } else {
                        $('#taskId').empty();
                        $('#taskId').append('<option value="">Select Sub Major Group</option>');
                    }
                });

                $('#taskId').change(function() {
                    var taskId = $(this).val();
                    //alert(majorGroupId);
                    if (taskId) {
                        taskChange();
                        $(".overlay").fadeIn();
                        $.ajax({
                            url: "<?php echo base_url('/getSubTask'); ?>",
                            type: "POST",
                            data: {
                                taskId: taskId,
                                [csrfName]: csrfHash,
                            },
                            dataType: "json",
                            success: function(subtasks) {
                                getCSRF();
                                $(".overlay").fadeOut(2000);
                                $('#subTaskId-parent').show();
                                $('#subTaskId').empty();
                                $('#subTaskId').append('<option value="">Select a Minor Group</option>');
                                $.each(subtasks, function(index, subtask) {
                                    $('#subTaskId').append('<option value="' + subtask.id + '">' + subtask.code + ' :: ' + subtask.name + '</option>');
                                });
                                setTimeout(function() { processAllSubTask(taskId); }, 2000);
                                //getSubTasks(taskId);
                                
                            }
                        });
                    } else {
                        $('#subTaskId').empty();
                        $('#subTaskId').append('<option value="">Select a Minor Group</option>');
                    }
                });


                $('#subTaskId').change(function() {
                    var subTaskId = $(this).val();
                    if (subTaskId) {
                        subtaskChange();
                        $.ajax({
                            url: "<?php echo base_url('/getSubTaskDetails'); ?>",
                            type: "POST",
                            data: {
                                subTaskId: subTaskId,
                                [csrfName]: csrfHash,
                            },
                            dataType: "html",
                            success: function(processSubTaskDetailsHtml) {
                                console.log(processSubTaskDetailsHtml);
                                $('#processAllSubTask').html('');
                                $('#processSubTaskDetails').html(processSubTaskDetailsHtml);
                                getCSRF();
                            }
                        });
                    }
                });
            });

           

            function processAllSubTask(taskId) {
                $(".overlay").fadeIn();
                $.ajax({
                    url: "<?php echo base_url('/getAllSubTask'); ?>",
                    type: "POST",
                    data: {
                        taskId: taskId,
                        [csrfName]: csrfHash,
                    },
                    dataType: "html",
                    success: function(allsubtasks) {
                        getCSRF();
                        $(".overlay").fadeOut(2000);
                        $('#processSubTaskDetails').html('&nbsp;');
                        $('#processAllSubTask').html(allsubtasks);
                        
                    }
                });
            }

            function majorGroupChange(){
                $('#sub-major-group-parent').hide();
                $('#taskId-parent').hide();
                $('#subTaskId-parent').hide();
                $('#processAllSubTask').html('');
                $('#processSubTaskDetails').html('');
            }

            function submajorGroupChange(){
                $('#taskId-parent').hide();
                $('#subTaskId-parent').hide();
                $('#processAllSubTask').html('');
                $('#processSubTaskDetails').html('');
            }

            function taskChange(){
                $('#subTaskId-parent').hide();
                $('#processAllSubTask').html('');
                $('#processSubTaskDetails').html('');
            }

            function subtaskChange(){
                $('#processAllSubTask').html('');
                $('#processSubTaskDetails').html('');
            }

            function getCSRF(){
                $.get("<?php echo base_url('/getCsrfCode'); ?>", function(data, status){
                    //alert("Data: " + data + "\nStatus: " + status);
                    csrfHash=data;
                    $( "input[name*='cmicaribbean_survey_token']" ).val(data);
                });
            }
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"> </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>