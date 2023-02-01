<?php defined('BASEPATH') OR exit('No direct script access allowed');


function print_custom($value = '')
{
	echo '<pre>';
	print_r($value);
	echo '</pre>';
}