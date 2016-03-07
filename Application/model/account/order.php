<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
            $status_query  =$this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_query->row['order_status_id'] . "'  and language_id='".(int)$this->config->get('config_language_id')."'");
            if ($status_query->num_rows) {
				$order_status_text = $status_query->row['name'];
			} else {
				$order_status_text = 'pedding';
			}
			return array(
				'order_id'                => $order_query->row['order_id'],
                'order_number'                          => $order_query->row['order_number'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
                'order_status'         => $order_status_text,
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip'],
                'parent_id '              => $order_query->row['parent_id'],
                'is_parent'               => $order_query->row['is_parent'],
			);
		} else {
			return false;	
		}
	}

	public function getOrders($data =array(),$start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}	
		$sql ="SELECT o.order_id,o.order_number,o.shipping_method,ost.track_number, o.firstname, o.lastname, os.name as status,o.order_status_id, o.date_added, o.total, o.currency_code, o.currency_value,o.parent_id,o.is_parent FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id)
        LEFT JOIN " . DB_PREFIX . "order_shipment osp on osp.order_id=o.order_id  LEFT JOIN " . DB_PREFIX . "order_shipment_track ost on ost.shippment_id=osp.shippment_id 
        WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if($data['order_number']){
			$sql .=" AND o.order_number='".$data['order_number']."'";
		}
		if($data['date_from']&&$data['date_from']!=1){
			$sql .=" AND o.date_added>='".$this->db->escape($data['date_from'])."'";
		}
		if($data['date_to']&&$data['date_to']!=1){
			$sql .=" AND o.date_added<='".$this->db->escape($data['date_to'])."'";
		}
        if(isset($data['is_parent'])){
            $sql .=" AND o.is_parent ='".intval($data['is_parent'])."'";
        }
        if(isset($data['parent_id'])){
            $sql .=" AND o.parent_id ='".intval($data['parent_id'])."'";
        }
        
        if(isset($data['order_status_id'])){
            $sql .=" AND o.order_status_id ='".intval($data['order_status_id'])."'";
        }
        
		$sql.=" ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);	
        $data = $query->rows;
        
		return $data;
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}	

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.notify = '1' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}	

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");

		return $query->rows; 
	}	

	public function getTotalOrders($data=array()) {
		$sql ="SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'";
		if($data['order_number']){
			$sql .=" AND order_number='".(int)$data['order_number']."'";
		}
		if($data['date_from']&&$data['date_from']!=1){
			$sql .=" AND date_added>='".$this->db->escape($data['date_from'])."'";
		}
		if($data['date_to']&&$data['date_to']!=1){
			$sql .=" AND date_added<='".$this->db->escape($data['date_to'])."'";
		}
        if($data['is_parent']){
            $sql .=" AND is_parent ='".$data['is_parent']."'";
        }
        if($data['parent_id']){
            $sql .=" AND parent_id ='".$data['parent_id']."'";
        }
        if(isset($data['order_status_id'])){
            $sql .=" AND o.order_status_id ='".intval($data['order_status_id'])."'";
        }
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
    

    public function getOrderId($order_number){
        $query =$this->db->query("select order_id from ". DB_PREFIX."order where order_number='".$order_number."'");
        if($query->num_rows){
            return $query->row['order_id'];
        }
        else{
            return false;
        }
    }

    public function getOrderTrack($order_id){
        $query = $this->db->query("SELECT st.*  FROM ".DB_PREFIX."order_shipment as s left join ".DB_PREFIX."order_shipment_track as st on s.shippment_id=st.shippment_id where s.order_id=".$order_id);
        if($query->num_rows){
            if($query->row['track_id']){
                 return $query->rows;
            }
           
        }
        else{
            return false;    
        }
    }
    
    public function getLastCustomerNoReviewOrders($limit = 2) {
		if ($limit < 1) {
			$limit = 1;
		}
        $customer_id = $this->session->data['customer_id'];
		$sql ="SELECT o.order_id, o.order_number,p.product_id,p.model from oc_order o LEFT JOIN oc_order_product p on  o.order_id = p.order_id  where o.customer_id = '{$customer_id}' AND o.order_status_id = 5 AND  o.is_parent = 0 order by o.order_id desc limit 0,100";
		$query = $this->db->query($sql);
        $data = array();
        $i = 0;
        foreach($query->rows as $row){
            $order_number = $row['order_number'];
            $order_id     = $row['order_id'];
            $product_id   = $row['product_id'];
            $_exist_sql   = "select count(*) as cnt from oc_review where order_number = '{$order_number}' and  customer_id='{$customer_id}' and product_id = '{$product_id}'";
            $_exist_rs    = $this->db->query($_exist_sql);

            $_exist_row = $_exist_rs->row;
            if($_exist_row['cnt'] == 0){
                $data['order-'.$order_number] = array(
                    'order_id'     => $order_id,
                    'order_number' => $order_number
                );
                
                if(count($data) >= $limit){
                    break;
                }
                $i ++ ;
            }
        }
        
		return $data;
	}


}
?>