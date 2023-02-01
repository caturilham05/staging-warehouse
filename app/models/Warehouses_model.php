<?php defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses_model extends CI_Model
{

    public $tables = array();
    public $activation_code;
    public $forgotten_password_code;
    public $new_password;
    public $identity;
    public $_ion_where = array();
    public $_ion_select = array();
    public $_ion_like = array();
    public $_ion_limit = NULL;
    public $_ion_offset = NULL;
    public $_ion_order_by = NULL;
    public $_ion_order = NULL;
    protected $_ion_hooks;
    protected $response = NULL;
    protected $messages;
    protected $errors;
    protected $error_start_delimiter;
    protected $error_end_delimiter;
    public $_cache_user_in_group = array();
    protected $_cache_groups = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addWarehouse($data)
    {
        if ($this->db->insert('warehouses', $data)) {
            return true;
        }
        return false;
    }

    public function addWarehouses($data = array())
    {
        if ($this->db->insert_batch('warehouses', $data)) {
            return true;
        }
        return false;
    }

    function updateWarehouse($id, $data)
    {
        if ($this->db->update('warehouses', $data, array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteWarehouse($id = NULL)
    {
        if ($this->db->delete('warehouses', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function warehouses_count()
    {

        return $this->db->count_all("warehouses");
    }

    public function getWarehouseById($id){
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function fetch_warehouses($limit = 0, $start = 0)
    {
        if ($limit > 0 && $start > 0) {
            $this->db->limit($limit, $start);
        }
        $this->db->select(['id', 'name']);

        $q = $this->db->get("warehouses");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
}
