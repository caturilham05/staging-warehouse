<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="">
    <h3><i class="fa fa-arrow-circle-down"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("check_out/add_by_csv", 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('date', 'date'); ?>
                                <?= form_input('date', set_value('date', date('Y-m-d H:i')), 'class="form-control tip" id="date"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('reference', 'reference'); ?>
                                <?= form_input('reference', set_value('reference', $reference), 'class="form-control tip" id="reference" required="required" onClick="this.select();"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('customer', 'customer'); ?>
                                <?php
                                $sp[''] = lang('select_customer');
                                foreach ($customers as $customer) {
                                    $sp[$customer->id] = $customer->name;
                                }
                                ?>
                                <?= form_dropdown('customer', $sp, set_value('customer'), 'class="form-control tip" id="customer"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('attachment', 'attachment'); ?>
                                <input name="attachment" type="file" id="attachment" />
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="well well-sm">
                              <a href="<?= base_url('uploads/csv/sample.csv'); ?>" class="btn btn-info btn-sm pull-right"><i class="fa fa-download"></i> <?= lang("download_sample_file"); ?></a>

                              <p><?= "<span class=\"text-info\">".lang("csv1")."</span><br /><span class=\"text-success\">". lang("csv2")." (<b>".lang("item_code").", ".lang("quantity")."</b>)</span> <span class=\"text-primary\">".lang("csv3")."</span>"; ?></p>
                          </div>
                          <div class="form-group">
                              <?= lang("upload_file", 'csv_file'); ?>
                              <input type="file" name="userfile" id="csv_file">
                              <div class="inline-help"><?= lang("csv_file_tip"); ?></div>
                          </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('note', 'note'); ?>
                                <?= form_textarea('note', set_value('note'), 'class="form-control redactor tip" id="note" style="height:100px;"'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><?php echo form_submit('check_out', lang('add_check_out'), 'class="btn btn-theme03"'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $assets ?>js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var stoutitems = {};
    $(document).ready(function() {
        if (get('stoutitems')) { remove('stoutitems'); }
        loadInItems();
    });
</script>
<script src="<?= $assets ?>js/stout.js" type="text/javascript"></script>
