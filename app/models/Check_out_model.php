<?php defined('BASEPATH') or exit('No direct script access allowed');

class Check_out_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStockOutByID($id)
    {
        $q = $this->db->get_where('check_out', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllOutItems($check_out_id)
    {
        // $this->db->select('check_out_items.*, items.name as item_name, items.code as item_code')
        // ->join('items', 'items.id=check_out_items.item_id')
        // ->order_by('check_out_items.id');
        // $q = $this->db->get_where('check_out_items', array('check_out_id' => $check_out_id));
        $this->db->select('check_out_items.check_out_id, items.id as item_id, items.name as item_name, items.code as item_code, check_out_items.quantity')
            ->join('item_warehouse', 'item_warehouse.id=check_out_items.item_warehouse_id')
            ->join('items', 'items.id=item_warehouse.item_id')
            ->order_by('check_out_items.id');
        $q = $this->db->get_where('check_out_items', array('check_out_id' => $check_out_id));

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return null;
    }

    function addStockOut($data, $items)
    {
        if ($this->db->insert('check_out', $data)) {
            $check_out_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['check_out_id'] = $check_out_id;
                if ($this->db->insert('check_out_items', $item)) {
                    $product = $this->getItemByID($item['item_id']);
                    $this->db->update('items', array('quantity' => ($product->quantity - $item['quantity'])), array('id' => $product->id));
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    function updateStockOut($id, $data, $items)
    {
        $oitems = $this->getAllOutItems($id);
        if ($this->db->update('check_out', $data, array('id' => $id)) && $this->db->delete('check_out_items', array('check_out_id' => $id))) {
            foreach ($items as $item) {
                $item['check_out_id'] = $id;
                if ($this->db->insert('check_out_items', $item)) {
                    $product = $this->getItemByID($item['item_id']);
                    $this->db->update('items', array('quantity' => ($product->quantity - $item['quantity'])), array('id' => $product->id));
                }
            }
            foreach ($oitems as $oitem) {
                $product = $this->getItemByID($oitem->item_id);
                $this->db->update('items', array('quantity' => ($product->quantity + $oitem->quantity)), array('id' => $product->id));
            }
            return TRUE;
        }
        return FALSE;
    }

    public function deleteStockOut($id = NULL)
    {
        if ($this->db->delete('check_out', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getItemWarehouseByID($id, $warehouse_id)
    {
        $q = $this->db->get_where('item_warehouse', array('item_id' => $id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllWarehouses()
    {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getItemByCode($code)
    {
        $q = $this->db->get_where('items', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductNames($term, $limit = 10)
    {
        $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCustomers($term, $limit = 10)
    {
        $this->db->select('customer')
            ->distinct()
            ->like('customer', $term, 'both')
            ->limit($limit);
        $q = $this->db->get('check_out');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomers()
    {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCustomerByID($id)
    {
        $q = $this->db->get_where('customers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockInByRef($ref)
    {
        $q = $this->db->get_where('check_out', array('reference' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function generateReference()
    {
        $this->load->helper('string');
        $ref = random_string('numeric', 12);
        if ($this->getStockInByRef($ref)) {
            $this->generateReference();
        } else {
            return $ref;
        }
    }

    public function getUser($id = NULL)
    {
        if (!$id) {
            $id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
}
