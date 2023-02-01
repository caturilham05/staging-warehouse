<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan</title>
</head>

<style>
    .center {
        margin: 0 auto;
        width: 70%;
        height: 100%;
        /* border: 1px black solid; */
        /* background-color: aqua; */
        padding: 0px;
    }

    .label{
        font-size: 16PX;
        color: black;
        margin: 0px;
        padding: 0px;
        line-height: 150%;
    }

    .label-long-text{
        font-size: 16PX;
        color: black;
        margin: 0px;
        padding: 0px;
        line-height: 150%;
        text-align: justify;
    }

    .label-title{
        font-size: 40PX;
        color: black;
        margin: 0px;
        padding: 0px;
        line-height: 150%;
    }

    .tabel-utama {
        width: 100%; 
        font-family:'Times New Roman', Times, serif;
        font-weight:500; 
        border: 1px;
    }

    .tabel-utama  td , tr{
        vertical-align: top;
        font-weight: 500;
        
        /* border: 1px solid; */
    }

    .tabel-anak {
        width: 100%; 
        font-family:'Times New Roman', Times, serif;
        font-weight:500; 
        border: 1px;
        border-collapse: collapse;

    }
    
    .tabel-anak td, tr {
        vertical-align: top;
        font-weight: 500;
        border: 1px solid;
        margin: 0px;
        padding: 8px;
    }

    hr {
    display: block;
    height: 4px;
    background: transparent;
    width: 100%;
    border: none;
    margin-bottom: 10px;
    border-top: solid 1px #aaa;
}
    




</style>

<body onload="window.print()">
    <!-- courier, note, date, sama list awb -->
    <div class="center">
        <!-- main table  -->
        <table class="tabel-utama">

            <!-- header section -->
            <tr style="text-align: center;">
                <td colspan="3"><div class="label-title">SURAT JALAN</div></td>
            </tr>
            <!-- end header section -->

            <tr>
                <td colspan="3" style="height: 16px;">
                    <hr>
                </td>
            </tr>

            <!-- courier and date section -->
            <tr>
                <td style="width: 10%;"><div class="label">Kurir</div></td>
                <td style="width: 1%;"><div class="label">:</div></td>
                <td ><div class="label"><?= $letter['travel_doc']->courier;?></div></td>
            </tr>
            <tr>
                <td><div class="label">Tanggal</div></td>
                <td style="width: 1%;"><div class="label">:</div></td>
                <td><div class="label"><?= $letter['travel_doc']->created_at; ?></div></td>
            </tr>
            <!--end courier and date section -->

            <tr>
                <td colspan="3" style="height: 16px;"></td>
            </tr>

            <!-- list awb section -->
            <tr>
                <td colspan="3">
                    <table class="tabel-anak">
                        <tr style="text-align: center;">
                            <td align="center"><div class="label">No</div></td>
                            <td><div class="label">AWB</div></td>
                        </tr>
                        <?php 
                        
                       
                        foreach($letter['detail_doc'] as $key=> $val)  { ?>
                      
                        <tr>
                            <td><div class="label"><?=$key + 1?></div></td>
                            <td><div class="label"><?=$val->awb?></div></td>
                        </tr>
                       <?php } ?>
                    </table>
                </td>
            </tr>
            <!-- end list awb section -->

            <tr>
                <td colspan="3" style="height: 16px;"></td>
            </tr>

            <!-- note section -->
            <tr>
                <td style="width: 25%"><div class="label">Catatan</div></td>
                <td style="width: 1%"><div class="label">:</div></td>
                <td style="width: 70%;"><div class="label-long-text"> 
                    <?=$letter['travel_doc']->note?>
                </div></td>
            </tr>
            <!-- end note section -->

        </table>
    </div>
    
</body>
</html>