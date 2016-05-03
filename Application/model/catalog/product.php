<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
        $review_store_where ='';
        $rating_store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $rating_store_where =" and r1.store_id=".(int)$this->config->get('config_store_id');
            $review_store_where =" and r2.store_id=".(int)$this->config->get('config_store_id');
        }
       
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND (pd2.customer_group_id = '" . (int)$customer_group_id . "' or pd2.customer_group_id =0) AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00 00:00:00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00 00:00:00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND (ps.customer_group_id = '" . (int)$customer_group_id . "' or ps.customer_group_id=0) AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, p.status,p.stock_status_id,(SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' AND r1.is_publish = '1' ".$rating_store_where." GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' AND r2.is_publish='1' ".$review_store_where." GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
        WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'  AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
            $url_path = $query->row['url_path'];
            if(strpos($url_path,'.html') === false){
                     $url_path = $url_path.".html";
            }
            $point =$query->row['special']?floor($query->row['special']):floor($query->row['price']);
            $special_price =$query->row['special'];
            //商品是否有专属价格
            $exclusive_price_info =$this->realy_exclusive_price($product_id);
            if($exclusive_price_info){
                 $special_price =$exclusive_price_info['price'];
            }
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
                'meta_title'             => $query->row['title'],
				'description'      => $query->row['description'],
                'shipping_description'      => $query->row['shipping_description'],
				'meta_description' => $query->row['meta_description'],
                'meta_keyword'     => $query->row['meta_keyword'],
				'packaging_list'     => $query->row['packaging_list'],
                'read_more'     => $query->row['read_more'],
                'application_image'     => $query->row['application_image'],
                'size_image'     => $query->row['size_image'],
                'features'     => $query->row['features'],
                'installation_method'     => $query->row['installation_method'],
                'video'     => $query->row['video'],
                'notes'     => $query->row['notes'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          =>$special_price ,
				'points'           => $point,
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
                'deals_limit_number'          => $query->row['deals_limit_number'],
                'is_new'          => $query->row['is_new'],
                'is_hot'          => $query->row['is_hot'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
                'url_path'         => $url_path,
                'status'           => $query->row['status'],
                'stock_status_id'  => $query->row['stock_status_id'],
                'battery_type'     => $query->row['battery_type'],
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
        
        $_cache_product_data = $this->cache->get('category-product-'.  md5(serialize($data).$this->config->get('config_language_id').$this->config->get('store_id')));
        if($_cache_product_data){
            return $_cache_product_data;
        }
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
        
		//$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total,count(review_id) as toal_review  FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00 00:00:00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00 00:00:00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,(select sum(quantity)  from oc_order_product op where op.product_id=p.product_id  GROUP BY op.product_id )  as qty";
		$sql = "SELECT distinct p.product_id,p2s.sales_num as sales_num,(select count(review_id) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' AND r1.is_publish = '1') as toal_review,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
		if (!empty($data['filter_category_id'])) {
			
			$sql .= " FROM " . DB_PREFIX . "product_to_category p2c left join ". DB_PREFIX ."category c on p2c.category_id=c.category_id";
			

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
        /*
		 if(!empty($data['attr_id'])||!empty($data['option_id'])){

			//$sql .="LEFT JOIN " . DB_PREFIX . "product_attribute as pa on  pa.product_id=p.product_id ";
            $attr_id_array=array();
            $attr_str ='';
			if(strpos($data['attr_id'],'-')!==false){
				$attr_id_array =explode('-',$data['attr_id']);
			}
			else{
				$attr_id_array[] =$data['attr_id'];
			}
            $option_id_array=array();
			if(strpos($data['option_id'],'-')!==false){
				$option_id_array=explode('-',$data['option_id']);
			}
			else{
				$option_id_array[] =$data['option_id'];
			}
            foreach($option_id_array as $key=>$item){
                $as ="pa".($key+2);
                 $attr_str.=" INNER JOIN  ".DB_PREFIX."product_attribute as ". $as." on ". $as.".product_id=p.product_id and ".$as.".attribute_id=".$attr_id_array[$key]." and ".$as.".attr_option_value_id=".$item;
            }
            $sql .=$attr_str;
		}
        */
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
		if (!empty($data['filter_category_id'])) {
			$query_path = $this->db->query("select path from " . DB_PREFIX ."category where category_id='".$data['filter_category_id']."'");
			$path =$query_path->row;
			$sql .= " AND c.path like '".$path['path']."%'";			
			
			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}	
        
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		//$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'sales_num',
			'p.price',
			'rating',
			'p.sort_order',
			'toal_review',
			'p.date_added',
			'qty'
		);	
        if (!empty($data['filter_category_id'])&&!isset($this->request->get['sort'])) {
            $sql .= " ORDER BY p2c.position DESC,";
        }
        else{
            $sql .= " ORDER BY ";
        }
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special  ELSE p.price END)";
               // $sql .= " ORDER BY p.price ";
			} 
			else {
				$sql .= $data['sort'];
			}
		} else {
			$sql .= " sales_num";	
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, p.product_id DESC";
		} else {
			$sql .= " ASC, p.product_id ASC";
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
		$product_data = array();
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
        $this->cache->set('category-product-'.  md5(serialize($data).$this->config->get('config_language_id').$this->config->get('store_id')),$product_data);
		return $product_data;
	}

	public function getProductSpecials($data = array(),$start_time='',$end_time='',$filter='') {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$where_time ='';
		//today's deal 今日特价
		if($start_time){
			$where_time .="AND (ps.date_start = '0000-00-00 00:00:00' OR (ps.date_start < '$start_time' and ps.date_end>'$start_time'))";
		}
		else{
			$where_time .='';
		}
		if($end_time){
			$where_time .="AND (ps.date_end = '0000-00-00 00:00:00' OR (ps.date_end > '$end_time' and ps.date_start<'$end_time'))";
		}
		else{
			$where_time .='';
		}
        if(!empty($filter)){
            $where_time.= " AND ".$filter;
        }
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1'  AND p.stock_status_id=7 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND (ps.customer_group_id = '" . (int)$customer_group_id . "'  or ps.customer_group_id=0 ) ".$where_time." GROUP BY ps.product_id";
		/*
		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}
         */
        $sql .= ' ORDER BY ps.date_end ASC';
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$product_data = array();

		$query = $this->db->query($sql);
        
		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	//得到商品特价信息
	public function getProductSpecial($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$sql = "SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id =".$product_id." and (customer_group_id = " . (int)$customer_group_id." or customer_group_id =0) AND ((date_start = '0000-00-00 00:00:00' OR date_start < NOW()) AND (date_end = '0000-00-00 00:00:00' OR date_end > NOW()))  order by priority asc limit 1" ;
		
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getLatestProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit,$is_random=false) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$product_data = array();
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			
			//$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			if(!$is_random){
			    $query = $this->db->query("SELECT p.product_id, p2s.sales_num AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY total DESC LIMIT " . (int)$limit);
                foreach ($query->rows as $result) { 
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                }
            }
			elseif($is_random){
                $count =$this->getTotalProducts();
                
                $rand_array =array();
                for($i=1;$i<=$limit;$i++){
                    $rand =mt_rand(0,$count-1);
                    $query = $this->db->query("SELECT p.product_id, p2s.sales_num AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1'   AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  LIMIT " .$rand.",1");
                    $result =$query->row;
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                   
                }
				
				
			}
			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}
        
		return $product_data;
	}

    public function getBestSellerProductsByCatgory($limit,$category_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$product_data = array();

        $query = $this->db->query("SELECT p.product_id, p2s.sales_num AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN ".DB_PREFIX."product_to_category as p2c on (p.product_id = p2c.product_id) WHERE p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' and p2c.category_id=".$category_id."  ORDER BY total DESC LIMIT " . (int)$limit);
        foreach ($query->rows as $result) { 
            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
        }
		return $product_data;
	}

    //deals页面top-sellers,使用top-seller.html页面的商品
    public function getBestSellerProductsByDeals($limit) {
		$sql = "SELECT DISTINCT p2c.product_id,p2s.sales_num AS total  FROM " . DB_PREFIX  . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2c.category_id=253 and  p.status = '1'  AND p.stock_status_id=7 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY total DESC limit ".$limit;
		$product_data = array();

		$query = $this->db->query($sql);
        
		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();
        $product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name, aov.option_value as text FROM " . DB_PREFIX . "new_product_attribute pa LEFT JOIN " . DB_PREFIX . "new_attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "new_attribute_description ad ON (pa.attribute_id = ad.attribute_id) left join ".DB_PREFIX."new_attribute_option_value as aov on pa.attr_option_value_id=aov.option_id WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND aov.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");
        foreach ($product_attribute_query->rows as $product_attribute) {
            $product_attribute_data[] = array(
                'attribute_id' => $product_attribute['attribute_id'],
                'name'         => $product_attribute['name'],
                'text'         => $product_attribute['text']		 	
            );
        }
		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND (customer_group_id = '" . (int)$customer_group_id . "' or customer_group_id =0)  AND quantity > 1 AND ((date_start = '0000-00-00 00:00:00' OR date_start < NOW()) AND (date_end = '0000-00-00 00:00:00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}
	//得到商品Customers Also Bought
	/*
	* 1.取得商品所在分类
	* 2.取得对应时间内该分类下销量最高的5个商品
	*/
	public function getProductAlsoBought($product_id,$limit=5){
		$query_cat =$this->db->query("select category_id from ".DB_PREFIX."product_to_category where product_id=".$product_id);
		$cat_in =array();
        $cat_in_str = '';
        if($query_cat->num_rows){
            foreach($query_cat->rows as $res){
                $cat_in[]=$res['category_id'];
                $cat_in_str .= "'" . $res['category_id']."',";
            }
            if($cat_in_str){
                $cat_in_str = substr($cat_in_str,0,-1);
            }
            $sql =" select p.product_id,pd.name,p.price,p.image,p.tax_class_id from ".DB_PREFIX."product_to_category as pc left join ".DB_PREFIX."product as p on pc.product_id=p.product_id left join ".DB_PREFIX."product_description as pd on pd.product_id=pc.product_id where pc.category_id in (".$cat_in_str.") AND p.stock_status_id=7 and  pd.language_id ='".(int)$this->config->get('config_language_id')."' order by p.salesnum desc limit ".$limit;
            $query =$this->db->query($sql);
            return $query->rows;
        }else{
            return false;
        }
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) { 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}	

    public function getCategoryInfo($product_id){
      $query = $this->db->query("SELECT c.category_id,cd.name FROM " . DB_PREFIX . "product_to_category ptc left join ". DB_PREFIX."category as c on ptc.category_id=c.category_id left join ".DB_PREFIX."category_description as cd on cd.category_id=c.category_id WHERE  ptc.product_id = '" . (int)$product_id . "' and cd.language_id=".(int)$this->config->get('config_language_id'));
       return $query->row; 
    
    }

	public function getTotalProducts($data = array()) {
        $cache_key = 'category-file-'.  md5(serialize($data));
        $count_cnt = $this->cache->get($cache_key);
        if($count_cnt){
              return $count_cnt;
        }
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total"; 

		if (!empty($data['filter_category_id'])) {
			
			$sql .= " FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN ".DB_PREFIX."category c ON p2c.category_id =c.category_id";
			

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
        
        /*
         if(!empty($data['attr_id'])||!empty($data['option_id'])){

			//$sql .="LEFT JOIN " . DB_PREFIX . "product_attribute as pa on  pa.product_id=p.product_id ";
            $attr_id_array=array();
            $attr_str ='';
			if(strpos($data['attr_id'],'-')!==false){
				$attr_id_array =explode('-',$data['attr_id']);
			}
			else{
				$attr_id_array[] =$data['attr_id'];
			}
            $option_id_array=array();
			if(strpos($data['option_id'],'-')!==false){
				$option_id_array=explode('-',$data['option_id']);
			}
			else{
				$option_id_array[] =$data['option_id'];
			}
            foreach($option_id_array as $key=>$item){
                $as ="pa".($key+2);
                 $attr_str.=" INNER JOIN  ".DB_PREFIX."product_attribute as ". $as." on ". $as.".product_id=p.product_id and ".$as.".attribute_id=".$attr_id_array[$key]." and ".$as.".attr_option_value_id=".$item;
            }
            $sql .=$attr_str;
		}
        */
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'  AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		if (!empty($data['filter_category_id'])) {
			$query_path = $this->db->query("select path from " . DB_PREFIX ."category where category_id='".$data['filter_category_id']."'");
			$path =$query_path->row;
			$sql .= " AND c.path like '".$path['path']."%'";	
			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";				
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		$query = $this->db->query($sql);
        $this->cache->set($cache_key,$query->row['total']);
		return $query->row['total'];
	}

    
    
    
	public function getProfiles($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_profile` `pp` JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`profile_id` = `pp`.`profile_id` JOIN `" . DB_PREFIX . "profile` `p` ON `p`.`profile_id` = `pd`.`profile_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$customer_group_id . " ORDER BY `sort_order` ASC")->rows;

	}

	public function getProfile($product_id, $profile_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;
	}

	//得到所有特价数量
	public function getTotalProductSpecials($filter='') {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		
        $filter_where ='';
        if(!empty($filter)){
            $filter_where.=" AND ".$filter;
        }

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.stock_status_id=7 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND (ps.customer_group_id = '" . (int)$customer_group_id . "' or ps.customer_group_id=0)  AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW()))".$filter_where);
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}

    
    public function getProductUrl($product_id){
        $cache = $this->cache->get('product-url-'.$product_id );
        if($cache){
            return $cache;
        }else{
            $query = $this->db->query("SELECT url_path FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id ."' limit 1");
            if(isset($query->row['url_path'])){
                $url_path = $query->row['url_path'];
                if(empty($url_path)){
                    return false;
                }
                if(strpos($url_path,'.html') == false){
                     $url_path = $url_path.".html";
                }
                $this->cache->set('product-url-'.$product_id,$url_path );
                return $url_path;
            }
        }
       return false;
    }
    public function getColune($product_id,$filed=array()){
        if(empty($filed)){
            return false;
        }
        else{
            $filed_str =implode(',',$filed);
        }
        $query =$this->db->query("select $filed_str from ".DB_PREFIX."product where product_id=".$product_id);
        return $query->row;
    }
    
   public function isWishlist($product_id){
        if(!$this->customer->isLogged()){
            return false;
        }
        else{
            $query =$this->db->query("SELECT wish_id FROM " . DB_PREFIX . "customer_wishlist where customer_id= ".$this->customer->getId()." and product_id=".$product_id);
            if($query->num_rows){
                return true;
            }
            else{
                return false;
            }
        }
   }

   public function getValue($key_array,$product_id){
       $key_str =implode(',',$key_array);
        $query =$this->db->query("SELECT ".$key_str." FROM " . DB_PREFIX . "product where product_id= ".$product_id);
        if($query->num_rows){
            return $query->row;
        }
        else{
            return false;
        }
   }

   function getDiscountPercent($new_price,$old_price,$number_format=2){
        $save =$old_price-$new_price;
        $save_rate =$save/$old_price;
        return round($save_rate,2)*100;
    }
    
	public function getProductBySKU($sku) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
        $sku = $this->db->escape($sku);
        $review_store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $rating_store_where =" and r1.store_id=".(int)$this->config->get('config_store_id');
            $review_store_where =" and r2.store_id=".(int)$this->config->get('config_store_id');
        }
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND (pd2.customer_group_id = '" . (int)$customer_group_id . "' or pd2.customer_group_id =0) AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00 00:00:00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00 00:00:00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND (ps.customer_group_id = '" . (int)$customer_group_id . "' or ps.customer_group_id=0) AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status,p.status,p.stock_status_id, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' AND r1.is_publish ='1' ".$rating_store_where." GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1'  ".$review_store_where." GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
        WHERE p.model= '" . $sku . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'  AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
            $url_path = $query->row['url_path'];
            if(strpos($url_path,'.html') === false){
                     $url_path = $url_path.".html";
            }
            $point =$query->row['special']?floor($query->row['special']):floor($query->row['price']);
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
                'meta_title'             => $query->row['title'],
				'description'      => $query->row['description'],
                'shipping_description'      => $query->row['shipping_description'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'points'           => $point,
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
                'deals_limit_number'          => $query->row['deals_limit_number'],
                'is_new'          => $query->row['is_new'],
                'is_hot'          => $query->row['is_hot'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
                'url_path'         => $url_path,
                'status'           => $query->row['status'],
                'stock_status_id' => $query->row['stock_status_id'],
			);
		} else {
			return false;
		}
	}
    //得到网商品所有的组合属性和属性值
    public function getAttrFilter($product_id){
        //得到该商品所在分组
        $query_group =$this->db->query("select group_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." limit 1");
        $group_id =$query_group->row['group_id'];
        //得到分组下的所有复合属性
        $query_attr =$this->db->query("select distinct attr_id from ".DB_PREFIX."product_attr_filter where group_id='".$group_id."' order by paf_id ASC");
        $attr_info =array();
        foreach($query_attr->rows as $key =>$attr_id){
            //得到属性名称
            $attr_name =$this->getNewAttrName($attr_id['attr_id']);
            //得到属性下的所有属性值
            $query_option =$this->db->query("select distinct paf.value_id,aov.option_value from ".DB_PREFIX."product_attr_filter as paf left join ".DB_PREFIX."new_attribute_option_value as aov on paf.value_id=aov.option_id  where paf.group_id='".$group_id."' and paf.attr_id=".(int)$attr_id['attr_id']." and aov.language_id=".(int)$this->config->get('config_language_id')." order by aov.option_value ASC");
            $attr_info[$key]['attr_id'] =$attr_id['attr_id'];
            $attr_info[$key]['attr_name']=$attr_name;
            $new_attr_option_info =array();
            foreach($query_option->rows as $key_option=>$item_option){
                 $res =$this->getHasProductAttr($product_id,$attr_id['attr_id'],$item_option['value_id']);
                 if($res){
                       $item_option['able'] =true;
                       $item_option['href'] =$this->url->link('product/product', 'product_id=' .$res);
                 }
                 else{
                     $item_option['able'] =false;
                     $item_option['href']='javascript:void()';
                 }
                 
                 $new_attr_option_info[$key_option] =$item_option;
            }
            $attr_info[$key]['attr_option_info']=$new_attr_option_info;
           
        }
        return $attr_info;
    }

    public function getPorductAttrFilter($product_id){
        //得到商品下复合属性
        $pro_attr =$this->db->query("select attr_id,value_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." order by paf_id ASC");
        $pro_attr_info =array();
        foreach($pro_attr->rows as $attr_id){
            $pro_attr_info[$attr_id['attr_id']]['option_id'] =$attr_id['value_id'];
        }
        return $pro_attr_info;
    }
    
    public function getPorductAttrFilterMainProduct($product_id){
        //得到该商品所在分组
        $query_group =$this->db->query("select group_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." limit 1");
        $group_id =$query_group->row['group_id'];
        $query_main_product = $this->db->query("select product_id from ".DB_PREFIX."product_attr_filter_main_product where group_id = '".$group_id."'");
        if($query_main_product->row){
            $main_product_id = $query_main_product->row['product_id'];
        }else{
            $main_product_id == false;
        }
        
        return $main_product_id;
    }
    
    
    //得到该属性在商品其他默认属性下是否有商品存在
    public function getHasProductAttr($product_id,$attr_id,$option_id){
        //得到该商品所在分组
        $query_group =$this->db->query("select group_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." limit 1");
        $group_id =$query_group->row['group_id'];
        $pro_attr_info =$this->getPorductAttrFilter($product_id);
        $in_product =array();
        $i=1;
        foreach($pro_attr_info as $key=>$item){
            if($key==$attr_id){
                $item['option_id'] =$option_id;
            }
            $query_selected=$this->db->query("SELECT product_id FROM ".DB_PREFIX."product_attr_filter where group_id='".$group_id."' and attr_id =".$key." and value_id=".$item['option_id']);
           foreach($query_selected->rows as $product_selected){
               $in_product[] =$product_selected['product_id'];
           }
           if($i>1){
                //去除重复值
               $unique_arr = array_unique($in_product);
               // 获取差集，既重复的数值
               $repeat_arr = array_diff_assoc ($in_product,$unique_arr );
               $in_product =$repeat_arr;
           }
           $i++;
        }
        if($in_product){
            reset($in_product);
            return current($in_product);
        }
        else{
            return false;
        }
        return $in_product;
    }
    //通过已经选择的属性得到可以组合的商品属性组合
    /*
    *  $selected_attr_option  已选择的属性
    *
    */
    public function getOtherAttrByAttr($selected_attr_option,$product_id,$attr_id,$option_id){
        $data =array();
        //得到该商品所在分组
        $query_group =$this->db->query("select group_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." limit 1");
        $group_id =$query_group->row['group_id'];
        //得到含有已选择属性的其他属性值
        $in_product =array();
        $i=1;
        $selected_attr_arr =array();
       foreach($selected_attr_option as $key=>$selected){
           $selected_attr_arr[] =$selected['attr_id'];
           $query_selected=$this->db->query("SELECT product_id FROM ".DB_PREFIX."product_attr_filter where group_id=".$group_id." and attr_id =".$selected['attr_id']." and value_id=".$selected['option_id']);
           foreach($query_selected->rows as $product_selected){
               $in_product[] =$product_selected['product_id'];
           }
           if($i>1){
                //去除重复值
               $unique_arr = array_unique($in_product);
               // 获取差集，既重复的数值
               $repeat_arr = array_diff_assoc ($in_product,$unique_arr );
               $in_product =$repeat_arr;
           }
           $i++;
       }
       $count =count($selected_attr_option);
       $attr_count =$this->getCountProductFilterAttr($product_id);
       //如果选择最后一个属性，且唯一确定一个商品 ,进行跳转
        if($count==$attr_count&&$in_product){
            reset($in_product);
            return $data =array(
                'status' =>'2',
                'product_id' =>current($in_product)
            );
        }
       //得到选择属性的可选属性
       if($in_product){
          $query_other=$this->db->query("select attr_id,value_id from ".DB_PREFIX."product_attr_filter where group_id=".$group_id." and product_id in (".implode(',',$in_product).") and attr_id not in(".implode(',',$selected_attr_arr).")");
          $data =array(
                      'status' =>'1',
                      'info' =>array()
           );
          foreach($query_other->rows as $key=>$row){
              if(!in_array($row['value_id'],$data[$row['attr_id']]['option_id'])){
                 $data['info'][$row['attr_id']][$row['value_id']] =$this->getNewAttrValueName($row['value_id']);
              }
              
          }
          return $data;
       }
       else{
           //2者组合没有商品,得到剩余的一个商品属性，该属性不能选择
            $query=$this->db->query("select distinct attr_id from ".DB_PREFIX."product_attr_filter where group_id=".$group_id." and attr_id not in(".implode(',',$selected_attr_arr).")");
            $data =array(
                'status' =>'0',
                'no_select' =>array()
            );
            foreach($query->rows as $key2=>$row2){
                 $data['no_select'][] =$row2['attr_id'];
          }
          return $data;
       }
       
       
      
    }

    //得到商品用于复合的属性个数
    public function getCountProductFilterAttr($product_id){
        //得到该商品所在分组
        $query_group =$this->db->query("select group_id from ".DB_PREFIX."product_attr_filter where product_id=".(int)$product_id." limit 1");
        $group_id =$query_group->row['group_id'];
        //得到分组下的所有复合属性
        $query_attr =$this->db->query("select distinct attr_id from ".DB_PREFIX."product_attr_filter where group_id=".(int)$group_id." order by paf_id ASC");
        return count($query_attr->rows);
    }

    //得到新的属性名称
    public function getNewAttrName($attr_id){
        $query_attr_name =$this->db->query("select name from ".DB_PREFIX."new_attribute_description where attribute_id=".(int)$attr_id." and language_id=".(int)$this->config->get('config_language_id'));
        $attr_name =$query_attr_name->row['name'];
        return $attr_name;
    }
    //得到新的属性值名称
    public function getNewAttrValueName($option_id){
        $query_attr_name =$this->db->query("select option_value from ".DB_PREFIX."new_attribute_option_value where option_id=".(int)$option_id." and language_id=".(int)$this->config->get('config_language_id'));
        $value_name =$query_attr_name->row['option_value'];
        return $value_name;
    }

    public function getProductsByElasticsearch($data){
        $keyword = '';
        $category_id = $data['filter_category_id'];
        $all_category_arr = array();
        if($category_id){
            $query_path = $this->db->query("select path from " . DB_PREFIX ."category where category_id='".$category_id."'");
			$path = $query_path->row['path'];
            $all_category_sql = "select distinct category_id from " .DB_PREFIX."category where `path` like '{$path}%' and status = 1" ;
            $all_category_query = $this->db->query($all_category_sql);
            foreach($all_category_query->rows as $row){
                $all_category_arr[] = $row['category_id'];
            }
        }
        $attribute_arr = array();
        if(isset($data['attr_id'])  && $data['attr_id'] && isset($data['option_id']) && $data['option_id']){
            $attr_id_array=array();
            $attr_str ='';
			if(strpos($data['attr_id'],'-')!==false){
				$attr_id_array =explode('-',$data['attr_id']);
			}
			else{
				$attr_id_array[] =$data['attr_id'];
			}
            $option_id_array=array();
			if(strpos($data['option_id'],'-')!==false){
				$option_id_array=explode('-',$data['option_id']);
			}
			else{
				$option_id_array[] =$data['option_id'];
			}
            if(count($attr_id_array)){
                for($i=0;$i<count($attr_id_array);$i++){
                    $attribute_arr[$attr_id_array[$i]] = array(
                        $option_id_array[$i]
                    );
                }
            }
        }
        
        $sort_by = array();
		$sort_data = array(
			'popularity',
			'price',
			'reviews',
			'new_arrivals',
		);
        if(!isset($this->request->get['sort'])){
            $sort_by['category.position']['order'] = 'desc';
            $sort_by['sales_num']['order'] = 'desc';
            $sort_by['product_id']['order'] = 'desc';
        }else{
            $param_sort = $this->request->get['sort'];
            $param_sort = strtolower($param_sort);
            
            if(!isset($this->request->get['order'])){
                $param_order = 'desc';
            }else{
                $param_order = $this->request->get['order'];
                $param_order = strtolower($param_order);
                if(!in_array($param_order,array('desc','asc'))){
                    $param_order = 'desc';
                }
            }
           
            if(in_array($param_sort,$sort_data)){
                if($param_sort == 'popularity'){
                    $sort_by['sales_num']['order'] = $param_order;
                    $sort_by['product_id']['order'] = 'desc';
                }
                if($param_sort == 'price'){
                    $sort_by['price']['order'] = $param_order;
                     $sort_by['product_id']['order'] = 'desc';
                }
                if($param_sort == 'reviews'){
                    $sort_by['review_rating']['order'] = $param_order;
                     $sort_by['product_id']['order'] = 'desc';
                }
                if($param_sort == 'new_arrivals'){
                    $sort_by['date_added']['order'] = $param_order;
                     $sort_by['product_id']['order'] = 'desc';
                }
            }else{
                $sort_by['category.position']['order'] = 'desc';
                $sort_by['sales_num']['order'] = 'desc';
                $sort_by['product_id']['order'] = 'desc';
            }
        }
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
        }else{
            $data['start'] = 0;
            $data['limit'] = 20;
        }
        $this->load->model('catalog/category');
        $this->load->model('search/search');

        $group_id = $data['filter_group_id'];
        $attribute_arr = $this->model_catalog_category->getAttrFilterOptionParam($group_id,$attribute_arr);
        $category_product_data = $this->model_search_search->getCategoryProduct($keyword,$all_category_arr,$attribute_arr,$sort_by,$data['start'],$data['limit'],$data['price_range']);
        
        $number = $category_product_data['number'];
        foreach ($category_product_data['data'] as $_product_id) {
			$product_data[$_product_id] = $this->getProduct($_product_id);
		}
       
         return array('number'=>$number,'data'=>$product_data);
        
        
    }

    public function getTotalProductsByElasticsearch($data){
                
        $keyword = '';
        $category_id = $data['filter_category_id'];
        $all_category_arr = array();
        if($category_id){
            $query_path = $this->db->query("select path from " . DB_PREFIX ."category where category_id='".$category_id."'");
			$path = $query_path->row['path'];
            $all_category_sql = "select distinct category_id from " .DB_PREFIX."category where `path` like '{$path}%' and status = 1" ;
            $all_category_query = $this->db->query($all_category_sql);
            foreach($all_category_query->rows as $row){
                $all_category_arr[] = $row['category_id'];
            }
        }
        $attribute_arr = array();
        if(isset($data['attr_id'])  && $data['attr_id'] && isset($data['option_id']) && $data['option_id']){
            $attr_id_array=array();
            $attr_str ='';
			if(strpos($data['attr_id'],'-')!==false){
				$attr_id_array =explode('-',$data['attr_id']);
			}
			else{
				$attr_id_array[] =$data['attr_id'];
			}
            $option_id_array=array();
			if(strpos($data['option_id'],'-')!==false){
				$option_id_array=explode('-',$data['option_id']);
			}
			else{
				$option_id_array[] =$data['option_id'];
			}
            if(count($attr_id_array)){
                for($i=0;$i<count($attr_id_array);$i++){
                    $attribute_arr[$attr_id_array[$i]] = array(
                        $option_id_array[$i]
                    );
                }
            }
        }
        $this->load->model('catalog/category');
        $this->load->model('search/search');
        $group_id = $data['filter_group_id'];
        $attribute_arr = $this->model_catalog_category->getAttrFilterOptionParam($group_id,$attribute_arr);
        $category_attribute_data = $this->model_search_search->getCategoryProductsNumber($keyword,$all_category_arr,$attribute_arr,$data['price_range']);
        return $category_attribute_data;
    }

    //判断来源是否具有专属渠道
    public function if_have_exclusive($url){
        $query =$this->db->query("select s_id from ".DB_PREFIX."product_exclusive_source where url='".$this->db->escape($url)."'");
        if($query->num_rows){
            return $query->row['s_id'];
        }
        else{
            return false;
        }
    }
    //得到商品是否在来源链接下具有专属特价,此特价优先于普通特价
    /*
    *  $product_id
    *  $sid   来源url ID
    *  $time  判断时间
    */
    public function if_exclusive_price($product_id,$sid,$time){
        $query =$this->db->query("select * from ".DB_PREFIX."product_exclusive_price where product_id='".$product_id."' and start_time <='".$time."' and end_time>='".$time."' ");
        if($query->num_rows){
            $from_url =$query->row['from_url'];
            $exclusive_source =explode(',',$sid);
            $from_url_arr =explode(',',trim($from_url));
            foreach($exclusive_source as $ss_id){
                if(in_array($ss_id,$from_url_arr)){
                    return  $query->row;
                }
            }
             return false;
        }
        else{
            return false;
        }
    } 
    

    //最终判断商品是否有专属的特价
    public function realy_exclusive_price($product_id){
        //是否具有链接专属特价
        $exclusive_price_info =array();
        $current_time =date("Y-m-d H:i:s",time());
        if(isset($_COOKIE['exclusive_source'])){
            $exclusive_source_id =$_COOKIE['exclusive_source'];
            $exclusive_price_info =$this->if_exclusive_price($product_id,$exclusive_source_id,$current_time);
        }
        if(!isset($_COOKIE['exclusive_source'])||!$exclusive_price_info){
            $href_refre =isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
            $source_id =$this->if_have_exclusive($href_refre);
            if($source_id){
                $exclusive_price_info =$this->if_exclusive_price($product_id,$source_id,$current_time);
            }
            else{
                //是否是edm渠道
                if(isset($this->request->get['utm_source'])&&$this->request->get['utm_source']=='EDM'){
                    if(isset($this->request->get['utm_medium'])){
                        $source_str =str_replace(array('-en','-de','-es','-fr','-it','-pt'),'',trim($this->request->get['utm_medium']));
                        $source_str ="EDM/".$source_str;
                        $source_id =$this->model_catalog_product->if_have_exclusive($source_str);
                        if($source_id){
                            $exclusive_price_info =$this->if_exclusive_price($product_id,$source_id,$current_time);
                        }
                    }     
                }
            }
        }
        return $exclusive_price_info;
    }
    public function getProductSpringArrivalBySKU($sku){
        $sql = "select * from oc_product_spring_arrival where sku = '{$sku}'";
        $query = $this->db->query($sql);
        if($query->num_rows){
            $row = $query->row;
            return $row['arrival'];
        }
        return false;
    }

    //得到所有的质检报告
    public function getAllProductBrochures($product_id){
        $query =$this->db->query("select * from ".DB_PREFIX."product_brochures where product_id=".(int)$product_id);
        return $query->rows;
    }
    
    public function canBatteryShipTo($country_code,$product_id=''){
        $sql = "select count(*) as cnt from  " .DB_PREFIX. "shipping_matrixrate_battery WHERE dest_country_id = '{$country_code}'";
        $query = $this->db->query($sql);
        $row = $query->row;
        if($row['cnt'] > 0){
            return true;
        }else{
            return false;
        }
        
    }
   
    //得到商品的英语名称
    public function get_product_en_name($product_id){
        $sql = "select name from ".DB_PREFIX."product_description where product_id = '{$product_id}'";
        $query = $this->db->query($sql);
        if($query->num_rows){
            $row = $query->row;
            return $row['name'];
        }
        return false;
    }
    
    public function get_custom_tag(){
        ///static $tags ;
        //if(!$tags){
            $sql = "select * from " . DB_PREFIX. "custom_tag where lang_id=".(int)$this->config->get('config_language_id');
            $query = $this->db->query($sql);
            if($query->num_rows){
                foreach($query->rows as $row){
                    $_tag  = $row['tag'];
                    $_link = $row['link'];
                    $_tag = strtolower($_tag);
                    $tags[$_tag] = $row;
                }
            }
       // }
        return $tags;
    }


    // 得到商品是否是活动信息设置的商品
    public function if_action_sku($sku){
        // 得到所有活动的sku
        $time =date("Y-m-d H:i:s",time());
		$sql = "SELECT * FROM " . DB_PREFIX . "sku_action_set  where start_time <= '$time' and end_time>= '$time' order by start_time desc limit 1";
		$query = $this->db->query($sql);
        $all_sku =$query->row['all_sku'];
        $all_sku_array =explode(",",trim($all_sku));
        $action_sku =array();
        foreach($all_sku_array as $in_sku){
            $action_sku[] =trim($in_sku);
        }
        if(in_array($sku,$action_sku)){
            return $query->row['id'];
        }else{
            return false;
        }
    }

    //得到商品活动信息的文字和链接内容
    public function get_sku_action_desc($set_id){
        $sql = "SELECT text,link  FROM " . DB_PREFIX . "sku_action_set_descrition  where set_id=".(int)$set_id." and lang_id=".(int)$this->config->get('config_language_id');
        $query = $this->db->query($sql);
        return $query->row;
    }

	public function is_product_hot_label($sku){
		$sql = "select * from oc_product_hot where sku = '{$sku}'";
		$query = $this->db->query($sql);
		$row = $query->row;
		if($row){

			$start_time = $row['start_time'];
			$start_time = strtotime($start_time);
			$end_time   = $row['end_time'];
			$end_time   = strtotime($end_time);
			$t = time();
			if($start_time < $t && $t < $end_time){

				return true;
			}
		}
		return false;
	}
}
?>