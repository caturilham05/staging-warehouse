<?php (defined('BASEPATH')) or exit('No direct script access allowed'); 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Address_books extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
    if (!$this->loggedIn) {
        $this->session->set_flashdata('warning', lang('access_denied'));
        redirect('logout');
    }

    if ($this->session->userdata('group_id') == 3) {
        $this->session->set_flashdata('warning', lang('access_denied'));
        redirect('welcome');
    }

    $this->load->library(['form_validation', 'zend']);
    $this->load->model(['address_books_model']);
		$this->load->helper('function_helper');
    ini_set('display_errors', 1);
	}

  public function index()
  {
    $this->data['error']         = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['address_books'] = $this->address_books_model->address_books_origin_jne();
    $this->data['page_title']    = lang('Address Books');
    $this->page_construct('address_books/index', $this->data);

    if (!empty($_SESSION['message'])) {
      unset($_SESSION['message']);
    }
  }

  public function address_books_all($alerts = NULL)
  {
			$this->load->helper('function_helper');
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
      $this->load->library('datatables');
      $this->datatables->select('address_books.id, address_books.location_id, address_books.name, address_books.phone, address_books.address, address_books.created_at, address_books.updated_at,'.$this->db->dbprefix('origin_code_jne').'.id AS origin_jne_id, origin_code_jne.origin_code, origin_code_jne.origin_name')->from('address_books')->join('origin_code_jne', 'origin_code_jne.id = address_books.location_id');
      $this->datatables->add_column("Actions", $links, "id");
      echo $this->datatables->generate();
  }

  public function add()
  {
		$this->load->helper('function_helper');
    if (!empty($_SESSION['message']) || !empty($_SESSION['error'])) {
        unset($_SESSION['message']);
        unset($_SESSION['error']);
    }

    $address_books = [
    	'location_id' => 0,
    	'name'        => '',
    	'phone'       => '',
    	'address'     => '',
    ];

		$config = array(
			array(
				'field' => 'name',
				'label' => 'Nama Pengirim',
				'rules' => 'required'
			),
			array(
				'field' => 'phone',
				'label' => 'Nomor HP',
				'rules' => 'trim|required|min_length[5]|max_length[13]|numeric|is_unique[address_books.phone]'
			),
			array(
				'field' => 'address',
				'label' => 'Alamat Lengkap',
				'rules' => 'required'
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('is_unique', '<span style="color: #fff;"><b>{field} Sudah Terpakai</b></span>');
		$this->form_validation->set_message('required', '<span style="color: #fff;"><b>{field} tidak boleh kosong</b></span>');
		$this->form_validation->set_message('min_length', '<span style="color: #fff;"><b>{field} harus lebih dari 5 karakter</b></span>');
		$this->form_validation->set_message('max_length', '<span style="color: #fff;"><b>{field} harus kurang dari 13 karakter</b></span>');
		$this->form_validation->set_message('numeric', '<span style="color: #fff;"><b>{field} harus berupa angka / number</b></span>');

		if ($this->form_validation->run() == false)
		{

	    $this->data['error']           = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	    $this->data['page_title']      = lang('Add Address Books');
	    $this->data['page']            = 'add';
	    $this->data['row']             = $address_books;
	    $this->data['origin_jne']      = $this->address_books_model->address_books_origin_jne();
	    $this->data['master_location'] = $this->address_books_model->fetch_master_location();
	    $this->page_construct('address_books/add', $this->data);
		}
		else
		{
			$this->process();
		}
  }

  public function edit($id = 0)
  {
		$this->load->helper('function_helper');
    if (!$this->Admin) {
      $this->session->set_flashdata('warning', lang('access_denied'));
      redirect('items');
    }

    $address_books = [
    	'location_id' => 0,
    	'name'        => '',
    	'phone'       => '',
    	'address'     => '',
    ];

		$config = array(
			array(
				'field' => 'name',
				'label' => 'Nama Pengirim',
				'rules' => 'required'
			),
			array(
				'field' => 'phone',
				'label' => 'Nomor HP',
				'rules' => 'trim|required|min_length[5]|max_length[13]|numeric'
			),			
			// array(
			// 	'field' => 'zip_code',
			// 	'label' => 'Kode Pos',
			// 	'rules' => 'trim|required',
			// ),
			array(
				'field' => 'address',
				'label' => 'Alamat Lengkap',
				'rules' => 'required'
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', '<span style="color: #fff;"><b>{field} tidak boleh kosong</b></span>');
		$this->form_validation->set_message('min_length', '<span style="color: #fff;"><b>{field} harus lebih dari 5 karakter</b></span>');
		$this->form_validation->set_message('max_length', '<span style="color: #fff;"><b>{field} harus kurang dari 13 karakter</b></span>');
		$this->form_validation->set_message('numeric', '<span style="color: #fff;"><b>{field} harus berupa angka / number</b></span>');
		if ($this->form_validation->run() == false)
		{
			$address_books   = $this->address_books_model->fetch_address_books_id($id);
			$master_location = $this->address_books_model->fetch_master_location_id($address_books->location_id);

	    $this->data['error']           = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	    $this->data['page_title']      = lang('Edit Address Books');
	    $this->data['page']            = 'edit';
	    $this->data['data']            = $address_books;
	    $this->data['origin_jne']      = $this->address_books_model->address_books_origin_jne();
	    $this->data['master_location'] = $master_location;
	    $this->page_construct('address_books/edit', $this->data);
		}
		else
		{
			$this->process($id);
		}

  }

  public function process($id = 0)
  {
		$this->load->helper('function_helper');

		$post = $this->input->post(null, true);
		if (isset($_POST['add']))
		{
			$insert = $this->address_books_model->add_address_books($post);
			if (empty($insert)) {
        $this->session->set_flashdata('error', lang("buku alamat gagal ditambahkan"));
        redirect("address_books/add");
			}

      $this->session->set_flashdata('message', lang("buku alamat berhasil ditambahkan"));
      redirect("address_books");
		}

		if (isset($_POST['edit']))
		{
			$update = $this->address_books_model->edit_address_books($post, $id);
			if (empty($update)) {
        $this->session->set_flashdata('error', lang("buku alamat gagal diupdate"));
        redirect("address_books/edit/".$id);
			}

      $this->session->set_flashdata('message', lang("buku alamat berhasil diupdate"));
      redirect("address_books");
		}
  }

  public function delete($id = 0)
  {
    if (!$this->Admin) {
      $this->session->set_flashdata('warning', lang('access_denied'));
      redirect('address_books');
    }
    if ($this->address_books_model->delete_address_books($id)) {
      $this->session->set_flashdata('message', lang("address_books_berhasil_dihapus"));
      redirect("address_books");
    } else {
      $this->session->set_flashdata('error', lang("address_books_gagal_dihapus"));
      redirect("address_books");
    }
  }

  public function upload_config_import_master_location($path)
  {
    if (!is_dir($path)) mkdir($path, 0777, TRUE);       
    $config['upload_path']      = './'.$path;       
    $config['allowed_types']    = 'csv|CSV|xlsx|XLSX|xls|XLS';
    $config['max_filename']     = '255';
    $config['encrypt_name']     = TRUE;
    $config['max_size']         = 0; 
    $this->load->library('upload', $config);
  }

  public function import_master_location_view()
  {
    set_cookie('ci_csrf_token', 'ci_csrf_token', 128000);
    $this->data['error']         = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['page_title']    = lang('Master Location Import Excel');
    $this->page_construct('address_books/import_master_location', $this->data);

    if (!empty($_SESSION['message'])) {
      unset($_SESSION['message']);
    }
  }


  public function import_master_location_process()
  {
    $path = 'uploads/import_excel_master_location/';
    $json = [];
    $this->upload_config_import_master_location($path);
    if (!$this->upload->do_upload('upload_master_location'))
    {
      $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
      redirect("address_books/import_master_location_process");
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

      foreach($sheet_data as $key => $value)
      {
          if($key != 0)
          {
          	$list[] = [
          		'country_name'     => $value[0],
          		'province_name'    => $value[1],
          		'city_name'        => $value[2],
          		'district_name'    => $value[3],
          		'subdistrict_name' => $value[4],
          		'zip_code'         => $value[5],
          		'tarif_code'       => $value[6],
          	];
          }
      }

      if(file_exists($file_name)) unlink($file_name);

      if(count($list) > 0)
      {
        $this->db->trans_begin();
        $this->db->insert_batch('master_locations', $list);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        redirect("address_books");
      }
      else
      {
          $this->session->set_flashdata('error', lang("Order gagal ditambahkan"));
          redirect("address_books/import_master_location_view");
          return false;
      }
    }
  }
}