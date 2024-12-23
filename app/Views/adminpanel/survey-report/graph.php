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
</style>
<div class="container mt-5">
    <div class="overlay" style="display:none"><img src="http://i.imgur.com/KUJoe.gif"></div>
    <div class="row">
        <div class="col align-self-start">
            <h1>Bar Chart <?php echo $title; ?></h1>
        </div>
    </div>
    <?php
    if (session()->getFlashdata('status') != '') {
        echo '<div class="row"><div class="alert alert-info">' . session()->getFlashdata('status') . '</div></div>';
    }
    ?>
    <div class="row">
        &nbsp;
    </div>
    <form action="<?php echo base_url('/adminpanel/survey-generate-report'); ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row">

            <div class="col-3 p-3">
                <select name="major-group" id="major-group" class="form-select w-100 fs-6" required>
                    <option value="">Select Top Group</option>
                    <?php foreach ($majorgroups as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $majorGroupId) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-7 p-3">
                <select class="form-select w-100" name="submajor_group_id" id="sub-major-group" required>
                    <option value="">Selec Major group</option>
                    <?php foreach ($SubmajorGroups as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $submajor_group_id) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-5 pr-0">
                <select class="form-select w-100" id="taskId" name="task_id" style="width: 320px;" required>
                    <option value="">Selec Sub Major group</option>
                    <?php foreach ($Tasks as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $task_id) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 pr-0 pl-0">
                <select class="form-select w-100" id="subTaskId" name="subTaskId" style="width: 320px;">
                    <option value="">Select Minor Group</option>
                    <?php foreach ($SubTasks as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $subTaskId) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-1 pr-0 pl-0">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-2 pl-0">
                <button type="button" class="btn btn-secondary" data-myid="" id="generategraph">Generate Graph</button>
            </div>

        </div>
    </form>
    <div class="row">&nbsp;</div>
    <?php if (!empty($reports)): ?>
        <div class="row pt-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bar Chart</h5>

                        <!-- Bar Chart -->
                        <div id="barChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#barChart"), {
                                    series: [{
                                        data: <?php echo json_encode($reportsData) ?>
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 350,
                                        stacked: true,
                                        stackType: "100%"
                                    },
                                    plotOptions: {
                                        bar: {
                                            borderRadius: 4,
                                            horizontal: true,
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    xaxis: {
                                        categories: <?php echo json_encode($reportsCategory) ?>,
                                    }
                                }).render();
                            });
                        </script>
                        <!-- End Bar Chart -->

                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row pt-4" style="height: 250px;">
            <h1> No Reports Found.</h1>
        </div>
    <?php endif; ?>


</div>

<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script>
    function majorGroupChange() {
        $('#sub-major-group').find('option').remove().end().append('<option value="">Selec Major group</option>');
        $('#taskId').find('option').remove().end().append('<option value="">Selec Sub Major group</option>');
        $('#subtaskId').find('option').remove().end().append('<option value="">Selec Sub Major group</option>');
    }

    function submajorGroupChange() {
        $('#taskId').find('option').remove().end().append('<option value="">Selec Sub Major group</option>');
        $('#subtaskId').find('option').remove().end().append('<option value="">Selec Sub Major group</option>');
    }

    function taskChange() {
        $('#subtaskId').find('option').remove().end().append('<option value="">Selec Sub Major group</option>');
    }

    function getCSRF() {
        $.get("<?php echo base_url('/getCsrfCode'); ?>", function(data, status) {
            //alert("Data: " + data + "\nStatus: " + status);
            csrfHash = data;
            $("input[name*='cmicaribbean_survey_token']").val(data);
        });
    }

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
                        getCSRF();
                        $('#sub-major-group').empty();
                        $('#sub-major-group').append('<option value="">Select a Major Group</option>');
                        $.each(states, function(index, state) {
                            $('#sub-major-group').append('<option value="' + state.id + '">' + state.code + ' :: ' + state.name + '</option>');
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
                        getCSRF();
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
                $('#generategraph').data('myid', subTaskId);
            }
        });

        $('#generategraph').click(function() {
            var subtaskid = $(this).data('myid');
            console.log('subtaskid using data attr::' + subtaskid);

            var taskId = $('#taskId').val();
            console.log('taskId using data attr::' + taskId);
            if (subtaskid != '') {
                console.log('1cond');
                location.href = '<?php echo base_url('/adminpanel/survey-generate-graph/'); ?>' + subtaskid + '/subtask';
            } else if (taskId != '') {
                console.log('2ndcond');
                location.href = '<?php echo base_url('/adminpanel/survey-generate-graph/'); ?>' + taskId + '/task';
            }
        })
    });
</script>
<?= $this->endSection() ?>