<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stockopname_model extends CI_Model
{
  public function __construct()
  {
      parent::__construct();
  }

  public function get_stockopname_all($limit = 0, $start = 0)
  {
    if ($limit > 0 && $start > 0) {
        $this->db->limit($limit, $start);
    }
    $this->db->select(['id', 'stock_opname', 'warehouse_id', 'qty', 'created_at', 'updated_at']);
    $q = $this->db->get("stock_opname");

    if ($q->num_rows() == 0) return false;

    $stockopname           = $q->result_array();
    $stockopname_id        = array_column($stockopname, 'id');
    $q_stockopname_product = $this->db->select(
    	[
    		'id AS stock_opname_product_id',
    		'stock_opname_id',
    		'product_id',
    		'product_code',
    		'product_name',
    		'qty',
    		'created_at',
    		'updated_at'
    	]
    )->from('stock_opname_product')
    ->where_in('stock_opname_id', $stockopname_id)
    ->get()->result_array();

    if (empty($q_stockopname_product)) return false;

    foreach ($q_stockopname_product as $key => $value) $so_product[$value['stock_opname_product_id']] = $value;
    foreach ($stockopname as $key => $value)
    {
    	$value['products'] = !empty($so_product[$value['id']]) ? $so_product[$value['id']] : [];
    	$stockopname[$key] = $value;
    }
    return $stockopname;
  }

  public function stockopname_by_id($stock_opname_id = 0)
  {
    $q = $this->db->get_where('stock_opname', array('id' => $stock_opname_id), 1);
    if ($q->num_rows() > 0) {
        return $q->row_array();
    }
    return FALSE;
  }

  public function add_stockopname($post)
  {
    if (empty($post)) return false;

    $qty_total      = 0;
    $qty_total_real = 0;

    foreach ($post['product_id'] as $key => $value)
    {
      $qty                    = !empty($post['quantity'][$key]) ? $post['quantity'][$key] : 0;
      $qty_total             += $qty;
      $product                = $this->db->select('code, name')->from('items')->where('id', $value)->get()->row_array();
      $product_in_warehouse   = $this->db->select('quantity')->from('item_warehouse')->where('item_id', $value)->where('warehouse_id', $post['warehouse_id'])->get()->row_array();
      $qty_total_real_product = !empty($product_in_warehouse['quantity']) ? $product_in_warehouse['quantity'] : 0; 
      $qty_total_real        += $qty_total_real_product; 
      $datas[]                = [
        'stock_opname_id' => $post['stock_opname_id'],
        'product_id'      => $value,
        'product_code'    => $product['code'],
        'product_name'    => $product['name'],
        'qty'             => $qty,
        'qty_real'        => !empty($product_in_warehouse['quantity']) ? $product_in_warehouse['quantity'] : 0,
        'status'          => 2
      ];
    }

    $data_so = [
      'qty'            => $qty_total,
      'qty_real_total' => $qty_total_real,
      'notes'          => $post['notes'],
      'status'         => 2
    ];

    $this->db->trans_begin();
    $this->db->insert_batch('stock_opname_product', $datas);
    $this->db->update('stock_opname', $data_so, 'id = '.$post['stock_opname_id']);
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      return false;
    }
    $this->db->trans_commit();
    return true;
  }
}