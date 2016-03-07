<?php
class ModelFestivalLottery extends Model {
	public function addPrizeName($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "prize_name set name='".$this->db->escape($data['name'])."',start_time='".$this->db->escape($data['start_time'])."',end_time='".$this->db->escape($data['end_time'])."',type='".intval($data['type'])."' ");
	}

	public function getPrizeName($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "prize_name  WHERE id = '" . (int)$id . "'");

		return $query->row;
	}
    public function editPrizeSet($prize_name_id,$data){
        $this->db->query("DELETE  FROM ".DB_PREFIX."prize_set where prize_name_id=".$prize_name_id);
        if(isset($data['prize_id'])){
            $count =count($data['prize_id']);
            for($i=0;$i<$count;$i++){
                if($data['prize_id'][$i]){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "prize_set set prize_name_id='".(int)$prize_name_id."',prize_id='".(int)$data['prize_id'][$i]."',prize_chance='".(int)$data['prize_chance'][$i]."',prize_num='".(int)$data['prize_num'][$i]."',prize_name='".$this->db->escape($data['prize_name'][$i])."' ");
                }
            }
        }
    }
    public function getPrizeSet($prize_name_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "prize_set  WHERE prize_name_id  = '" . (int)$prize_name_id . "' order by prize_id  ASC");

		return $query->rows;
    }
	public function editPrizeName($id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "prize_name SET name='".$this->db->escape($data['name'])."',start_time='".$this->db->escape($data['start_time'])."',end_time='".$this->db->escape($data['end_time'])."', type='".intval($data['type'])."' WHERE id = " . (int)$id);
	}
	public function getPrizeNameList($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "prize_name  where 1";																																					  

		$sort_data = array(
			'id',
			'name'
		);
	
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

	public function getTotalPrizeName($data) {
        $sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "prize_name  where 1";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
}
?>