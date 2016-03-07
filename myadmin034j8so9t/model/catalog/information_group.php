<?php 
class ModelCatalogInformationGroup extends Model {
	public function addInformationGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information_group SET information_group_code ='".$data['information_group_code']."',status = '".(int)$data['status']."',sort_order = '" . (int)$data['sort_order'] . "'");
		$information_group_id = $this->db->getLastId();
		foreach ($data['information_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_group_description SET information_group_id = '" . (int)$information_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
	}

	public function editInformationGroup($information_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "information_group SET information_group_code ='".$data['information_group_code']."',status='".(int)$data['status']."',sort_order = '" . (int)$data['sort_order'] . "' WHERE information_group_id = '" . (int)$information_group_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_group_description WHERE information_group_id = '" . (int)$information_group_id . "'");

		foreach ($data['information_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_group_description SET information_group_id = '" . (int)$information_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
	}

	public function deleteInformationGroup($information_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_group WHERE information_group_id = '" . (int)$information_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_group_description WHERE information_group_id = '" . (int)$information_group_id . "'");
	}

	public function getInformationGroup($information_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_group WHERE information_group_id = '" . (int)$information_group_id . "'");

		return $query->row;
	}
	public function getInformationGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "information_group ag";

		$sort_data = array(
			'ag.information_group_code',
			'ag.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ag.sort_order";	
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

	public function getInformationGroupDescriptions($information_group_id) {
		$information_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_group_description WHERE information_group_id = '" . (int)$information_group_id . "'");

		foreach ($query->rows as $result) {
			$information_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $information_group_data;
	}
	public function getTotalInformationGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information_group");

		return $query->row['total'];
	}	
}
?>