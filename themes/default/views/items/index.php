<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<style type="text/css">
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
</style>
<div class="">
    <h3><i class="fa fa-barcode"></i> <?= $page_title; ?><a href="<?= site_url('items/add') ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?= lang('add_item'); ?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>

<div class="row">
    <div class="col-lg-12">

        <div class="content-panel">
            <div class="table-responsive">
                <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                    <thead class="cf">
                        <tr>
                            <!-- <th><?= lang('id'); ?></th> -->
                            <th class="col-xs-1"><?= lang('image'); ?></th>
                            <th class="col-xs-2"><?= lang('code'); ?></th>
                            <th class="col-xs-2"><?= lang('name'); ?></th>
                            <th class="col-xs-1"><?= lang('category'); ?></th>
                            <!-- <th class="col-xs-1"><?= lang('warehouse'); ?></th> -->
                            <th class="col-xs-2"><?= lang('quantity'); ?></th>
                            <th class="col-xs-1"><?= lang('unit'); ?></th>
                            <th class="col-xs-1"><?= lang('alert_on'); ?></th>
                            <th class="col-xs-2"><?= lang('actions'); ?></th>



                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <!-- <th><input class="form-control full-width" placeholder="[<?= lang('id'); ?>]" type="text"></th> -->
                            <th class="col-xs-1"><?= lang('image'); ?></th>
                            <th class="col-xs-2">[<?= lang('code'); ?>]</th>
                            <th class="col-xs-2">[<?= lang('name'); ?>]</th>
                            <th class="col-xs-1">[<?= lang('category'); ?>]</th>
                            <!-- <th class="col-xs-1">[<?= lang('Warehouse'); ?>]</th> -->
                            <th class="col-xs-2">[<?= lang('quantity'); ?>]</th>
                            <th class="col-xs-1">[<?= lang('unit'); ?>]</th>
                            <th class="col-xs-1">[<?= lang('alert_on'); ?>]</th>
                            <th class="col-xs-2"><?= lang('actions'); ?></th>


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

