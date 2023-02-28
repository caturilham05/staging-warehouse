<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div class="">
    <h3><i class="fa fa-arrow-circle-up"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("check_out/check_out_view_v2", 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('date', 'date'); ?>
                                <input type="hidden" name="stock_opname_id" value="<?= $stock_opname_id?>">
                                <?= form_input('date', set_value('date', date('Y-m-d H:i')), 'class="form-control tip datetime" id="date"  required="required"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('reference', 'reference'); ?>
                                <?= form_input('reference', set_value('reference', $reference), 'class="form-control tip" id="reference" required="required" onClick="this.select();"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('customer', 'customer'); ?>
                                <?php
                                $sp[''] = lang('select_customer');
                                foreach ($customers as $customer) {
                                    $sp[$customer->id] = $customer->name;
                                }
                                ?>
                                <?= form_dropdown('customer', $sp, set_value('customer'), 'class="form-control tip" id="customer"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('attachment', 'attachment'); ?>
                                <input name="attachment" type="file" id="attachment" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('No AWB', 'No AWB'); ?>
                                <?= form_input('add_item', '', 'class="form-control tip" id="add_item" placeholder="'.lang('search_awb_or_scan').'"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Warehouse', 'Warehouse'); ?>
                                <?php 
                                 $sp[''] = lang('Select Warehouse');
                                 foreach ($warehouses as $warehouse) {
                                     $sp[$warehouse->id] = $warehouse->name;
                                 }
                                 ?>
                                 <?php if($this->session->userdata('warehouse_id') == null) { ?>
                                 <?= form_dropdown('warehouse_id', $sp, set_value('warehouse_id'), 'class="form-control tip" id="warehouse_id" required="required"'); ?>
                                 <?php } else { ?>
                                     <?= form_dropdown('warehouse_id', $sp, set_value('warehouse_id',$this->session->userdata('warehouse_id')), 'class="form-control tip" id="warehouse_id" disabled="disabled" '); ?>
                                 <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="inTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th><?= lang('awb'); ?></th>
                                            <th><?= lang('description'); ?></th>
                                            <th class="col-xs-2"><?= lang('quantity'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4"><?= lang('add_product_by_searching_above_field'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('note', 'note'); ?>
                                <?= form_textarea('note', set_value('note'), 'class="form-control redactor tip" id="note" style="height:100px;"'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><?php echo form_submit('check_out_awb', lang('add_check_out'), 'class="btn btn-theme03"'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $assets ?>js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#add_item').keyup(function(){
			const awb = this.value;
            const productId = 
			$.ajax({
				url: '<?php echo site_url('check_out/suggestions_awb')?>',
				type: "GET",
				data: {
					"awb": awb
				},
				dataType: "json",
				success:function(result){
					// A022302270025928
					if (result.length > 0)
					{
                        result.map((v) => {
                            if ($('#awb_'+v.awb_no+'_'+v.id).text() == '')
                            {
                                var newTr = $('<tr id="' + v.id + '" data-item-id="' + v.id + '"></tr>');
                                tr_html   = '<td style="min-width:100px;"><input name="awb_no[]" type="hidden" class="rid" value="' + v.awb_no + '"><input name="order_id[]" type="hidden" class="rid" value="' + v.id + '"><span class="sname" id="awb_'+v.awb_no+'_'+v.id+'">' + v.awb_no + '</span></td>';
                                tr_html  += '<td style="min-width:100px;"><input name="product_id[]" type="hidden" class="rid" value="' + v.product_id + '"><span class="sname" id="name_' + v.id + '">' + v.product_name + ' (' + v.product_code + ')</span></td>';
                                tr_html  += '<td style="min-width:100px;"><input name="product_quantity[]" type="hidden" class="rid" value="' + v.product_quantity + '"><span class="sname" id="qty_' + v.id + '">' + v.product_quantity + '</span></td>';
                                newTr.html(tr_html);
                                newTr.prependTo("#inTable");
                            }
                        })
					}
				}
			})
		})
	});
</script>