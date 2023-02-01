<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address_books_model extends CI_Model
{
	public function __construct()
	{
    parent::__construct();
		$this->load->helper('function_helper');
	}

  public function fetch_address_books($limit = 0, $start = 0)
  {
    if ($limit > 0 && $start > 0) {
        $this->db->limit($limit, $start);
    }

    $this->db->select([
    	'id',
    	'location_id',
    	'name',
    	'phone',
    	'address',
    	'created_at',
    	'updated_at'
    ]);
    $q = $this->db->get("address_books");
    if ($q->num_rows() > 0) {
        foreach ($q->result() as $row) {
            $data[] = $row;
        }
        return $data;
    }
    return false;
  }

  public function fetch_master_location($limit = 0, $start = 0)
  {
		if ($limit > 0 && $start > 0) {
		  $this->db->limit($limit, $start);
		}

		$this->db->select([
			'id',
			'title',
			'detail',
			'postcode'
		])->like('detail', 'indonesia')->order_by('title ASC');
		$q = $this->db->get("master_locations");
		if ($q->num_rows() > 0) {
		    foreach ($q->result() as $row) {
		        $data[] = $row;
		    }
		    return $data;
		}
		return false;
  }

  public function fetch_master_location_id($id = 0)
  {
	  $q = $this->db->get_where('master_locations', array('id' => $id), 1);
	  if ($q->num_rows() > 0) {
	      return $q->row();
	  }
	  return FALSE;
  }

  public function add_address_books($post)
  {
		$this->load->helper('function_helper');

    $params = [
        'location_id' => $post['location_id'],
        'name'        => $post['name'],
        'phone'       => $post['phone'],
        'address'     => $post['address'],
    ];
    // var_dump($params);die();

    $insert = $this->db->insert('address_books', $params);
    if (empty($insert))
    {
    	return false;
    }
  	return true;
  }
}