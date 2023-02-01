
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="TTable" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                            <thead>
                            <tr>
                                <th><?= lang('id'); ?></th>
                                <th><?= lang('code'); ?></th>
                                <th><?= lang('name'); ?></th>
                                <th style="width:100px;"><?= lang('actions'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#TTable').DataTable({

            "order": [[ 1, "desc" ]],
            "pageLength": <?=$Settings->rows_per_page;?>,
            "processing": true, "serverSide": true,
            'ajax' : { url: '<?=site_url('settings/get_categories');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', exportOptions: { columns: [ 0, 1, 2 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2 ] } },
            { extend: 'pdfHtml5', pageSize: 'A4', 'footer': false, 
            exportOptions: { columns: [ 0, 1, 2 ] },
            customize: function (doc) {
                doc.content[1].table.widths = 
                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id", "visible": false },
            { "data": "code" },
            { "data": "name" },
            { "data": "Actions", "searchable": false, "orderable": false }
            ]
        });
    });
</script>