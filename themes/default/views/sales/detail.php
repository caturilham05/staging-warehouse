<?php (defined('BASEPATH')) or exit('No direct script access allowed');
$this->load->helper('function_helper');
?>

<style type="text/css">
.card__custom {
    width: 100%;
    height: 35rem;
    padding: 1rem;
    margin: 1rem;
    -moz-box-shadow: 3px 3px 5px 6px #ccc;
    -webkit-box-shadow: 3px 3px 5px 6px #ccc;
		box-shadow: 2px 2px 5px 0px rgba(128, 122, 117, 0.94);
		border-radius: 10px;
}
</style>

<center>
	<h1><?php echo $invoice ?> (<?php echo strtoupper($sales[0]['status']) ?>)</h1>
</center>

<div class="row">
		<div class="col-md-4">
			<div class="card__custom">
				<center>
				  <h2>SHIPPER</h2>
		          <hr style="border: none; height: 5px; width: 20%; margin: auto; box-shadow: 0px 5px #c8c8c8;">
				</center>
				<div style="margin-top: 3rem;">
			        <h4><?php echo $sales[0]['shipper_name']?></h4>
			        <h4><?php echo $sales[0]['shipper_phone']?></h4>
			        <h4><?php echo $sales[0]['shipper_address']?>, <?php echo $sales[0]['shipper_subdistrict']?></h4>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card__custom">
				<center>
				  <h2>RECEIVER</h2>
          <hr style="border: none; height: 5px; width: 20%; margin: auto; box-shadow: 0px 5px #c8c8c8;">
				</center>
				<div style="margin-top: 3rem;">
	        <h4><?php echo $sales[0]['receiver_name']?></h4>
	        <h4><?php echo $sales[0]['receiver_phone']?></h4>
	        <h4><?php echo $sales[0]['receiver_address']?>, <?php echo $sales[0]['receiver_subdistrict']?></h4>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card__custom">
				<center>
				  <h2>EXPEDITION</h2>
		          <hr style="border: none; height: 5px; width: 20%; margin: auto; box-shadow: 0px 5px #c8c8c8;">
				</center>
				<div style="margin-top: 3rem;">
			        <h4><?php echo $sales[0]['awb_no']?></h4>
			        <h4><?php echo $sales[0]['courier']?></h4>
			        <h4><?php echo $sales[0]['service']?></h4>
			        <h4><?php echo $sales[0]['type']?></h4>
			        <h4><?php echo $sales[0]['shipper_city_code']?></h4>
			        <h4><?php echo $sales[0]['receiver_destination']?></h4>
			        <h4><?php echo $sales[0]['shipping_note']?></h4>
				</div>
			</div>
		</div>
</div>
<center>
	<h2>PRODUCTS</h2>
</center>
<div class="table" style="margin-top: 2rem">
	<table class="table table-bordered table-striped table-hover table-responsive">
		<thead>
			<tr>
				<th>Product Code</th>
				<th>Product Name</th>
				<th>Product Quantity</th>
				<th>Weight</th>
				<th>Dimension Size</th>
				<th>Goods Description</th>
				<th>Package Price</th>
				<th>Shipping Price</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sales as $key => $value): ?>
				<tr>
					<td><?php echo $value['product_id']?></td>
					<td><?php echo $value['products']->name?></td>
					<td><?php echo $value['product_quantity']?></td>
					<td><?php echo $value['weight']?></td>
					<td><?php echo $value['dimension_size']?></td>
					<td><?php echo $value['goods_description']?></td>
					<td><?php echo $value['package_price']?></td>
					<td><?php echo $value['shipping_price']?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
