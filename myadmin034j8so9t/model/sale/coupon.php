<?php
class ModelSaleCoupon extends Model {
	public function addCoupon($data) {
        if(isset($data['combine_condition_value'])){
            $combine_condition_value_array =array();
            foreach($data['combine_condition_value'] as $value){
                $combine_condition_value_array[] =$value;
            }
            $combine_condition_value =implode(',',$combine_condition_value_array);
        }
        else{
            $combine_condition_value=null;
        }
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', buy_x ='".(int)$data['buy_x']."',discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "',combine_condition='".$data['combine_condition']."',combine_condition_value='".$combine_condition_value."', total = '" . (float)$data['total'] . "',condition_total = '" . (float)$data['condition_total'] . "',logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "',total_qty='".$data['total_qty']."',condition_total_qty='".$data['condition_total_qty']."',row_item_qty='".$data['row_item_qty']."',sku_condition='".$data['sku_condition']."',category_condition='".$data['category_condition']."',  date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		$coupon_id = $this->db->getLastId();
        if(isset($data['coupon_description'])){
            foreach($data['coupon_description'] as $lang_id=>$value){
                if(!$value['front_name']){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "',language_id=".$lang_id.", front_name = '" . $this->db->escape($data['coupon_description'][1]['front_name']) . "'");
                }else{
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "',language_id=".$lang_id.", front_name = '" . $this->db->escape($value['front_name']) . "'");
                }
                
            }
        }
		if (isset($data['coupon_product'])&&$data['coupon_product']) {
			foreach (explode(',',$data['coupon_product']) as $sku) {
                $product_id_query =$this->db->query("select product_id from ".DB_PREFIX."product where model='".$sku."' ");
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id_query->row['product_id'] . "'");
			}
		}	

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
        
	}

	public function editCoupon($coupon_id, $data) {
        if(isset($data['combine_condition_value'])){
            $combine_condition_value_array =array();
            foreach($data['combine_condition_value'] as $value){
                $combine_condition_value_array[] =$value;
            }
            $combine_condition_value =implode(',',$combine_condition_value_array);
        }
        else{
            $combine_condition_value=null;
        }
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', buy_x ='".(int)$data['buy_x']."',discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "',combine_condition='".$data['combine_condition']."',combine_condition_value='".$combine_condition_value."', total = '" . (float)$data['total'] . "', condition_total = '" . (float)$data['condition_total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "',total_qty='".$data['total_qty']."',condition_total_qty='".$data['condition_total_qty']."',row_item_qty='".$data['row_item_qty']."',sku_condition='".$data['sku_condition']."',category_condition='".$data['category_condition']."', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

		
        
        if(isset($data['coupon_description'])){
            $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
            foreach($data['coupon_description'] as $lang_id=>$value){
                $value['front_name'] =trim($value['front_name']);
                if(empty($value['front_name'])){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "',language_id=".$lang_id.", front_name = '" . $this->db->escape($data['coupon_description'][1]['front_name']) . "'");
                }else{
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "',language_id=".$lang_id.", front_name = '" . $this->db->escape($value['front_name']) . "'");
                }
            }
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_product'])&&$data['coupon_product']) {
			foreach (explode(',',$data['coupon_product']) as $sku) {
                $product_id_query =$this->db->query("select product_id from ".DB_PREFIX."product where model='".$sku."' ");

				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id_query->row['product_id'] . "'");
			}
		}	

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_category'])&&$data['coupon_category']) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}				
	}

	public function deleteCoupon($coupon_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");		
	}

	public function getCoupon($coupon_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");

		return $query->row;
	}

    public function getCouponDescription($coupon_id) {
        $res= array();
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
        foreach($query->rows as $item){
            $res[$item['language_id']] =$item;
        }
		return $res;
	}

	public function getCouponByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getCoupons($data = array()) {
		$sql = "SELECT coupon_id, name, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon";
        
        $sql .=" where 1";
        if(isset($data['filter'])&&$data['filter']['filter_id']){
            $sql .=" and coupon_id=".$data['filter']['filter_id'];
        }
        if(isset($data['filter'])&&$data['filter']['filter_name']){
            $sql .=" and name='".$data['filter']['filter_name']."'";
        }
        if(isset($data['filter'])&&$data['filter']['filter_code']){
            $sql .=" and code='".$data['filter']['filter_code']."'";
        }
        if(isset($data['filter'])&&$data['filter']['filter_start_from']){
            $sql .=" and date_start >='".$data['filter']['filter_start_from']."'";
        }
        if(isset($data['filter'])&&$data['filter']['filter_start_to']){
            $sql .=" and date_start <='".$data['filter']['filter_start_to']."'";
        }
        if(isset($data['filter'])&&$data['filter']['filter_end_from']){
            $sql .=" and date_end >='".$data['filter']['filter_end_from']."'";
        }
        if(isset($data['filter'])&&$data['filter']['filter_end_to']){
            $sql .=" and date_end <='".$data['filter']['filter_end_to']."'";
        }
		$sort_data = array(
            'coupon_id',
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY coupon_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}

		return $coupon_product_data;
	}

	public function getCouponCategories($coupon_id) {
		$coupon_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_category_data[] = $result['category_id'];
		}

		return $coupon_category_data;
	}

	public function getTotalCoupons() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");

		return $query->row['total'];
	}	

	public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}	

		$query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalCouponHistories($coupon_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");

		return $query->row['total'];
	}			
}
?>