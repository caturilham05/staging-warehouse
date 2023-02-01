<?php defined('BASEPATH') or exit('No direct script access allowed');

class Check_in_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStockInByID($id)
    {
        $q = $this->db->get_where('check_in', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllInItems($check_in_id)
    {
        $this->db->select('check_in_items.check_in_id, items.id as item_id, items.name as item_name, items.code as item_code, check_in_items.quantity')
            ->join('item_warehouse', 'item_warehouse.id=check_in_items.item_warehouse_id')
            ->join('items', 'items.id=item_warehouse.item_id')
            ->order_by('check_in_items.id');
        $q = $this->db->get_where('check_in_items', array('check_in_id' => $check_in_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    function addIn($data, $items)
    {
        if ($this->db->insert('check_in', $data)) {
            $check_in_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['check_in_id'] = $check_in_id;
                $data_item_warehouse = array('item_id' => $item['item_id'], 'warehouse_id' => $item['warehouse_id'], 'quantity' => $item['quantity']);
                if ($this->db->insert('check_in_items', $item)) {

                    $product = $this->getItemByID($item['item_id']);
                    if (empty($product)) {
                        $this->db->insert('item_warehouse', $data_item_warehouse);
                    } else {
                        $this->db->update('item_warehouse', array('quantity' => ($product->quantity + $item['quantity'])), array('id' => $product->id, 'warehouse_id' => $item['warehouse_id']));
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    function updateIn($id, $data, $items)
    {
        //db begin
        $this->db->trans_begin();

        $item_delete = $this->getAllInItems($id);
        $update_check_in = $this->db->update('check_in', $data, array('id' => $id));
        $post_check_in_items = [];
        if ($update_check_in) {
            foreach ($item_delete as $v) {
                $this->db->delete('item_warehouse', array('id' => $v->item_warehouse_id));
            }
            foreach ($items as $item) {
                $this->db->insert('item_warehouse', $item);
                $new_item_warehouse_id = $this->db->insert_id();
                $post_check_in_items[] = [
                    'check_in_id' => $id,
                    'item_warehouse_id' => $new_item_warehouse_id,
                ];
            }

            $this->db->delete('check_in_items', array('check_in_id' => $id));

            $this->db->insert_batch('check_in_items', $post_check_in_items);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function deleteIn($id = NULL)
    {
        if ($this->db->delete('check_in', array('id' => $id))) {
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
        $q = $this->db->get_where('item_warehouse', array('item_id' => $id, 'warehouse_id' => $warehouse_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
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
        $q = $this->db
            ->from('items')
            ->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')")
            ->limit($limit)
            ->group_by('code')->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuppliers($term, $limit = 10)
    {
        $this->db->select('supplier')
            ->distinct()
            ->like('supplier', $term, 'both')
            ->limit($limit);
        $q = $this->db->get('check_in');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSuppliers()
    {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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

    public function getSupplierByID($id)
    {
        $q = $this->db->get_where('suppliers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockInByRef($ref)
    {
        $q = $this->db->get_where('check_in', array('reference' => $ref), 1);
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
