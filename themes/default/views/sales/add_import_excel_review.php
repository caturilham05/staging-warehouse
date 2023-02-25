<?php (defined('BASEPATH')) or exit('No direct script access allowed');
?>

<div class="">
    <h3><?= $page_title; ?></h3>
    <p><?= lang('enter_info'); ?></p>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="content-panel">
      	<?= form_open_multipart("sales/sales_add_import_excel_data_review", 'class="validation"'); ?>
      	<div class="table">
      		<table class="table table-bordered table-striped table-hover table-responsive">
      			<thead>
      				<tr>
      					<th>Invoice</th>
      					<th>Shipper Origin Address</th>
      					<th>Receiver Destination</th>
      					<th>Service Expedition</th>
      					<!-- <th>Service</th> -->
      					<th>Shipping Price</th>
      				</tr>
      			</thead>
      			<tbody>
      				<?php
	      				foreach ($data as $key => $value)
	      				{
	      					$get_key[$key]['order_key']     = $key;
	      					$get_key[$key]['shipper_city']  = $value['shipper_city'];
	      					$get_key[$key]['receiver_city'] = $value['receiver_city'];
	      					$get_key[$key]['service']       = $value['service'];
	      					$get_key[$key]['weight']        = $value['weight'];
	      					?>
	      						<tr>
					      			<td>
                        <div class="form-group">
													<button type="button" class="btn btn-link" data-toggle="modal" data-target="#<?php echo $key?>">
                            <span><b><?= $value['order_no']?></b></span>
													</button>
													<div class="modal" tabindex="-1" role="dialog" id="<?php echo $key?>">
													  <div class="modal-dialog" role="document">
													    <div class="modal-content">
													      <div class="modal-header">
													        <h5 class="modal-title"><?php echo $value['order_no']?></h5>
													        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													          <span aria-hidden="true">&times;</span>
													        </button>
													      </div>
													      <div class="modal-body">
													      	<div class="row">
													      		<div class="col-md-6">
													      			<h3>Shipper</h3>
			                                <hr style="border: 1px solid #c8c8c8">
													      			<span><b><?php echo $value['shipper_name']?></b></span><br>
													      			<span><?php echo $value['shipper_phone']?></span><br>
													      			<span><?php echo $value['shipper_address'].', '.$value['shipper_city'].', '.$value['shipper_subdistrict'].'('.$value['shipper_zip_code'].')'?></span><br>
													      			<span><?php echo $value['shipper_zip_code']?></span>
													      		</div>
													      		<div class="col-md-6">
													      			<h3>Receiver</h3>
			                                <hr style="border: 1px solid #c8c8c8">
													      			<span><b><?php echo $value['receiver_name']?></b></span><br>
													      			<span><?php echo $value['receiver_phone']?></span><br>
													      			<span><?php echo $value['receiver_address'].', '.$value['receiver_city'].', '.$value['receiver_subdistrict'].'('.$value['receiver_zip_code'].')'?></span><br>
													      		</div>
													      	</div>
													      	<center>
													      		<h3>Products</h3>
													      	</center>
												      		<div class="row">
												      			<div class="col-md-12">
													      			<div class="table">
													      				<table class="table table-bordered table-striped table-hover table-responsive">
													      					<thead>
													      						<tr>
													      							<th>Product Code</th>
													      							<th>Product Name</th>
													      							<th>Product Qty</th>
													      						</tr>
													      					</thead>
													      					<tbody>
													      						<?php
													      						$total = 0;
													      						foreach ($value['products'] as $k_product => $product)
													      						{
													      							?>
													      								<tr>
													      									<td><?php echo $product['code']?></td>
													      									<td><?php echo $product['product_name']?></td>
													      									<td><?php echo $product['qty']?></td>
													      								</tr>
													      							<?php
													      							$total += $product['qty'];
													      						}
													      						?>
													      					</tbody>
													      				</table>
													      			</div>
												      			</div>
												      		</div>
												      		<div style="display: flex; align-items: center; justify-content: space-around; ">
												      			<span>Package Price: <?= $value['package_price']?></span>
												      			<span>Weight: <?= $value['weight']?></span>
												      			<span>Total Product: <?= $total?></span>
												      		</div>
													      </div>
													    </div>
													  </div>
													</div>
                          <input type="hidden" name="warehouse_id[]" value="<?php echo $value['warehouse_id']?>">
                          <input type="hidden" name="order_no[]" value="<?php echo $value['order_no']?>">
                          <input type="hidden" name="courier[]" value="<?php echo $value['courier']?>">
                          <input type="hidden" name="type[]" value="<?php echo $value['type']?>">
                          <input type="hidden" name="package_price[]" value="<?php echo $value['package_price']?>">
                          <input type="hidden" name="shipper_id[]" value="<?php echo $value['shipper_id']?>">
                          <input type="hidden" name="shipper_name[]" value="<?php echo $value['shipper_name']?>">
                          <input type="hidden" name="shipper_phone[]" value="<?php echo $value['shipper_phone']?>">
                          <input type="hidden" name="shipper_address[]" value="<?php echo $value['shipper_address']?>">
                          <input type="hidden" name="shipper_city[]" value="<?php echo $value['shipper_city']?>">
                          <input type="hidden" name="shipper_subdistrict[]" value="<?php echo $value['shipper_subdistrict']?>">
                          <input type="hidden" name="shipper_zip_code[]" value="<?php echo $value['shipper_zip_code']?>">
                          <input type="hidden" name="receiver_name[]" value="<?php echo $value['receiver_name']?>">
                          <input type="hidden" name="receiver_phone[]" value="<?php echo $value['receiver_phone']?>">
                          <input type="hidden" name="receiver_address[]" value="<?php echo $value['receiver_address']?>">
                          <input type="hidden" name="receiver_city[]" value="<?php echo $value['receiver_city']?>">
                          <input type="hidden" name="receiver_subdistrict[]" value="<?php echo $value['receiver_subdistrict']?>">
                          <input type="hidden" name="receiver_zip_code[]" value="<?php echo $value['receiver_zip_code']?>">
                          <input type="hidden" name="goods_description[]" value="<?php echo $value['goods_description']?>">
                          <input type="hidden" name="weight[]" value="<?php echo $value['weight']?>">
                          <input type="hidden" name="dimension_size[]" value="<?php echo $value['dimension_size']?>">
                          <input type="hidden" name="shipping_note[]" value="<?php echo $value['shipping_note']?>">
                          <input type="hidden" name="product_id[]" value="<?php echo $value['product_id']?>">
                          <input type="hidden" name="product_quantity[]" value="<?php echo $value['product_quantity']?>">
                          <input type="hidden" name="shipper_city_code_result[]" value="" id="shipper_city_code_result_<?php echo $key?>">
                          <input type="hidden" name="receiver_destination_result[]" value="" id="receiver_destination_result_<?php echo $key?>">
	                      </div>
	      							</td>
	      							<td>
                          <div class="form-group">
                            <?php
                              if (empty($value['service']))
                              {
	                              $jne_origin_data[''] = lang('Select Shippier Origin Address');
	                              foreach ($jne_origin['origin'] as $jne_origin_v) $jne_origin_data[$jne_origin_v['City_Code']] = $jne_origin_v['City_Name'].' (Code: '.$jne_origin_v['City_Code'].')';
	                              ?>
		                      			<?= form_dropdown('shipper_city_code[]', $jne_origin_data, set_value('shipper_city_code'), 'class="form-control tip" id="shipper_city_code_'.$key.'" required="required"'); ?> -->
	                              <?php
                              }
                              else
                              {
                              	?>
                              	<span id="origin_code_<?= $key?>"><?= $value['shipper_city']?></span>
                              	<?php
                              }
                            ?>

                            <!-- <?php 
                              $jne_origin_data[''] = lang('Select Shippier Origin Address');
                              foreach ($jne_origin['origin'] as $jne_origin_v) $jne_origin_data[$jne_origin_v['City_Code']] = $jne_origin_v['City_Name'].' (Code: '.$jne_origin_v['City_Code'].')';
                            ?>
                      			<?= form_dropdown('shipper_city_code[]', $jne_origin_data, set_value('shipper_city_code'), 'class="form-control tip" id="shipper_city_code_'.$key.'" required="required"'); ?> -->
                          </div>
	      							</td>
	      							<td>
                          <div class="form-group">
                            <?php
                              if (empty($value['service']))
                              {
	                              $jne_destination_data[''] = lang('Receiver Destination');
	                              foreach ($jne_destination['destination'] as $jne_destination_v) $jne_destination_data[$jne_destination_v['City_Code']] = $jne_destination_v['City_Name'].' (Code: '.$jne_destination_v['City_Code'].')';
	                              ?>
		                              <?= form_dropdown('receiver_destination[]', $jne_destination_data, set_value('receiver_destination'), 'class="form-control tip" id="receiver_destination_'.$key.'" required="required"'); ?>
	                              <?php
                              }
                              else
                              {
                              	?>
                              	<span id="destination_code_<?= $key?>"><?= $value['receiver_city']?></span>
                              	<?php
                              }
                            ?>
                          </div>
	      							</td>
                      <!-- <td>
                          <div class="form-group">
														<select name="service_shipping_price[]" id="service_shipping_price_<?php echo $key?>">
														  <option value="">Select Service Expedition</option>
														</select>
                          </div>
                      </td> -->
                      <td>
                          <div class="form-group">
                              <?= form_input('service[]', !empty($value['service']) ? $value['service'] : '', 'class="form-control tip" id="service_'.$key.'" placeholder="Service" readonly'); ?>
                          </div>
                      </td>
                      <td>
                          <div class="form-group">
                              <?= form_input('shipping_price[]', '', 'class="form-control tip" id="shipping_price_'.$key.'" placeholder="0" readonly'); ?>
                          </div>
                      </td>
	      						</tr>
	      					<?php
	      				}
	      				$get_key_encode = json_encode($get_key);
      				?>
      			</tbody>
      		</table>
      	</div>
      	<div style="display: flex; justify-content: center;"><h3>Products</h3></div>
      	<div class="table">
      		<table class="table table-bordered table-striped table-hover table-responsive">
      			<thead>
      				<tr>
      					<th>Invoice</th>
      					<th>Product Code</th>
      					<th>Product Name</th>
      					<th>Product Quantity</th>
      				</tr>
      			</thead>
      			<tbody>
      				<?php
      					foreach ($data as $key => $value)
      					{
      						foreach ($value['products'] as $product)
      						{
      							?>
      								<tr>
      									<td><?php echo $value['order_no']?></td>
      									<td><?php echo $product['code']?></td>
      									<td><?php echo $product['product_name']?></td>
      									<td><?php echo $product['qty']?></td>
      								</tr>
      							<?php
      						}
      					}
      				?>
      			</tbody>
      		</table>
      	</div>
        <div class="col-md-12">
          <div class="form-group">
              <p><?php echo form_submit('submit_order_excel', lang('Submit Order'), 'class="btn btn-theme03"'); ?></p>
          </div>
        </div>
      	<?= form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="invoice_<?php echo $key?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $value['order_no']?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	let orderKey = '<?php echo $get_key_encode?>';
	let orderKeyParse = JSON.parse(orderKey);
	
	$(document).ready(function(){
		orderKeyParse.map((v) => {
			shipperCity  = v.shipper_city;
			receiverCity = v.receiver_city;
			service      = v.service;
			getWeight    = v.weight;

			$.ajax({
				url: '<?php echo site_url('sales/api_jne_price_json')?>',
				type:'GET',
				data: {
					"shipper_city": shipperCity,
					"receiver_city": receiverCity,
					"weight": getWeight
				},
				dataType: "json",
				success:function(result){
					if (result.length !== 0)
					{
						// let attendanceFilter = result.attendances.filter((v) => v.status == attendance_status);
						let servicePrice = result.filter((v_result) => v_result.service_display == service ? v_result.price : 0);
						console.log(servicePrice)
						$('#shipping_price_' + v.order_key).val(servicePrice == 0 ? 0 : servicePrice[0].price)
						$('#_' + v.order_key).val(servicePrice == 0 ? 0 : servicePrice[0].price)

						// result.map((v_result) => {
						// 	$('#service_shipping_price_' + v.order_key).append('<option value="'+ v_result.service_display +' | '+ v_result.price +'">'+v_result.service_display+' | '+v_result.price+'</option>')
						// 	$('#service_shipping_price_' + v.order_key).on('change', function(){
						// 		let service_shipping_price         = $(this).val();
						// 		let service_shipping_price_explode = service_shipping_price.split(' | ')
						// 		// let pattern_service             = /[A-Za-z]+/i
						// 		// let result_service              = service_shipping_price.match(pattern_service)
						// 		// let pattern_price               = /[0-9]+/i
						// 		// let result_price                = service_shipping_price.match(pattern_price)
						// 		$('#service_' + v.order_key).val(service_shipping_price_explode[0])
						// 		$('#shipping_price_' + v.order_key).val(service_shipping_price_explode[1])
						// 	})
						// })
					}
				}
			})
		})

		// orderKeyParse.map((v) => {
		// 	$('#shipper_city_code_' + v.order_key).on('change', function(e){
		// 		getShipperCityCode = $(this).val();
		// 		console.log(getShipperCityCode)
		// 	})

		// 	$('#receiver_destination_' + v.order_key).on('change', function(){
		// 		getDestination           = $(this).val()
		// 		getShipperCityCodeChange = $('#shipper_city_code_' + v.order_key).val();
		// 		getWeight                = v.weight;
				// $.ajax({
				// 	url: '<?php echo site_url('sales/api_jne_price_json')?>',
				// 	type:'GET',
				// 	data: {
				// 		"origin": getShipperCityCodeChange,
				// 		"destination": getDestination,
				// 		"weight": getWeight
				// 	},
				// 	dataType: "json",
				// 	success:function(result){
				// 		if (result.length !== 0)
				// 		{
				// 			result.map((v_result) => {
				// 				$('#service_shipping_price_' + v.order_key).append('<option value="'+ v_result.service_display +' | '+ v_result.price +'">'+v_result.service_display+' | '+v_result.price+'</option>')
				// 				$('#service_shipping_price_' + v.order_key).on('change', function(){
				// 					let service_shipping_price         = $(this).val();
				// 					let service_shipping_price_explode = service_shipping_price.split(' | ')
				// 					// let pattern_service             = /[A-Za-z]+/i
				// 					// let result_service              = service_shipping_price.match(pattern_service)
				// 					// let pattern_price               = /[0-9]+/i
				// 					// let result_price                = service_shipping_price.match(pattern_price)
				// 					$('#service_' + v.order_key).val(service_shipping_price_explode[0])
				// 					$('#shipping_price_' + v.order_key).val(service_shipping_price_explode[1])
				// 				})
				// 			})
				// 		}
				// 	}
				// })
		// 	})
		// })
	})
</script>
