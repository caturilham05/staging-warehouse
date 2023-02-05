<?php defined('BASEPATH') or exit('No direct script access allowed');

use WpOrg\Requests\Requests as Api;

use function PHPSTORM_META\map;

class Sales_model extends CI_Model
{



    public function __construct()
    {
        parent::__construct();
    }

    public function addSales(string $data)
    {
        return $this->insertBatch($data);
    }

    public function getSales()
    {
        return $this->db->get('sales')->result_array();
    }

    public function getSalesByCode($awb)
    {
    }

    public function insertBatch(string $data)
    {
        $this->load->helper('function_helper');
        $this->db->trans_begin();

        $api = Api::get($data);

        $api = json_decode($api->body);
        $key  = key($api);
        $array_data = $api->$key;
        $new_data = [];
        $data_post = [];

        foreach ($array_data as $value) {
            $key = array_keys((array)$value);
            $key = preg_replace('/\s.*/', '', $key);
            $warehouseId = $this->db->get_where('warehouses', ['name' => strtoupper($value->warehouse)])->row()->id;

            $value->warehouse = $warehouseId != null ? $warehouseId : null;
            $new_data[] = array_combine($key, (array)$value);
        }


        $check_data = $this->db->select(['order_no', 'awb_no'])->from('sales')->get()->result_array();

        foreach ($new_data as $v) {

            if (in_array($v['orderNo'], array_column($check_data, 'order_no')) && in_array($v['awbNo'], array_column($check_data, 'awb_no'))) {
                continue;
            }

            $product_id = explode('#', $v['productId']);
            $product_quantity = explode('#', $v['productQuantity']);
            foreach ($product_id as $key => $p) {

                $date = $v['createdDate'];
                // $date = explode(' ', $date);
                // $date[1] = ($date[1] == 'Desember') ? $this->convertDate($date[1]) : $date[1];
                // $date = implode(' ', $date);
                // $newDate = date('Y-m-d H:i:s', strtotime($date));

                $data_post[] = [
                    'order_no' => isset($v['orderNo']) ? $v['orderNo'] : null,
                    'awb_no' => isset($v['awbNo']) ? $v['awbNo'] : null,
                    'no_referensi' => isset($v['noReferensi']) ?  $v['noReferensi'] : null,
                    'warehouse_id' => isset($v['warehouse']) ? $v['warehouse'] : null,
                    'courier'   => isset($v['courier']) ? $v['courier'] : null, //index 4
                    'status'    => isset($v['status']) ? $v['status'] : null,
                    'service' => isset($v['service']) ? $v['service'] : null, //index 6
                    'type'  => isset($v['type']) ? $v['type'] : null,
                    'created_date' => $date, //index 8
                    //'created_date' => date('Y-m-d H:i:s', strtotime($v['createdDate'])), //index 8
                    'dispatch_date' => isset($v['dispatchDate']) ? $v['dispatchDate'] : null,
                    'delivered_date' => isset($v['deliveredDate']) ? $v['deliveredDate'] : null,
                    'returned_date' => isset($v['returnedDate']) ? $v['returnedDate'] : null,
                    'package_price' => isset($v['packagePrice']) ? $v['packagePrice'] : null, //index 12
                    'insurance' => isset($v['insurance']) ? $v['insurance'] : null,
                    'shipping_price' => isset($v['shippingPrice']) ? $v['shippingPrice'] : null,
                    'shipping_cashback' => isset($v['shippingCashback']) ? $v['shippingCashback'] : null,
                    'cod_value' => isset($v['codValue']) ? $v['codValue'] : null, //index 16
                    'cod_fee' => isset($v['codFee']) ? $v['codFee'] : null,
                    'cod_disbursement' => isset($v['codDisbursement']) ? $v['codDisbursement'] : null,
                    'shipper_name' => isset($v['shipperName']) ? $v['shipperName'] : null, //index 19
                    'shipper_phone' => isset($v['shipperPhone']) ? $v['shipperPhone'] : null,
                    'shipper_address' => isset($v['shipperAddress']) ? $v['shipperAddress'] : null, //index 21
                    'shipper_city' => isset($v['shipperCity']) ? $v['shipperCity'] : null,
                    'shipper_subdistrict' => isset($v['shipperSubdistrict']) ? $v['shipperSubdistrict'] : null,
                    'shipper_zip_code' => isset($v['shipperZipCode']) ? $v['shipperZipCode'] : null,
                    'receiver_name' => isset($v['receiverName']) ? $v['receiverName'] : null, //index 25
                    'receiver_phone' => isset($v['receiverPhone']) ? $v['receiverPhone'] : null,
                    'receiver_address' => isset($v['receiverAddress']) ? $v['receiverAddress'] : null,
                    'receiver_city' => $v['receiverCity'],
                    'receiver_subdistrict' => isset($v['receiverSubdistrict']) ? $v['receiverSubdistrict'] : null,
                    'receiver_zip_code' => isset($v['receiverZipCode']) ? $v['receiverZipCode'] : null, //index 30
                    'goods_description' => isset($v['goodsDescription']) ? $v['goodsDescription'] : null, //index 31
                    'quantity' => isset($v['quantity']) ? $v['quantity'] : null, //index 32
                    'weight' => isset($v['weight']) ? $v['weight'] : null, //index 33
                    'dimension_size' => isset($v['lengthXWidthXHeight']) ? $v['lengthXWidthXHeight'] : null, //index 34
                    'shipping_note' => isset($v['shippingNote']) ? $v['shippingNote'] : null,
                    'last_tracking_status' => isset($v['lastTrackingStatus'])  ? $v['lastTrackingStatus'] : null,
                    'product_id' => $p,
                    'product_quantity' => $product_quantity[$key],
                ];
            }
        }

        $this->db->insert_batch('sales', $data_post);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return false;
        } else {
            $this->db->trans_commit();
            return true;
        
        }
    }

    public function delete($id)
    {

        return $this->db->where('id', $id)->delete('sales');
    }

    public function update_status_packing($id)
    {
        return $this->db->where('id', $id)->update('sales', ['status_packing' => 'sent']);
    }

    public function convertDate($month){
        switch ($month) {
            case 'Januari':
                return 'January';
                break;
            case 'Februari':
                return 'February';
                break;
            case 'Maret':
                return 'March';
                break;
            case 'April':
                return 'April';
                break;
            case 'Mei':
                return 'May';
                break;
            case 'Juni':
                return 'June';
                break;
            case 'Juli':
                return 'July';
                break;
            case 'Agustus':
                return 'August';
                break;
            case 'September':
                return 'September';
                break;
            case 'Oktober':
                return 'October';
                break;
            case 'November':
                return 'November';
                break;
            case 'Desember':
                return 'December';
                break;
            default:
               return 'January';
                break;
        }
    }

    public function add_sales_manually($post)
    {
        $this->load->helper('function_helper');
        $order_exist = $this->db->get('sales')->row()->order_no;

        if (empty($post['shipper_id'])) return false;

        $address_book = $this->db->get_where('address_books', ['id' => $post['shipper_id']], 1)->row_array();
        if (empty($address_book)) return false;
        $address_book['phone'] = preg_match('~08~is', $address_book['phone']) ? preg_replace('~08~is', '628', $address_book['phone']) : $address_book['phone'];

        $master_location = $this->db->get_where('master_locations', ['id' => $address_book['location_id']], 1)->row_array();
        if (empty($master_location)) return false;

        $warehouse = $this->db->get_where('warehouses', ['id' => $post['warehouse_id']], 1)->row_array();
        if (empty($warehouse)) return false;

        $product_q = $this->db->where_in('code', $post['product_code']);
        $products  = $product_q->get('items')->result_array();
        if (empty($products)) return false;

        foreach ($post['product_code'] as $key => $value)
        {
            $product_groups['product_code']      = $value;
            $product_groups['product_quantity']  = !empty($post['product_quantity'][$key]) ?  $post['product_quantity'][$key] : 0;
            $product_groups['weight']            = !empty($post['weight'][$key]) ?  $post['weight'][$key] : 0;
            $product_groups['dimension_size']    = !empty($post['dimension_size'][$key]) ?  $post['dimension_size'][$key] : '';
            $product_groups['goods_description'] = !empty($post['goods_description'][$key]) ?  $post['goods_description'][$key] : '';
            $product_groups_set[$value]          = $product_groups;
        }

        foreach ($products as $key => $value)
        {
            $data[] = [
                'order_no'             => $post['order_no'],
                'awb_no'               => $post['awb_no'],
                'warehouse_id'         => $post['warehouse_id'],
                'courier'              => $post['courier'],
                'service'              => $post['service'],
                'type'                 => $post['type'],
                'package_price'        => $post['package_price'],
                'shipping_price'       => $post['shipping_price'],
                'shipper_id'           => $post['shipper_id'],
                'shipper_name'         => $address_book['name'],
                'shipper_phone'        => $address_book['phone'],
                'shipper_address'      => $address_book['address'],
                'shipper_city'         => $master_location['title'],
                'shipper_subdistrict'  => $master_location['detail'],
                'shipper_zip_code'     => $master_location['postcode'],
                'receiver_name'        => $post['receiver_name'],
                'receiver_phone'       => $post['receiver_phone'],
                'receiver_city'        => $post['receiver_city'],
                'receiver_subdistrict' => $post['receiver_subdistrict'],
                'receiver_zip_code'    => $post['receiver_zip_code'],
                'receiver_address'     => $post['receiver_address'],
                'product_id'           => !empty($product_groups_set[$value['code']]) ? $value['code'] : 0,
                'product_quantity'     => !empty($product_groups_set[$value['code']]['product_quantity']) ? $product_groups_set[$value['code']]['product_quantity'] : 0,
                'weight'               => !empty($product_groups_set[$value['code']]['weight']) ? $product_groups_set[$value['code']]['weight'] : 0,
                'dimension_size'       => !empty($product_groups_set[$value['code']]['dimension_size']) ? $product_groups_set[$value['code']]['dimension_size'] : '',
                'goods_description'    => !empty($product_groups_set[$value['code']]['goods_description']) ? $product_groups_set[$value['code']]['goods_description'] : '',
            ];
        }

        $this->db->trans_begin();
        $this->db->insert_batch('sales', $data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
}
