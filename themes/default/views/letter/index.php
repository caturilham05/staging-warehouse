<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

<div class="">
    <h3>
        <i class="fa fa-barcode"></i> <?= $page_title; ?>
        <a href="<?= site_url('letter/travelAdd') ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?= lang('Add Doc'); ?></a>
    </h3>
    <p><?= lang('list_results'); ?></p>
</div>

    <div class="row d-flex justify-content-end">
      
        <div class="col-lg-12">
           
         
                <div class="content-panel">
                    <div class="table-responsive">
                        <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
                            <thead class="cf">
                                <tr>

                                    <th class="col-xs-2"><?= lang('Date'); ?></th>
                                    <th class="col-xs-3"><?= lang('Courier'); ?></th>
                                    <th class="col-xs-2"><?= lang('Note'); ?></th>
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
                                    <th class="col-xs-2"><?= lang('Date'); ?></th>
                                    <th class="col-xs-3"><?= lang('Courier'); ?></th>
                                    <th class="col-xs-2"><?= lang('Note'); ?></th>
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
    const print_url = '<?= site_url('letter/print_letter/'); ?>'
    $(document).ready(function() {

        $('.awb_search').select2();
       
        table = $('#TTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": <?= $Settings->rows_per_page; ?>,
            "processing": true,
            "serverSide": true,
            'ajax': {
                url: '<?= site_url('letter/get_letter_travel'); ?>',
                type: 'POST',
                "data": function(d) {
                    d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
                }
            },
           
            "buttons": [
                // {

                //     extend: 'copyHtml5',
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5]
                //     },
                // },
                // {
                //     extend: 'excelHtml5',
                //     'footer': true,
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5]
                //     }
                // },
                // {
                //     extend: 'csvHtml5',
                //     'footer': true,
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5]
                //     }
                // },
                // {
                //     extend: 'pdfHtml5',
                //     orientation: 'landscape',
                //     pageSize: 'A4',
                //     'footer': true,
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                //     },
                //     customize: function(doc) {
                //         doc.content[1].table.widths =
                //             Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                //     }
                // },
                // {
                //     extend: 'colvis',
                //     text: 'Columns'
                // },


            ],

            columns: [{
                    //date

                    "render": (data, type, row, meta) => {
                        return `         
                   
                    <h5 class="m-0 p-0">${row[3]}</h5>
                   
                         `;
                    }
                },
                {
                    //courier

                    "render": (data, type, row, meta) => {
                        return `   
                       
                            <p class="m-0 p-0">${row[1]}</p>
                             
                         `;
                    }
                },
                {
                    //note


                    "render": (data, type, row, meta) => {
                        return `         
                       
                            <p class="m-0 p-0">${row[2]}</p>
                       
                         `;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        
                           return ` 
                            <a onclick="window.open('${print_url}${row[0]}', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href='#' class='btn btn-primary  tip po'>
                             Print
                            </a>
                            `;
                    }
                }
            ],
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
               if(data.icon == 'success'){
                toastr.success(data.message);
                table.ajax.reload();
               } else if(data.icon == 'error') {
                toastr.error(data.message);
               } else {
                toastr.warning(data.message);
               }
            })
            .catch(error => toastr.error(error));
            }
     
      
        }
    
    )
        
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }


    });

    
</script>