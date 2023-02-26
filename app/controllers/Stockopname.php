<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stockopname extends MY_Controller
{
  function __construct()
  {
    parent::__construct();

    if (!$this->loggedIn) {
        $this->session->set_flashdata('warning', lang('access_denied'));
        redirect('logout');
    }

    $this->load->helper('function_helper');
    $this->load->library(['form_validation', 'pagination', 'datatables']);
    $this->load->model(['warehouses_model', 'items_model', 'stockopname_model', 'check_out_model']);

    ini_set('display_errors', 1);
  }

  public function index()
  {
    $this->data['error']       = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['page_title']  = lang('Stockopname List');
    $this->data['stockopname'] = $this->stockopname_model->get_stockopname_all();
    $this->page_construct('stockopname/index', $this->data);
    
    if(!empty($_SESSION['message'])){unset($_SESSION['message']); }
  }

  public function stock_opname_json()
  {
    if ($this->Admin) {
        $links .= "<div class='btn-group' role='group'>
						          <a class=\"btn btn-warning btn-xs tip\" title='" . lang("edit_item") . "' href='" . site_url('items/edit/$1') . "'>
						          	<i class=\"fa fa-edit\"></i>
						          </a>
						        </div>";
    }
    $links .= "</div>";
    $q = $this->datatables->select(
    	'stock_opname.id,
    	stock_opname.stock_opname,
    	stock_opname.warehouse_id,
    	stock_opname.qty,
    	stock_opname.qty_real_total,
    	stock_opname.notes,
    	stock_opname.created_at,
    	warehouses.name,
    	stock_opname.status'
    )->from('stock_opname')->join('warehouses', 'warehouses.id = stock_opname.warehouse_id');
    if (!$this->Admin) $q->where('warehouses.id', $this->session->userdata('warehouse_id'));
    $this->datatables->add_column('Actions', $links, 'id');
    echo $this->datatables->generate();
  }

  public function stock_opname_add_view()
  {
    if (!empty($_SESSION['message']) || !empty($_SESSION['error'])) {
      unset($_SESSION['message']);
      unset($_SESSION['error']);
    }

		$so_process_exist = $this->db->select('id, status')->from('stock_opname')->where('status', 1)->where('warehouse_id', $this->session->userdata('warehouse_id'))->get()->row_array();
		if (!empty($so_process_exist))
		{
      $this->session->set_flashdata('warning', lang("Masih ada stock opname yang harus diproses, anda otomatis diarahkan ke halaman Continue Process Stock Opname"));
      redirect('stockopname/stock_opname_process_view/'.$so_process_exist['id']);
		}

		if (!empty($this->session->userdata('warehouse_id')))
		{
			$this->db->insert('stock_opname', ['stock_opname' => stock_opname_generate(), 'warehouse_id' => $this->session->userdata('warehouse_id'), 'status' => 1]);

			$so_id   = $this->db->insert_id();
			$so_data = $this->db->select('stock_opname, warehouse_id, status')->from('stock_opname')->where('id', $so_id)->get()->row_array();
		}

		if (empty($so_data))
		{
      $this->session->set_flashdata('error', lang("Stock opname tidak ditemukan"));
      redirect('stockopname');
      return;
		}

		redirect('stockopname/stock_opname_process_view/'.$so_id);

		$config = array(
			array(
				'field' => 'product_code',
				'label' => 'Product Code',
				'rules' => 'required'
			),
			array(
				'field' => 'qty',
				'label' => 'Qty',
				'rules' => 'required|numeric'
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', '<span style="color: #fff;"><b>{field} tidak boleh kosong</b></span>');
		$this->form_validation->set_message('numeric', '<span style="color: #fff;"><b>{field} harus berupa angka / number</b></span>');
		
		if ($this->form_validation->run() == false)
		{
			$so_data['id']						= $so_id;
	    $this->data['error']      = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	    $this->data['page_title'] = lang('Create Stock Opname');
	    $this->data['warehouse']  = !empty($this->session->userdata('warehouse_id')) ? $this->warehouses_model->getWarehouseById($this->session->userdata('warehouse_id')) : $this->warehouses_model->fetch_warehouses();
	    $this->data['so_data']    = $so_data;
	    $this->page_construct('stockopname/add', $this->data);
		}
		else
		{
			$this->process();
		}


  }

  public function stock_opname_process_view($stock_opname_id = 0)
  {
		$config = array(
			array(
				'field' => 'product_id[]',
				'label' => 'Product Id',
				'rules' => 'required|numeric'
			),
			array(
				'field' => 'quantity[]',
				'label' => 'Qty',
				'rules' => 'required|is_natural_no_zero'
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', '<span style="color: #fff;"><b>{field} tidak boleh kosong</b></span>');
		$this->form_validation->set_message('numeric', '<span style="color: #fff;"><b>{field} harus berupa angka / number</b></span>');
		
		if ($this->form_validation->run() == false)
		{
	    $this->data['error']           = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	    $this->data['page_title']      = lang('Continue Process Stock Opname');
	    $this->data['warehouse']       = !empty($this->session->userdata('warehouse_id')) ? $this->warehouses_model->getWarehouseById($this->session->userdata('warehouse_id')) : $this->warehouses_model->fetch_warehouses();
	    $this->data['stock_opname_id'] = $stock_opname_id;
	    $this->data['so_data'] 				 = $this->db->select('stock_opname')->from('stock_opname')->where('id', $stock_opname_id)->get()->row_array();
	    $this->page_construct('stockopname/add', $this->data);			
		}
		else
		{
			$this->process();
		}
  }

  public function process()
  {
    $post = $this->input->post(null, true);
    if (isset($post['create_stock_opname']))
    {
    	$insert = $this->stockopname_model->add_stockopname($post);
    }

		if (empty($insert))
		{
      $this->session->set_flashdata('error', lang("gagal membuat stock opname"));
      redirect('stockopname');
      return;
		}

    $this->session->set_flashdata('message', lang("berhasil membuat stock opname"));
    redirect('stockopname');
  }

  public function suggestions()
  {
  	$warehouse_id 				= $this->session->userdata('warehouse_id');
  	$post         				= $this->input->get('term', true);
    $rows                 = $this->check_out_model->getProductNames($term);

    if ($rows) {
        foreach ($rows as $row) {
				    $qty_real 		 = $this->db->select('quantity')->from('item_warehouse')->where('item_id', $row->id)->where('warehouse_id', $warehouse_id)->get()->row_array();
            $row->qty_real = $qty_real['quantity'];
            $row->qty      = 1;
            $pr[]          = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
        }
        echo json_encode($pr);
    } else {
        echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
    }
  }

  public function stockopname_detail($stock_opname_id = 0)
  {
  	$stockopname                   = $this->stockopname_model->stockopname_by_id($stock_opname_id);
  	$stockopname['warehouse_name'] = $this->db->select('name')->from('warehouses')->where('id', $stockopname['warehouse_id'])->get()->row()->name;
  	$stockopname_products          = $this->db->select('*')->from('stock_opname_product')->where('stock_opname_id', $stockopname['id'])->get()->result_array();
  	
  	foreach ($stockopname_products as $key => $value) $stockopname['products'][$key] = $value;

    $this->data['error']       = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['page_title']  = lang('Stockopname List');
    $this->data['stockopname'] = $stockopname;
    $this->page_construct('stockopname/detail', $this->data);
    
    if(!empty($_SESSION['message'])){unset($_SESSION['message']); }
  }

  public function stockopname_detail_json()
  {
  	$stock_opname_id = !empty($_GET['stock_opname_id']) ? intval($_GET['stock_opname_id']) : 0;
  	if (empty($stock_opname_id)) return false;
   
    if ($this->Admin) {
        $links .= "<div class='btn-group' role='group'>
					          <a class=\"btn btn-warning btn-xs tip\" title='" . lang("edit_item") . "' href='" . site_url('items/edit/$1') . "'>
					          	<i class=\"fa fa-edit\"></i>
					          </a>
					        </div>";
    }
    $links .= "</div>";
  
  	$this->datatables->select('
  		stock_opname_product.id,
  		stock_opname_product.product_code,
  		stock_opname_product.product_name,
  		stock_opname_product.qty'
  	)->from('stock_opname_product')->where('stock_opname_id', $stock_opname_id);
  	$this->datatables->add_column('Actions', $links, 'id');
  	echo $this->datatables->generate();
    
    // $this->datatables->select(
    // 	'stock_opname.id,
    // 	stock_opname.stock_opname,
    // 	stock_opname.warehouse_id,
    // 	stock_opname.qty,
    // 	stock_opname.qty_real_total,
    // 	stock_opname.notes,
    // 	stock_opname.created_at,
    // 	warehouses.name'
    // )->from('stock_opname')->join('warehouses', 'warehouses.id = stock_opname.warehouse_id');
    // $this->datatables->add_column('Actions', $links, 'id');
    // echo $this->datatables->generate();
  }
}