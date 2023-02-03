<?php (defined('BASEPATH')) or exit('No direct script access allowed');
$this->load->helper('function_helper');
?>

<div class="">
    <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open_multipart("sales/sales_add_manually_view", 'class="validation"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
	                        <div class="form-group">
	                            <?= lang('Order Invoice', 'Order Invoice'); ?>
	                            <?= form_input('order_no', '', 'class="form-control tip" id="order_no" placeholder="Order Invoice"'); ?>
	                        </div>
                        </div>
                        <div class="col-md-6">
	                        <div class="form-group">
	                            <?= lang('Airway Bill', 'Airway Bill'); ?>
	                            <?= form_input('awb_no', '', 'class="form-control tip" id="awb_no" placeholder="Airway Bill"'); ?>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Shipper', 'Shipper'); ?>
                                <?php 
                                $address_books_data[''] = lang('Select Shippier');
                                foreach ($address_books as $value) $address_books_data[$value->id] = $value->name;
                                ?>
                                <?= form_dropdown('shipper_id', $address_books_data, set_value('shippier_id'), 'class="form-control tip" id="shipper_id" required="required"'); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                              <p><?php echo form_submit('add', lang('Create Sales Manually'), 'class="btn btn-theme03"'); ?></p>
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
    var stinitems = {};
    var lang = new Array();
    lang['code_error'] = '<?= lang('code_error'); ?>';
    lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
    lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
    $(document).ready(function() {
        if (get('stinitems')) { remove('stinitems'); }
        loadInItems();
    });
</script>
<script src="<?= $assets ?>js/stin.js" type="text/javascript"></script>