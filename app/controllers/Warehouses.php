<?php defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        $this->load->library(['form_validation', 'pagination']);
        $this->load->model(['warehouses_model', 'items_model']);
        $this->load->helper('function_helper');

        ini_set('display_errors', 1);
    }

    public function index()
    {
        $this->data['error']      = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Warehouses');
        $this->page_construct('warehouses/index', $this->data);
        
        if(!empty($_SESSION['message'])){unset($_SESSION['message']); }
    }

    public function get_warehouses()
    {
        $this->load->library('datatables');
        $this->datatables->select('warehouses.name AS warehouse_name, items.code, items.name AS product_name, item_warehouse.quantity');

        $this->datatables->from('item_warehouse');
        $this->datatables->join('warehouses', 'item_warehouse.warehouse_id = warehouses.id');
        $this->datatables->join('items', 'item_warehouse.item_id = items.id');
        $this->datatables->add_column("Actions", '', "id");
        echo $this->datatables->generate();
    }

    function alerts()
    {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('stock_alert');
        $bc = array(array('link' => '#', 'page' => lang('stock_alert')));
        $meta = array('page_title' => lang('stock_alert'), 'bc' => $bc);
        $this->page_construct('items/alerts', $this->data, $meta);
    }

    function get_alerts()
    {

        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('products') . ".id as pid, " . $this->db->dbprefix('products') . ".image as image, " . $this->db->dbprefix('products') . ".code as code, " . $this->db->dbprefix('products') . ".name as pname, type, " . $this->db->dbprefix('categories') . ".name as cname, quantity, alert_quantity, tax, tax_method, cost, price", FALSE)
            ->join('categories', 'categories.id=products.category_id')
            ->from('products')
            ->where('quantity < alert_quantity', NULL, FALSE)
            ->group_by('products.id');
        $this->datatables->add_column("Actions", "<div class='text-center'><a href='#' class='btn btn-xs btn-primary ap tip' data-id='$1' title='" . lang('add_to_purcahse_order') . "'><i class='fa fa-plus'></i></a></div>", "pid");
        $this->datatables->unset_column('pid');
        echo $this->datatables->generate();
    }

    public function add()
    {


        $this->form_validation->set_rules('name', lang("name"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
            );
        }

        if ($this->form_validation->run() == true && $this->warehouses_model->addWarehouse($data)) {
            $this->session->set_flashdata('message', lang("warehouses_added"));
           
            redirect('warehouses');

        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['categories'] = $this->warehouses_model->fetch_warehouses();
            $this->data['page_title'] = lang('add');
            $this->page_construct('warehouses/add', $this->data);
        }
    }

    public function edit($id)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('warehouses');
        }


        $this->form_validation->set_rules('name', lang("name"), 'trim|required');

        $warehouses = $this->warehouses_model->getItemByID($id);


        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['max_width'] = '800';
                $config['max_height'] = '800';
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("items/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;
            }
        }

        if ($this->form_validation->run() == true && $this->warehouses_model->updateWarehouse($id, $data)) {
            $this->session->set_flashdata('message', lang("warehouses_updated"));
            redirect('warehouses');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['warehouses'] = $warehouses;
            $this->data['page_title'] = lang('edit_warehouses');
            $this->page_construct('warehouses/edit', $this->data);
        }
    }

    function import()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('items');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('items');
            }

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
                    redirect("items/import");
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

                $keys = array('name');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 1001) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("items/import");
                }

                foreach ($final as $csv_pr) {
                    $category = FALSE;
                    if ($this->items_model->getItemByCode($csv_pr['code'])) {
                        $this->session->set_flashdata('error', lang("check_item_code") . " (" . $csv_pr['code'] . "). " . lang("code_already_exist"));
                        redirect("items/import");
                    }
                    if (!empty($csv_pr['category'])) {
                        if (!($category = $this->items_model->getCategoryByCode($csv_pr['category']))) {
                            $this->session->set_flashdata('error', lang("check_category") . " (" . $csv_pr['category'] . "). " . lang("category_x_already_exist"));
                            redirect("items/import");
                        }
                    }
                    $data[] = array(

                        'name' => $csv_pr['name'],

                    );
                }
                // $this->tec->print_arrays($data);
            }
        }

        if ($this->form_validation->run() == true && $this->warehouses_models->addWarehouses($data)) {

            $this->session->set_flashdata('message', lang("items_added"));
            redirect('items');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('import_items');
            $this->page_construct('items/import', $this->data);
        }
    }

    public function delete($id = NULL)
    {
        return false;
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('warehouses');
        }
        if ($this->warehouses_model->deleteWarehouse($id)) {
            $this->session->set_flashdata('message', lang("item_deleted"));
            redirect("warehouses");
        } else {
            $this->session->set_flashdata('error', lang("delete_failed"));
            redirect("warehouses");
        }
    }

    /* -------------------------------------------------------------------------------- */


    function item_barcode($item_code = NULL, $bcs = 'code39', $height = 60)
    {
        if ($this->input->get('code')) {
            $item_code = $this->input->get('code');
        }
        return "<img src='" . base_url() . "items/gen_barcode/{$item_code}/{$bcs}/{$height}' alt='{$item_code}' />";
    }

    function gen_barcode($item_code = NULL, $bcs = 'code39', $height = 60, $text = 1)
    {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $item_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }


    function print_barcodes($per_page = 0)
    {

        $this->load->library('pagination');
        if ($this->input->get('per_page')) {
            $per_page = $this->input->get('per_page');
        }

        $config['base_url'] = site_url('items/print_barcodes');
        $config['total_rows'] = $this->items_model->items_count();
        $config['per_page'] = 16;
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
        $r = 1;
        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        foreach ($items as $pr) {
            if ($r != 1) {
                $rw = (bool)($r & 1);
                $html .= $rw ? '</tr><tr>' : '';
            }
            $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $pr->name . '</strong><br>' . $this->item_barcode($pr->code, $pr->barcode_symbology, 60) . '</td>';
            $r++;
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'items/print_barcodes', $this->data);
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

        foreach ($items as $pr) {
            $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->item_barcode($pr->code, $pr->barcode_symbology, 25) . '</div>';
        }

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme . 'items/print_labels', $this->data);
    }

    function single_barcode($item_id = NULL)
    {
        $item = $this->items_model->getItemByID($item_id);
        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        if ($item->quantity > 0) {
            for ($r = 1; $r <= $item->quantity; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 60) . '</td>';
            }
        } else {
            for ($r = 1; $r <= 16; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 60) . '</td>';
            }
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'items/single_barcode', $this->data);
    }

    function single_label($item_id = NULL, $warehouse_id = NULL)
    {
        $item = $this->items_model->getItemByID($item_id);
        $html = "";
        if ($item->quantity > 0) {
            for ($r = 1; $r <= $item->quantity; $r++) {
                $html .= '<div class="labels"><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 25) . '</div>';
            }
        } else {
            for ($r = 1; $r <= 10; $r++) {
                $html .= '<div class="labels"><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 25) . '</div>';
            }
        }
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'items/single_label', $this->data);
    }

    function report($item_id)
    {
        $this->data['item'] = $this->items_model->getItemByID($item_id);
        $this->data['checkins'] = $this->items_model->getCheckIns($item_id, 5);
        $this->data['checkouts'] = $this->items_model->getCheckOuts($item_id, 5);
        $this->data['page_title'] = lang("item_report");
        $this->load->view($this->theme . 'items/view', $this->data);
    }
}
