<script>
    $(document).ready(function () {

        var table = $('#CuData').DataTable({

            "order": [[ 1, "asc" ]],
            "pageLength": <?=$Settings->rows_per_page;?>,
            "processing": true, "serverSide": true,
            'ajax' : { url: '<?=site_url('customers/get_customers');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', exportOptions: { columns: [ 0, 1, 2, 3, 4 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true, 
            exportOptions: { columns: [ 0, 1, 2, 3, 4 ] },
            customize: function (doc) {
                doc.content[1].table.widths = 
                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id", "visible": false },
            { "data": "name" },
            { "data": "phone" },
            { "data": "email" },
            { "data": "cf1" },
            { "data": "cf2" },
            { "data": "Actions", "searchable": false, "orderable": false<?= $Admin ? '' : ', "visible": false'; ?> }
            ]
        });

        $('#CuData tfoot th:not(:last)').each(function () {
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

<div class="">
    <h3><i class="fa fa-users"></i> <?=$page_title;?><a href="<?=site_url('customers/add')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?=lang('add_customer');?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="content-panel">
                <div class="table-responsive">
                    <table id="CuData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?= lang("id"); ?></th>
                            <th class="col-xs-3"><?= lang("name"); ?></th>
                            <th class="col-xs-2"><?= lang("phone"); ?></th>
                            <th class="col-xs-2"><?= lang("email_address"); ?></th>
                            <th class="col-xs-2"><?= lang("ccf1"); ?></th>
                            <th class="col-xs-2"><?= lang("ccf2"); ?></th>
                            <th style="width:65px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input class="form-control full-width" placeholder="[<?=lang('id');?>]" type="text"></th>
                                <th class="col-xs-3">[<?= lang("name"); ?>]</th>
                                <th class="col-xs-2">[<?= lang("phone"); ?>]</th>
                                <th class="col-xs-2">[<?= lang("email_address"); ?>]</th>
                                <th class="col-xs-2">[<?= lang("ccf1"); ?>]</th>
                                <th class="col-xs-2">[<?= lang("ccf2"); ?>]</th>
                                <th style="width:65px;"><?= lang("actions"); ?></th>
                            </tr>
                            <tr>
                                <td colspan="7" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="[<?= lang('type_hit_enter'); ?>]" style="width:100%;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
