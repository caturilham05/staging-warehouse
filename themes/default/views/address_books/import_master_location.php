<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<div class="">
    <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<hr style="border: 1px solid #959595">
<p>Pastikan kolom excel sesuai dengan kriteria dibawah ini: <br>• (*) = Wajib<br>• (**) = <b>Boleh</b> diisi atau <b>Tidak</b><br>• (-) = Tidak perlu di isi</p>
<div class="mb-4" style="overflow-x: auto;">
    <table class="table table-bordered table table-striped mb-0">
        <tr>
            <th>A</th>
            <th>B</th>
            <th>C</th>
            <th>D</th>
            <th>E</th>
            <th>F</th>
            <th>G</th>
        </tr>
        <tr>
            <td><b>COUNTRY_NAME</b></td>
            <td><b>PROVINCE_NAME</b></td>
            <td><b>CITY_NAME</b></td>
            <td><b>DISTRICT_NAME</b></td>
            <td><b>SUBDISTRICT_NAME</b></td>
            <td><b>ZIP_CODE</b></td>
            <td><b>TARIFF_CODE</b></td>
        </tr>
        <tr>
        </tr>
    </table>
</div>
<hr style="border: 1px solid #959595">
<br>
<div class="row">
    <div class="col-md-12">
            <?= form_open_multipart("address_books/import_master_location_process", 'class="validation"'); ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <?= form_upload('upload_master_location', '', '')?>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                      <p><?php echo form_submit('upload_master_location', lang('Import Excel'), 'class="btn btn-theme03"'); ?></p>
                  </div>
                </div>
            <?= form_close(); ?>
    </div>
</div>