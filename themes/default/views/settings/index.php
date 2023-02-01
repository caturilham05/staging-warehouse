<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<?php
$ps = array('0' => $this->lang->line("disable"), '1' => $this->lang->line("enable"));
?>
<div class="">
<h3><i class="fa fa-cogs"></i> <?= lang('settings'); ?></h3>
<p><?= lang('update_info'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">

            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("settings", $attrib); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("site_name", "site_name"); ?>
                            <?= form_input('site_name', $Settings->site_name, 'class="form-control tip" id="site_name"  required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('language', 'language'); ?>
                            <?php $available_langs = array(
                             'english' => 'English', 
                             'spanish' => 'Spanish', 
                             ); ?>
                             <?= form_dropdown('language', $available_langs, $settings->language, 'class="form-control tip select2" id="language"  required="required" style="width:100%;"'); ?>
                         </div>
                     </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="rows_per_page"><?= lang("rows_per_page"); ?></label>
                            <div class="controls">
                                <?= form_input('rows_per_page', $Settings->rows_per_page, 'class="form-control tip" id="rows_per_page" required="required"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="date_format"><?= lang("date_format"); ?></label>
                            <div class="controls">
                                <?= form_input('date_format', $Settings->dateformat, 'class="form-control tip" id="date_format" required="required"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="time_format"><?= lang("time_format"); ?></label>
                            <div class="controls">
                                <?= form_input('time_format', $Settings->timeformat, 'class="form-control tip" id="time_format" required="required"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <?= lang('default_email', 'default_email'); ?>
                        <?= form_input('default_email', $settings->default_email, 'class="form-control tip" id="default_email" required="required"'); ?>
                      </div>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
            <div style="clear: both; height: 10px;"></div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="controls">
                        <?= form_submit('update_settings', $this->lang->line("update_settings"), 'class="btn btn-theme03"'); ?>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
