<?php defined('BASEPATH') or exit('No direct script access allowed');


class Letter_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLetter()
    {
        // return $this->db->get('travel_document')->result();
        $q = $this->db->get("travel_document");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAwbByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }    

    public function getAwbNames($term, $limit = 10) {
        $this->db->where("(awb_no LIKE '%" . $term . "%' OR  concat(receiver_name, ' ') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->group_by('id')->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTravelDocById($id, $awb) {
        $q = $this->db->get_where('detail_travel_document', array('travel_document_id' => $id, 'awb' => $awb));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }

    public function getAllSales() {
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTravelDoc($id){
        $array_data = [];
       
        $travel_doc = $this->db->get_where('travel_document', array('id' => $id))->row();
        $detail_doc = $this->db->get_where('detail_travel_document', array('travel_document_id' => $id))->result();
        
        $array_data['travel_doc'] = $travel_doc;
        $array_data['detail_doc'] = $detail_doc;
        
        return $array_data;        
    }

  

   
}