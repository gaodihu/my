<?php
class ModelSettingExtension extends Model {
	function getExtensions($type) {
        unset($this->session->data['package_total_data']);
        unset($this->session->data['package_total']);
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
}
?>