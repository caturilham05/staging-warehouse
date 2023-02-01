<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getQtyAlerts() {
        // $this->db->join('item_warehouse a', 'items.id=item_warehouse.item_id', 'left')->where('a.quantity < alert_quantity', NULL, FALSE);
        // return $this->db->count_all_results('items');
    }

    public function updateLogo($photo) {
        $logo = array( 'logo' => $photo );
        if ($this->db->update('settings', $logo)) {
            return true;
        }
        return false;
    }

    public function getDateFormats() {
        $q = $this->db->get('date_format');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateSetting($data) {
        $this->db->where('setting_id', '1');
        if ($this->db->update('settings', $data)) {
            return true;
        }
        return false;
    }

    public function getSettings() {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUser($id = NULL) {
        if(!$id) { $id = $this->session->userdata('user_id'); }
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllUsers() {
        $this->db->select('users.*, groups.name as group')->join('groups', 'groups.id=users.group_id', 'left')->group_by('users.id');
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserGroup($user_id = false) {
        if(!$user_id) { $user_id = $this->session->userdata('user_id'); }
        $group_id = $this->getUserGroupID($user_id);
        $q = $this->db->get_where('groups', array('id' => $group_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserGroupID($user_id = false) {
        if(!$user_id) { return FALSE; }
        $user = $this->getUser($user_id);
        return $user->group_id;
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategoryByCode($code) {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCategories() {
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;;
    }

    public function addCategory($data) {
        if ($this->db->insert('categories', $data)) {
            return true;
        }
        return false;
    }

    public function add_categories($data = array()) {
        if ($this->db->insert_batch('categories', $data)) {
            return true;
        }
        return false;
    }

    public function updateCategory($id, $data = NULL) {
        if ($this->db->update('categories', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteCategory($id) {
        if ($this->db->delete('categories', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
