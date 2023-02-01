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
    </div>
</form>
<script>
    const print_barcode = '<?= base_url('items/single_barcode/') ?>'
    const print_label = '<?= base_url('items/single_label/') ?>'
    const delete_items = '<?= base_url('items/delete/') ?>'
    const edit_items = '<?= base_url('items/edit/') ?>'
    var detail_table;
    const formAdd = document.getElementById('formAdd');

    $(document).ready(function() {
        detail_table = $('#detail_table').DataTable({
            "pageLength": <?= $Settings->rows_per_page; ?>,
            "processing": true,
            "serverSide": true,
            'ajax': {
                url: '<?= site_url('items/get_detail_item/'); ?>' + <?= $id; ?>,
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
            "buttons": [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    'footer': true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'csvHtml5',
                    'footer': true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    'footer': true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    customize: function(doc) {
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },

            ],
        });

    });

    function itemDetail(id) {
        $("#item_id").val(id);

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
                console.log(data)
            })
            .catch(error => {
                console.error(error);
            });
    })

    function deleteDetail(id) {
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
    }

    function editDetail(id) {
        fetch('<?= site_url('items/getItemsRackById/'); ?>' + id)
            .then(response => response.json())
            .then(res => {

                if (res.status) {
                    console.log(res.data)
                    document.getElementById('id').value = res.data.id;
                    document.getElementById('item_id').value = res.data.item_id;
                    document.getElementById('warehouse_id').value = res.data.warehouse_id;
                    document.getElementById('location').value = res.data.location;
                    document.getElementById('rack_name').value = res.data.rack_name;
                    document.getElementById('bin').value = res.data.bin;

                    var select = document.getElementById('warehouse_id');
                    var text = select.text;
                    var value = select.value;
                    var options = select.options;
                    //set select2 text
                    

                } else {
                    toastr.error(data.message, 'Information');
                }
            })
            .catch(error => {
                toastr.error(error);
            });
    }
</script>