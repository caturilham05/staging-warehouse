<?php (defined('BASEPATH')) or exit('No direct script access allowed');
$this->load->helper('function_helper');
$order_id            = !empty($sales) ? json_encode(array_column($sales, 'id')) : [];
$product_name_concat = !empty($sales) ? array_column($sales, 'product_name') : [];
$product_name_concat = !empty($sales) ? implode(',', $product_name_concat) : '';

?>
<?php
    if (empty($page_ekspedition_process))
    {
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
                                    <?php
                                        if (!empty($this->session->userdata('warehouse_id')))
                                        {
                                            ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h3>Warehouse: <b><?= $warehouses->name?></b></h3>
                                                        <?= form_hidden('warehouse_id', $warehouses->id, 'class="form-control tip" id="warehouse_id" placeholder="Order Invoice"'); ?>
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
                                                        foreach ($warehouses as $value) $warehouse_data[$value->id] = $value->name;
                                                        ?>
                                                        <?= form_dropdown('warehouse_id', $warehouse_data, set_value('warehouse_id'), 'class="form-control tip" id="warehouse_id" required="required"'); ?>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <!-- <?= lang('Shipper Zip Code', 'Shipper Zip Code'); ?>
                                            <?= form_input('shipper_zip_code', '', 'class="form-control tip" id="shipper_zip_code" placeholder="Shipper Zip Code"'); ?> -->
                                            <?= lang('Shipper Origin Address', 'Shipper Origin Address'); ?>
                                            <?php 
                                            $jne_origin_data[''] = lang('Select Shippier Origin Address');
                                            foreach ($jne_origin['origin'] as $value) $jne_origin_data[$value['City_Code']] = $value['City_Name'].' (Code: '.$value['City_Code'].')';
                                            ?>
                                            <?= form_dropdown('shipper_city_code', $jne_origin_data, set_value('shipper_city_code'), 'class="form-control tip" id="shipper_city_code" required="required"'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <!-- <?= lang('Receiver Zip Code', 'Receiver Zip Code'); ?>
                                            <?= form_input('receiver_zip_code', '', 'class="form-control tip" id="receiver_zip_code" placeholder="Receiver Zip Code"'); ?> -->
                                            <?= lang('Receiver Destination', 'Receiver Destination'); ?>
                                            <?php 
                                            $jne_destination_data[''] = lang('Receiver Destination');
                                            foreach ($jne_destination['destination'] as $value) $jne_destination_data[$value['City_Code']] = $value['City_Name'].' (Code: '.$value['City_Code'].')';
                                            ?>
                                            <?= form_dropdown('receiver_destination', $jne_destination_data, set_value('receiver_destination'), 'class="form-control tip" id="receiver_destination" required="required"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                       <div class="form-group">
                                            <?= lang('Weight (KG)', 'Weight (KG)'); ?>
                                           <input type="number" name="weight" value=""  class="form-control tip" id="weight" placeholder="Weight (KG)"/>
                                           <small>weight of all selected products (KG)</small>
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
                                            foreach ($items as $value) $items_data[$value->code] = $value->name.' - '.$value->code;
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
                                      <p><?php echo form_submit('create_invoice', lang('Create Invoice'), 'class="btn btn-theme03"'); ?></p>
                                  </div>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
    else
    {
        ?>
            <div class="">
                <h3><i class="fa fa-plus"></i> <?= $page_title; ?></h3>
                <p><?= lang('enter_info'); ?></p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="content-panel">
                        <?= form_open_multipart("sales/sales_add_manually_view?invoice=".urlencode($invoice), 'class="validation"'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center>
                                                <h3><b><?= $invoice?></b></h3>
                                            </center>
                                            <?= form_hidden('order_no', $invoice, 'class="form-control tip" id="order_no" placeholder="Order Invoice"'); ?>
                                        </div>
                                    </div>

                                    <!-- table -->                                    
                                    <div class="table" style="margin-bottom: 5rem">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Status</th>
                                                    <th>Status Packing</th>
                                                    <th>Shipper City Code</th>
                                                    <th>Receiver Destination</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Product Quantity</th>
                                                    <!-- <th>Weight</th> -->
                                                    <!-- <th>Dimension Size</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($sales as $key => $value)
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $warehouses->name ?></td>
                                                            <td><?php echo $value['status']?></td>
                                                            <td><?php echo $value['status_packing']?></td>
                                                            <td><?php echo $value['shipper_city_code']?></td>
                                                            <td><?php echo $value['receiver_destination']?></td>
                                                            <td><?php echo $value['product_id'] ?></td>
                                                            <td><?php echo $value['product_name'] ?></td>
                                                            <td><?php echo $value['product_quantity'] ?></td>
                                                            <!-- <td><?php echo $value['weight'] ?></td> -->
                                                            <!-- <td><?php echo $value['dimension_size'] ?></td> -->
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- table -->

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
                                            <?= lang('Receiver Address', 'Receiver Address'); ?>
                                            <?= form_textarea('receiver_address', '', 'class="form-control tip" id="receiver_address" placeholder="Receiver Address"'); ?>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border: 1px solid #428bca">
                                <br>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Courier', 'Courier'); ?>
                                            <?= form_input('courier', 'JNE', 'class="form-control tip" id="courier" placeholder="Courier" readonly'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Package Price', 'Package Price'); ?>
                                            <?= form_input('package_price', '', 'class="form-control tip" id="package_price" placeholder="0"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Type', 'Type'); ?>
                                            <?= form_dropdown('type', ['' => 'select type expedition', 'COD PICKUP' => 'COD PICKUP', 'PICKUP' => 'PICKUP' ], '', 'class="form-control tip" id="type" required="required"'); ?>
                                            <!-- <?= form_input('type', '', 'class="form-control tip" id="type" placeholder="Type"'); ?> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Select Service Expedition', 'Select Service Expedition'); ?>
                                            <?php 
                                            $jne_price_data[''] = lang('Select Service Expedition');
                                            foreach ($sales[0]['jne_price'] as $jne)
                                            {
                                                $shipping = sprintf("Destination: %s | Service: %s | Price: %s (%s) | Delivery Estimate: %s - %s (%s)", $jne['destination_name'], $jne['service_display'], $jne['price'], $jne['currency'], $jne['etd_from'], $jne['etd_thru'], $jne['times']);
                                                $jne_price_data[$shipping] = $shipping;
                                            }
                                            ?>
                                            <?= form_dropdown('shipping_price_text', $jne_price_data, set_value('shipping_price_text'), 'class="form-control tip" id="shipping_price_text" required="required"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Service', 'Service'); ?>
                                            <?= form_input('service', '', 'class="form-control tip" id="service" placeholder="Serivces" readonly'); ?>
                                            <small>This column is automatically filled after selecting a service expedition</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('Shipping Price', 'Shipping Price'); ?>
                                            <?= form_input('shipping_price', '', 'class="form-control tip" id="shipping_price" placeholder="0" readonly'); ?>
                                            <small>This column is automatically filled after selecting a service expedition</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang('Shipping Note', 'Shipping Note'); ?>
                                            <?= form_textarea('shipping_note', '', 'class="form-control tip" id="shipping_note" placeholder="Shipping Note"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang('Goods Description', 'Goods Description'); ?>
                                            <?= form_textarea('goods_description', $product_name_concat, 'class="form-control tip" id="goods_description" placeholder="Goods Description" readonly'); ?>
                                        </div>
                                    </div>
                                    <?php
                                        foreach ($sales as $k_id => $v_id)
                                        {
                                            ?>
                                            <input type="hidden" name="id[]" value="<?php echo $v_id['id']?>"  class="form-control tip" id="id"/>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <!-- <div class="table" style="margin-bottom: 5rem">
                                    <table class="table table-bordered table-striped table-hover table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Order</th>
                                                <th>Courier</th>
                                                <th>Select Service Expedition (Include Shipping Price)</th>
                                                <th>Service</th>
                                                <th>Shipping Price</th>
                                                <th>Package Price</th>
                                                <th>Type</th>
                                                <th>Shipping Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($sales as $key => $value)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('id[]', $value['id'], 'class="form-control tip" id="id" readonly'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('courier[]', '', 'class="form-control tip" id="courier" placeholder="Courier"'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?php 
                                                                $jne_price_data[''] = lang('Select Service Expedition ');
                                                                foreach ($value['jne_price'] as $jne)
                                                                {
                                                                    $shipping = sprintf("Destination: %s | Service: %s | Price: %s (%s) | Delivery Estimate: %s - %s (%s)", $jne['destination_name'], $jne['service_display'], $jne['price'], $jne['currency'], $jne['etd_from'], $jne['etd_thru'], $jne['times']);
                                                                    $jne_price_data[$shipping] = $shipping;
                                                                }
                                                                ?>
                                                                <?= form_dropdown('shipping_price_text', $jne_price_data, set_value('shipping_price_text'), 'class="form-control tip" id="shipping_price_text_'.$value['id'].'" required="required"'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('service[]', '', 'class="form-control tip" id="service_'.$value['id'].'" placeholder="Serivces" readonly'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('shipping_price[]', '', 'class="form-control tip" id="shipping_price_'.$value['id'].'" placeholder="0" readonly'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('package_price[]', '', 'class="form-control tip" id="package_price" placeholder="Package Price"'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_input('type[]', '', 'class="form-control tip" id="Type" placeholder="Type"'); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <?= form_textarea('shipping_note[]', '', 'class="form-control tip" id="shipping_note" placeholder="Shipping Note"'); ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> -->
                                <div class="col-md-15">
                                  <div class="form-group">
                                      <p><?php echo form_submit('submit_order', lang('Submit Order'), 'class="btn btn-theme03"'); ?></p>
                                  </div>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
?>

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
                            '<div class="col-md-10">'+
                                '<div class="form-group">'+
                                    '<input type="hidden" name="product_code[]" value="'+result.data.code+'"  class="form-control tip" id="product_code"/>'+
                                    '<span>'+result.data.name+'</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="form-group">'+
                                    '<input type="number" name="product_quantity[]" value=""  class="form-control tip" id="product_quantity" placeholder="Qty"/>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<hr>'
                    );
                }
            },
        });
    })

    let orderId      = '<?= $order_id?>';
    let orderIdParse = JSON.parse(orderId);
    $('#shipping_price_text').on('change', function(){
        let value           = $(this).val();
        let pattern_service = /service\:\s(.*?)\s\|/i
        let result_service  = value.match(pattern_service)
        let pattern_price   = /price\:\s([0-9]+)/i
        let result_price    = value.match(pattern_price)
        $('#service').val(result_service[1])
        $('#shipping_price').val(result_price[1])
    })    
</script>
<script src="<?= $assets ?>js/stin.js" type="text/javascript"></script>