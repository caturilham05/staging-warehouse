<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Module: General Language File for common lang keys
 * Language: English
 *
 * Last edited:
 * 15th August 2015
 *
 * Package:
 * Simple Stock Manage v2.0
 *
 * You can translate this file to your language.
 * For instruction on new language setup, please visit the documentations.
 * You also can share your language files by emailing to saleem@tecdiary.com
 * Thank you
 */

/* ----------------------- CUSTOM FILEDS ----------------------- */
$lang['ccf1']                               = "Customer Custom Field 1";
$lang['ccf2']                               = "Customer Custom Field 2";
$lang['scf1']                               = "Supplier Custom Field 1";
$lang['scf2']                               = "Supplier Custom Field 2";


/* ----------------- DATATABLES LANGUAGE ---------------------- */
/*
* Below are datatables language entries
* Please only change the part after = and make sure you change the the words in between "";
* 'sEmptyTable'                     => "No data available in table",
* Don't change this                 => "You can change this part but not the word between and ending with _ like _START_;
* For support email support@tecdiary.com Thank you!
*/

$lang['datatables_lang']        = array(
    'sEmptyTable'                   => "No data available in table",
    'sInfo'                         => "Showing _START_ to _END_ of _TOTAL_ entries",
    'sInfoEmpty'                    => "Showing 0 to 0 of 0 entries",
    'sInfoFiltered'                 => "(filtered from _MAX_ total entries)",
    'sInfoPostFix'                  => "",
    'sInfoThousands'                => ",",
    'sLengthMenu'                   => "Show _MENU_ ",
    'sLoadingRecords'               => "Loading...",
    'sProcessing'                   => "Processing...",
    'sSearch'                       => "Search",
    'sZeroRecords'                  => "No matching records found",
    'oAria'                         => array(
        'sSortAscending'            => ": activate to sort column ascending",
        'sSortDescending'           => ": activate to sort column descending"
        ),
    'oPaginate'                     => array(
        'sFirst'                    => "<i class=\"fa fa-angle-double-left\"></i>",
        'sLast'                     => "<i class=\"fa fa-angle-double-right\"></i>",
        'sNext'                     => "<i class=\"fa fa-angle-right\"></i>",
        'sPrevious'                 => "<i class=\"fa fa-angle-left\"></i>",
        )
    );

/* ----------------- Select2 LANGUAGE ---------------------- */
/*
* Below are select2 lib language entries
* Please only change the part after = and make sure you change the the words in between "";
* 's2_errorLoading'                 => "The results could not be loaded",
* Don't change this                 => "You can change this part but not the word between {} like {t};
* For support email support@tecdiary.com Thank you!
*/

$lang['select2_lang']               = array(
    'formatMatches_s'               => "One result is available, press enter to select it.",
    'formatMatches_p'               => "results are available, use up and down arrow keys to navigate.",
    'formatNoMatches'               => "No matches found",
    'formatInputTooShort'           => "Please type {n} or more characters",
    'formatInputTooLong_s'          => "Please delete {n} character",
    'formatInputTooLong_p'          => "Please delete {n} characters",
    'formatSelectionTooBig_s'       => "You can only select {n} item",
    'formatSelectionTooBig_p'       => "You can only select {n} items",
    'formatLoadMore'                => "Loading more results...",
    'formatAjaxError'               => "Ajax request failed",
    'formatSearching'               => "Searching..."
    );


