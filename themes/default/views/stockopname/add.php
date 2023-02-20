<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="">
    <h3><?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">  
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open(empty($stock_opname_id) ? 'stockopname/stock_opname_add_view' : 'stockopname/stock_opname_process_view/'.$stock_opname_id);?>
            	<div class="row">
            		<div class="col-md-12">
            			<div class="row">
            				<div class="col-md-6">
            					<div class="form-group">
                        <h3><b><?= $so_data['stock_opname']?></b></h3>
                        <?= form_hidden('stock_opname_id', empty($stock_opname_id) ? $so_data['id'] : $stock_opname_id, 'class="form-control tip" id="stock_opname_id"'); ?>
            					</div>
            				</div>

                    <?php
                        if (!empty($this->session->userdata('warehouse_id')))
                        {
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h3>Warehouse: <b><?= $warehouse->name?></b></h3>
                                        <?= form_hidden('warehouse_id', $warehouse->id, 'class="form-control tip" id="warehouse_id"'); ?>
                                    </div>
                                </div>                                            
                            <?php
                        }
                        else
                        {
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('Warehouse', 'Warehouse'); ?>
                                        <?php 
                                        $warehouse_data[''] = lang('Select Warehouse');
                                        foreach ($warehouse as $value) $warehouse_data[$value->id] = $value->name;
                                        ?>
                                        <?= form_dropdown('warehouse_id', $warehouse_data, set_value('warehouse_id'), 'class="form-control tip" id="warehouse_id"'); ?>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
            			</div>
            			<div class="row" style="margin-top: 3rem">
                    <div class="col-md-8">
                        <div class="form-group">
                          <?= form_input('product_code', '', 'class="form-control tip" id="product_code" placeholder="Input Product Code"'); ?>
                          <center>
	                          <small>Enter the product code to add in stock opname</small>
                          </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                          <input type="number" name="qty" class="form-control tip" id="qty" placeholder="Input Qty">
                          <center>
	                          <small>Enter the Quantity to add in stock opname</small>
                          </center>
                        </div>
                    </div>
                    <div class="col-md-2">
											<?php echo form_submit('create_stock_opname', lang('Create Stock Opname'), 'class="btn btn-primary"'); ?>
                    </div>
            			</div>
            		</div>
            	</div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
