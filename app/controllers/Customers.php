<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        if ($this->session->userdata('group_id') == 3) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('welcome');
        }

        $this->load->library('form_validation');
        $this->load->model('customers_model');
    }

    function index()
    {

    	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    	$this->data['page_title'] = lang('customers');
    	$bc = array(array('link' => '#', 'page' => lang('customers')));
    	$meta = array('page_title' => lang('customers'), 'bc' => $bc);
    	$this->page_construct('customers/index', $this->data, $meta);

        if(!empty($_SESSION['message'])){         unset($_SESSION['message']); }
    }

    function get_customers()
    {

    	$this->load->library('datatables');
    	$this->datatables
    	->select("id, name, phone, email, cf1, cf2")
    	->from("customers")
    	->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='" . site_url('customers/edit/$1') . "' class='tip btn btn-warning btn-xs' title='".$this->lang->line("edit_customer")."'><i class='fa fa-edit'></i></a> <a href='#' class='btn btn-danger btn-xs tip po' title='<b>" . lang("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div></div>", "id")
    	->unset_column('id');

    	echo $this->datatables->generate();

    }

	function add()
	{

		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required|is_unique[customers.name]');
		$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');

		if ($this->form_validation->run() == true) {

			$data = array('name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'cf1' => $this->input->post('cf1'),
				'cf2' => $this->input->post('cf2')
			);

		}

		if ( $this->form_validation->run() == true && $this->customers_model->addCustomer($data)) {

            $this->session->set_flashdata('message', $this->lang->line("customer_added"));
            redirect("customers");

		} else {

			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    		$this->data['page_title'] = lang('add_customer');
    		$bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('add_customer')));
    		$meta = array('page_title' => lang('add_customer'), 'bc' => $bc);
    		$this->page_construct('customers/add', $this->data, $meta);

		}
	}

	function edit($id = NULL)
	{
        if (!$this->Admin) {
            $this->session->set_flashdata('error', $this->lang->line('access_denied'));
            redirect('/');
        }
		if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }
        $customer = $this->customers_model->getCustomerByID($id);
		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
		$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        if ($customer->name != $this->input->post('name')) {
            $this->form_validation->set_rules('name', $this->lang->line("name"), 'is_unique[customers.name]');
        }
		if ($this->form_validation->run() == true) {

			$data = array('name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'cf1' => $this->input->post('cf1'),
				'cf2' => $this->input->post('cf2')
			);

		}

		if ( $this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {

			$this->session->set_flashdata('message', $this->lang->line("customer_updated"));
			redirect("customers");

		} else {

			$this->data['customer'] = $customer;
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
    		$this->data['page_title'] = lang('edit_customer');
    		$bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('edit_customer')));
    		$meta = array('page_title' => lang('edit_customer'), 'bc' => $bc);
    		$this->page_construct('customers/edit', $this->data, $meta);

		}
	}

	function delete($id = NULL)
	{
		if(DEMO) {
			$this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
			redirect('/');
		}

		if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

		if (!$this->Admin)
		{
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('/');
		}

		if ( $this->customers_model->deleteCustomer($id) )
		{
			$this->session->set_flashdata('message', lang("customer_deleted"));
			redirect("customers");
		}

	}

    function import() {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('error', lang("disabled_in_demo"));
                redirect('welcome');
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
                    redirect("customers/import");
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

                $keys = array('name', 'email', 'phone', 'cf1', 'cf2');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 1001) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("customers/import");
                }
                $this->load->helper('email');

                foreach ($final as $csv_pr) {
                    if($this->customers_model->getCustomerByName($csv_pr['name'])) {
                        $this->session->set_flashdata('error', lang("check_name") . " (" . $csv_pr['name'] . "). " . lang("name_already_exist"));
                        redirect("customers/import");
                    }
                    if ( ! empty($csv_pr['email']) && ! valid_email($csv_pr['email'])) {
                        $this->session->set_flashdata('error', lang("check_email") . " (" . $csv_pr['email'] . "). " . lang("email_x_valid"));
                        redirect("customers/import");
                    }
                    $data[] = array('name' => $csv_pr['name'], 'email' => $csv_pr['email'], 'phone' => $csv_pr['phone'], 'cf1' => $csv_pr['cf1'], 'cf2' => $csv_pr['cf2']);
                }
            }
            // $this->tec->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->customers_model->add_customers($data)) {

            $this->session->set_flashdata('message', lang("customers_added"));
            redirect('customers');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('import_customers');
            $this->page_construct('customers/import', $this->data);

        }
    }

}
