<?php 
class ModelSettingSetting extends Model {
	public function getSetting($group, $store_id = 0) {
		$data = array();

		$setting_data = $this->cache->get('store_setting_group_'.$store_id ."_".$this->db->escape($group));
		if(!$setting_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "' ");

			foreach ($query->rows as $result) {
				if (!$result['serialized']) {
					$data[$result['key']] = $result['value'];
				} else {
					$data[$result['key']] = unserialize($result['value']);
				}
			}
		}else{
			$data = $setting_data;
		}

		return $data;
	}

	public function getSettingByKey($key,$store_id){
		$data = array();
		$setting_data = $this->cache->get('store_setting_key_'.$store_id ."_".$this->db->escape($key));
		if(!$setting_data){
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "' ");
			foreach ($query->rows as $result) {
				if (!$result['serialized']) {
					$data[$result['key']] = $result['value'];
				} else {
					$data[$result['key']] = unserialize($result['value']);
				}
			}
		}else{
			$data = $setting_data;
		}
		return $data;
	}
}
?>