<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="">
  <h3><i class="fa fa-barcode"></i> <?= $page_title; ?></h3>
  <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="content-panel">

      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-body">

              <div class="well well-sm">
                <a href="<?= base_url('uploads/csv/sample_products.csv'); ?>" class="btn btn-info btn-sm pull-right"><i class="fa fa-download"></i> <?= lang("download_sample_file"); ?></a>

                <p><?= "<span class=\"text-info\">".lang("csv1")."</span><br /><span class=\"text-success\">". lang("csv2")." (<b>".lang("product_code").", ".lang("product_name").", ".lang("category_code").", ".lang("quantity").", ".lang("unit").", ".lang("alert_quantity").", ".lang("image_with_ext")."</b>)</span> <span class=\"text-primary\">".lang("csv3")."</span>"; ?></p>
              </div>

              <?= form_open_multipart("items/import");?>
              <div class="form-group">
                <?= lang("upload_file", 'csv_file'); ?>
                <input type="file" name="userfile" id="csv_file">
                <div class="inline-help"><?= lang("csv_file_tip"); ?></div>
              </div>
              <div class="form-group">
                <?= form_submit('import', lang('import'), 'class="btn btn-primary"'); ?>
              </div>
              <?= form_close();?>

              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
