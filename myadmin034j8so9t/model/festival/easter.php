<?php
class ModelFestivalEaster extends Model {
    public function updatePrizeDetailSend($id,$is_send) {
		$this->db->query("UPDATE " . DB_PREFIX . "prize_get_detail SET is_send =".$is_send." WHERE id = " . (int)$id); 
	}
	public function getPrizeDList($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "prize_get_detail    
        where 1=1 ";
        //$sql = "SELECT pd.*,ps. FROM " . DB_PREFIX . "prize_get_detail as pd left join prize_set as ps on pd.prize_id =ps.prize_id  and pd.prize_name_id=ps.prize_name_id where pd.prize_name_id=".$prize_name_id;	
		$sort_data = array(
			'id',
			'prize_id',
            'is_send',
		);
		if(!empty($data['filter_prize_name_id'])){
			$sql .=" and  prize_name_id= '".$data['filter_prize_name_id']."'";
		}
		if(!empty($data['filter_prize_token'])){
			$sql .=" and  prize_token= '".$data['filter_prize_token']."'";
		}
		if(!empty($data['filter_nickname'])){
			$sql .=" and nickname= '".$data['filter_nickname']."'";
		}

		if($data['filter_is_send'] != ''){
			$sql .=" and is_send= '".$data['filter_is_send']."'";
		}

		if($data['filter_prize_id'] != ''){
			$sql .=" and prize_id= '".$data['filter_prize_id']."'";
		}

		if($data['filter_email'] != ''){
			$sql .=" and email= '".$data['filter_email']."'";
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY id";	
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
	public function getTotalPrizeDetail($data) {
        $sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "prize_get_detail where 1=1 ";

		if(!empty($data['filter_prize_name_id'])){
			$sql .=" and  prize_name_id= '".$data['filter_prize_name_id']."'";
		}

       if(!empty($data['filter_prize_token'])){
			$sql .=" and  prize_token= '".$data['filter_prize_token']."'";
		}
		if(!empty($data['filter_nickname'])){
			$sql .=" and nickname= '".$data['filter_nickname']."'";
		}

		if(!empty($data['filter_is_send'])){
			$sql .=" and is_send= '".$data['filter_is_send']."'";
		}

		if($data['filter_prize_id'] != ''){
			$sql .=" and prize_id= '".$data['filter_prize_id']."'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>