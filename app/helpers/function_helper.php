<?php defined('BASEPATH') OR exit('No direct script access allowed');


function print_custom($value = '')
{
	echo '<pre>';
	print_r($value);
	echo '</pre>';
}

function invoice_generate()
{
	return 'INV/'.date('d/m/y').'/'.random_int(00000000001, 99999999999);
}