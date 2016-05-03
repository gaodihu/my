<?php
class ModelDesignBanner extends Model {	
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->rows;
	}

	public function getBannerByCode($banner_code) {
        $time = date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT b.banner_width,b.banner_height,bid.* FROM " . DB_PREFIX . "banner b LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (b.banner_id  = bid.banner_id) WHERE b.banner_code = '" . $this->db->escape($banner_code) . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (bid.start_time is NULL OR bid.start_time = '0000-00-00 00:00:00' OR bid.start_time <='{$time}') AND (  bid.end_time is NULL OR bid.end_time = '0000-00-00 00:00:00' OR bid.end_time >= '{$time}' ) and bid.status = 1 order by bid.sort ASC");
		
		return $query->rows;
	}
    public function getBannerByCodeByCategory($banner_code,$category_id) {
        $time = date('Y-m-d H:i:s');
		$query = $this->db->query("SELECT b.banner_width,b.banner_height,bid.* FROM " . DB_PREFIX . "banner b LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (b.banner_id  = bid.banner_id) WHERE b.banner_code = '" . $this->db->escape($banner_code) . "' AND bid.category_id='".$category_id."' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (bid.start_time is NULL OR bid.start_time = '0000-00-00 00:00:00' OR bid.start_time <='{$time}') AND (  bid.end_time is NULL OR bid.end_time = '0000-00-00 00:00:00' OR bid.end_time >= '{$time}' ) and bid.status = 1 order by bid.sort ASC");
		return $query->rows;
	}
}
?>