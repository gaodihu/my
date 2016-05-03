<?php
class ModelSaleCustomerPoint extends Model {
	public function getCustomerPoints($data) {
        $sql ="SELECT cr.customer_reward_id,cr.customer_id,cr.order_id,o.order_number,c.email,cr.points,cr.points_spent,cr.status from ".DB_PREFIX."customer_reward as cr left join ".DB_PREFIX."order as o on cr.order_id =o.order_id left join ".DB_PREFIX."customer as c on cr.customer_id=c.customer_id where 1 ";
        if (isset($data['filter_customer_id'])&&!empty($data['filter_customer_id'])) {
			$sql .= " AND cr.customer_id=" . (int)$data['filter_customer_id'];
		}

		if (isset($data['filter_email'])&&!empty($data['filter_email'])) {
			$sql .= " AND c.email ='".$this->db->escape($data['filter_email'])."' ";
		}

		if (isset($data['filter_order_number']) && !empty($data['filter_order_number'])) {
			$sql .= " AND o.order_number= '" . $data['filter_order_number'] . "'";
		}	

		if (isset($data['filter_status']) &&$data['filter_status']>0) {
			$sql .= " AND cr.status= '" . (int)$data['filter_status'] . "'";
		}	
        $sort_data = array(
			'cr.customer_id',
			'o.order_number',
			'cr.status'
		);	
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cr.customer_reward_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
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
    

    public function getTotalCustomerPoints($data) {
        $sql ="SELECT count(*) as total from ".DB_PREFIX."customer_reward as cr left join ".DB_PREFIX."order as o on cr.order_id =o.order_id left join ".DB_PREFIX."customer as c on cr.customer_id=c.customer_id where 1 ";
        if (isset($data['filter_customer_id'])&&!empty($data['filter_customer_id'])) {
			$sql .= " AND cr.customer_id=" . (int)$data['filter_customer_id'];
		}

		if (isset($data['filter_email'])&&!empty($data['filter_email'])) {
			$sql .= " AND c.email  ='".$this->db->escape($data['filter_email'])."' ";
		}

		if (isset($data['filter_order_number']) && !empty($data['filter_order_number'])) {
			$sql .= " AND o.order_number= '" . (int)$data['filter_order_number'] . "'";
		}	

		if (isset($data['filter_status']) &&$data['filter_status']>0) {
			$sql .= " AND cr.status= '" . (int)$data['filter_status'] . "'";
		}	
        
        $query = $this->db->query($sql);

		return $query->row['total'];	
    }

    public function deleteCustomerPoint($customer_point_id){
        $this->db->query("delete from ".DB_PREFIX."customer_reward where customer_reward_id=".(int)$customer_point_id);
    }
}
?>