<div class="modal fade" id="picModal" role="dialog" aria-labelledby="picModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel">title</h4>
            </div>
            <div class="modal-body text-center">
                <img id="product_image" src="" alt="" class="img-responsive" style="display: inline-block;" />
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="itemDetail" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">DETAIL ITEM</h5>

            </div>
            <div class="modal-body">
                <form id="formAdd">
                    <div class="col-md-12">
                        <div class="row">
                            <input type="hidden" id="id" name="id" placeholder="id">
                            <input type="hidden" id="item_id" name="item_id" placeholder="item_id">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('Warehouse', 'Warehouse'); ?>
                                    <?php
                                    $sp[''] = lang('Select Warehouse');
                                    foreach ($warehouses as $warehouse) {
                                        $sp[$warehouse->id] = $warehouse->name;
                                    }
                                    ?>
                                    <?php if ($this->session->userdata('warehouse_id') == null) { ?>
                                        <?= form_dropdown('warehouse_id', $sp, set_value('warehouse_id'), 'class="form-control tip" id="warehouse_id" required="required"'); ?>
                                    <?php } else { ?>
                                        <?= form_dropdown('warehouse_id', $sp, set_value('warehouse_id', $this->session->userdata('warehouse_id')), 'class="form-control tip" id="warehouse_id" disabled="disabled" '); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('Location', 'Location'); ?>
                                    <?= form_input('location', set_value('location'), 'class="form-control tip" id="location"  required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('Rack Name', 'Rack Name'); ?>
                                    <?= form_input('rack_name', set_value('rack_name'), 'class="form-control tip" id="rack_name"  required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('BIN', 'BIN'); ?>
                                    <?= form_input('bin', set_value('bin'), 'class="form-control tip" id="bin"  required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p><button type="submit" class="btn btn-theme03 btn-block">Add Rack</button></p>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <div class="table-responsive">
                    <table id="detail_table" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                        <thead class="cf">
                            <tr>
                                <th align="center">Warehouse</th>
                                <th align="center">Location</th>
                                <th align="center">Rack</th>
                                <th align="center">BIN</th>
                                <th align="center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="item_detail_table">

                        </tbody>
                        <tfoot class="cf">
                            <tr>
                                <th align="center">Warehouse</th>
                                <th align="center">Location</th>
                                <th align="center">Rack</th>
                                <th align="center">BIN</th>
                                <th align="center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    const print_barcode = '<?= base_url('items/single_barcode/') ?>'
    const print_label = '<?= base_url('items/single_label/') ?>'
    const delete_items = '<?= base_url('items/delete/') ?>'
    const edit_items = '<?= base_url('items/edit/') ?>'
    var detail_table;
    const formAdd = document.getElementById('formAdd');

    $(document).ready(function() {


        function image(n) {
            if (n !== null && n != '') {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?= base_url(); ?>uploads/' + n + '" class="open-image"><img src="<?= base_url(); ?>uploads/' + n + '" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }

        var table = $('#TTable').DataTable({
            "order": [
                [2, "desc"]
            ],

            "pageLength": <?= $Settings->rows_per_page; ?>,
            "processing": true,
            "serverSide": true,
            'ajax': {
                url: '<?= site_url('items/get_items'); ?>',
                type: 'POST',
                "data": function(d) {
                    d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
                }
            },
            "buttons": [


            ],
            "columns": [

                {
                    "render": (data, type, row, meta) => {
                        return `    
                        <div style="width:32px; margin: 0 auto;"><a href="<?= base_url(); ?>uploads/${row[1]}" class="open-image"><img src="<?= base_url(); ?>uploads/${row[1]}" alt="" class="img-responsive"></a></div>
                        `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {
                        return `    
                       ${row[2]}
                                                 `;
                    }
                }, {
                    "render": (data, type, row, meta) => {
                        return `    
                      <a href="#" onclick="itemDetail(${row[0]})">${row[3]}</a>
                                                 `;
                    }
                }, {
                    "render": (data, type, row, meta) => {
                        return `    
                       ${row[4]}
                                                 `;
                    }
                }, {
                    "render": (data, type, row, meta) => {
                        return `    
                        <table>
                        <tr>
                        <td  style="text-align:left;vertical-align:bottom">
                        <span>${row[7] != null ? row[7] : 'Belum Ada Quantity'}</span>
                        </td>
                        </tr>
                        </table>
                       
                                                 `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {
                        return `    
                       ${row[5]}
                                                 `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {
                        return `    
                       ${row[6]}
                                                 `;
                    }
                },

                {
                    "render": (data, type, row, meta) => {
                        return `    
                        <div class="btn-group btn-group-toolbar" role="group">
                        
                        <!-- print barcode -->

                        <div class='btn-group' role='group'>
                            <a onclick="
                            window.open('${print_barcode}${row[0]}', 
                            'pos_popup', 
                            'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0');
                            return false;" 
                            href='#'
                            title="Print Barcode"
                            class='tip btn btn-default btn-sm'>
                            <i class='fa fa-print'></i>
                            </a>
                        </div> 


                        <!-- print label -->

                        <div class='btn-group' role='group'>
                            <a onclick="
                            window.open('${print_label}${row[0]}', 
                            'pos_popup', 
                            'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0');
                            return false;" 
                            href='#'
                            title="Print Label"
                            class='tip btn btn-default btn-sm'>
                            <i class='fa fa-print'></i>
                            </a>
                        </div> 

                        

                        <!-- edit button -->

                        <div class='btn-group' role='group'>
                            <a class='tip btn btn-warning btn-sm' 
                                title="Edit Item"
                                href='${edit_items}${row[0]}'>
                                <i class='fa fa-edit'></i>
                            </a>
                        </div>



                        <!-- delete button -->

                        <div class='btn-group div-confirm' role='group'>
                            <a href='#' class='btn btn-danger  btn-sm tip po btn-delete'
                                title="Delete Item"
                                data-content="<p>Are you sure?</p><a class='btn btn-danger po-delete' 
                                href='${delete_items}${row[0]}'>
                                I'm Sure</a>
                                <button class='btn po-close'>No</button>" rel='popover'>
                                <i class='fa fa-trash-o'></i>
                            </a>
                        </div>

                        </div>
                                                 `;
                    }
                },
            ],
        });

        $('#TTable').on('click', '.open-image', function(e) {
            e.preventDefault();
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').children('td:eq(1)').text();
            var name = $(this).closest('tr').children('td:eq(2)').text();
            $('#myModalLabel').text(name + ' (' + code + ')');
            $('#product_image').attr('src', a_href);
            $('#picModal').modal();
            return false;
        });


        $('#search_table').on('keyup change', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
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


    });



    function itemDetail(id) {
        var url = "<?= site_url('items/view_item/') ?>";
        $("#item_id").val(id);
        var detail_table = $('#detail_table').DataTable({
            "pageLength": <?= $Settings->rows_per_page; ?>,
            "processing": true,
            "serverSide": true,
            'ajax': {
                url: '<?= site_url('items/get_detail_item/'); ?>' + id,
                type: 'POST',
                "data": function(d) {
                    d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
                }
            },
            "columns": [{
                    "render": (data, type, row, meta) => {
                        return `    
                       WAREHOUSE ${row[1]}
                                                 `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {
                        return `    
                    ${row[2]}
                                                 `;
                    }
                },

                {
                    "render": (data, type, row, meta) => {

                        return `    
                           ${row[3]}
                                                     `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {
                        return `    
                           ${row[4]}
                                                     `;
                    }
                },
                {
                    "render": (data, type, row, meta) => {

                        return `    
                           ${row[5]}
                                                     `;
                    }
                },

            ],
            "buttons": [

            ],
        });

        $("#itemDetail").modal('show');
    }
    $("#itemDetail").on('hidden.bs.modal', function() {
        $('#detail_table').DataTable().destroy();
    });

    formAdd.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash() ?>');

        fetch('<?= site_url('items/addItemRack'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    toastr.success(data.message, 'Information');
                    document.getElementById('id').value = '';
                    document.getElementById('rack_name').value = '';
                    document.getElementById('bin').value = '';
                    document.getElementById('location').value = '';


                    $('#detail_table').DataTable().ajax.reload();
                } else {
                    toastr.error(data.message, 'Information');
                }
            })
            .catch(error => {
                console.log(error);
            });
    })

    function deleteDetail(id) {
        let text = 'Are you sure want to delete this data?';
        if (confirm(text)) {
            fetch('<?= site_url('items/deleteItemRack/'); ?>' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        toastr.success(data.message, 'Information');
                        $('#detail_table').DataTable().ajax.reload();
                    } else {
                        toastr.error(data.message, 'Information');
                    }
                })
                .catch(error => {
                    toastr.error(error);
                });
        } else {
            return false;
        }

    }

    function editDetail(id) {
        fetch('<?= site_url('items/getItemsRackById/'); ?>' + id)
            .then(response => response.json())
            .then(res => {

                if (res.status) {

                    document.getElementById('id').value = res.data.id;
                    document.getElementById('item_id').value = res.data.item_id;
                    document.getElementById('warehouse_id').value = res.data.warehouse_id;
                    document.getElementById('location').value = res.data.location;
                    document.getElementById('rack_name').value = res.data.rack_name;
                    document.getElementById('bin').value = res.data.bin;

                    var select = document.getElementById('warehouse_id');
                    var options = select.options;
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == res.data.warehouse_id) {
                            select.selectedIndex = i;
                            break;
                        }
                    }

                    document.querySelector('.btn-block').innerHTML = 'Update Rack';


                } else {
                    toastr.error(data.message, 'Information');
                }
            })
            .catch(error => {
                toastr.error(error);
            });
    }
</script>