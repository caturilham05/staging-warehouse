<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<style type="text/css">
    .table.dataTable td {
        vertical-align: top;
    }

    .table td:first-child {
        padding: 3px;
    }



    .table td:last-child {
        padding: 6px;
    }

    .table td:first-child,
    .table td:nth-child(5),
    .table td:nth-child(6),
    .table td:nth-child(7),
    .table td:last-child {
        text-align: center;
    }

    .modal-header {
        padding: 9px 15px;
        border-bottom: 1px solid #eee;
        background-color: #0480be;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-topright: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD SALES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open("sales/addSales"); ?>
            <div class="modal-body">
                <div class="input">
                    <span class="input-group-text" style="font-weight: bold;" id="inputGroup-sizing-default">MASUKAN LINK API </span>
                </div>
                </br>

                <?= form_input('link', set_value('link'), 'class="form-control tip" id="link" required="required"'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?php echo form_submit('Save', lang('Save'), 'class="btn btn-theme03"'); ?>
            </div>
            <?= form_close(); ?>

        </div>
    </div>
</div>

<!-- Modal Filter WH -->
<div class="modal fade in" id="modalFilter" aria-labelledby="modalFilterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Warehouse</h5>

            </div>
            <div class="modal-body modal-body-filter">
                <div class="list-group">
                    <?php foreach ($warehouses as $warehouse) { ?>
                        <a href="#" id="filter_warehouse_<?= $warehouse->id ?>" onclick="set_filter(<?= $warehouse->id ?>)" data-id="<?= $warehouse->id ?>" data-value="<?= $warehouse->name ?>" class="list-group-item list-group-item-action" aria-current="true">
                            <?= $warehouse->name ?>
                        </a>
                    <?php } ?>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Apply</button> -->
            </div>
        </div>
    </div>
</div>


<div class="">
    <h3>
        <i class="fa fa-barcode"></i> <?= $page_title; ?>
        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i> <?= lang('Add Sales'); ?></a>
    </h3>
    <p><?= lang('list_results'); ?></p>
</div>

<div class="row">

    <div class="col-lg-12 my-0">


        <div class="content-panel">
            <div class="row d-flex justify-content-between">
                <div class="col-xs-3 d-flex">
                    <select name="filter" id="filter" class="form-control">
                        <option value="">Filter Status Packing</option>
                        <option value="not_sent">Belum Dikirim</option>
                        <option value="sent">Dikirim</option>
                    </select>

                </div>

                <div class="col-xs-3 d-flex">
                    <input type="text" class="form-control" placeholder="Filter Date" id="filter_date" onfocus="(this.type='date')" onblur="(this.type='text')">

                </div>
                <?php if ($_SESSION['group_id'] == 1) { ?>
                    <div class="col-xs-3">


                        <input type="text" class="form-control" placeholder="Filter Warehouse" id="filter_warehouse" onfocus="modalFilter()" onblur="(this.type='text')" data-value="">
                    </div>
                <?php } ?>
                <input type="hidden" class="form-control" placeholder="Filter Warehouse" id="filter_warehouse_selected" data-value="">
                <button onclick="reset()" type="button" class="btn btn-default" id="btn-reset">Reset All</button>
            </div>
            <br>
            <div class="table-responsive">
                <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                    <thead class="cf">
                        <tr>
                            <th class="col-xs-2"><?= lang('Order'); ?></th>
                            <th class="col-xs-3"><?= lang('Pengirim/Penerima'); ?></th>
                            <!-- <th class="col-xs-2"><?= lang('Paket'); ?></th>  -->
                            <th class="col-xs-1"><?= lang('Kurir'); ?></th>
                            <!-- <th class="col-xs-1"><?= lang('Total'); ?></th> -->
                            <th class="col-xs-1"><?= lang('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="col-xs-2"><?= lang('Order'); ?></th>
                            <th class="col-xs-3"><?= lang('Pengirim/Penerima'); ?></th>
                            <!-- <th class="col-xs-2"><?= lang('Paket'); ?></th> -->
                            <th class="col-xs-1"><?= lang('Kurir'); ?></th>
                            <!-- <th class="col-xs-1"><?= lang('Total'); ?></th> -->
                            <th class="col-xs-1"><?= lang('Action'); ?></th>
                        </tr>
                        <tr>
                            <td colspan="9" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="[<?= lang('type_hit_enter'); ?>]" style="width:100%;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
    const delete_url = '<?= base_url('sales/deleteSales/'); ?>';
    const status_packing_url = '<?= base_url('sales/update_status_packing/'); ?>';
    const url_continue = '<?= base_url('sales/sales_add_manually_view?invoice='); ?>';
    const detail = '<?= base_url('sales/sales_detail_view?invoice='); ?>';


    $(document).ready(function() {
        if ($('#filter_warehouse').hasClass("select2-hidden-accessible")) {
            $('#filter_warehouse').select2('destroy');
        }

        fetch('<?= base_url('sales/get_warehouses') ?>').then(res => res.json()).then(data => {
            for (i in data) {
                document.getElementById('filter_warehouse').appendChild(new Option(data[i].name, data[i].id))
            }
        }).catch(err => console.log(err))


        table = $('#TTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": <?= $Settings->rows_per_page; ?>,
            "processing": true,
            "serverSide": true,
            'ajax': {
                url: '<?= site_url('sales/get_sales'); ?>',
                type: 'POST',
                "data": function(d) {
                    d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
                    d.filter = $('#filter').val();
                    d.filter_date = $('#filter_date').val();
                    d.filter_warehouse = $('#filter_warehouse_selected').val();
                }
            },
            "buttons": [{
                    text: 'Excel',
                    action: function(e, dt, node, config) {
                        dt.button(0).text('Loading...');

                        window.location.href = '<?= site_url('sales/export_excel'); ?>';
                        setTimeout(function() {
                            dt.button(0).text('Excel');
                        }, 3000);

                    }
                }


            ],

            columns: [{
                    //order

                    "render": (data, type, row, meta) => {
                        return `         
                    </br>
                    <span style="font-size:16;font-weight:bold;text-align:left;">WAREHOUSE ${row[38]}</span>
                    <div style="background-color:#cce5f2; padding:8px; margin-left:4px;margin-right:4px;" class="text-left"> 
                    <p class="m-0 p-0 order_number"> 
                    <span style="font-size:16;font-weight:bold;">${row[1]}
                    </span>
                    </p>
                    <p class="m-0 p-0"> <h6 style="font-size:5;">Dibuat : ${row[8]}</h6></p>
                    <p class="m-0 p-0"> <h6 style="font-size:5;">Status Packing : ${row[37] === 'process' ? '<b>Process</b>' : (row[37] === null ? '<b>Belum Dikirim</b>' : '<b>Dikirim</b>')}</h6></p>
                    </div>
                         `;
                    }
                },
                {
                    //pengirim/penerima

                    "render": (data, type, row, meta) => {
                        return `         
                        <table >
                        <tr>
                        <td><i class="fa fa-map-marker fa-lg"  style="color:#0480BE;font-size:20px;" ></i></td>
                        <td>&nbsp;</td>
                        <td style="text-align:left;vertical-align:bottom">
                            <span style="font-size:14px;font-weight:bold;"> ${row[19]}</span>
                            <span style=font-size:12px;font-weight:bold;color:grey;margin:0px;">
                            (${row[20]})</span>
                        </td>
                        <tr>
                        <td></td>
                        <td>&nbsp;</td>
                        <td style="text-align : left;">
                            <span style="font-size:13px;">${row[21]}.</span>
                            </br>
                            <span style="font-size:12px;color:grey;">${row[22]}, ${row[23]}, ${row[24]}.</span>
                            
                        </td>
                        <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                        <tr>
                        <td><i class="fa fa-map-marker fa-lg"  style="color:#0480BE;font-size:20px;"></i></td>
                        <td>&nbsp;</td>
                        <td style="text-align:left;vertical-align:bottom">
                            <span style="font-size:14px;font-weight:bold;"> ${row[25]}</span>
                            <span style=font-size:12px;font-weight:bold;color:grey;margin:0px;">
                            (${row[26]})</span>
                        </td>
                        <tr>
                        <td></td>
                        <td>&nbsp;</td>
                        <td style="text-align : left;">
                            <span style="font-size:13px;">${row[27]}.</span>
                            </br>
                            <span style="font-size:12px;color:grey;">${row[28]}, ${row[29]}, ${row[30]}.</span>
                            
                        </td>
                        </table>
                         `;
                    }
                },
                // {
                //     //paket


                //     "render": (data, type, row, meta) => {
                //         let package_price = row[12] === null ? 0 : row[12];
                //         return `         
                //     <p class="order_number" style="font-size:14px;font-weight:bold;">${capitalizeFirstLetter(row[31])}
                //     </p>
                //     <p style="font-size:13px;">Berat (kg) : ${row[33]}</p>
                //     <p style="font-size:13px;">Harga Paket : Rp${
                //         package_price.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")
                //     }</p>
                   
                //          `;
                //     }
                // },
                {
                    //kurir
                    "data": "awb_no",
                    "name": "awb_no",
                    "render": (data, type, row, meta) => {
                        return `         
                    <p class="m-0 p-0"  style="font-size:16; font-weight:bold;">${row[4]} ${row[6]}</p>
                    <div style="background-color:#cce5f2; padding:8px;">
                    <p class="m-0 p-0"> <h6 style="font-size:14;">No AWB:</h6></p>
                    <p class="m-0 p-0 order_number"> 
                    <span style="font-size:16;font-weight:bold;;">${row[2]} 
                    </span> 
                    </p>
                    </div>
                         `;
                    }
                },
                // {

                //     "render": (data, type, row, meta) => {
                //         let cod_value = row[16] === null ? 0 : row[16];
                //         let package_price = row[12] === null ? 0 : row[12];
                //         return `
                        
                        
                //     <!-- jika cod_value != nol, maka cod_value dipake , otherwise package_price dipake -->
                        
                //         <p style="font-size:14px;font-weight:bold;"> ${cod_value != 0 ? 
                //             //indonesia rupiah
                //             'Rp' + cod_value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") :
                //             'Rp' + package_price.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")} </p>
                //          `;
                //     }
                // },
                {
                    "render": function(data, type, row, meta) {
                        let invoice = row[1];
                        return `
                            <div style="display: flex; flex-direction: columns; justify-content: center;">
                                <div class='btn-group' role='group'>
                                    <a href="${detail + encodeURIComponent(invoice)}" class="btn btn-info">Detail</a>
                                </div>&nbsp;
                                ${
                                    row[37] === 'process' ? `
                                    <div class='btn-group' role='group'>
                                        <a href="${url_continue + encodeURIComponent(invoice)}" class="btn btn-primary">Continue</a>
                                    </div>&nbsp;`
                                    :
                                    ''
                                }
                                <div class='btn-group' role='group'>
                                    <a href='#' class='btn btn-danger  tip po'
                                        data-content="<p>Are you sure?</p><a class='btn btn-danger po-delete' 
                                        href='${delete_url}${row[0]}'>
                                        I'm Sure</a>
                                        <button class='btn po-close'>No</button>" rel='popover'>
                                        <span>Delete</span>
                                    </a>
                                </div>
                            </div>
                            `;
                    }
                }
            ],
            // 'fnRowCallback': function(nRow, aData) {
            //     nRow.id = aData.id;
            //     nRow.className = "item_link";
            //     return nRow;
            // }
        });

        $('#filter').on('change', function() {
            table.draw();
        });
        $('#filter_date').on('change', function() {
            table.draw();
        });

        $('#filter_warehouse').on('change', function() {
            table.draw();
        });

        $('#search_table').on('keyup change', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);

            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                console.log(this.value);
                console.log(table.search(this.value).draw());
                table.search(this.value).draw();
            }
        });

        table.columns().every(function() {
            var self = this;
            $('input', this.footer()).on('keyup change', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
                    self.search(this.value).draw();
                }
            });
        });

        $('#search_awb').on(
            'keyup',
            function(e) {
                if (e.keyCode == 13) {
                    fetch('<?= base_url('sales/getSalesbyCode/'); ?>' + e.target.value)
                        .then(response => response.json())
                        .then(data => {
                            if (data.icon == 'success') {
                                toastr.success(data.message);
                                table.ajax.reload();
                            } else if (data.icon == 'error') {
                                toastr.error(data.message);
                            } else {
                                toastr.warning(data.message);
                            }
                            $("#search_awb").val('');
                        })
                        .catch(error => {
                            toastr.error(error);
                            $("#search_awb").val('');
                        });
                }


            }

        )

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }



    });

    function modalFilter() {
        $('#modalFilter').modal('show');
        $("#filter_warehouse").attr("readonly", true);
    }

    function set_filter(id) {

        $('.list-group-item').click(function(e) {
            e.preventDefault();

            $('.list-group-item').removeClass('active');
            $(this).addClass('active');

            const data_value = $(`#filter_warehouse_${id}`).data('value')
            const filter_warehouse = $('#filter_warehouse')
            filter_warehouse.val(data_value)

            $('#filter_warehouse_selected').val(id);
            table.draw();
            $('#modalFilter').modal('hide');

        });


    }

    function reset() {
        $('.list-group-item').removeClass('active');
        $('#filter_date').val('');
        $('#filter_warehouse').val('');
        $('#filter').val('');
        $('#search_table').val('');
        $("#filter_warehouse").attr("readonly", false);
        $('#filter_warehouse_selected').val('');
        table.draw();

    }
</script>