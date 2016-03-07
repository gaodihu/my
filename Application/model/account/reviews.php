<?php
class ModelAccountReviews extends Model {
	public function getReviews($customer_id,$data) {
		$sql ="select * from " . DB_PREFIX . "review  where status =1 AND  customer_id =".$customer_id ;
		$sort_data = array(
			'review_id',
			'rating',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
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
		$query=$this->db->query($sql );
		return $query->rows;
	}

	public function getTotalReviews($customer_id) {
		$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "review WHERE status =1 AND customer_id = " . (int)$customer_id);

		return $query->row['total'];
	}	

	
}
?>