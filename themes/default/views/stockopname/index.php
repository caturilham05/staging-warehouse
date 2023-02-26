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
                            <?php
                                if (!empty($Admin))
                                {
                                    ?>
                                        <th class="col-xs-1"><?= lang('Qty Real'); ?></th>
                                    <?php
                                }
                            ?>
                            <th class="col-xs-1"><?= lang('Notes'); ?></th>
                            <th class="col-xs-1"><?= lang('Status'); ?></th>
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
                            <?php
                                if (!empty($Admin))
                                {
                                    ?>
                                        <th class="col-xs-1"><?= lang('Qty Real'); ?></th>
                                    <?php
                                }
                            ?>
                            <th class="col-xs-1"><?= lang('Notes'); ?></th>
                            <th class="col-xs-1"><?= lang('Status'); ?></th>
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
    const inbond = '<?= base_url('check_in/add?stock_opname_id=') ?>'
    const outbond = '<?= base_url('checkout/add') ?>'
    var detail_table;
    const formAdd = document.getElementById('formAdd');
    const detail = '<?= base_url('stockopname/stockopname_detail/'); ?>';
    const setAdmin = '<?= $Admin?>'

    $(document).ready(function() {
        if (setAdmin)
        {        
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
                  "render": (data, type, row, meta) => {return `${row[7]}`;}
                },
                {
                  "render": (data, type, row, meta) => {return row[3];}
                },
                {
                  "render":(data, type, row, meta) => {return row[4];}
                },
                {
                  "render": (data, type, row, meta) => {return row[5];}
                },
                {
                  "render": (data, type, row, meta) => {
                                                            let status = parseInt(row[8])
                                                            return status == 1 ? 'Process' : (status == 2 ? 'Selesai' : (status == 3 ? 'Batal' : (status == 4 ? 'Selesai Outbond' : (status == 5 ? 'Selesai Inbond' : ''))))
                                                        }
                },
                {
                  "render": (data, type, row, meta) => {return row[6] === '0000-00-00 00:00:00' ? 'data belum diupdate' : hrld(row[6]);}
                },
                {
                    "render": (data, type, row, meta) => {
                        let qty           = parseInt(row[3]);
                        let qtyReal       = parseInt(row[4]);
                        let status        = parseInt(row[8]);
                        let outbondInbond = '';

                        if (status == 2)
                        {
                            outbondInbond = qty < qtyReal ? `
                                        <div class='btn-group' role='group'>
                                            <a href="${outbond}${row[0]}" class="btn btn-primary">New Outbond</a>
                                        </div>` : (qty > qtyReal ? `
                                        <div class='btn-group' role='group'>
                                            <a href="${inbond}${row[0]}" class="btn btn-primary">New Inbond</a>
                                        </div>` : null)

                        }

                        return `    
                            <div style="display: flex; flex-direction: columns; justify-content: center;">
                                <div class='btn-group' role='group'>
                                    <a href="${detail}${row[0]}" class="btn btn-info">Detail</a>
                                </div>&nbsp;
                                ${outbondInbond}
                            </div>
                        `;
                    }
                }
            ],
          });
        }
        else
        {
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
                  "render": (data, type, row, meta) => {return `${row[7]}`;}
                },
                {
                  "render": (data, type, row, meta) => {return row[3];}
                },
                {
                  "render": (data, type, row, meta) => {return row[5];}
                },
                {
                  "render": (data, type, row, meta) => {
                                                            let status = parseInt(row[8])
                                                            return status == 1 ? 'Process' : (status == 2 ? 'Selesai' : (status == 3 ? 'Batal' : (status == 4 ? 'Selesai Outbond' : (status == 5 ? 'Selesai Inbond' : ''))))
                                                        }
                },
                {
                  "render": (data, type, row, meta) => {return row[6] === '0000-00-00 00:00:00' ? 'data belum diupdate' : hrld(row[6]);}
                },
                {
                    "render": (data, type, row, meta) => {
                        let qty           = parseInt(row[3]);
                        let qtyReal       = parseInt(row[4]);
                        let status        = parseInt(row[8]);
                        let outbondInbond = '';

                        if (status == 2)
                        {
                            outbondInbond = qty < qtyReal ? `
                                        <div class='btn-group' role='group'>
                                            <a href="${outbond}${row[0]}" class="btn btn-primary">New Outbond</a>
                                        </div>` : (qty > qtyReal ? `
                                        <div class='btn-group' role='group'>
                                            <a href="${inbond}${row[0]}" class="btn btn-primary">New Inbond</a>
                                        </div>` : null)

                        }

                        return `    
                            <div style="display: flex; flex-direction: columns; justify-content: center;">
                                <div class='btn-group' role='group'>
                                    <a href="${detail}${row[0]}" class="btn btn-info">Detail</a>
                                </div>&nbsp;
                                ${outbondInbond}
                            </div>
                        `;
                    }
                }
            ],
          });

        }
    });
</script>
