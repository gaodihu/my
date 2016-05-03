<?php
class ModelReportSale extends Model {
    /*
	public function getOrders($data = array()) {
		$sql = "SELECT MIN(tmp.date_added) AS date_start, MAX(tmp.date_added) AS date_end, COUNT(tmp.order_id) AS `orders`, SUM(tmp.products) AS products, SUM(tmp.tax) AS tax, SUM(tmp.total) AS total FROM (SELECT o.order_id, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, (SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, o.total, o.date_added FROM `" . DB_PREFIX . "order` o"; 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY o.order_id) tmp";
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(tmp.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(tmp.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(tmp.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(tmp.date_added)";
				break;									
		}
		
		$sql .= " ORDER BY tmp.date_added DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
        echo $sql;exit;
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	

    */

    public function getOrders($data = array()) {
		//$sql = "SELECT MIN(tmp.date_added) AS date_start, MAX(tmp.date_added) AS date_end, COUNT(tmp.order_id) AS `orders`, SUM(tmp.products) AS products, SUM(tmp.tax) AS tax, SUM(tmp.total) AS total FROM (SELECT o.order_id, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, (SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, o.total, o.date_added FROM `" . DB_PREFIX . "order` o"; 
        $sql ="select o.order_id,o.order_number,o.order_status_id,o.date_added,o.currency_code,o.grand_total,o.base_grand_total from ".DB_PREFIX."order o";
		if (!empty($data['filter_order_status_id'])) {
			 if($data['filter_order_status_id']==99){
                $sql .= " WHERE (o.order_status_id =2 or o.order_status_id =5) ";
            }
            else{
                $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
            }
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND o.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND o.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$sql .= " AND  o.parent_id  = 0 " ;
		$sql .= " ORDER BY o.date_added DESC";
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
	public function getSubOrderInfo($data = array()){
        $sql ="select count(o.order_id) as order_num,sum(base_shipping_amount) as shipping_cost,sum(o.base_grand_total) as sub_grand_total from ".DB_PREFIX."order o";
		if (!empty($data['filter_order_status_id'])) {
			 if($data['filter_order_status_id']==99){
                $sql .= " WHERE (o.order_status_id =2 or o.order_status_id =5) ";
            }
            else{
                $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
            }
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND o.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND o.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
        $sql .= " AND  o.parent_id = 0";
		$query = $this->db->query($sql);
		
		return $query->row;
    }
	public function getTotalOrders($data = array()) {
	$sql ="select count(o.order_id) as total from ".DB_PREFIX."order o";
		if (!empty($data['filter_order_status_id'])) {
			 if($data['filter_order_status_id']==99){
                $sql .= " WHERE (o.order_status_id =2 or o.order_status_id =5) ";
            }
            else{
                $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
            }
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND o.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND o.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
        $sql .= " AND  o.parent_id = 0";
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax' AND o.parent_id = 0"; 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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
	
	public function getTotalTaxes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax' AND o.parent_id = 0";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") tmp";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}	
	
	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping' AND o.parent_id = 0"; 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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
	
	public function getTotalShipping($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping' AND o.parent_id = 0 ";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") tmp";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}		


    public function getOrdersData($start,$end){
        $query = $this->db->query("SELECT SUM(base_grand_total) AS total, sum(base_subtotal) as product_total,sum(base_shipping_amount) as total_shipping_cost FROM `" . DB_PREFIX . "order` WHERE order_status_id in(2,5) AND date_added >='".$start."' and  date_added<='".$end."' and  parent_id = 0");

		return $query->row;
    }

    public function getOrderSubOriginal($start,$end){
        $SubOriginal =0;
        $sql ="select order_id from ".DB_PREFIX."order
        where order_status_id in(2,5) AND parent_id = 0 AND date_added >='".$start."' and date_added<='".$end."'";
        $query =$this->db->query($sql);
        foreach($query->rows as $row){
            $query_pro =$this->db->query("select sum(quantity*original_price) as original_total from ".DB_PREFIX."order_product where order_id=".$row['order_id']);
            $original_total =$query_pro->row['original_total'];
            $SubOriginal +=$original_total ;
        }
        return $SubOriginal;
    }

    public function getProductSaleInfo($data){
   
        $info =array();
        $sql = "select o.order_id,o.store_id,op.product_id,op.model,op.name,op.quantity  from oc_order as o left join oc_order_product as op on o.order_id=op.order_id where (order_status_id=2 or order_status_id=5 ) and  parent_id = 0 " ;
        if(isset($data['filter_date_start'])){
            $sql.=" and o.date_added>='".$data['filter_date_start']."'";
        }
        if(isset($data['filter_date_end'])){
            $sql.=" and o.date_added<='".$data['filter_date_end']."'";
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
        $query=$this->db->query($sql);
        foreach($query->rows as $row){
             if(isset($info[$row['product_id']][$row['store_id']])){
                 $info[$row['product_id']][$row['store_id']]['order_count'] +=1;
                 $info[$row['product_id']][$row['store_id']]['order_product_count'] +=$row['quantity'];
            }else{
                $info[$row['product_id']]['product_id'] =$row['product_id'];
                $info[$row['product_id']]['model'] =$row['model'];
                $info[$row['product_id']]['name'] =$row['name'];
                $info[$row['product_id']][$row['store_id']]['order_count'] =1;
                $info[$row['product_id']][$row['store_id']]['order_product_count'] =$row['quantity'];
            }
        }
        return $info;
    }
    public function getTotalProductSale($data){
        $sql = "select count(distinct op.product_id) as total  from oc_order as o left join oc_order_product as op on o.order_id=op.order_id where (order_status_id=2 or order_status_id=5 ) AND  parent_id  = 0 ";
         if(isset($data['filter_date_start'])){
            $sql.=" and o.date_added>='".$data['filter_date_start']."'";
        }
        if(isset($data['filter_date_end'])){
            $sql.=" and o.date_added<='".$data['filter_date_end']."'";
        }
        $query=$this->db->query($sql);
        return $query->row['total'];
    }
}
?>