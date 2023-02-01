<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<style>
    @media print 
    {
     @page
        {
            size: 100mm 10mm;
          
        }
    
    }
    @page {
        size: A4;
    }
    .container {
        width: 10cm;
        height: 10cm;
        margin: 0 auto;
        padding: 0;
        background-color: #fff;
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        color: #000;
        border: 1px solid #000;
    }    

   
    table {
        width: 100%;
        height: 100%;
        /* border-collapse: collapse; */
        max-width: 100%;
        background-color: transparent;
        
        border: 1px solid #000;
    }
    

   h6 {
   font-size: 12px;
   font-weight: normal;
    margin: 0;
   }
   h5 {
   font-size: 16px;
   font-weight: normal;
    margin: 0;
   }
   img {
    margin: 0;
   }

</style>
</head>
<!-- <body onload="window.print()"> -->
<body>
<?php
for ($i=0; $i < 5; $i++) { ?>
    <div class="container" style="margin-bottom: 10px;">
    <table border="0" cellpadding="0">

            <thead >
            <tr>
                <td>
                    <img src="../themes/default/assets/img/jne.png" alt="jne" width="100px">
                </td>
                <td align="center">
                    <img src="../themes/default/assets/img/barcode.gif" alt="jne" width="200px">
                   <h6>AWB : 0213382200066624</h6>
                   <h6>TRD2022110704605</h6>
                </td>

            </tr>
            </thead>
        <tbody>
            <tr>
                <td>Jenis Layanan : REG19</td>
                <td>Berat 1 KG</td>
                
            </tr>
            <tr>
                <td>Biaya Pengiriman : Rp45.000</td>
                
                <td align="right"><h5>PICKUP</h5></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><h3 style="margin:0">COD Rp320.000</h3></td>
                
            </tr>
            <tr>
                <td>
                    <h6>Asal</h6>
                    <h5>Majalengka</h5>
                </td>   
                <td>
                    <h6>Tujuan</h6>
                    <h5>Majalengka</h5>
                </td>
               
            </tr>
            <tr style="vertical-align: top;">
                <td>
                    <h6>Pengirim</h6>
                    <h6>Nama : Iqbal</h6>
                    <h6>No Telepon : 628xxxxxx99</h6>
                    <h6>Alamat Lengkap : Majalengka Jawa Barat 454547</h6>

                </td>
                <td>
                    <h6>Pengirim</h6>
                    <h6>Nama : Iqbal</h6>
                    <h6>No Telepon : 628xxxxxx99</h6>
                    <h6>Alamat Lengkap : Majalengka Jawa Barat 454547</h6>
                </td>
            </tr>
            <tr>
               
            </tr>
            <tr>
                <td><h5>BD010000</h5></td>
                <td><h5>PKU21011</h5></td>
            </tr>
            <tr>
                <td colspan="3">Catatan</td>
            </tr>
            <tr>
                <td colspan="3">Catatan Barang</td>
            </tr>
            <tr>
                <td colspan="3">Barang fashion baju dengan celana model spanish</td>
            </tr>
        </tbody>
    </table>    
</div>
<?php } ?>
</body>
</html>