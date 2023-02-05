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
            <th>H</th>
            <th>I</th>
            <th>J</th>
            <th>K</th>
            <th>L</th>
            <th>M</th>
            <th>N</th>
            <th>O</th>
            <th>P</th>
            <th>Q</th>
            <th>R</th>
            <th>S</th>
            <th>T</th>
            <th>U</th>
        </tr>
        <tr>
            <td><b>Warehouse(*)</b></td>
            <td><b>AWB No.(**)</b></td>
            <td><b>Courier(**)</b></td>
            <td><b>Service(**)</b></td>
            <td><b>Type(*)</b></td>
            <td><b>Package Price(*)</b></td>
            <td><b>Shipping Price(*)</b></td>
            <td><b>Shipper Name(*)</b></td>
            <td><b>Receiver Name(*)</b></td>
            <td><b>Receiver Phone(*)</b></td>
            <td><b>Receiver Address(*)</b></td>
            <td><b>Receiver City(*)</b></td>
            <td><b>Receiver Subdistrict(*)</b></td>
            <td><b>Receiver Zip Code(*)</b></td>
            <td><b>Goods Description(*)</b></td>
            <td><b>Weight(*)</b></td>
            <td><b>Length x Width x Height(**)</b></td>
            <td><b>Shipping Note(**)</b></td>
            <td><b>Last Tracking Status(**)</b></td>
            <td><b>Product Id(*)</b></td>
            <td><b>Product Quantity(*)</b></td>
        </tr>
        <tr>
            <td>Pastikan nama <b>WAREHOUSE</b> sesuai berdasarkan ejaan kata dan sudah terdaftar di sistem. bisa dilihat di menu <a href="<?= site_url('warehouses');?>" target="_blank"><b>Warehouse</b></a></td>
            <td>Nomor resi</td>
            <td>Kurir Pengiriman</td>
            <td>Service pengiriman yang dimiliki pihak ekspedisi</td>
            <td>Type pengiriman</td>
            <td>Harga paket pengiriman</td>
            <td>harga pengiriman</td>
            <td>Nama pengirim produk (Pastikan nama pengirim sesuai berdasarkan ejaan kata dan sudah terdaftar di sistem). bisa dilihat di menu <a href="<?= site_url('address_books');?>" target="_blank"><b>Address Books</b></a></td>
            <td>Nama penerima produk</td>
            <td>Nomor HP penerima produk</td>
            <td>Alamat penerima produk</td>
            <td>Kota penerima produk</td>
            <td>Daerah penerima produk</td>
            <td>Kode pos penerima produk</td>
            <td>Deskripsi produk</td>
            <td>Berat produk</td>
            <td>Dimensi produk</td>
            <td>Catatan pengiriman</td>
            <td>Status pelacakan terakhir</td>
            <td>id produk (code)</td>
            <td>qty produk</td>
        </tr>
    </table>
</div>
<hr style="border: 1px solid #959595">
<br>
<div class="row">
    <div class="col-md-12">
            <?= form_open_multipart("sales/proses_import_excel", 'class="validation"'); ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <?= form_upload('sales_import_excel', '', '')?>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                      <p><?php echo form_submit('import', lang('Import Excel'), 'class="btn btn-theme03"'); ?></p>
                  </div>
                </div>
            <?= form_close(); ?>
    </div>
</div>