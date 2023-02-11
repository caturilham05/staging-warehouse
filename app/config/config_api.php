<?php defined('BASEPATH') OR exit('No direct script access allowed');

switch (ENVIRONMENT)
{
	case 'development':
		$config['jne_username'] = 'TESTAPI';
		$config['jne_api_key']  = '25c898a9faea1a100859ecd9ef674548';
		break;

	case 'production':
		$config['jne_username'] = 'RITEN';
		$config['jne_api_key']  = '415c2ea558d0ea32dc04f17a97d736c3';
		break;
	
	default:
		// code...
		break;
}