/* ----------------- GENERAL LANGUAGE KEYS -------------------- */
$lang['dashboard']                            = "Casa";
$lang['items']                                = "Artículos";
$lang['list_items']                           = "List Items";
$lang['add_item']                             = "Add Item";
$lang['import_items']                         = "Import Items";
$lang['print_barcodes']                       = "Print Barcodes";
$lang['print_labels']                         = "Print Labels";
$lang['check_ins']                            = "Check-ins";
$lang['list_check_ins']                       = "List Check-ins";
$lang['new_check_in']                         = "New Check-in";
$lang['check_in_by_csv']                      = "Check-in by csv";
$lang['check_outs']                           = "Check-outs";
$lang['list_check_outs']                      = "List Check-outs";
$lang['new_check_out']                        = "New Check-outs";
$lang['check_out_by_csv']                     = "Check-out by csv";
$lang['users']                                = "Users";
$lang['list_users']                           = "List Users";
$lang['add_user']                             = "Add User";
$lang['settings']                             = "Settings";
$lang['categories']                           = "Categories";
$lang['add_category']                         = "Add Category";
$lang['import_categories']                    = "Import Categories";
$lang['backups']                              = "Backups";
$lang['updates']                              = "Updates";
$lang['profile']                              = "Profile";
$lang['logout']                               = "Logout";
$lang['no_of_check_ins_and_outs']             = "Number of Check-ins & Check-outs";
$lang['welcome']                              = "Welcome to";
$lang['dashboard_heading']                    = "Quick links and chart of for total number of check-ins and check-outs per month";
$lang['products']                             = "Items";
$lang['check_in_items']                       = "Check-in Items";
$lang['check_out_items']                      = "Check-out Items";
$lang['code_error']                           = "Action Failed, Please check you code";
$lang['r_u_sure']                             = "Are you sure?";
$lang['no_match_found']                       = "No matching item found";
$lang['unexpected_value']                     = "Unexpected value provided";
$lang['username']                             = "Username";
$lang['password']                             = "Password";
$lang['first_name']                           = "First Name";
$lang['last_name']                            = "Last Name";
$lang['email']                                = "Email";
$lang['phone']                                = "Phone";
$lang['gender']                               = "Gender";
$lang['confirm_password']                     = "Conform Password";
$lang['list_results']                         = "Please review the result below";
$lang['image']                                = "Image";
$lang['code']                                 = "Code";
$lang['name']                                 = "Name";
$lang['category']                             = "Category";
$lang['quantity']                             = "Quantity";
$lang['unit']                                 = "Unit";
$lang['alert_on']                             = "Alert On";
$lang['actions']                              = "Actions";
$lang['all']                                  = "All";
$lang['edit_item']                            = "Edit Item";
$lang['delete_item']                          = "Delete Item";
$lang['i_m_sure']                             = "I am sure";
$lang['no']                                   = "No";
$lang['yes']                                  = "Yes";
$lang['alert_quantity']                       = "Alert Quantity";
$lang['enter_info']                           = "Please enter the information below";
$lang['code_tip']                             = "Barcode/SKU/UPC/ISBN";
$lang['barcode_symbology']                    = "Barcode Symbology";
$lang['select_category']                      = "Select Category";
$lang['product_code']                         = "Product Code";
$lang['product_name']                         = "Product Name";
$lang['category_code']                        = "Category Code";
$lang['category_name']                        = "Category NAme";
$lang['image_with_ext']                       = "Image name with extension";
$lang['update_info']                          = "Please update the information below";
$lang['item_added']                           = "Item successfully added";
$lang['item_updated']                         = "Item successfully updated";
$lang['item_deleted']                         = "Item successfully deleted";
$lang['items_added']                          = "Items successfully added";
$lang['category_added']                       = "Category successfully added";
$lang['category_updated']                     = "Category successfully updated";
$lang['category_deleted']                     = "Category successfully deleted";
$lang['categories_added']                     = "Categories successfully added";
$lang['check_in_added']                       = "Check-in successfully added";
$lang['check_in_updated']                     = "Check-in successfully updated";
$lang['check_in_deleted']                     = "Check-in successfully deleted";
$lang['check_out_added']                      = "Check-out successfully added";
$lang['check_out_updated']                    = "Check-out successfully updated";
$lang['check_out_deleted']                    = "Check-out successfully deleted";
$lang['access_denied']                        = "Access denied! You don't have right to access the requested page.";
$lang['login']                                = "Login";
$lang['submit']                               = "Submit";
$lang['print']                                = "Print";
$lang['check_in']                             = "Check-in";
$lang['add_check_in']                         = "New Check-in";
$lang['id']                                   = "ID";
$lang['date']                                 = "Date";
$lang['reference']                            = "Reference";
$lang['supplier']                             = "Supplier";
$lang['edit_check_in']                        = "Edit Check-in";
$lang['delete_check_in']                      = "Delete Check-in";
$lang['install']                              = "Install";
$lang['update']                               = "Update";
$lang['backup_database']                      = "Backup Database";
$lang['action_x_undone']                      = "This action cannot be undo. The record will be delete permanently from the database.";
$lang['upload_file']                          = "Upload File";
$lang['download_sample_file']                 = "Download sample File";
$lang['csv_file_tip']                         = "Please select .csv files (allowed file size 200KB)";
$lang['import']                               = "Import";
$lang['csv1']                                 = "The first line in downloaded csv file should remain as it is. Please do not change the order of columns.";
$lang['csv2']                                 = "The correct column order is";
$lang['csv3']                                 = "&amp; you must follow this. If you are using any other language then English, please make sure the csv file is UTF-8 encoded and not saved with byte order mark (BOM)";
$lang['attachment']                           = "Attachment";
$lang['search_product_or_scan']               = "Search Item or Scan Barcode";
$lang['description']                          = "Description";
$lang['add_product_by_searching_above_field'] = "Add item by searching above field";
$lang['note']                                 = "Note";
$lang['item_code']                            = "Item Code";
$lang['check_out']                            = "Check-out";
$lang['add_check_out']                        = "New Check-out";
$lang['customer']                             = "Customer";
$lang['edit_check_out']                       = "Edit Check-out";
$lang['delete_check_out']                     = "Delete Check-out";
$lang['status']                               = "Status";
$lang['active']                               = "Active";
$lang['inactive']                             = "Inactive";
$lang['select']                               = "Select";
$lang['male']                                 = "Male";
$lang['female']                               = "Female";
$lang['update']                               = "Update";
$lang['site_name']                            = "Site Name";
$lang['date_format']                          = "Date Format";
$lang['time_format']                          = "Time Format";
$lang['default_email']                        = "Default Email";
$lang['rows_per_page']                        = "Rows per page";
$lang['disable']                              = "Disable";
$lang['enable']                               = "Enable";
$lang['update_settings']                      = "Update Settings";
$lang['loading_data_from_server']             = "Leading data from server";
$lang['edit_category']                        = "Edit Category";
$lang['delete_category']                      = "Delete Category";
$lang['backup_on']                            = "Backup taken on ";
$lang['restore']                              = "Restore";
$lang['download']                             = "Download";
$lang['file_backups']                         = "File Backups";
$lang['backup_files']                         = "Backup Files";
$lang['database_backups']                     = "Database Backups";
$lang['db_saved']                             = "Database successfully saved.";
$lang['db_deleted']                           = "Database successfully deleted.";
$lang['backup_deleted']                       = "Backup successfully deleted.";
$lang['backup_saved']                         = "Backup successfully saved.";
$lang['backup_modal_heading']                 = "Backing up your files";
$lang['backup_modal_msg']                     = "Please wait, this could take few minutes.";
$lang['restore_modal_heading']                = "Restoring the backup files";
$lang['restore_confirm']                      = "This action cannot be undone. Are you sure about this restore?";
$lang['delete_confirm']                       = "This action cannot be undone. Are you sure about this delete?";
$lang['restore_heading']                      = "Please backup before restoring to any older version.";
$lang['full_backup']                          = 'Full Backup';
$lang['database']                             = 'Database';
$lang['files_restored']                       = 'Files successfully restored';
$lang['update_heading']                       = "This page will help you check and install the updates easily with single click. <strong>If there are more than 1 updates available, please update them one by one starting from the top (lowest version)</strong>.";
$lang['update_successful']                    = "Item successfully updated";
$lang['using_latest_update']                  = "You are using the latest version.";
$lang['version']                              = "Version";
$lang['install']                              = "Install";
$lang['changelog']                            = "Change Log";
$lang['disabled_in_demo']                     = "We are sorry but this feature is disabled in demo.";
$lang['purchase_code']                        = "Purchase Code";
$lang['envato_username']                      = "Envato Username";
$lang['delete']                               = "Delete";
$lang['please_wait']                          = "Please wait...";
$lang['change_your_password']                 = "Change your password";
$lang['change_password']                      = "Change Password";
$lang['ref']                                  = "Reference";
$lang['created_by']                           = "Created by";
$lang['created_at']                           = "Created at";
$lang['updated_by']                           = "Updated by";
$lang['updated_at']                           = "Updated at";
$lang['check_out_id']                         = "Check-out ID";
$lang['check_in_id']                          = "Check-in ID";
$lang['email_address']                        = "Email Address";
$lang['suppliers']                            = "Suppliers";
$lang['list_suppliers']                       = "List Suppliers";
$lang['add_supplier']                         = "Add Supplier";
$lang['edit_supplier']                        = "Edit Supplier";
$lang['delete_supplier']                      = "Delete Supplier";
$lang['supplier_added']                       = "Supplier successfully added";
$lang['supplier_updated']                     = "Supplier successfully updated";
$lang['supplier_deleted']                     = "Supplier successfully deleted";
$lang['customers']                            = "Customers";
$lang['list_customers']                       = "List Customers";
$lang['add_customer']                         = "Add Customer";
$lang['edit_customer']                        = "Edit Customer";
$lang['delete_customer']                      = "Delete Customer";
$lang['customer_added']                       = "Customer successfully added";
$lang['customer_updated']                     = "Customer successfully updated";
$lang['customer_deleted']                     = "Customer successfully deleted";
$lang['select_customer']                      = "Select Customer";
$lang['select_supplier']                      = "Select Supplier";
$lang['import_customers']                     = "Import Customers";
$lang['import_suppliers']                     = "Import Suppliers";
$lang['customers_added']                      = "Customers successfully added";
$lang['suppliers_added']                      = "Suppliers successfully added";
$lang['from']                                 = "From";
$lang['till']                                 = "Till";
$lang['get']                                  = "Get";
