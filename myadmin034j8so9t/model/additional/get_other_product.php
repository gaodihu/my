<?php
class ModelAdditionalGetOtherProduct extends Model {
	public function deleteOtherProduct($new_pro_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "new_product_post WHERE new_pro_id = '" . (int)$new_pro_id . "'");
	}

	public function getOtherProduct($new_pro_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "new_product_post 
        WHERE new_pro_id = '" . (int)$new_pro_id . "'");

		return $query->row;
	}

    public function UpdateOtherProductEmail($new_pro_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "new_product_post SET email_send =1 WHERE new_pro_id = " . (int)$new_pro_id); 
	}


	public function editOtherProduct($new_pro_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "new_product_post SET status = '" . (int)$data['status'] . "',replay_content='".$this->db->escape($data['reply_content'])."' WHERE new_pro_id = " . (int)$new_pro_id); 
	}
	public function getOtherProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "new_product_post as np
        where 1";																																					  

		$sort_data = array(
			'np.new_pro_id',
			'np.created_at'
		);
		if(!empty($data['filter_email'])){
			$sql .=" and np.email= '".$data['filter_email']."'";
		}
		if(!empty($data['filter_language_code'])){
			$sql .=" and np.language_code= '".$data['filter_language_code']."'";
		}
        if(!empty($data['filter_status'])){
			$sql .=" and np.status= ".$data['filter_status'];
		}
		if(!empty($data['filter_email_send'])){
			$sql .=" and np.email_send= ".$data['filter_email_send'];
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY np.new_pro_id";	
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

	public function getTotalOtherProducts($data) {
        $sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_product_post as np ";
        $sql .=" where 1";
        if(!empty($data['filter_email'])){
			$sql .=" and np.email= '".$data['filter_email']."'";
		}
		if(!empty($data['filter_language_code'])){
			$sql .=" and np.language_code= '".$data['filter_language_code']."'";
		}
        if(!empty($data['filter_status'])){
			$sql .=" and np.status= ".$data['filter_status'];
		}
		if(!empty($data['filter_email_send'])){
			$sql .=" and np.email_send= ".$data['filter_email_send'];
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalFaqssAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faqs WHERE status = '0'");

		return $query->row['total'];
	}	
	
}
?>