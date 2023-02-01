<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="">
    <h4><i class="fa fa-database"></i> <?= lang('backup_database'); ?> <a href="<?= site_url('settings/backup_database'); ?>" id="backup_databse" class="pull-right btn btn-primary"><i class="icon fa fa-database"></i> <?= lang('backup_database'); ?></a></h4>
    <p><?= lang('restore_heading'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">

            <div class="row">
                <div class="col-md-12">
                    <?php
                    if(! empty($dbs)) {
                        echo '<ul class="list-group">';
                        foreach ($dbs as $file) {
                            $file = basename($file);
                            echo '<li class="list-group-item">';
                            $date_string = substr($file, 13, 10);
                            $time_string = substr($file, 24, 8);
                            $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                            $bkdate = $this->tec->hrld($date);
                            echo '<strong>' . lang('backup_on') . ' <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-7px;">' . anchor('settings/download_database/' . substr($file, 0, -4), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('settings/restore_database/' . substr($file, 0, -4), '<i class="fa fa-database"></i> ' . lang('restore'), 'class="btn btn-warning restore_db"') . ' ' . anchor('settings/delete_database/' . substr($file, 0, -4), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></strong>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="wModal" tabindex="-1" role="dialog" aria-labelledby="wModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="wModalLabel"><?= lang('please_wait'); ?></h4>
            </div>
            <div class="modal-body">
                <?= lang('backup_modal_msg'); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#backup_files').click(function (e) {
            e.preventDefault();
            $('#wModalLabel').text('<?=lang('backup_modal_heading');?>');
            $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
            window.location.href = '<?= site_url('settings/backup_files'); ?>';
        });
        $('.restore_backup').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var r = confirm("<?=lang('restore_confirm');?>");
            if (r == true) {
               $('#wModalLabel').text('<?=lang('restore_modal_heading');?>');
                $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
                window.location.href = href;
            } else {
                return false;
            }
        });
        $('.restore_db').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var r = confirm("<?=lang('restore_confirm');?>");
            if (r == true) {
                window.location.href = href;
            } else {
                return false;
            }
        });
        $('.delete_file').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var r = confirm("<?=lang('delete_confirm');?>");
            if (r == true) {
                window.location.href = href;
            } else {
                return false;
            }
        });
    });
</script>