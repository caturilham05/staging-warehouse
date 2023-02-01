<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .table td:first-child { padding: 3px; }
    .table td:last-child { padding: 6px; }
    .table td:first-child, .table td:nth-child(5), .table td:nth-child(6), .table td:nth-child(7), .table td:last-child { text-align: center; }
</style>
<div class="">
    <h3><i class="fa fa-barcode"></i> <?=$page_title;?></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="content-panel">
                <div class="table-responsive">
                    <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                        <thead class="cf">
                            <tr>
                                <th><?=lang('id');?></th>
                                <th class="col-xs-1"><?=lang('image');?></th>
                                <th class="col-xs-1"><?=lang('code');?></th>
                                <th class="col-xs-3"><?=lang('name');?></th>
                                <th class="col-xs-2"><?=lang('category');?></th>
                                <th class="col-xs-1"><?=lang('quantity');?></th>
                                <th class="col-xs-1"><?=lang('unit');?></th>
                                <th class="col-xs-1"><?=lang('alert_on');?></th>
                                <th class="col-xs-2"><?=lang('actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input class="form-control full-width" placeholder="[<?=lang('id');?>]" type="text"></th>
                                <th class="col-xs-1"><?=lang('image');?></th>
                                <th class="col-xs-1">[<?=lang('code');?>]</th>
                                <th class="col-xs-3">[<?=lang('name');?>]</th>
                                <th class="col-xs-2">[<?=lang('category');?>]</th>
                                <th class="col-xs-1">[<?=lang('quantity');?>]</th>
                                <th class="col-xs-1">[<?=lang('unit');?>]</th>
                                <th class="col-xs-1">[<?=lang('alert_on');?>]</th>
                                <th class="col-xs-2"><?=lang('actions');?></th>
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
</div>
<div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-labelledby="picModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel">title</h4>
            </div>
            <div class="modal-body text-center">
                <img id="product_image" src="" alt="" class="img-responsive" style="display: inline-block;"/>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        function image(n) {
            if(n !== null && n != '') {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }

        var table = $('#TTable').DataTable({

            "order": [[ 1, "desc" ]],
            "pageLength": <?=$Settings->rows_per_page;?>,
            "processing": true, "serverSide": true,
            'ajax' : { url: '<?=site_url('items/get_items/alerts');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true, 
            exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] },
            customize: function (doc) {
                doc.content[1].table.widths = 
                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id", "visible": false },
            { "data": "image", "render": image },
            { "data": "code" },
            { "data": "name" },
            { "data": "cname" },
            { "data": "quantity" },
            { "data": "unit" },
            { "data": "alert_quantity" },
            { "data": "Actions", "searchable": false, "orderable": false }
            ],
            'fnRowCallback': function (nRow, aData) {
                nRow.id = aData.id; nRow.className = "item_link";
                return nRow;
            }
        });

        $('#TTable').on('click', '.open-image', function(e) {
            e.preventDefault();
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').children('td:eq(1)').text();
            var name = $(this).closest('tr').children('td:eq(2)').text();
            $('#myModalLabel').text(name+' ('+code+')');
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
        $('#TTable tfoot th:not(:first, :last)').each(function () {
            var title = $(this).text();
            $(this).html( '<input type="text" class="form-control full-width" placeholder="'+title+'" />' );
        });

        $('#search_table').on( 'keyup change', function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                table.search( this.value ).draw();
            }
        });

        table.columns().every(function () {
            var self = this;
            $( 'input', this.footer() ).on( 'keyup change', function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
                    self.search( this.value ).draw();
                }
            });
        });
    });
</script>
