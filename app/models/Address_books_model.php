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

    public function fetch_address_books_id($id = 0)
    {
      $q = $this->db->get_where('address_books', array('id' => $id), 1);
      if ($q->num_rows() > 0) {
          return $q->row();
      }
      return FALSE;    
    }

    public function fetch_master_location($limit = 0, $start = 0)
    {
    	if ($limit > 0 && $start > 0) {
    	  $this->db->limit($limit, $start);
    	}

    	$this->db->select([
    		'id',
    		'country_name',
    		'province_name',
    		'city_name',
            'district_name',
            'subdistrict_name',
            'zip_code',
            'tarif_code'
    	])->order_by('city_name ASC');
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

    public function fetch_master_location_zip_code($zip_code = 0)
    {
      $q = $this->db->get_where('master_locations', array('zip_code' => $zip_code), 1);
      if ($q->num_rows() > 0) {
          return $q->row();
      }
      return FALSE;
    }

    public function add_address_books($post)
    {
        $this->load->helper('function_helper');
        // $master_location = $this->fetch_master_location_zip_code($post['zip_code']);
        
        $params = [
            'location_id' => $post['location_id'],
            'name'        => $post['name'],
            'phone'       => $post['phone'],
            'address'     => $post['address'],
            'district'    => $post['district'],
            'subdistrict' => $post['subdistrict'],
            'zipcode'     => $post['zipcode'],
        ];

        $insert = $this->db->insert('address_books', $params);
        if (empty($insert))
        {
        	return false;
        }
    	return true;
    }

    public function edit_address_books($post, $id)
    {
        // $master_location = $this->fetch_master_location_zip_code($post['zip_code']);
        $params          = [
            'location_id' => $post['location_id'],
            'name'        => $post['name'],
            'phone'       => $post['phone'],
            'address'     => $post['address'],
            'district'    => $post['district'],
            'subdistrict' => $post['subdistrict'],
            'zipcode'     => $post['zipcode'],
        ];

        $this->db->where('id', $id);
        $updated = $this->db->update('address_books', $params);
        if (empty($updated)) return false;
        return true;
    }

    public function delete_address_books($id = 0) {
        if($this->db->delete('address_books', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function address_books_by_id($name = '')
    {
        $q = $this->db->get_where('address_books', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }
        return FALSE;
    }

    public function address_books_origin_jne()
    {
        $origin_jne = $this->db->select('id, origin_code, origin_name')->from('origin_code_jne')->get()->result_array();
        if (empty($origin_jne)) return false;
        return $origin_jne;
    }
}

