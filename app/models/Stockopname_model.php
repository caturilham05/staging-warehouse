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

  public function add_stockopname($post)
  {
    if (empty($post)) return false;
    
    $product = $this->db->select('id, name')->from('items')->where('code', $post['product_code'])->get()->row_array();
    if (empty($product)) return false;

    $data = [
      'stock_opname_id' => $post['stock_opname_id'],
      'product_id'      => $product['id'],
      'product_code'    => $post['product_code'],
      'product_name'    => $product['name'],
      'qty'             => $post['qty'],
      'status'          => 1
    ];

    $insert = $this->db->insert('stock_opname_product', $data);
    return $insert;
  }
}