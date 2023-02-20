<?php defined('BASEPATH') OR exit('No direct script access allowed');


function print_custom($value = '')
{
	echo '<pre>';
	print_r($value);
	echo '</pre>';
}

function invoice_generate()
{
	return 'INV/'.date('d/m/y').'/'.random_int(00000000000, 99999999999);
}

function stock_opname_generate()
{
	return 'SO/'.date('d/m/y').'/'.random_int(00000000000, 99999999999);
}

function curl_custom($host = '', $header = array(), $fields = array(), $method = '')
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $host);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	if (!empty($fields)) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	}
	$output = curl_exec($ch);
	curl_close($ch);
	$output = json_decode($output, true);
	return $output;
}
