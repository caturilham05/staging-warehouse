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
    $this->load->model(['warehouses_model', 'items_model', 'stockopname_model']);

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
    $links = "<div class=''>";
    $links .= "<div class='btn-group btn-group-justified' role='group'>
					      <div class='btn-group' role='group'>
					      	<a onclick=\"window.open('" . site_url('items/single_barcode/$1') . "', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;\" href='#' title='" . lang('print_barcodes') . "' class='tip btn btn-default btn-xs'>
					      		<i class='fa fa-print'></i>
					      	</a>
					      </div>
					      <div class='btn-group' role='group'>
					      	<a onclick=\"window.open('" . site_url('items/single_label/$1') . "', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;\" href='#' title='" . lang('print_labels') . "' class='tip btn btn-default btn-xs'>
					      	<i class='fa fa-print'></i>
					      	</a>
					      </div>";

    if ($this->Admin) {
        $links .= " <div class='btn-group' role='group'>
						          <a class=\"btn btn-warning btn-xs tip\" title='" . lang("edit_item") . "' href='" . site_url('items/edit/$1') . "'>
						          	<i class=\"fa fa-edit\"></i>
						          </a>
						        </div>
						        <div class='btn-group' role='group'>
						        	<a href='#' class='btn btn-danger btn-xs tip po' title='<b>" . lang("delete_item") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
						        	<a class='btn btn-danger po-delete' href='" . site_url('items/delete/$1') . "'>" . lang('i_m_sure') . "</a>
						        	<button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>
						        </div>";
    }
    $links .= "</div>";
    $this->datatables->select(
    	'stock_opname.id,
    	stock_opname.stock_opname,
    	stock_opname.warehouse_id,
    	stock_opname.qty,
    	stock_opname.created_at,
    	warehouses.name'
    )->from('stock_opname')->join('warehouses', 'warehouses.id = stock_opname.warehouse_id');
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
	    $this->data['error']           = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	    $this->data['page_title']      = lang('Continue Process Stock Opname');
	    $this->data['warehouse']       = !empty($this->session->userdata('warehouse_id')) ? $this->warehouses_model->getWarehouseById($this->session->userdata('warehouse_id')) : $this->warehouses_model->fetch_warehouses();
	    $this->data['stock_opname_id'] = $stock_opname_id;;
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
    redirect('stockopname/stock_opname_process_view/'.$post['stock_opname_id']);
  }
}