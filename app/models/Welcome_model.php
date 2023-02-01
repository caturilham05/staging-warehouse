<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getChartData()
    {
        $myQuery = "SELECT I.month,
        COALESCE(I.total, 0) as check_ins,
        COALESCE( O.total, 0 ) as check_outs
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                COUNT(id) total
                FROM " . $this->db->dbprefix('check_in') . "
                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )
                GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            GROUP BY S.Month
            ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCheckins($user_id = NULL) {
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('date_format(date, \'%Y-%m\') as month, COUNT(id) as check_ins')
        ->where($this->db->dbprefix('check_in').".date >= date_sub( now( ) , INTERVAL 12 MONTH ) ", NULL, FALSE)
        ->group_by('date_format(date, \'%Y-%m\')')
        ->order_by('month desc');
        if($user_id) {
             $this->db->where('created_by', $user_id);
        }
        $q = $this->db->get('check_in');
        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCheckouts($user_id = NULL) {
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('date_format(date, \'%Y-%m\') as month, COUNT(id) as check_outs')
        ->where($this->db->dbprefix('check_out').".date >= date_sub( now( ) , INTERVAL 12 MONTH ) ", NULL, FALSE)
        ->group_by('date_format(date, \'%Y-%m\')')
        ->order_by('month desc');
        if($user_id) {
             $this->db->where('created_by', $user_id);
        }
        $q = $this->db->get('check_out');
        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
