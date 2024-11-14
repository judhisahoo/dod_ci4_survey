<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeIgniter 4 Crud App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        <style>
            .rating {
                display: flex;
                justify-content: space-between;
            }

            /*.rating input[type="radio"] {
                display: none;
            }*/

            .rating label {
                cursor: pointer;
                padding: 5px 10px;
                border: 1px solid #ced4da;
                border-radius: 5px;
                transition: background-color 0.2s;
            }

            .rating label:hover,
            .rating input[type="radio"]:checked+label {
                background-color: #0d6efd;
                color: #fff;
            }

            .header-image {
                width: 100%;
                border-radius: 0 0 10px 10px;
            }

            .form-label-required::after {
                content: " *";
                color: red;
            }

            .overlay {
    position: fixed; /* Fixed position to overlay on top of everything */
    top: 0; /* Align to the top */
    left: 0; /* Align to the left */
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 999; /* High z-index to sit on top of other elements */
    display: flex; /* Use flexbox for centering */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    color: white; /* Text color */
    font-size: 2rem; /* Text size */
    text-align: center; /* Center text */
    pointer-events: none; /* Allow clicks to pass through the overlay */
}
        </style>


        <div class="container mt-5">
            <div class="card">
                <img src="header-image.jpg" class="card-img-top" alt="Survey Banner">
                <div class="card-body">
                    <h1 class="card-title text-center">Survey Portal</h1>
                    <p class="text-center mb-4">Survey Portal: Select Your Occupation</p>

                    <form action="<?php echo base_url('/submit-survey'); ?>" method="post">
                    <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="name" class="form-label form-label-required">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="phone" class="form-label form-label-required" required>Phone</label>
                                <input type="tel" class="form-control" id="phone" placeholder="Enter your phone">
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label form-label-required">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                            </div>
                        </div>


                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="business-name" class="form-label form-label-required">Name of Business</label>
                                <input type="text" class="form-control" id="business-name" placeholder="Enter business name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sector" class="form-label form-label-required">Sector</label>
                                <input type="text" class="form-control" id="sector" placeholder="Enter sector" required>
                            </div>
                            <div class="col-md-4">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" placeholder="Enter address">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="major-group" class="form-label form-label-required">Top Group:</label>
                            <select class="form-select" id="major-group" required>
                                <option value="">Select Top Group</option>
                                <?php foreach ($majorGroupData as $majorGroup): ?>
                                    <option value="<?php echo $majorGroup['id']; ?>"><?php echo $majorGroup['code']; ?> :: <?php echo $majorGroup['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4" id="sub-major-group-parent" style="display: none;">
                            <label for="sub-major-group" class="form-label">Select Major Group:</label>
                            <select class="form-select" id="sub-major-group">
                                <option selected></option>
                            </select>
                        </div>

                        <div class="mb-4" id="taskId-parent"  style="display: none;">
                            <label for="taskId" class="form-label">Select Sub Major Group:</label>
                            <select class="form-select" id="taskId">
                                <option selected></option>
                            </select>
                        </div>

                        <div class="mb-4" id="subTaskId-parent"  style="display: none;">
                            <label for="subTaskId" class="form-label">Select Minor Group:</label>
                            <select class="form-select" id="subTaskId">
                                <option selected></option>
                            </select>
                        </div>

                        <div class="row" id="processAllSubTask">

                        </div>

                        <div class="row" id="processSubTaskDetails">
                            
                        </div>




                        

                        <button type="submit" class="btn btn-primary">Submit Survey</button>
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
                                majorGroupId: majorGroupId
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
                        $.ajax({
                            url: "<?php echo base_url('/getTask'); ?>",
                            type: "POST",
                            data: {
                                subMajorGroupId: subMajorGroupId
                            },
                            dataType: "json",
                            success: function(tasks) {
                                $('#taskId-parent').show();
                                $('#taskId').empty();
                                $('#taskId').append('<option value="">Select Sub Major Group</option>');
                                $.each(tasks, function(index, task) {
                                    $('#taskId').append('<option value="' + task.id + '">' + task.code + ' :: ' + task.name + '</option>');
                                });
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
                        $.ajax({
                            url: "<?php echo base_url('/getSubTask'); ?>",
                            type: "POST",
                            data: {
                                taskId: taskId
                            },
                            dataType: "json",
                            success: function(subtasks) {
                                $('#subTaskId-parent').show();
                                $('#subTaskId').empty();
                                $('#subTaskId').append('<option value="">Select a Minor Group</option>');
                                $.each(subtasks, function(index, subtask) {
                                    $('#subTaskId').append('<option value="' + subtask.id + '">' + subtask.code + ' :: ' + subtask.name + '</option>');
                                });
                                processAllSubTask(taskId);
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
                                subTaskId: subTaskId
                            },
                            dataType: "html",
                            success: function(processSubTaskDetailsHtml) {
                                console.log(processSubTaskDetailsHtml);
                                $('#processAllSubTask').html('');
                                $('#processSubTaskDetails').html(processSubTaskDetailsHtml);
                            }
                        });
                    }
                });
            });

           

            function processAllSubTask(taskId) {
                $.ajax({
                    url: "<?php echo base_url('/getAllSubTask'); ?>",
                    type: "POST",
                    data: {
                        taskId: taskId
                    },
                    dataType: "html",
                    success: function(allsubtasks) {
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
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"> </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>