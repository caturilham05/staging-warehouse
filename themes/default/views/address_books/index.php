<?php (defined('BASEPATH')) or exit('No direct script access allowed');?>

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
    <h3><i class="fa fa-book"></i> <?= $page_title; ?><a href="<?= site_url('address_books/add') ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?= lang('Add Address Books'); ?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <div class="table-responsive">
                <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                    <thead class="cf">
                        <tr>
                            <th class="col-xs-1"><?= lang('name'); ?></th>
                            <th class="col-xs-2"><?= lang('phone'); ?></th>
                            <th class="col-xs-2"><?= lang('address'); ?></th>
                            <th class="col-xs-1"><?= lang('created'); ?></th>
                            <th class="col-xs-2"><?= lang('updated'); ?></th>
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
                            <th class="col-xs-1"><?= lang('name'); ?></th>
                            <th class="col-xs-2">[<?= lang('phone'); ?>]</th>
                            <th class="col-xs-2">[<?= lang('address'); ?>]</th>
                            <th class="col-xs-1">[<?= lang('created'); ?>]</th>
                            <th class="col-xs-2"><?= lang('updated'); ?></th>
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
<script type="text/javascript">
    // const print_barcode = '<?= base_url('items/single_barcode/') ?>'
    // const print_label = '<?= base_url('items/single_label/') ?>'
    const delete_items = '<?= base_url('address_books/delete/') ?>'
    const edit_items = '<?= base_url('address_books/edit/') ?>'
    var detail_table;
    const formAdd = document.getElementById('formAdd');

    $(document).ready(function() {
      var table = $('#TTable').DataTable({
          "order": [
              [2, "desc"]
          ],

          "pageLength": <?= $Settings->rows_per_page; ?>,
          "processing": true,
          "serverSide": true,
          'ajax': {
              url: '<?= site_url('address_books/address_books_all'); ?>',
              type: 'POST',
              "data": function(d) {d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";}
          },
          "buttons": [],
          "columns": [
            {
            	"render": (data, type, row, meta) => { return `${row[2]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[3]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[4]}` + " - " + `${row[7]}`;}
            },
            {
              "render": (data, type, row, meta) => {return hrld(row[5]);}
            },
            {
              "render": (data, type, row, meta) => {return row[6] === '0000-00-00 00:00:00' ? 'data belum diupdate' : hrld(row[6]);}
            },
            {
                "render": (data, type, row, meta) => {
                    return `    
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
                    </div>`;
                }
            }
          ],
      });

      // $('#TTable').on('click', '.open-image', function(e) {
      //     e.preventDefault();
      //     var a_href = $(this).attr('href');
      //     var code = $(this).closest('tr').children('td:eq(1)').text();
      //     var name = $(this).closest('tr').children('td:eq(2)').text();
      //     $('#myModalLabel').text(name + ' (' + code + ')');
      //     $('#product_image').attr('src', a_href);
      //     $('#picModal').modal();
      //     return false;
      // });


      // $('#search_table').on('keyup change', function(e) {
      //     var code = (e.keyCode ? e.keyCode : e.which);
      //     if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
      //         table.search(this.value).draw();
      //     }
      // });

      // table.columns().every(function() {
      //     var self = this;
      //     $('input', this.footer()).on('keyup change', function(e) {
      //         var code = (e.keyCode ? e.keyCode : e.which);
      //         if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
      //             self.search(this.value).draw();
      //         }
      //     });
      // });
    });
    // function itemDetail(id) {
    //     var url = "<?= site_url('items/view_item/') ?>";
    //     $("#item_id").val(id);
    //     var detail_table = $('#detail_table').DataTable({
    //         "pageLength": <?= $Settings->rows_per_page; ?>,
    //         "processing": true,
    //         "serverSide": true,
    //         'ajax': {
    //             url: '<?= site_url('items/get_detail_item/'); ?>' + id,
    //             type: 'POST',
    //             "data": function(d) {
    //                 d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
    //             }
    //         },
    //         "columns": [{
    //                 "render": (data, type, row, meta) => {
    //                     return `    
    //                    WAREHOUSE ${row[1]}
    //                                              `;
    //                 }
    //             },
    //             {
    //                 "render": (data, type, row, meta) => {
    //                     return `    
    //                 ${row[2]}
    //                                              `;
    //                 }
    //             },

    //             {
    //                 "render": (data, type, row, meta) => {

    //                     return `    
    //                        ${row[3]}
    //                                                  `;
    //                 }
    //             },
    //             {
    //                 "render": (data, type, row, meta) => {
    //                     return `    
    //                        ${row[4]}
    //                                                  `;
    //                 }
    //             },
    //             {
    //                 "render": (data, type, row, meta) => {

    //                     return `    
    //                        ${row[5]}
    //                                                  `;
    //                 }
    //             },

    //         ],
    //         "buttons": [

    //         ],
    //     });

    //     $("#itemDetail").modal('show');
    // }
    // $("#itemDetail").on('hidden.bs.modal', function() {
    //     $('#detail_table').DataTable().destroy();
    // });

    // formAdd.addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     var formData = new FormData(this);
    //     formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash() ?>');

    //     fetch('<?= site_url('items/addItemRack'); ?>', {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.status) {
    //                 toastr.success(data.message, 'Information');
    //                 document.getElementById('id').value = '';
    //                 document.getElementById('rack_name').value = '';
    //                 document.getElementById('bin').value = '';
    //                 document.getElementById('location').value = '';


    //                 $('#detail_table').DataTable().ajax.reload();
    //             } else {
    //                 toastr.error(data.message, 'Information');
    //             }
    //         })
    //         .catch(error => {
    //             console.log(error);
    //         });
    // })

    // function deleteDetail(id) {
    //     let text = 'Are you sure want to delete this data?';
    //     if (confirm(text)) {
    //         fetch('<?= site_url('items/deleteItemRack/'); ?>' + id)
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.status) {
    //                     toastr.success(data.message, 'Information');
    //                     $('#detail_table').DataTable().ajax.reload();
    //                 } else {
    //                     toastr.error(data.message, 'Information');
    //                 }
    //             })
    //             .catch(error => {
    //                 toastr.error(error);
    //             });
    //     } else {
    //         return false;
    //     }

    // }

    // function editDetail(id) {
    //     fetch('<?= site_url('items/getItemsRackById/'); ?>' + id)
    //         .then(response => response.json())
    //         .then(res => {

    //             if (res.status) {

    //                 document.getElementById('id').value = res.data.id;
    //                 document.getElementById('item_id').value = res.data.item_id;
    //                 document.getElementById('warehouse_id').value = res.data.warehouse_id;
    //                 document.getElementById('location').value = res.data.location;
    //                 document.getElementById('rack_name').value = res.data.rack_name;
    //                 document.getElementById('bin').value = res.data.bin;

    //                 var select = document.getElementById('warehouse_id');
    //                 var options = select.options;
    //                 for (var i = 0; i < options.length; i++) {
    //                     if (options[i].value == res.data.warehouse_id) {
    //                         select.selectedIndex = i;
    //                         break;
    //                     }
    //                 }

    //                 document.querySelector('.btn-block').innerHTML = 'Update Rack';


    //             } else {
    //                 toastr.error(data.message, 'Information');
    //             }
    //         })
    //         .catch(error => {
    //             toastr.error(error);
    //         });
    // }
</script>