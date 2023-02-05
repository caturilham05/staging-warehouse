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
                                <h3><b><?= invoice_generate()?></b></h3>
	                            <?= form_hidden('order_no', invoice_generate(), 'class="form-control tip" id="order_no" placeholder="Order Invoice"'); ?>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Courier', 'Courier'); ?>
                                <?= form_input('courier', '', 'class="form-control tip" id="courier" placeholder="Courier"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Services', 'Services'); ?>
                                <?= form_input('service', '', 'class="form-control tip" id="service" placeholder="Serivces"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Type', 'Type'); ?>
                                <?= form_input('type', '', 'class="form-control tip" id="Type" placeholder="Type"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Package Price', 'Package Price'); ?>
                                <?= form_input('package_price', '', 'class="form-control tip" id="package_price" placeholder="Package Price"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Shipping Price', 'Shipping Price'); ?>
                                <?= form_input('shipping_price', '', 'class="form-control tip" id="shipping_price" placeholder="Shipping Price"'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('Shipping Note', 'Shipping Note'); ?>
                                <?= form_textarea('shipping_note', '', 'class="form-control tip" id="shipping_note" placeholder="Shipping Note"'); ?>
                            </div>
                        </div>
                    </div>

                    <hr style="border: 1px solid #428bca">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Receiver Name', 'Receiver Name'); ?>
                                <?= form_input('receiver_name', '', 'class="form-control tip" id="receiver_name" placeholder="Receiver Name"'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Receiver Phone', 'Receiver Phone'); ?>
                                <?= form_input('receiver_phone', '', 'class="form-control tip" id="receiver_phone" placeholder="Receiver Phone"'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Receiver City', 'Receiver City'); ?>
                                <?= form_input('receiver_city', '', 'class="form-control tip" id="receiver_city" placeholder="Receiver City"'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Receiver Subdistrict', 'Receiver Subdistrict'); ?>
                                <?= form_input('receiver_subdistrict', '', 'class="form-control tip" id="receiver_subdistrict" placeholder="Receiver Subdistrict"'); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('Receiver Zip Code', 'Receiver Zip Code'); ?>
                                <?= form_input('receiver_zip_code', '', 'class="form-control tip" id="receiver_zip_code" placeholder="Receiver Zip Code"'); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('Receiver Address', 'Receiver Address'); ?>
                                <?= form_textarea('receiver_address', '', 'class="form-control tip" id="receiver_address" placeholder="Receiver Address"'); ?>
                            </div>
                        </div>
                    </div>

                    <hr style="border: 1px solid #428bca">

                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('Product', ''); ?>
                                <?php 
                                $items_data[''] = lang('Select Product');
                                foreach ($items as $value) $items_data[$value->code] = $value->name;
                                ?>
                                <?= form_dropdown('product_code_js[]', $items_data, set_value('product_code_js'), 'class="form-control tip" id="product_code_js" required="required"'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- new product -->
                    <div id="insert_product"></div>
                    <!-- new product -->

                    <!-- total product -->
                    <input type="hidden" id="jumlah-form" value="1">
                    <!-- total product -->

                    <div class="col-md-15">
                      <div class="form-group">
                          <p><?php echo form_submit('add', lang('Create Sales Manually'), 'class="btn btn-theme03"'); ?></p>
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

    $('#product_code_js').on('change', function(e){
        e.preventDefault()
        let code = $(this).val();
        $.ajax({
            url : '<?php echo site_url('items/get_items_code'); ?>',
            type: "get",
            data: {
                "code": code,
            },
            dataType: "json",
            success:function(result){
                if (result.status === true)
                {
                    console.log(result)
                    $('#insert_product').append(
                        '<div class="row">'+
                            '<div class="col-md-3">'+
                                '<div class="form-group">'+
                                    '<input type="hidden" name="product_code[]" value="'+result.data.code+'"  class="form-control tip" id="product_code"/>'+
                                    '<span>'+result.data.name+'</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-1">'+
                                '<div class="form-group">'+
                                    '<input type="number" name="product_quantity[]" value=""  class="form-control tip" id="product_quantity" placeholder="Qty"/>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="form-group">'+
                                    '<input type="number" name="weight[]" value=""  class="form-control tip" id="weight" placeholder="Weight (KG)"/>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-3">'+
                                '<div class="form-group">'+
                                    '<input type="text" name="dimension_size[]" value=""  class="form-control tip" id="dimension_size" placeholder="Length (1x1x1 cm)"/>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-3">'+
                                '<div class="form-group">'+
                                    '<input type="text" name="goods_description[]" value=""  class="form-control tip" id="goods_description" placeholder="Goods Description"/>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<hr>'
                    );
                }
            },
        });
    })
</script>
<script src="<?= $assets ?>js/stin.js" type="text/javascript"></script>