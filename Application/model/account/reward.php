<?php
class ModelAccountReward extends Model {	
	//得到用户的积分记录
	public function getRewards($data = array()) {
		$sql = "SELECT c.*,o.order_number  FROM `" . DB_PREFIX . "customer_reward` c left join ".DB_PREFIX."order as o on c.order_id=o.order_id WHERE c.customer_id = '" . (int)$this->customer->getId() . "'";

		$sort_data = array(
			'points',
			'description',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
	
	public function getTotalRewards() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}	
	
	
	//得到用户的历史总积分
	public function getTotalPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status =1 GROUP BY customer_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
	//得到用户的使用总积分
	public function getTotalSpentPoints() {
		$query = $this->db->query("SELECT SUM(points_spent) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "' GROUP BY customer_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
	//得到用户的待验证总积分
	public function getTotalValidationPoints(){
		$query = $this->db->query("SELECT SUM(points) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status =0 GROUP BY customer_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
}
?>