<?php defined('BASEPATH') or exit('No direct script access allowed');

use WpOrg\Requests\Requests as Api;

use function PHPSTORM_META\map;

class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('function_helper');
        $this->config->load('config_api');
    }

    public function addSales(string $data)
    {
        return $this->insertBatch($data);
    }

    public function getSales()
    {
        return $this->db->get('sales')->result_array();
    }

    public function getSalesByInvoice($invoice)
    {
        // $q = $this->db->get_where('sales', array('order_no' => $invoice));
        $sales_q = $this->db->where('order_no', $invoice);
        $sales  = $sales_q->get('sales')->result_array();
        if (empty($sales)) {
            return false;
        }
        return $sales;
        // if ($q->num_rows() > 0) {
        //     return $q->row_array();
        // }
        // return FALSE;
    }

    public function get_sales_process($warehouse_id)
    {
        $q = !empty($warehouse_id) ? $this->db->get_where('sales', array('warehouse_id' => $warehouse_id, 'status' => 'process', 'status_packing' => 'process'), 1) : $this->db->get_where('sales', array('status' => 'process', 'status_packing' => 'process'), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }
        return FALSE;
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

    public function api_jne_create_waybill_model(
        $SHIPPER_NAME     = '',
        $SHIPPER_ADDR1    = '',
        $SHIPPER_CITY     = '',
        $SHIPPER_ZIP      = '',
        $SHIPPER_REGION   = '',
        $SHIPPER_CONTACT  = '',
        $SHIPPER_PHONE    = '',
        $RECEIVER_NAME    = '',
        $RECEIVER_ADDR1   = '',
        $RECEIVER_CITY    = '',
        $RECEIVER_ZIP     = '',
        $RECEIVER_REGION  = '',
        $RECEIVER_CONTACT = '',
        $RECEIVER_PHONE   = '',
        $ORIGIN_DESC      = '',
        $SERVICE_CODE     = '',
        $DESTINATION_DESC = '',
        $WEIGHT           = '',
        $QTY              = '',
        $GOODS_DESC       = '',
        $DELIVERY_PRICE   = '',
        $COD_FLAG         = '',
        $COD_AMOUNT       = '',
        $BOOK_CODE        = 0,
        $SHIPPER_COUNTRY  = 'ID',
        $RECEIVER_COUNTRY = 'ID',
        $AWB_TYPE         = 'SPECIFIC',
        $CUST_ID          = 80990400,
        $BRANCH           = 'BDO000'
    )
    {
        $BOOK_CODE       = random_int(0000000000000000, 9999999999999999);
        $SHIPPER_ZIP     = empty($SHIPPER_ZIP) ? 1 : $SHIPPER_ZIP;
        // $jne_url      = 'http://apiv2.jne.co.id:10102/job/direct';
        $jne_url         = 'https://apiv2.jne.co.id:10206/job/direct';
        $jne_username    = $this->config->item('jne_username');
        $jne_api_key     = $this->config->item('jne_api_key');
        // $jne_username = 'TESTAPI';
        // $jne_api_key  = '25c898a9faea1a100859ecd9ef674548';
        $header          = [
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: ".$_SERVER['HTTP_USER_AGENT'],
        ];

        $body = 'username='.$jne_username.'&api_key='.$jne_api_key.'&SHIPPER_NAME='.$SHIPPER_NAME.'&SHIPPER_ADDR1='.$SHIPPER_ADDR1.'&SHIPPER_CITY='.$SHIPPER_CITY.'&SHIPPER_ZIP='.$SHIPPER_ZIP.'&SHIPPER_REGION='.$SHIPPER_REGION.'&SHIPPER_COUNTRY='.$SHIPPER_COUNTRY.'&SHIPPER_CONTACT='.$SHIPPER_CONTACT.'&SHIPPER_PHONE='.$SHIPPER_PHONE.'&RECEIVER_NAME='.$RECEIVER_NAME.'&RECEIVER_ADDR1='.$RECEIVER_ADDR1.'&RECEIVER_CITY='.$RECEIVER_CITY.'&RECEIVER_ZIP='.$RECEIVER_ZIP.'&RECEIVER_REGION='.$RECEIVER_REGION.'&RECEIVER_COUNTRY='.$RECEIVER_COUNTRY.'&RECEIVER_CONTACT='.$RECEIVER_CONTACT.'&RECEIVER_PHONE='.$RECEIVER_PHONE.'&ORIGIN_DESC='.$ORIGIN_DESC.'&SERVICE_CODE='.$SERVICE_CODE.'&DESTINATION_DESC='.$DESTINATION_DESC.'&WEIGHT='.$WEIGHT.'&QTY='.$QTY.'&GOODS_DESC='.$GOODS_DESC.'&DELIVERY_PRICE='.$DELIVERY_PRICE.'&BOOK_CODE='.$BOOK_CODE.'&AWB_TYPE='.$AWB_TYPE.'&CUST_ID='.$CUST_ID.'&BRANCH='.$BRANCH.'&COD_FLAG='.$COD_FLAG.'&COD_AMOUNT='.$COD_AMOUNT;
        $out  = curl_custom($jne_url, $header, $body, 'POST');
        return $out;
    }

    public function add_sales_manually($post, $is_import = 0)
    {
        if (empty($is_import))
        {
            if (isset($post['submit_order']))
            {
                $address_book = $this->db->get_where('address_books', ['id' => $post['shipper_id']], 1)->row_array();
                if (empty($address_book)) return false;

                $master_location = $this->db->get_where('master_locations', ['id' => $address_book['location_id']], 1)->row_array();
                if (empty($master_location)) return false;

                $sales            = $this->getSalesbyInvoice($post['order_no']);
                $product_quantity = 0;

                foreach ($post['id'] as $key => $value)
                {
                    $product_quantity += $sales[$key]['product_quantity'];
                }


                $params_awb_cod_flag   = '';
                $params_awb_cod_amount = '';
                if ($post['type'] == 'COD PICKUP')
                {
                    $params_awb_cod_flag   = 'YES';
                    $params_awb_cod_amount = $post['package_price'];
                }

                $create_awb = $this->api_jne_create_waybill_model(
                    $address_book['name'],
                    $address_book['address'],
                    $master_location['city_name'],
                    $master_location['zip_code'],
                    $master_location['city_name'],
                    $address_book['name'],
                    $address_book['phone'],
                    $post['receiver_name'],
                    $post['receiver_address'],
                    $post['receiver_city'],
                    $post['receiver_zip_code'],
                    $post['receiver_city'],
                    $post['receiver_name'],
                    $post['receiver_phone'],
                    $master_location['city_name'],
                    $post['service'],
                    $post['receiver_city'],
                    $sales[0]['weight'],
                    $product_quantity,
                    $post['goods_description'],
                    $post['shipping_price'],
                    $params_awb_cod_flag,
                    $params_awb_cod_amount
                );

                foreach ($post['id'] as $key => $value)
                {
                    $data[] = [
                        'id'                   => $value,
                        'awb_no'               => $create_awb['no_tiket'],
                        'order_no'             => $post['order_no'],
                        'receiver_name'        => $post['receiver_name'],
                        'receiver_phone'       => $post['receiver_phone'],
                        'receiver_city'        => $post['receiver_city'],
                        'receiver_subdistrict' => $post['receiver_subdistrict'],
                        'receiver_zip_code'    => $post['receiver_zip_code'],
                        'receiver_address'     => $post['receiver_address'],
                        'shipper_id'           => $post['shipper_id'],
                        'shipper_name'         => $address_book['name'],
                        'shipper_phone'        => $address_book['phone'],
                        'shipper_address'      => $address_book['address'],
                        'shipper_city'         => $master_location['city_name'],
                        'shipper_subdistrict'  => $master_location['district_name'],
                        'shipper_zip_code'     => $master_location['zip_code'],
                        'courier'              => $post['courier'],
                        'service'              => $post['service'],
                        'shipping_price'       => $post['shipping_price'],
                        'package_price'        => $post['package_price'],
                        'type'                 => $post['type'],
                        'shipping_note'        => $post['shipping_note'],
                        'goods_description'    => $post['goods_description'],
                        'status'               => 'process packing',
                        'status_packing'       => 'process packing',
                    ];
                }
            }
            else
            {
                $product_q = $this->db->where_in('code', $post['product_code']);
                $products  = $product_q->get('items')->result_array();
                if (empty($products)) return false;

                foreach ($post['product_code'] as $key => $value)
                {
                    $product_groups['product_code']     = $value;
                    $product_groups['product_quantity'] = !empty($post['product_quantity'][$key]) ?  $post['product_quantity'][$key] : 0;
                    $product_groups['weight']           = !empty($post['weight'][$key]) ?  $post['weight'][$key] : 0;
                    $product_groups_set[$value]         = $product_groups;
                }

                foreach ($products as $key => $value)
                {
                    $data[] = [
                        'order_no'             => $post['order_no'],
                        'warehouse_id'         => $post['warehouse_id'],
                        'shipper_city_code'    => $post['shipper_city_code'],
                        'receiver_destination' => $post['receiver_destination'],
                        'weight'               => $post['weight'],
                        'status'               => 'process',
                        'status_packing'       => 'process',
                        'created_date'         => date('Y-m-d H:i:s'),
                        'product_id'           => !empty($product_groups_set[$value['code']]) ? $value['code'] : 0,
                        'product_quantity'     => !empty($product_groups_set[$value['code']]['product_quantity']) ? $product_groups_set[$value['code']]['product_quantity'] : 0,
                    ];
                }
            }
        }
        else
        {
            if (empty($post)) return false;
            $data = $post;
        }

        $this->db->trans_begin();
        if (isset($post['submit_order']))
        {
            $this->db->update_batch('sales', $data, 'id');
        }
        else
        {
            $this->db->insert_batch('sales', $data);
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return !empty($is_import) ? $post[0]['order_no'] : $post['order_no'];
    }

    public function getSalesThisWeeks()
    {
        $d          = strtotime("today");
        $start_week = strtotime("last sunday midnight",$d);
        $end_week   = strtotime("next saturday",$d);
        $start      = date("Y-m-d",$start_week); 
        $end        = date("Y-m-d",$end_week);

        $this->db->select('order_no, SUM(package_price) AS package_price, SUM(product_quantity) AS qty, created_at')->where('DATE(created_at) >=', date('Y-m-d', strtotime($start)))->where('DATE(created_at) <=', date('Y-m-d', strtotime($end)))->group_by('order_no');
        $q = $this->db->get("sales");
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getSalesLastWeeks()
    {
        $previous_week = strtotime("-1 week +1 day");
        $start_week    = strtotime("last sunday midnight",$previous_week);
        $end_week      = strtotime("next saturday",$start_week);
        $start_week    = date("Y-m-d",$start_week);
        $end_week      = date("Y-m-d",$end_week);

        $this->db->select('order_no, SUM(package_price) AS package_price, SUM(product_quantity) AS qty, created_at')->where('DATE(created_at) >=', date('Y-m-d', strtotime($start_week)))->where('DATE(created_at) <=', date('Y-m-d', strtotime($end_week)))->group_by('order_no');
        $q = $this->db->get("sales");
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
}
