<?php defined('BASEPATH') or exit('No direct script access allowed');

class Letter extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        $this->load->library(['form_validation']);
        $this->load->model(['items_model', 'warehouses_model', 'sales_model', 'letter_model']);

        ini_set('display_errors', 1);
    }

    public function travelIndex()
    {
        set_cookie('ci_csrf_token', 'ci_csrf_token', 128000);

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Travel Document');
        $this->page_construct('letter/index', $this->data);
        if (!empty($_SESSION['message'])) {
            unset($_SESSION['message']);
        }   
    }

    public function get_letter_travel()
    {
        $this->load->library('datatables');

        if($this->session->userdata('warehouse_id') != null){

            $this->datatables
            ->select($this->db->dbprefix('travel_document').".id, courier, note, created_at")
            ->from('travel_document')
            ->where('warehouse_id', $this->session->userdata('warehouse_id'));
        } else {
            $this->datatables
            ->select($this->db->dbprefix('travel_document').".id, courier, note, created_at")
            ->from('travel_document');
        }
        
        $this->datatables->add_column("Actions", "id");
        echo $this->datatables->generate();
    }

    public function travelAdd() 
    {
        $this->form_validation->set_rules('courier', lang("courier"), 'trim|required');
        
        if ($this->form_validation->run() == true) {
            
            $data = array( 
                'created_at' => $this->input->post('date'),
                'courier' => $this->input->post('courier'),
                'note' => $this->input->post('note'),
                'warehouse_id' => $this->session->userdata('warehouse_id') != null ? $this->session->userdata('warehouse_id')  :$this->input->post('warehouse_id') ,
            );

            if($this->db->insert('travel_document', $data)) {
                $travelId = $this->db->insert_id();
                $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
                for ($r = 0; $r < $i; $r++) {
                
                    $awbNo = $_POST['product_id'][$r];
                    $this->db->update('sales', array('status_packing' => 'sent'), array('awb_no' => $awbNo));
                        $items[] = array(
                            'travel_document_id' => $travelId,
                            'awb' => $awbNo,
                        );
                }
                $this->db->insert_batch('detail_travel_document', $items);
            }
            
            if (!isset($items) || empty($items)) {
                $this->form_validation->set_rules('awb_no', lang("sales"), 'required');
            } else {
                krsort($items);
            }

            

            $this->session->set_flashdata('message', lang("Letter added"));
            redirect('letter/travelIndex');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('Add Travel Document');
            $this->data['sales'] = $this->letter_model->getAllSales();
            $this->data['warehouses'] = $this->warehouses_model->fetch_warehouses();
            $this->page_construct('letter/add', $this->data);
    
        }

       
    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);

        $rows = $this->letter_model->getAwbNames($term);
        if ($rows) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $pr[] = array('id' => $row->id, 'awb_no' => $row->awb_no . " (" . $row->receiver_name . ")", 'row' => $row);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'awb_no' => lang('no_match_found'), 'value' => $term)));
        }
    }

    public function print_letter($id){
        $this->data['letter'] = $this->letter_model->getTravelDoc($id);
        $this->data['page_title'] = lang("Print Travel Document");
        $this->load->view($this->theme . 'letter/print', $this->data);
    }
}