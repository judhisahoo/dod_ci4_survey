<div class="row" id="processSubTaskDetails">
    <?php foreach($subTask AS $k=>$v):?>
    <h3 class="mt-4">Tasks (<?php echo $v['subtask_name'];?>)</h3>
    <p>Please give a rating from 1 - 10 (1 for Lowest Demand and 10 for highest Demand)</p>

    <?php foreach($v['subtask_rattings'] AS $m=>$n):?>
    <div class="mb-3">
        <p><?php echo $n['ratting_name'] ?></p>
        <div class="rating">
            <?php for($i=1;$i<11;$i++):?>
            <input type="radio" id="r1-<?php echo $i;?>" name="<?php echo $n['subtask_ratting_id']?>" value="<?php echo $i;?>"><label for="r1-<?php echo $i;?>"><?php echo $i;?></label>
            <?php endfor;?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endforeach;?>
</div>