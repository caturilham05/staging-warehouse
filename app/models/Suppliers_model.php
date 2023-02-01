<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers_model extends CI_Model
{

	public function __construct() {
		parent::__construct();
	}

	public function getSupplierByID($id)
	{
		$q = $this->db->get_where('suppliers', array('id' => $id), 1);
		if( $q->num_rows() > 0 ) {
			return $q->row();
		}
		return FALSE;
	}

	public function addSupplier($data = array())
	{
		if($this->db->insert('suppliers', $data)) {
			return true;
		}
		return false;
	}

	public function updateSupplier($id, $data = array())
	{
		if($this->db->update('suppliers', $data, array('id' => $id))) {
			return true;
		}
		return false;
	}

	public function deleteSupplier($id)
	{
		if($this->db->delete('suppliers', array('id' => $id))) {
			return true;
		}
		return FALSE;
	}

	public function getSupplierByName($name)
	{
		$q = $this->db->get_where('suppliers', array('name' => $name), 1);
		if( $q->num_rows() > 0 ) {
			return $q->row();
		}
		return FALSE;
	}

	public function add_suppliers($data = array()) {
        if ($this->db->insert_batch('suppliers', $data)) {
            return true;
        }
        return false;
    }

}
