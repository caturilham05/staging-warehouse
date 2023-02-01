<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $page_title ?> | <?= $Settings->site_name; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= $assets ?>img/icon.png" />
	<link href="<?= $assets ?>css/app.min.css" rel="stylesheet" type="text/css" />
	<script src="<?= $assets ?>js/jquery.js"></script>
  <!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
  <!--<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>-->
	<link rel="stylesheet" href="<?= base_url('themes/default/assets/helpers/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('themes/default/assets/helpers/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
</head>

<body>
	<section id="container">
		<header class="header orange-bg">
			<div class="color-line"></div>
			<div class="container">
				<span class="logo"><a href="<?= site_url(); ?>"><strong><?= $Settings->site_name; ?></strong></a></span>
				<div class="nav notify-row pull-right bounceInRight" id="top_menu">
					<ul class="nav top-menu pull-right">
						<!-- <li class="dropdown hidden-xs">
							<a class="btn tip" title="<?= lang('language') ?>" data-placement="left" data-toggle="dropdown" href="#">
								<img src="<?= base_url('uploads/' . $Settings->language . '.png'); ?>" alt="">
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<?php $scanned_lang_dir = array_map(function ($path) {
									return basename($path);
								}, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
								foreach ($scanned_lang_dir as $entry) { ?>
									<li>
										<a href="<?= site_url('welcome/language/' . $entry); ?>">
											<img src="<?= base_url(); ?>uploads/<?= $entry; ?>.png" class="language-img">
											&nbsp;&nbsp;<?= ucwords($entry); ?>
										</a>
									</li>
								<?php } ?>
							</ul>

						</li> -->
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-barcode"></i> <span class="hidden-xs hidden-sm">&nbsp;&nbsp;<?= lang('Address Books'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('address_books'); ?>"><i class="fa fa-list"></i> <?= lang('list_address_books'); ?></a></li>
								<li><a href="<?= site_url('address_books/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_address_books'); ?></a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-barcode"></i> <span class="hidden-xs hidden-sm">&nbsp;&nbsp;<?= lang('items'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('items'); ?>"><i class="fa fa-list"></i> <?= lang('list_items'); ?></a></li>
								<li><a href="<?= site_url('items/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_item'); ?></a></li>
								<li><a href="<?= site_url('items/import'); ?>"><i class="fa fa-plus"></i> <?= lang('import_items'); ?></a></li>
								<li id="products_print_barcodes">
									<a onclick="window.open('<?= site_url('items/print_barcodes'); ?>', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#">
										<i class="fa fa-print"></i> <?= lang('print_barcodes'); ?>
									</a>
								</li>
								<li id="products_print_labels">
									<a onclick="window.open('<?= site_url('items/print_labels'); ?>', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#">
										<i class="fa fa-print"></i> <?= lang('print_labels'); ?>
									</a>
								</li>
							</ul>
						</li>
						<?php if ($qty_alert_num) { ?>
							<li class="">
								<a href="<?= site_url('items/alerts'); ?>"><i class="fa fa-bullhorn"></i><span class="badge bg-theme"><?= $qty_alert_num; ?></span></a>
							</li>
						<?php } ?>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-arrow-circle-down"></i> <span class="hidden-xs hidden-sm"><?= lang('Inbound'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('check_in'); ?>"><i class="fa fa-list"></i> <?= lang('Inbound'); ?></a></li>
								<li><a href="<?= site_url('check_in/add'); ?>"><i class="fa fa-plus"></i> <?= lang('New Inbound'); ?></a></li>
								<!--<li><a href="<?= site_url('check_in/add_by_csv'); ?>"><i class="fa fa-plus"></i> <?= lang('Inbound by CSV'); ?></a></li>-->
							</ul>
						</li>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="fa fa-arrow-circle-up"></i> <span class="hidden-xs hidden-sm"><?= lang('Outbond'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('check_out'); ?>"><i class="fa fa-list"></i> <?= lang('List Outbond'); ?></a></li>
								<li><a href="<?= site_url('check_out/add'); ?>"><i class="fa fa-plus"></i> <?= lang('New Outbond'); ?></a></li>
								<!--<li><a href="<?= site_url('check_out/add_by_csv'); ?>"><i class="fa fa-plus"></i> <?= lang('Outbond by CSV'); ?></a></li>-->
							</ul>
						</li>
						
						<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm"><?= lang('Sales'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('sales/index'); ?>"><i class="fa fa-cogs"></i> <?= lang('List Sales'); ?></a></li>
									<li id="sales_print_labels">
									<a onclick="window.open('<?= site_url('sales/print_labels'); ?>', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#">
										<i class="fa fa-print"></i> <?= lang('Print Shipping Label'); ?>
									</a>
								</li>

								</ul>
						</li>
						<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-file"></i> <span class="hidden-xs hidden-sm"><?= lang('Surat Jalan'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('letter/travelIndex'); ?>"><i class="fa fa-car"></i> <?= lang('List Surat Jalan'); ?></a></li>

								</ul>
						</li>
						<?php if ($Admin) { ?>
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-users"></i> <span class="hidden-xs hidden-sm"><?= lang('people'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('users'); ?>"><i class="fa fa-list"></i> <?= lang('list_users'); ?></a></li>
									<li><a href="<?= site_url('users/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_user'); ?></a></li>
									<li><a href="<?= site_url('customers'); ?>"><i class="fa fa-list"></i> <?= lang('list_customers'); ?></a></li>
									<li><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_customer'); ?></a></li>
									<li><a href="<?= site_url('customers/import'); ?>"><i class="fa fa-save"></i> <?= lang('import_customers'); ?></a></li>
									<li><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-list"></i> <?= lang('list_suppliers'); ?></a></li>
									<li><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_supplier'); ?></a></li>
									<li><a href="<?= site_url('suppliers/import'); ?>"><i class="fa fa-save"></i> <?= lang('import_suppliers'); ?></a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-building-o"></i> <span class="hidden-xs hidden-sm"><?= lang('Warehouse'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('warehouses/index'); ?>"><i class="fa fa-cogs"></i> <?= lang('List Warehouse'); ?></a></li>

								</ul>
							</li>
							
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-cogs"></i> <span class="hidden-xs hidden-sm"><?= lang('settings'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('settings'); ?>"><i class="fa fa-cogs"></i> <?= lang('settings'); ?></a></li>
									<li><a href="<?= site_url('settings/categories'); ?>"><i class="fa fa-folder"></i> <?= lang('categories'); ?></a></li>
									<li><a href="<?= site_url('settings/add_category'); ?>"><i class="fa fa-plus"></i> <?= lang('add_category'); ?></a></li>
									<li><a href="<?= site_url('settings/import_categories'); ?>"><i class="fa fa-plus"></i> <?= lang('import_categories'); ?></a></li>
									<li><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-download"></i> <?= lang('backups'); ?></a></li>
									<!-- <li><a href="<?= site_url('settings/updates'); ?>"><i class="fa fa-upload"></i>  <?= lang('updates'); ?></a></li> -->
								</ul>
							</li>
							<!-- debugging -->
						
							
						<?php } else { ?>
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<i class="fa fa-users"></i> <span class="hidden-xs hidden-sm"><?= lang('people'); ?></span>
								</a>
								<ul class="dropdown-menu extended pull-right">
									<div class="notify-arrow"></div>
									<li><a href="<?= site_url('customers'); ?>"><i class="fa fa-list"></i> <?= lang('list_customers'); ?></a></li>
									<li><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_customer'); ?></a></li>
									<li><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-list"></i> <?= lang('list_suppliers'); ?></a></li>
									<li><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-plus"></i> <?= lang('add_supplier'); ?></a></li>
								</ul>
							</li>
						<?php } ?>
						<li class="dropdown">
							<a data-toggle="dropdown" class="logout dropdown-toggle" href="#">
								<i class="fa fa-user"></i> <span class="hidden-xs hidden-sm capitalize">Hi! <?= $this->session->userdata('username'); ?></span>
							</a>
							<ul class="dropdown-menu extended pull-right">
								<div class="notify-arrow"></div>
								<li><a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>"><i class="fa fa-user"></i> <?= lang('profile'); ?></a></li>
								<li><a href="<?= site_url('logout'); ?>"><i class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</header>
		<section id="main-content">
			<section class="wrapper">
				<div class="container">