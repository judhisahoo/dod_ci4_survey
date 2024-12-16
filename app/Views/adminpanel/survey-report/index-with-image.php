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
            <h1><?php echo $title; ?></h1>
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
                    <option>Selec Major group</option>
                    <?php foreach ($SubmajorGroups as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $submajor_group_id) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-5 pr-0">
                <select class="form-select w-100" id="taskId" name="task_id" style="width: 320px;" required>
                    <option>Selec Sub Major group</option>
                    <?php foreach ($Tasks as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $task_id) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 pr-0 pl-0">
                <select class="form-select w-100" id="subTaskId" name="subTaskId" style="width: 320px;" required>
                    <option>Select Minor Group</option>
                    <?php foreach ($SubTasks as $majorgroup): ?>
                        <option value="<?= $majorgroup['id'] ?>" <?php echo ($majorgroup['id'] == $subTaskId) ? 'selected' : ''; ?>><?= $majorgroup['code'] ?> :: <?= $majorgroup['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-1 pr-0 pl-0">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-3 pl-0">
                <button type="button" class="btn btn-secondary" data-myid="" title="" id="generategraph">Generate Graph</button>
            </div>
            <div class="col-1 pl-0">
                <button type="button" class="btn btn-secondary" id="capture-btn">Capture</button>
            </div>

        </div>
    </form>
    <div class="row">&nbsp;</div>
    <?php if (!empty($reports)): ?>
        <div class="row pt-4">

            <table id="example" class="table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Unit Title</th>
                        <th>Demand By Emloyer</th>
                        <th>Supplied By institution</th>
                        <th>Indivisual Skill Scores</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $survey): ?>
                        <tr>
                            <td><?= $pageSlNo ?></td>
                            <td><?= $survey['name'] ?></td>
                            <td><?php echo (array_key_exists('employer_ratting', $survey)) ? $survey['employer_ratting'] : '0'; ?></td>
                            <td><?php echo (array_key_exists('institution_ratting', $survey)) ? $survey['institution_ratting'] : '0'; ?></td>
                            <td><?= $survey['skill_score'] ?></td>
                        </tr>
                        <?php $pageSlNo++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="row pt-4" style="height: 250px;">
            <h1> No Reports Found.</h1>
        </div>
    <?php endif; ?>


</div>

<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    document.getElementById('capture-btn').addEventListener('click', () => {
        const element = document.getElementById('example');
        html2canvas(element).then((canvas) => {
            // Convert the canvas to an image
            const imgData = canvas.toDataURL('image/png');
            
            // Create a download link
            const link = document.createElement('a');
            link.href = imgData;
            var currentdate = new Date();
            var fileName = "Last Sync: " + currentdate.getDate() + "_"
                + (currentdate.getMonth()+1)  + "_" 
                + currentdate.getFullYear() + "_"  
                + currentdate.getHours() + "_"  
                + currentdate.getMinutes() + "_" 
                + currentdate.getSeconds()+"_screenshot.png";
            link.download = $fileName;
            link.click();
        });
    })

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
        $('#generategraph').click(function() {
            //var subtaskid = $(this).data('myid');
            var subtaskid = $('#subTaskId').val();
            console.log('subtaskid using data attr::' + subtaskid);
            if (subtaskid != '') {
                location.href = '<?php echo base_url('/adminpanel/survey-generate-graph/'); ?>' + subtaskid;
            }

            /*subtaskid = $(this).attr('title'); 
            console.log('subtaskid user title attr::' +subtaskid);
            if(subtaskid != ''){
                location.href = '<?php echo base_url('/adminpanel/survey-generate-graph/'); ?>'+subtaskid;
            }*/
        })

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
                        //getSubTasks(taskId);

                    }
                });
            } else {
                $('#subTaskId').empty();
                $('#subTaskId').append('<option value="">Select a Minor Group</option>');
            }
        });
    });

    new DataTable('#example', {
        info: false,
        paging: false,
        layout: {
            topStart: {
                buttons: [{
                        extend: 'excelHtml5',
                        text: "Export AS Excel",
                        attr: {
                            class: 'btn btn-primary buttons-pdf buttons-html5',
                        }
                    },

                    {
                        extend: 'pdfHtml5',
                        text: "Export AS Pdf"
                    }
                ]
            }
        }
    });
</script>
<?= $this->endSection() ?>