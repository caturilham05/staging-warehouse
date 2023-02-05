<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sales extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        $this->load->library(['form_validation']);
        $this->load->model(['items_model', 'warehouses_model', 'sales_model', 'address_books_model']);
        $this->load->helper('function_helper');

        ini_set('display_errors', 1);
    }

    public function convertDate($month){
        switch ($month) {
            case 'Januari':
                return 'January';
                break;
            case 'Februari':
                return 'February';
                break;
            case 'Maret':
                return 'March';
                break;
            case 'April':
                return 'April';
                break;
            case 'Mei':
                return 'May';
                break;
            case 'Juni':
                return 'June';
                break;
            case 'Juli':
                return 'July';
                break;
            case 'Agustus':
                return 'August';
                break;
            case 'September':
                return 'September';
                break;
            case 'Oktober':
                return 'October';
                break;
            case 'November':
                return 'November';
                break;
            case 'Desember':
                return 'December';
                break;
            default:
               return 'January';
                break;
        }
    }

    public function index()
    {   
        
        

        set_cookie('ci_csrf_token', 'ci_csrf_token', 128000);

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Sales');
        $this->data['warehouses'] = $this->warehouses_model->fetch_warehouses();
        $this->page_construct('sales/index', $this->data);

        if (!empty($_SESSION['message'])) {
            unset($_SESSION['message']);
        }
    }

    public function get_warehouses(){
        $warehouses = $this->warehouses_model->fetch_warehouses();
        echo json_encode($warehouses);
    }

    public function get_sales($alerts = NULL)
    {
        $warehouse_id = $this->session->userdata('warehouse_id');

        $this->load->library('datatables');

        $query = null;
        if($warehouse_id != null) {
            $query = $this->datatables
            ->select("sales.id, order_no, awb_no, no_referensi, courier, status, service, type, created_date, dispatch_date, delivered_date, returned_date, package_price, insurance, shipping_price, shipping_cashback, cod_value, cod_fee, cod_disbursement, shipper_name, shipper_phone, shipper_address, shipper_city, shipper_subdistrict, shipper_zip_code, receiver_name, receiver_phone, receiver_address, receiver_city, receiver_subdistrict, receiver_zip_code, goods_description, quantity, weight, dimension_size, shipping_note, last_tracking_status, status_packing,group_concat(concat(" . $this->db->dbprefix('items') . ".name) SEPARATOR '<br>') as product_name," . $this->db->dbprefix('warehouses') . '.name as warehouse_name')
            ->from('sales')
            ->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
            ->join('items', 'items.code=sales.product_id', 'left')
            ->group_by('sales.awb_no')
            ->where('sales.deleted_at', NULL)
            ->where('warehouse_id', $this->session->userdata('warehouse_id'));
        } else {
            $query = $this->datatables
            ->select("sales.id, order_no, awb_no, no_referensi, courier, status, service, type, created_date, dispatch_date, delivered_date, returned_date, package_price, insurance, shipping_price, shipping_cashback, cod_value, cod_fee, cod_disbursement, shipper_name, shipper_phone, shipper_address, shipper_city, shipper_subdistrict, shipper_zip_code, receiver_name, receiver_phone, receiver_address, receiver_city, receiver_subdistrict, receiver_zip_code, goods_description, quantity, weight, dimension_size, shipping_note, last_tracking_status, status_packing,group_concat(concat(" . $this->db->dbprefix('items') . ".name) SEPARATOR '<br>') as product_name," . $this->db->dbprefix('warehouses') . '.name as warehouse_name')
            ->from('sales')
            ->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
            ->join('items', 'items.code=sales.product_id', 'left')
            ->group_by('sales.awb_no')
            ->where('sales.deleted_at', NULL);
        }

        if($_POST['filter'] != '') {
            if($_POST['filter'] == 'not_sent'){
                $_POST['filter'] = NULL;
            } 
            $query->where('status_packing', $_POST['filter']);
        } 
        if($_POST['filter_date'] != ''){
            $query->where('DATE(created_date)', $_POST['filter_date']);
        }
        if($_POST['filter_warehouse'] != '') {
            
            $query->where('warehouse_id', $_POST['filter_warehouse']);
        } 
        $query->add_column("Actions", "id");
        // $query->add_column("Actions", $links, "id");
        echo $query->generate();
    }

    public function add()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('add');
        $this->page_construct('sales/add', $this->data);
    }

    public function addSales()
    {
        $link = $this->input->post('link');
     
        if (empty($link)) {
            $this->session->set_flashdata('error', lang("Link is required"));
            redirect("sales");
        }

        if ($this->sales_model->addSales($link)) {
            $this->session->set_flashdata('message', lang("sales_added"));
            redirect('sales');
        } else {
            $this->session->set_flashdata('message', lang("Gagal menambahkan data"));
            redirect('sales');
        }
    }

    public function deleteSales($id = null)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect("sales");
        }
        if ($this->sales_model->delete($id)) {
            $this->session->set_flashdata('message', lang("item_deleted"));
            redirect("sales");
        } else {
            $this->session->set_flashdata('error', lang("delete_failed"));
            redirect("sales");
        }
    }

    public function update_status_packing($id = null)
    {


        $query = $this->sales_model->update_status_packing($id);
        if ($query) {
            $this->session->set_flashdata('message', lang("Status Packing Updated"));
            redirect("sales");
        } else {
            $this->session->set_flashdata('error', lang("Status Packing Failed Update"));
            redirect("sales");
        }
    }

    public function getSalesbyCode($awb = null)
    {
        $query =  $this->db->get_where('sales', array('awb_no' => $awb))->row();
        if($query != null){
                $update = $this->db->update('sales', array('status_packing' => 'sent'), array('awb_no' => $awb));
                if($update){
                    echo json_encode(['status' => true, 'icon'=> 'success', 'message' => 'Nomor Resi ' . $awb . ' berhasil diubah']);
                } else {
                    echo json_encode(['status' => false, 'icon'=> 'error', 'message' => 'Nomor Resi ' . $awb . ' gagal diubah']);
                }
        } else {
            echo json_encode(['status' => false, 'icon'=> 'warning', 'message' => 'Nomor Resi ' . $awb . ' tidak ditemukan']);
        }
    }

    function print_labels($per_page = 0)
    {

        $this->load->library('pagination');
        if ($this->input->get('per_page')) {
            $per_page = $this->input->get('per_page');
        }

        $config['base_url'] = site_url('items/print_labels');
        $config['total_rows'] = $this->items_model->items_count();
        $config['per_page'] = 10;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $items = $this->items_model->fetch_items($config['per_page'], $per_page);

        $html = "";

        // foreach ($items as $pr) {
        //     $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->item_barcode($pr->code, $pr->barcode_symbology, 25) . '</div>';
        // }

        $this->data['sales'] = $this->db->get('sales')->result();
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme . 'sales/print_labels', $this->data);
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
            ],

            'numberFormat' => [
                'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            ]

        ];

        $sheet->setCellValue('A1', "NO");
        $sheet->setCellValue('B1', "WAREHOUSE");
        $sheet->setCellValue('C1', "ORDER NO");
        $sheet->setCellValue('D1', "AWB NO");
        $sheet->setCellValue('E1', "NO REFERENSI");
        $sheet->setCellValue('F1', "COURIER");
        $sheet->setCellValue('G1', "SERVICE");
        $sheet->setCellValue('H1', "TYPE");
        $sheet->setCellValue('I1', "STATUS");
        $sheet->setCellValue('J1', "CREATED DATE");
        $sheet->setCellValue('K1', "DISPATCH DATE");
        $sheet->setCellValue('L1', "DELIVERED DATE");
        $sheet->setCellValue('M1', "RETURNED DATE");
        $sheet->setCellValue('N1', "PACKAGE DATE");
        $sheet->setCellValue('O1', "INSURANCE");
        $sheet->setCellValue('P1', "SHIPPING PRICE");
        $sheet->setCellValue('Q1', "SHIPPING CASHBACK");
        $sheet->setCellValue('R1', "COD VALUE");
        $sheet->setCellValue('S1', "COD FEE");
        $sheet->setCellValue('T1', "COD DIBURSEMENT");
        $sheet->setCellValue('U1', "SHIPPER NAME");
        $sheet->setCellValue('V1', "SHIPPER PHONE");
        $sheet->setCellValue('W1', "SHIPPER ADDRESS");
        $sheet->setCellValue('X1', "SHIPPER CITY");
        $sheet->setCellValue('Y1', "SHIPPER SUBDISTRICT");
        $sheet->setCellValue('Z1', "SHIPPER ZIP CODE");
        $sheet->setCellValue('AA1', "RECEIVER NAME");
        $sheet->setCellValue('AB1', "RECEIVER PHONE");
        $sheet->setCellValue('AC1', "RECEIVER ADDRESS");
        $sheet->setCellValue('AD1', "RECEIVER CITY");
        $sheet->setCellValue('AE1', "RECEIVER SUB DISTRICT");
        $sheet->setCellValue('AF1', "RECEIVER ZIP CODE");
        $sheet->setCellValue('AG1', "GOODS DESCRIPTION");
        $sheet->setCellValue('AH1', "QUANTITY");
        $sheet->setCellValue('AI1', "WEIGHT (KG)");
        $sheet->setCellValue('AJ1', "LENGTH x WIDTH x HEIGHT");
        $sheet->setCellValue('AK1', "SHIPPING NOTE");
        $sheet->setCellValue('AL1', "LAST TRACKING STATUS");


        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        $sheet->getStyle('C1')->applyFromArray($style_col);
        $sheet->getStyle('D1')->applyFromArray($style_col);
        $sheet->getStyle('E1')->applyFromArray($style_col);
        $sheet->getStyle('F1')->applyFromArray($style_col);
        $sheet->getStyle('G1')->applyFromArray($style_col);
        $sheet->getStyle('H1')->applyFromArray($style_col);
        $sheet->getStyle('I1')->applyFromArray($style_col);
        $sheet->getStyle('J1')->applyFromArray($style_col);
        $sheet->getStyle('K1')->applyFromArray($style_col);
        $sheet->getStyle('L1')->applyFromArray($style_col);
        $sheet->getStyle('M1')->applyFromArray($style_col);
        $sheet->getStyle('N1')->applyFromArray($style_col);
        $sheet->getStyle('O1')->applyFromArray($style_col);
        $sheet->getStyle('P1')->applyFromArray($style_col);
        $sheet->getStyle('Q1')->applyFromArray($style_col);
        $sheet->getStyle('R1')->applyFromArray($style_col);
        $sheet->getStyle('S1')->applyFromArray($style_col);
        $sheet->getStyle('T1')->applyFromArray($style_col);
        $sheet->getStyle('U1')->applyFromArray($style_col);
        $sheet->getStyle('V1')->applyFromArray($style_col);
        $sheet->getStyle('W1')->applyFromArray($style_col);
        $sheet->getStyle('X1')->applyFromArray($style_col);
        $sheet->getStyle('Y1')->applyFromArray($style_col);
        $sheet->getStyle('Z1')->applyFromArray($style_col);
        $sheet->getStyle('AA1')->applyFromArray($style_col);
        $sheet->getStyle('AB1')->applyFromArray($style_col);
        $sheet->getStyle('AC1')->applyFromArray($style_col);
        $sheet->getStyle('AD1')->applyFromArray($style_col);
        $sheet->getStyle('AE1')->applyFromArray($style_col);
        $sheet->getStyle('AF1')->applyFromArray($style_col);
        $sheet->getStyle('AG1')->applyFromArray($style_col);
        $sheet->getStyle('AH1')->applyFromArray($style_col);
        $sheet->getStyle('AI1')->applyFromArray($style_col);
        $sheet->getStyle('AJ1')->applyFromArray($style_col);
        $sheet->getStyle('AK1')->applyFromArray($style_col);
        $sheet->getStyle('AL1')->applyFromArray($style_col);


        $sales = $this->db->select('sales.*, warehouses.name as warehouse_name')
            ->from('sales')
            ->join('warehouses', 'sales.warehouse_id=warehouses.id', 'left')
            ->get()
            ->result();
        // echo "<pre>" . print_r($sales, true) . "</pre>";
        // die;
        $no = 1;
        $numrow = 2;

        foreach ($sales as $data) {
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->warehouse_name);
            $sheet->setCellValue('C' . $numrow, $data->order_no);
            $sheet->setCellValue('D' . $numrow, $data->awb_no . ' ');
            $sheet->setCellValue('E' . $numrow, $data->no_referensi);
            $sheet->setCellValue('F' . $numrow, $data->courier);
            $sheet->setCellValue('G' . $numrow, $data->service);
            $sheet->setCellValue('H' . $numrow, $data->type);
            $sheet->setCellValue('I' . $numrow, $data->status);
            $sheet->setCellValue('J' . $numrow, ($data->created_date != '0000-00-00 00:00:00') ? date_format(date_create($data->created_date), 'd F Y H:i') : '-');
            $sheet->setCellValue('K' . $numrow, ($data->dispatch_date != '0000-00-00 00:00:00') ? date_format(date_create($data->dispatch_date), 'd F Y H:i') : '-');
            $sheet->setCellValue('L' . $numrow, ($data->delivered_date != '0000-00-00 00:00:00') ? date_format(date_create($data->delivered_date), 'd F Y H:i') : '-');
            $sheet->setCellValue('M' . $numrow, ($data->returned_date != '0000-00-00 00:00:00') ? date_format(date_create($data->returned_date), 'd F Y H:i') : '-');
            $sheet->setCellValue('N' . $numrow, $data->package_price);
            $sheet->setCellValue('O' . $numrow, $data->insurance);
            $sheet->setCellValue('P' . $numrow, $data->shipping_price);
            $sheet->setCellValue('Q' . $numrow, $data->shipping_cashback);
            $sheet->setCellValue('R' . $numrow, $data->cod_value);
            $sheet->setCellValue('S' . $numrow, $data->cod_fee);
            $sheet->setCellValue('T' . $numrow, $data->cod_disbursement);
            $sheet->setCellValue('U' . $numrow, $data->shipper_name);
            $sheet->setCellValue('V' . $numrow, $data->shipper_phone);
            $sheet->setCellValue('W' . $numrow, $data->shipper_address);
            $sheet->setCellValue('X' . $numrow, $data->shipper_city);
            $sheet->setCellValue('Y' . $numrow, $data->shipper_subdistrict);
            $sheet->setCellValue('Z' . $numrow, $data->shipper_zip_code);
            $sheet->setCellValue('AA' . $numrow, $data->receiver_name);
            $sheet->setCellValue('AB' . $numrow, $data->receiver_phone);
            $sheet->setCellValue('AC' . $numrow, $data->receiver_address);
            $sheet->setCellValue('AD' . $numrow, $data->receiver_city);
            $sheet->setCellValue('AE' . $numrow, $data->receiver_subdistrict);
            $sheet->setCellValue('AF' . $numrow, $data->receiver_zip_code);
            $sheet->setCellValue('AG' . $numrow, $data->goods_description);
            $sheet->setCellValue('AH' . $numrow, $data->quantity);
            $sheet->setCellValue('AI' . $numrow, $data->weight);
            $sheet->setCellValue('AJ' . $numrow, $data->dimension_size);
            $sheet->setCellValue('AK' . $numrow, $data->shipping_note);
            $sheet->setCellValue('AL' . $numrow, $data->last_tracking_status);

            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('R' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('S' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('T' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('U' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('V' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('W' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('X' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('Y' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('Z' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AA' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AB' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AC' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AD' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AE' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AF' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AG' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AH' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AI' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AJ' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AK' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('AL' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(25);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(25);
        $sheet->getColumnDimension('Q')->setWidth(25);
        $sheet->getColumnDimension('R')->setWidth(25);
        $sheet->getColumnDimension('S')->setWidth(25);
        $sheet->getColumnDimension('T')->setWidth(25);
        $sheet->getColumnDimension('U')->setWidth(25);
        $sheet->getColumnDimension('V')->setWidth(25);
        $sheet->getColumnDimension('W')->setWidth(35);
        $sheet->getColumnDimension('X')->setWidth(25);
        $sheet->getColumnDimension('Y')->setWidth(25);
        $sheet->getColumnDimension('Z')->setWidth(20);
        $sheet->getColumnDimension('AA')->setWidth(30);
        $sheet->getColumnDimension('AB')->setWidth(30);
        $sheet->getColumnDimension('AC')->setWidth(30);
        $sheet->getColumnDimension('AD')->setWidth(30);
        $sheet->getColumnDimension('AE')->setWidth(30);
        $sheet->getColumnDimension('AF')->setWidth(30);
        $sheet->getColumnDimension('AG')->setWidth(30);
        $sheet->getColumnDimension('AH')->setWidth(30);
        $sheet->getColumnDimension('AI')->setWidth(30);
        $sheet->getColumnDimension('AJ')->setWidth(30);
        $sheet->getColumnDimension('AK')->setWidth(30);
        $sheet->getColumnDimension('AL')->setWidth(30);


        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);

        $sheet->setTitle("SALES");
        $filename = "SALES - " . date('d-m-Y') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function sales_add_manually_view()
    {
        $this->load->helper('function_helper');
        $post = [
            'order_no'               => '',
            'awb_no'                 => '',
            'warehouse_id'           => 0,
            'shipper_id'             => 0,
            'courier'                => '',
            'service'                => '',
            'type'                   => '',
            'package_price'          => 0,
            'shipping_price'         => 0,
            'shipping_note'          => '',
            'receiver_name'          => '',
            'receiver_phone'         => '',
            'receiver_address'       => '',
            'receiver_city'          => '',
            'receiver_subdistrict'   => '',
            'receiver_zip_code'      => 0,
            'goods_description'      => '',
            'weight'                 => 0,
            'dimension_size'         => 0,
            'product_code'             => 0,
            'product_quantity'       => 0
        ];

        $config = array(
            array(
                'field' => 'order_no',
                'label' => 'Order Invoice',
                'rules' => 'trim|required|is_unique[sales.order_no]'
            ),
            array(
                'field' => 'awb_no',
                'label' => 'Airway Bill',
                'rules' => 'trim|required|is_unique[sales.awb_no]'
            ),
            array(
                'field' => 'warehouse_id',
                'label' => 'Warehouse',
                'rules' => 'required'
            ),
            array(
                'field' => 'shipper_id',
                'label' => 'Shipper',
                'rules' => 'required'
            ),
            array(
                'field' => 'courier',
                'label' => 'Courier Name',
                'rules' => 'required'
            ),
            array(
                'field' => 'service',
                'label' => 'Service',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'required'
            ),
            array(
                'field' => 'package_price',
                'label' => 'Package Price',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'shipping_price',
                'label' => 'Shipping Price',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'receiver_name',
                'label' => 'Receiver Name',
                'rules' => 'required'
            ),
            array(
                'field' => 'receiver_phone',
                'label' => 'Receiver Phone',
                'rules' => 'trim|required|min_length[5]|max_length[13]|numeric|is_unique[sales.receiver_phone]'
            ),
            array(
                'field' => 'receiver_city',
                'label' => 'Receiver City',
                'rules' => 'required'
            ),
            array(
                'field' => 'receiver_subdistrict',
                'label' => 'Receiver Subdistrict',
                'rules' => 'required'
            ),
            array(
                'field' => 'receiver_zip_code',
                'label' => 'Receiver Zip Code',
                'rules' => 'required'
            ),
            array(
                'field' => 'receiver_address',
                'label' => 'Receiver Address',
                'rules' => 'required'
            ),
            array(
                'field' => 'shipping_note',
                'label' => 'Shipping Note',
                'rules' => 'required'
            ),
            array(
                'field' => 'product_code[]',
                'label' => 'Product',
                'rules' => 'required'
            ),
            array(
                'field' => 'product_quantity[]',
                'label' => 'Qty',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'weight[]',
                'label' => 'Weight (KG)',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'dimension_size[]',
                'label' => 'Length',
                'rules' => 'required'
            ),
            array(
                'field' => 'goods_description[]',
                'label' => 'Goods Description',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('is_unique', '<span style="color: #fff;"><b>{field} Sudah Terpakai</b></span>');
        $this->form_validation->set_message('required', '<span style="color: #fff;"><b>{field} tidak boleh kosong</b></span>');
        if ($this->form_validation->run() == false)
        {   
            set_cookie('ci_csrf_token', 'ci_csrf_token', 128000);
            $this->data['error']         = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['warehouses']    = $this->warehouses_model->fetch_warehouses();
            $this->data['address_books'] = $this->address_books_model->fetch_address_books();
            $this->data['items']         = $this->items_model->fetch_items_all();
            $this->data['page_title']    = lang('Sales Add Manually');
            // $this->data['alert']         = $alert;
            // $this->data['hidden_form']   = $hidden_form;
            $this->page_construct('sales/add_manually', $this->data);

            if (!empty($_SESSION['message'])) {
              unset($_SESSION['message']);
            }
        }
        else
        {
            $this->process();
        }

    }
    
    public function process()
    {
        $this->load->helper('function_helper');
        $post = $this->input->post(null, true);
        if (isset($_POST['add']))
        {
            $insert = $this->sales_model->add_sales_manually($post);

            if (empty($insert))
            {
                $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
                redirect("sales");
                return false;
            }

            $this->session->set_flashdata('message', lang("Order berhasil ditambahkan"));
            redirect('sales');
            return true;
        }
    }

    public function sales_add_import_excel_view()
    {
        set_cookie('ci_csrf_token', 'ci_csrf_token', 128000);
        $this->data['error']         = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses']    = $this->warehouses_model->fetch_warehouses();
        $this->data['page_title']    = lang('Sales Add Import Excel');
        $this->page_construct('sales/add_import_excel', $this->data);

        if (!empty($_SESSION['message'])) {
          unset($_SESSION['message']);
        }
        
        // if (is_uploaded_file($_FILES['sales_import_excel']['tmp_name']))
        // {
        //     print_custom([$_POST, $_FILES]);        
        // }
    }

    public function upload_config($path)
    {
        if (!is_dir($path)) mkdir($path, 0777, TRUE);       
        $config['upload_path']      = './'.$path;       
        $config['allowed_types']    = 'csv|CSV|xlsx|XLSX|xls|XLS';
        $config['max_filename']     = '255';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = 4096; 
        $this->load->library('upload', $config);
    }

    public function proses_import_excel()
    {
        $path = 'uploads/import_excel/';
        $json = [];
        $this->upload_config($path);
        if (!$this->upload->do_upload('sales_import_excel'))
        {
            $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
            redirect("sales/sales_add_import_excel_view");
            return false;
        }
        else
        {
            $file_data = $this->upload->data();
            $file_name = $path.$file_data['file_name'];
            $arr_file  = explode('.', $file_name);
            $extension = end($arr_file);

            if ('csv' == $extension)
            {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            }
            else
            {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($file_name);
            $sheet_data  = $spreadsheet->getActiveSheet()->toArray();
            $list        = [];
            $invoice     = invoice_generate();

            foreach($sheet_data as $key => $value)
            {
                if($key != 0)
                {
                    $address_book    = $this->address_books_model->address_books_by_id($value[7]);
                    $master_location = $this->db->get_where('master_locations', ['id' => $address_book['location_id']], 1)->row_array();
                    $list[]          = [
                        'warehouse_id'         => $this->warehouses_model->getWarehouseByName($value[0]),
                        'order_no'             => $invoice,
                        'awb_no'               => $value[1],
                        'courier'              => $value[2],
                        'service'              => $value[3],
                        'type'                 => $value[4],
                        'package_price'        => $value[5],
                        'shipping_price'       => $value[6],
                        'shipper_id'           => $address_book['id'],
                        'shipper_name'         => $value[7],
                        'shipper_phone'        => $address_book['phone'],
                        'shipper_address'      => $address_book['address'],
                        'shipper_city'         => $master_location['title'],
                        'shipper_subdistrict'  => $master_location['detail'],
                        'shipper_zip_code'     => $master_location['postcode'],
                        'receiver_name'        => $value[8],
                        'receiver_phone'       => $value[9],
                        'receiver_address'     => $value[10],
                        'receiver_city'        => $value[11],
                        'receiver_subdistrict' => $value[12],
                        'receiver_zip_code'    => $value[13],
                        'goods_description'    => $value[14],
                        'weight'               => $value[15],
                        'dimension_size'       => $value[16],
                        'shipping_note'        => $value[17],
                        'last_tracking_status' => $value[18],
                        'product_id'           => $value[19],
                        'product_quantity'     => $value[20],
                    ];
                }
            }

            if(file_exists($file_name)) unlink($file_name);

            if(count($list) > 0)
            {
                $insert = $this->sales_model->add_sales_manually($list, 1);
                if (empty($insert))
                {
                    $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
                    redirect("sales/sales_add_import_excel_view");
                    return false;
                }

                $this->session->set_flashdata('message', lang("Order berhasil ditambahkan"));
                redirect("sales");
                return true;
            }
            else
            {
                $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
                redirect("sales/sales_add_import_excel_view");
                return false;
            }
        }
    }
}
