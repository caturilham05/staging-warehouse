<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

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
    <h3><?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <div class="table-responsive">
                <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                    <thead class="cf">
                        <tr>
                            <th class="col-xs-1"><?= lang('Id'); ?></th>
                            <th class="col-xs-2"><?= lang('Stock Opname'); ?></th>
                            <th class="col-xs-2"><?= lang('Warehouse Name'); ?></th>
                            <th class="col-xs-1"><?= lang('Qty Total'); ?></th>
                            <th class="col-xs-2"><?= lang('Created'); ?></th>
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
                            <th class="col-xs-1"><?= lang('Id'); ?></th>
                            <th class="col-xs-2"><?= lang('Stock Opname'); ?></th>
                            <th class="col-xs-2"><?= lang('Warehouse Name'); ?></th>
                            <th class="col-xs-1"><?= lang('Qty Total'); ?></th>
                            <th class="col-xs-2"><?= lang('Created'); ?></th>
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
              url: '<?= site_url('stockopname/stock_opname_json'); ?>',
              type: 'POST',
              "data": function(d) {d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>"; console.log(d)}
          },
          "buttons": [],
          "columns": [
            {
            	"render": (data, type, row, meta) => { return `${row[0]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[1]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[5]}`;}
            },
            {
              "render": (data, type, row, meta) => {return row[3];}
            },
            {
              "render": (data, type, row, meta) => {return row[4] === '0000-00-00 00:00:00' ? 'data belum diupdate' : hrld(row[4]);}
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
    });
</script>