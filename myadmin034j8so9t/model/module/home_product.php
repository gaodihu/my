<?php
class ModelModuleHomeProduct extends Model {
	public function addHomeProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "home_products SET product_id = " . (int)$data['product_id'] . ",type =" . (int)$data['type'] . ",start_time ='".$data['start_time']."',end_time='".$data['end_time']."', sort_order = '" . (int)$data['sort_order'] . "'");
		//$rec_id = $this->db->getLastId();
	}

	public function editHomeProduct($rec_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "home_products SET product_id='".(int)$data['product_id']."',type = '" . (int)$data['type'] . "',start_time='".$data['start_time']."',end_time ='".$data['end_time']."',sort_order='".(int)$data['sort_order']."' WHERE rec_id = '" . (int)$rec_id . "'");	
	}

	public function deleteHomeProduct($rec_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "home_products WHERE rec_id = '" . (int)$rec_id . "'");
	}

	public function getHomeProduct($rec_id) {
		$query = $this->db->query("SELECT DISTINCT hp.* ,p.model,pd.name FROM " . DB_PREFIX . "home_products as hp left join ".DB_PREFIX."product as p on hp.product_id=p.product_id left join ".DB_PREFIX."product_description as pd on hp.product_id=pd.product_id WHERE hp.rec_id = '" . (int)$rec_id . "' and pd.language_id='".(int)$this->config->get('config_language_id')."'");

		return $query->row;
	}

	public function getHomeProducts($data = array()) {
		$sql = "SELECT DISTINCT hp.* ,p.model,pd.name FROM " . DB_PREFIX . "home_products as hp left join ".DB_PREFIX."product as p on hp.product_id=p.product_id left join ".DB_PREFIX."product_description as pd on hp.product_id=pd.product_id WHERE  pd.language_id='".(int)$this->config->get('config_language_id')."'";

		if($data['filter_product_id']){
			$sql.="and  hp.product_id=".$data['filter_product_id'];
		}
		if($data['filter_type']){
			$sql.="and  hp.type=".$data['filter_type'];
		}
		if($data['filter_model']){
			$sql.="and p.model='".$data['filter_model']."' ";
		}
		if($data['filter_name']){
			$sql.="and  pd.name like '%".$data['filter_name']."%'";
		}
		if($data['filter_start_time_from']){
			$sql.="and  hp.start_time >'".$data['filter_start_time_from']."' ";
		}
		if($data['filter_start_time_to']){
			$sql.="and  hp.start_time <'".$data['filter_start_time_to']."' ";
		}
		if($data['filter_end_time_from']){
			$sql.="and  hp.end_time >'".$data['filter_end_time_from']."' ";
		}
		if($data['filter_end_time_to']){
			$sql.="and  hp.end_time <'".$data['filter_end_time_to']."' ";
		}
		

		$sort_data = array(
			'hp.sort_order',
			'hp.type',
			'product_id',
			'start_time',
			'end_time'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY hp.type";	
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

	public function getTotalHomeProducts($type=0) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "home_products";
		if($type){
			$sql .=" where type=".$type;
		}
		$query = $this->db->query($sql);
	

		return $query->row['total'];
	}	
}
?>