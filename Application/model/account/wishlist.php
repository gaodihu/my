<?php
class ModelAccountWishlist extends Model {
	
	public function addWishlist($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_wishlist SET customer_id =".$data['customer_id'].",product_id=".$data['product_id'].",add_time = NOW() ");
	}
	public function delWishlist($data) {
		$this->db->query("DELETE from " . DB_PREFIX . "customer_wishlist where customer_id =".$data['customer_id']." and product_id=".$data['product_id']);
	}
	public function getWishlist($customer_id,$product_id) {
		$query=$this->db->query("select * from " . DB_PREFIX . "customer_wishlist where customer_id =".$customer_id." and product_id=".$product_id);
		return $query->row;
	}
	public function getWishlists($customer_id,$start,$limit) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_wishlist where customer_id =".$customer_id." order by  wish_id desc  limit ".$start.",".$limit);
		return $query->rows;
	}

	public function deleteWishlist($wish_id,$customer_id) {
		$sql ="delete from ". DB_PREFIX . "customer_wishlist where wish_id =".$wish_id." and customer_id = " . (int)$customer_id ;
		$query = $this->db->query($sql);	

		return $query->rows;
	}

	public function getTotalWishlists($customer_id) {
		$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = " . (int)$customer_id);

		return $query->row;
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
			$sql .=" AND order_number='".$data['order_number']."'";
		}
		if($data['date_from']&&$data['date_from']!=1){
			$sql .=" AND date_added>='".$data['date_from']."'";
		}
		if($data['date_to']&&$data['date_to']!=1){
			$sql .=" AND date_added<='".$data['date_to']."'";
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
}
?>