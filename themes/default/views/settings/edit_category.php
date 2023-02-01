<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="">
    <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">

            <?php echo form_open("settings/edit_category/".$category->id);?>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <?= lang('code', 'code'); ?>
                        <?= form_input('code', $category->code, 'class="form-control tip" id="code"  required="required"'); ?>
                    </div>

                    <div class="form-group">
                        <?= lang('name', 'name'); ?>
                        <?= form_input('name', $category->name, 'class="form-control tip" id="name"  required="required"'); ?>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <?= form_submit('edit_category', lang('edit_category'), 'class="btn btn-primary"'); ?>
            </div>

            <?php echo form_close();?>
        </div>
    </div>
</div>
