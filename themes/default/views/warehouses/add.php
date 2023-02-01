<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<div class="">
    <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("warehouses/add", 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("name", "name"); ?>
                                <?= form_input('name', set_value('name'), 'class="form-control tip" id="name" required="required"'); ?>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><?php echo form_submit('Add Warehouse', lang('Add Warehouse'), 'class="btn btn-theme03"'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>