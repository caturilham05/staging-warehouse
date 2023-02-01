<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="">
    <h3><i class="fa fa-file-text-o"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
    <script>
        $(function() {

            $('#date').change(function() {
                window.location.replace('<?= site_url('log/'.$page) ?>/' + $(this).val());
                return false;
            });
            $('#del_log').click(function(){
                if($('#ddate').val() != '') {
                    con = confirm('All log files older then selected date will be deleted permanently. Press OK to proceed and Cancel to Go Back');
                    if(con) {
                        $('#del-form').submit();
                    } else {
                        return false;
                    }
                } else {
                    alert('Please select date first');
                }
            });
        });
    </script>
    <style>
        ul.details { color: #222; margin-left: 0; background: #F5F5F5; border: 1px solid #EEE; padding: 0; font-family: Arial, sans-serif; }
        ul.details li {  list-style: none; line-height: 1.5em; padding:10px; border-bottom: 1px solid #eee; }
        ul.details li:nth-child(odd) { background: #FFF; }
        ul.details li:nth-child(even) { background: #F9F9F9; }
        .styled { margin-bottom: 10px; }
        ul.details li:last-child { display: none; }
    </style>

    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-btn"><button type="button" class="btn btn-primary" />Select Date</button></div>
            <input type="text" name="date" class="styled form-control datepicker" placeholder="Select Date to list Log" id="date" value="<?=$log_date?>" />
        </div>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <?= form_open('log/delete/'.($page == 'close' ? 'task' : 'email'), 'id="del-form"'); ?>
        <div class="input-group">
            <input class="form-control datepicker" type="text" name="ddate" id="ddate" placeholder="Select date to delete old logs" />
            <div class="input-group-btn"><button type="button" class="btn btn-danger" id="del_log" />Delete Logs</button></div>
        </div>
        <div style="display:none;"><input type="submit" value="Delete Older" name="delete" id="delete" /></div>
        <?=form_close();?>
    </div>


    <div class="clearfix" style="margin-bottom: 15px;"></div>
    <div class="col-md-12">
        <ul class="details">
            <?php
            if(!empty($logs[0])) {
                foreach ($logs as $value) {
                    echo '<li>' . $value . '</li>';
                }
            } else {
                echo "<li>Log is empty for selected date</li><li></li>";
            }
            ?>
        </ul>
    </div>
</div>