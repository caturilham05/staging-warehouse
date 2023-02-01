<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= $page_title.' | '.$Settings->site_name; ?></title>
	<link rel="shortcut icon" href="<?= $assets ?>img/icon.png"/>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- <link href="<?= $assets ?>css/bootstrap.css" rel="stylesheet" type="text/css" /> -->
	<style>
		/* body { text-align:center; }
		td { text-align: center; }
		h4 { margin:5px; padding:0; }
		.price { font-size:0.8em; font-weight:bold; }
		@media print
		{
			.container { width: auto !important; }
			.container h4, .container p,
			.btn-group, .pagination { display: none; }
			.labels { text-align:center;font-size:10pt;page-break-after:always;padding:1px; }
		} */

		/* <style> */
    .center {
        margin: 0 auto;
        width: 100mm;
        height: 100mm;
        border: 1px solid;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 55px;
        padding-bottom: 52px;
    }

    .label{
        font-size: 8PX;
        color: black;
        margin: 0px;
        padding: 0px;
        line-height: 150%;
    }

    .label-title{
        font-size: 10PX;
        color: black;
        margin: 0px;
        padding: 0px;
        line-height: 165%;

    }  
    
    
    .tabel-utama {
        width: 100%; 
        font-family:Arial, Helvetica, sans-serif;
        font-weight:500; 
        margin: 0px;
        padding: 0px; 
        margin: 0px;
    }

    .tabel-utama  td , tr{
        padding: 0; 
        margin: 0;
        vertical-align: top;
        font-weight: 500;
    }
    
    .line{
        border-bottom: 1px rgb(219, 215, 219);
    }

    .tabel-ku {
        font-family:Arial, Helvetica, sans-serif;
        font-weight:500; 
        margin: 0px;
        padding: 0px; 
        margin: 0px;
    }

    .tabel-ku  td {
        padding: 0; 
        margin: 0;
        vertical-align: top;
        font-weight: 500;
    }

    /* @media print {
    .pagebreak {
        page-break-inside: initial;
        display: block;
    }
} */
@media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
}
</style>
</head>
<body>
	<!-- <div class="container">
		<h4><?=$Settings->site_name.'<br>'.$page_title?></h4>
		
		<p>Each image will be printed on separate page.</p>
		<div class="text-center"><?php echo $this->pagination->create_links(); ?></div>
		<?=$html?>
		<div  class="text-center"><?php echo $this->pagination->create_links(); ?></div>
	</div> -->
	<!-- <div class="btn-group">
			<a class="btn btn-primary" href="<?=site_url('settings');?>"><i class="glyphicon glyphicon-dashboard"></i> <?= lang('dashboard'); ?></a>
			<a class="btn btn-default" href="javascript:void();" onclick="window.print();"><i class="glyphicon glyphicon-print"></i> <?= lang('print'); ?></a>
		</div> -->
    <?php 
     $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
 
     foreach ($sales as $val) { 
         $data = $generator->getBarcode($val->awb_no, $generator::TYPE_CODE_128);
        ?>
       
        <div class="center" id="rPage">
            <table class="tabel-utama" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 50%"><img src="../themes/default/assets/img/jne.png" alt="gambar" height="45" srcset=""></td>  
                    <td colspan="3" style="width: 60%">
                        <center>
                            <table class="tabel-ku" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width: 100%"> <img src="data:image/png;base64, <?= base64_encode($data)?>"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;"> <div for="" class="label">AWB: <?=$val->awb_no?>
                                    </br>
                                    <?= $val->order_no ?>
                                </div></td>
                                </tr>
                            
                            </table>
                        </center>
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <hr style="height:1px;border-width:0;background-color:rgb(219, 215, 219)">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td style="width: 60%" >
                        <table class="tabel-ku" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div for="" class="label">Jenis layanan: <?= $val->service ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Biaya Pengiriman: Rp<?=number_format($val->shipping_price, 0, ',', '.')?></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td >  
                        <div for="" class="label">Berat: <?=$val->weight ?> KG</div>
                    </td>
                
                    <td colspan="2" style="width: 5%">  
                        <div for="" class="label-title"><?=$val->type?></div>
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <hr style="height:1px;border-width:0;background-color:rgb(219, 215, 219)">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4" style="text-align: center">
                        <div for="" class="label-title">COD: Rp<?=number_format($val->cod_value, 0, ',', '.')?></div>
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <hr style="height:1px;border-width:0;background-color:rgb(219, 215, 219)">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td>
                        <table class="tabel-ku" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div for="" class="label">Asal</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label-title"><?= $val->shipper_city ?></div>
                                </td>
                            </tr>
                        </table class="tabel-ku">
                    </td>
                    <td colspan="3">
                        <table class="tabel-ku" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div for="" class="label">Tujuan</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label-title"><?=$val->receiver_city ?></div>
                                </td>
                            </tr>
                        </table class="tabel-ku">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <hr style="height:1px;border-width:0;background-color:rgb(219, 215, 219)">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td align="">
                        <!-- pengirim -->
                        <table class="tabel-ku" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div for="" class="label-title">PENGIRIM</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Nama : <?= $val->shipper_name ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Nomor Telepon: <?= $val->shipper_phone ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Alamat Lengkap: <?=$val->shipper_address?>, <?= $val->shipper_subdistrict?>, <?=$val->shipper_city?> - <?= $val->shipper_zip_code ?></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- penerima -->
                    <td colspan="3">
                        <table class="tabel-ku" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div for="" class="label-title">PENERIMA</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Nama: <?= $val->shipper_name ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">Nomor Telepon: <?= $val->receiver_phone ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div for="" class="label">
                                    <?=$val->shipper_address?>, <?= $val->shipper_subdistrict?>, <?=$val->shipper_city?> - <?= $val->shipper_zip_code ?>
                                    </div>
                                </td>
                            </tr>
                        </table class="tabel-ku">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td>
                        <div for="" class="label-title"><?= $val->shipper_city?></div>
                    </td>
                    <td colspan="3">
                        <div for="" class="label-title"><?= $val->shipper_city?></div>
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <hr style="height:1px;border-width:0;background-color:rgb(219, 215, 219)">
                    </td>
                </tr>
                <!-- newsection -->
                <tr>
                    <td colspan="4">
                        <div for="" class="label-title">CATATAN</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div for="" class="label">Catatan barang:</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div for="" class="label"><?= $val->goods_description?></div>
                    </td>
                </tr>
            </table>
        </div> 
    <?php } ?>
</body>
</html> 