<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Check_out extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        if ($this->session->userdata('group_id') == 3) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('welcome');
        }

        $this->load->library('form_validation');
        $this->load->model('check_out_model');
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';

        ini_set('display_errors', 1);
    }

    public function index() {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Outbond');
        $this->page_construct('check_out/index', $this->data);

        if(!empty($_SESSION['message'])){ unset($_SESSION['message']); unset($_SESSION['warning']); unset($_SESSION['error']); }
    }

    public function get_list() {
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        if($_SESSION['warehouse_id'] != null) {
            $query = $this->datatables
            ->select($this->db->dbprefix('check_out').".id as id, date, reference, ".$this->db->dbprefix('customers').".name, concat(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as created_by, note, attachment", FALSE)
            ->from('check_out')
            ->join('users', 'users.id=check_out.created_by', 'left')
            ->join('customers', 'customers.id=check_out.customer', 'left')
            ->join('check_out_items', 'check_out_items.check_out_id=check_out.id', 'left')
            ->join('item_warehouse', 'item_warehouse.id=check_out_items.item_warehouse_id', 'left')
            ->where('item_warehouse.warehouse_id', $_SESSION['warehouse_id'])
            ->group_by('check_out.id')
            ->add_column("Actions", "<div class='text-center'><div class='btn-group btn-group-justified' role='group'> <div class='btn-group' role='group'><a class=\"btn btn-warning btn-sm tip\" title='" . lang("edit_check_out") . "' href='" . site_url('check_out/edit/$1') . "'><i class=\"fa fa-edit\"></i></a></div> <div class='btn-group' role='group'><a href='#' class='btn btn-danger btn-sm tip po' title='<b>" . lang("delete_check_out") . "</b>' data-content=\"<p>" . lang('action_x_undone') . "</p><a class='btn btn-danger po-delete' href='" . site_url('check_out/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div></div>", "id");

        } else {
            $query = $this->datatables
            ->select($this->db->dbprefix('check_out').".id as id, date, reference, ".$this->db->dbprefix('customers').".name, concat(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as created_by, note, attachment", FALSE)
            ->from('check_out')
            ->join('users', 'users.id=check_out.created_by', 'left')
            ->join('customers', 'customers.id=check_out.customer', 'left')
            ->group_by('check_out.id')
            ->add_column("Actions", "<div class='text-center'> 
            <div class='btn-group btn-group-toolbar' role='group'>
            <div class='btn-group' role='group'>
                <a class=\"btn btn-success btn-sm tip\" title='" . lang("Detail Outbond") . "' href='#' onclick='detail($1)'><i class=\" fa fa-info-circle\"></i></a>
            </div>
            <div class='btn-group' role='group'>
            <a class=\"btn btn-warning btn-sm tip\" title='" . lang("Edit Outbond") . "' href='" . site_url(' check_out/edit/$1') . "'><i class=\" fa fa-edit\"></i></a>
            </div>
            <div class='btn-group div-confirm' role='group'>
                <a href='#' class='btn btn-danger btn-sm tip po' title='<b>" . lang("Delete Outbond") . "</b>' data-content=\"<p>" . lang('action_x_undone') . "</p><a class='btn btn-danger po-delete' href='" . site_url(' check_out/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\" rel='popover'><i class=\"fa fa-trash-o\"></i></a>
            </div>
            </div>
            </div>", "id");
        }
        
      
        if($start_date) { $query->where('date >=', $start_date); }
        if($end_date) { $query->where('date <=', $end_date); }
        echo $query->generate();
    }

    public function view($id) {

        $inv = $this->check_out_model->getStockOutByID($id);
        $this->data['inv'] = $inv;
        $this->data['items'] = $this->check_out_model->getAllOutItems($id);
        $this->data['created_by'] = $this->check_out_model->getUser($inv->created_by);
        $this->data['updated_by'] = $this->check_out_model->getUser($inv->updated_by);
        $this->data['customer'] = $this->check_out_model->getCustomerByID($inv->customer);
        $this->data['page_title'] = lang('Outbond')." ".$id;
        $this->load->view($this->theme.'check_out/view', $this->data);
    }

    public function add() {

        $this->form_validation->set_rules('reference', lang("reference"), 'trim|required|is_unique[check_out.reference]');
        $this->form_validation->set_rules('date', lang("date"), 'trim|required');
        $this->form_validation->set_rules('customer', lang("customer"), 'trim');

        if ($this->form_validation->run() == true) {

            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_qty = $_POST['quantity'][$r];
                $warehouse_id =$this->session->userdata('warehouse_id') != null ? $this->session->userdata('warehouse_id') : $_POST['warehouse_id'];
                if( $item_id && $item_qty ) {

                    if(!$this->check_out_model->getItemByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('check_out/add');
                    }

                    $items[] = array(
                        'item_id' => $item_id,
                        'quantity' => $item_qty,
                        'warehouse_id' => $this->session->userdata('warehouse_id') != null ? $this->session->userdata('warehouse_id') : $warehouse_id,
                        );

                }
            }

            if (!isset($items) || empty($items)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($items);
            }

            $data = array( 'date' => $this->input->post('date'),
                'reference' => $this->input->post('reference'),
                'customer' => $this->input->post('customer'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
            );

            if ($_FILES['attachment']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2000;
                $config['encrypt_name'] = TRUE;
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('attachment')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("check_out/add");
                }

                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
        }

        if ( $this->form_validation->run() == true) {
            if($this->db->insert('check_out', $data)) {
                $check_out_id = $this->db->insert_id();
                foreach ($items as $item) {
                    $product = $this->check_out_model->getItemWarehouseByID($item['item_id'], $item['warehouse_id']);
                    $this->db->update('item_warehouse', [
                        'quantity' => ($item['quantity'] > $product->quantity) ? $product->quantity - $product->quantity : $product->quantity - $item['quantity'],
                    ], ['item_id' => $item['item_id'], 'warehouse_id' => $item['warehouse_id']]);

                    $item_warehouse_id = $this->db->from('item_warehouse')->where(['item_id' => $item['item_id'], 'warehouse_id' => $item['warehouse_id']])->get()->row()->id;
                    $this->db->insert('check_out_items', [
                        'check_out_id' => $check_out_id,
                        'item_warehouse_id' => $item_warehouse_id,
                        'quantity' => $item['quantity'],
                        'warehouse_id' => $item['warehouse_id'],
                        'item_id' => $item['item_id'],
                    ]);
                }
                $stock_opname_id = !empty($_POST['stock_opname_id']) ? intval($_POST['stock_opname_id']) : 0;
                if (!empty($stock_opname_id))
                {
                    $this->db->update('stock_opname', ['status' => 4], 'id = '.$stock_opname_id);
                    $this->db->update('stock_opname_product', ['status' => 4], 'stock_opname_id = '.$stock_opname_id);
                }
            }

            $this->session->set_flashdata('message', lang("check_out_added"));
            redirect('check_out');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('Add Outbond');
            $this->data['customers'] = $this->check_out_model->getAllCustomers();
            $this->data['warehouses'] = $this->check_out_model->getAllWarehouses();
            $this->data['reference'] = $this->check_out_model->generateReference();
            $this->page_construct('check_out/add', $this->data);

        }
    }

    public function add_by_csv() {

        $this->form_validation->set_rules('reference', lang("reference"), 'trim|required|is_unique[check_out.reference]');
        $this->form_validation->set_rules('date', lang("date"), 'trim|required');
        $this->form_validation->set_rules('customer', lang("customer"), 'trim');

        if ($this->form_validation->run() == true) {

          if (isset($_FILES["userfile"])) {

              $this->load->library('upload');

              $config['upload_path'] = 'uploads/';
              $config['allowed_types'] = 'csv';
              $config['max_size'] = '500';
              $config['overwrite'] = TRUE;
              $config['encrypt_name'] = TRUE;

              $this->upload->initialize($config);

              if (!$this->upload->do_upload()) {
                  $error = $this->upload->display_errors();
                  $this->session->set_flashdata('error', $error);
                  redirect("check_out/add_by_csv");
              }


              $csv = $this->upload->file_name;

              $arrResult = array();
              $handle = fopen("uploads/" . $csv, "r");
              if ($handle) {
                  while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                      $arrResult[] = $row;
                  }
                  fclose($handle);
              }
              array_shift($arrResult);

              $keys = array('code', 'quantity');

              $final = array();
              foreach ($arrResult as $key => $value) {
                  $final[] = array_combine($keys, $value);
              }

              if (sizeof($final) > 1001) {
                  $this->session->set_flashdata('error', lang("more_than_allowed"));
                  redirect("check_out/add_by_csv");
              }

              foreach ($final as $csv_pr) {
                  if($item = $this->check_out_model->getItemByCode($csv_pr['code'])) {
                    $items[] = array('item_id' => $item->id, 'quantity' => $csv_pr['quantity']);
                  } else {
                    $this->session->set_flashdata('error', lang("check_item_code") . " (" . $csv_pr['code'] . "). " . lang("item_x_exist"));
                    redirect("check_out/add_by_csv");
                  }

              }
          } else {
            $this->form_validation->set_rules('userfile', lang("csv_file"), 'required');
          }

            $data = array( 'date' => $this->input->post('date'),
                'reference' => $this->input->post('reference'),
                'customer' => $this->input->post('customer'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
            );

            if ($_FILES['attachment']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2000;
                $config['encrypt_name'] = TRUE;
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('attachment')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("check_out/add");
                }

                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;

            }

            // $this->tec->print_arrays($data, $items);

        }

        if ( $this->form_validation->run() == true && $this->check_out_model->addStockOut($data, $items)) {
            $this->session->set_flashdata('message', lang("check_out_added"));
            redirect('check_out');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('add_check_out');
            $this->data['customers'] = $this->check_out_model->getAllCustomers();
            $this->data['reference'] = $this->check_out_model->generateReference();
            $this->page_construct('check_out/add_by_csv', $this->data);

        }
    }

    public function edit($id) {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('check_out');
        }

        $this->form_validation->set_rules('reference', lang("reference"), 'trim|required');
        $this->form_validation->set_rules('date', lang("date"), 'trim|required');
        $this->form_validation->set_rules('customer', lang("customer"), 'trim');
        $check_out = $this->check_out_model->getStockOutByID($id);
        if($check_out->reference != $this->input->post('reference')) {
            $this->form_validation->set_rules('reference', lang("reference"), 'is_unique[check_out.reference]');
        }

        if ($this->form_validation->run() == true) {

            $quantity = "quantity";
            $product_id = "product_id";
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_qty = $_POST['quantity'][$r];
                if( $item_id && $item_qty ) {

                    if(!$this->check_out_model->getItemByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('check_out/add');
                    }

                    $items[] = array(
                        'item_id' => $item_id,
                        'quantity' => $item_qty,
                        );

                }
            }

            if (!isset($items) || empty($items)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($items);
            }

            $data = array( 'date' => $this->input->post('date'),
                'reference' => $this->input->post('reference'),
                'customer' => $this->input->post('customer'),
                'note' => $this->input->post('note'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H-i:s'),
            );

            if ($_FILES['attachment']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2000;
                $config['encrypt_name'] = TRUE;
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('attachment')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("check_out/eidt/".$id);
                }

                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;

            }

            // $this->tec->print_arrays($data, $items);

        }

        if ( $this->form_validation->run() == true && $this->check_out_model->updateStockOut($id, $data, $items)) {
            $this->session->set_flashdata('message', lang("check_out_updated"));
            redirect('check_out');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['check_out'] = $check_out;
            $items = array();
            if($check_out_items = $this->check_out_model->getAllOutItems($id)) {
                foreach ($check_out_items as $item) {
                    $row = $this->check_out_model->getItemByID($item->item_id);
                    $row->qty = $item->quantity;
                    $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
                }
                $items = json_encode($pr);
            }
            $this->data['items'] = $items;
            $this->data['customers'] = $this->check_out_model->getAllCustomers();
            $this->data['page_title'] = lang('edit_check_out');
            $this->page_construct('check_out/edit', $this->data);

        }
    }

    public function delete($id = NULL) {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('check_out');
        }
        if ($this->check_out_model->deleteStockOut($id)) {
            $this->session->set_flashdata('message', lang("check_out_deleted"));
            redirect("check_out");
        } else {
            $this->session->set_flashdata('error', lang("delete_failed"));
            redirect("check_out");
        }
    }

    function suggestions()
    {
        $warehouse_id               = $this->session->userdata('warehouse_id');
        $term = $this->input->get('term', TRUE);

        $rows = $this->check_out_model->getProductNames($term);
        if ($rows) {
            foreach ($rows as $row) {
                $qty_real        = $this->db->select('quantity')->from('item_warehouse')->where('item_id', $row->id)->where('warehouse_id', $warehouse_id)->get()->row_array();
                $row->qty_real = $qty_real['quantity'];
                $row->qty = 1;
                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    function customers()
    {
        $term = $this->input->get('term', TRUE);

        $rows = $this->check_out_model->getCustomers($term);
        if ($rows) {
            foreach ($rows as $row) {
                $cu[] = array('id' => $row->customer, 'label' => $row->customer, 'value' => $row->customer);
            }
            echo json_encode($cu);
        } else {
            echo NULL;
        }
    }

    public function export_excel(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $sheet->setCellValue('A1', "NO");
        $sheet->setCellValue('B1', "TANGGAL");
        $sheet->setCellValue('C1', "CUSTOMER");
        $sheet->setCellValue('D1', "NAMA PRODUK");
        $sheet->setCellValue('E1', "KUANTITI");
        $sheet->setCellValue('F1', "DIBUAT OLEH");
        $sheet->setCellValue('G1', "CATATAN");

        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        $sheet->getStyle('C1')->applyFromArray($style_col);
        $sheet->getStyle('D1')->applyFromArray($style_col);
        $sheet->getStyle('E1')->applyFromArray($style_col);
        $sheet->getStyle('F1')->applyFromArray($style_col);
        $sheet->getStyle('G1')->applyFromArray($style_col);

        $outbond = $this->db->select('check_out.date, check_out.note, customers.name as customer, items.name as item_name, check_out_items.quantity as quantity, users.first_name as first_name, users.last_name as last_name')
            ->from('check_out')
            ->join('check_out_items', 'check_out_items.check_out_id=check_out.id', 'left')
            ->join('item_warehouse', 'item_warehouse.id=check_out_items.item_warehouse_id', 'left')
            ->join('items', 'items.id=item_warehouse.item_id', 'left')
            ->join('customers', 'check_out.customer=customers.id', 'left')
            ->join('users', 'users.id=check_out.created_by', 'left')
            ->get()
            ->result();

        $no = 1;
        $numrow = 2;

        foreach ($outbond as $data) {
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->date);
            $sheet->setCellValue('C' . $numrow, $data->customer);
            $sheet->setCellValue('D' . $numrow, $data->item_name);
            $sheet->setCellValue('E' . $numrow, $data->quantity);
            $sheet->setCellValue('F' . $numrow, $data->first_name . ' ' . $data->last_name);
            $sheet->setCellValue('G' . $numrow, $data->note);


            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(35);
        $sheet->getColumnDimension('G')->setWidth(40);

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);

        $sheet->setTitle("OUTBOND");
        $filename = "OUTBOND - " . date('d-m-Y') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function check_out_view_v2()
    {
        if (isset($_POST['check_out_awb']))
        {
            if (!isset($_POST['order_id']))
            {
                $this->session->set_flashdata('warning', lang('Tidak ada Nomor AWB yang terscan'));
                redirect('check_out/check_out_view_v2');
            }

            $data_post_check_out = [
                'date'             => $_POST['date'],
                'reference'        => $_POST['reference'],
                'customer'         => empty($_POST['customer']) ? 0 : $_POST['customer'],
                'created_by'       => $this->session->userdata('user_id'),
                'note'             => $_POST['note'],
            ];

            $this->db->trans_begin();
            if ($this->db->insert('check_out', $data_post_check_out))
            {
                $check_out_id = $this->db->insert_id();
                foreach ($_POST['order_id'] as $key => $value)
                {
                    $product_id       = $_POST['product_id'][$key];
                    $product_quantity = $_POST['product_quantity'][$key];
                    $item_warehouse   = $this->db->select('id, quantity')->from('item_warehouse')->where('item_id', $product_id)->where('warehouse_id', $this->session->userdata('warehouse_id'))->get()->row_array();

                    $this->db->update('item_warehouse', [
                        'quantity' => ($product_quantity > $item_warehouse['quantity']) ? 0 : $item_warehouse['quantity'] - $product_quantity
                    ],[
                        'item_id'      => $product_id,
                        'warehouse_id' => $this->session->userdata('warehouse_id')
                    ]);

                    $this->db->update('sales', ['status' => 'waiting delivery', 'status_packing' => 'waiting delivery'], ['id' => $value]);

                    $data_post[$key] = [
                        'check_out_id'      => $check_out_id,
                        'item_warehouse_id' => $item_warehouse['id'],
                        'item_id'           => $product_id,
                        'warehouse_id'      => $this->session->userdata('warehouse_id'),
                        'quantity'          => $product_quantity
                    ];
                }
            }

            $this->db->insert_batch('check_out_items', $data_post);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return false;
            }
            $this->db->update('item_warehouse', [
                'quantity' => ($item['quantity'] > $product->quantity) ? $product->quantity - $product->quantity : $product->quantity - $item['quantity'],
            ], ['item_id' => $item['item_id'], 'warehouse_id' => $item['warehouse_id']]);

            $this->db->trans_commit();
            $this->session->set_flashdata('message', lang('Outbond successfully created'));
            redirect('check_out');
        }

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Add Outbond');
        $this->data['customers'] = $this->check_out_model->getAllCustomers();
        $this->data['warehouses'] = $this->check_out_model->getAllWarehouses();
        $this->data['reference'] = $this->check_out_model->generateReference();
        $this->page_construct('check_out/add_v2', $this->data);
    }

    public function suggestions_awb()
    {
        $awb   = $this->input->get('awb', TRUE);
        $sales = $this->db->select('awb_no, id, product_id, product_quantity')->from('sales')->where('awb_no', $awb)->where('status', 'process packing')->where('status_packing', 'process packing')->get()->result_array();
        if (!empty($sales))
        {
            $product_codes = array_column($sales, 'product_id');
            $products      = $this->db->select('id, code, name')->from('items')->where_in('code', $product_codes)->get()->result_array();
            foreach ($products as $key => $value) $product_with_code[$value['code']] = $value;
            foreach ($sales as $key => $value)
            {
                $value['product_code'] = $value['product_id'];
                $value['product_name'] = !empty($product_with_code[$value['product_code']]['name']) ? $product_with_code[$value['product_code']]['name'] : '';
                $value['product_id']   = !empty($product_with_code[$value['product_code']]['id']) ? $product_with_code[$value['product_code']]['id'] : '';
                $sales[$key]           = $value;
            }
            echo json_encode($sales);
        }
    }
}
