<?php defined('BASEPATH') OR exit('No direct script access allowed');
 $style = $Admin ? 'style="margin-top: 5rem"' : '';
?>

<div class="row" <?= $style?>>
	<div class="table table-responsive">
		<table class="table table-bordered table-striped table-responsive table-hover">
			<thead>
				<tr>
					<th>Stock Opname</th>
					<th>Warehouse</th>
					<th>Qty Total</th>
					<th>Status</th>
					<th>Notes</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?= $stockopname['stock_opname']?></td>
					<td><?= $stockopname['warehouse_name'] ?></td>
					<td><?= $stockopname['qty'] ?></td>
					<td><?= $stockopname['status'] == 1 ? 'Process' : ($stockopname['status'] == 2 ? 'Selesai' : ($stockopname['status'] == 3 ? 'Batal' : ($stockopname['status'] == 4 ? 'Selesai Outbond' : ($stockopname['status'] == 5 ? 'Selesai Inbond' : '')))) ?></td>
					<td><?= $stockopname['notes'] ?></td>
					<td><?= date('d F Y H:i:s', strtotime($stockopname['created_at'])) ?></td>
				</tr>
			</tbody>
		</table>
    <div class="table-responsive">
      <table id="TTable" class="table table-bordered table-striped cf" style="margin-bottom:5px;">
        <thead class="cf">
            <tr>
              <th class="col-xs-2"><?= lang('Product Code'); ?></th>
              <th class="col-xs-2"><?= lang('Product Name'); ?></th>
              <th class="col-xs-1"><?= lang('Qty'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
              <th class="col-xs-2"><?= lang('Product Code'); ?></th>
              <th class="col-xs-2"><?= lang('Product Name'); ?></th>
              <th class="col-xs-1"><?= lang('Qty'); ?></th>
            </tr>
            <tr>
                <td colspan="9" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="[<?= lang('type_hit_enter'); ?>]" style="width:100%;"></td>
            </tr>
        </tfoot>
      </table>
    </div>

	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
      var table = $('#TTable').DataTable({
          "order": [
            [2, "desc"]
          ],

          "pageLength": <?= $Settings->rows_per_page; ?>,
          "processing": true,
          "serverSide": true,
          'ajax': {
              url: '<?= site_url('stockopname/stockopname_detail_json?stock_opname_id='.$stockopname['id']); ?>',
              type: 'POST',
              "data": function(d) {d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>"; console.log(d)}
          },
          "buttons": [],
          "columns": [
            {
            	"render": (data, type, row, meta) => { return `${row[1]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[2]}`;}
            },
            {
              "render": (data, type, row, meta) => {return `${row[3]}`;}
            },
            // {
            //     "render": (data, type, row, meta) => {
            //         return `    
            //             <!-- delete button -->
            //             <div class='btn-group' role='group'>
            //                 <a href="${detail}${row[0]}" class="btn btn-info">Detail</a>
            //             </div>
            //         `;
            //     }
            // }
        ],
      });
    });

</script>