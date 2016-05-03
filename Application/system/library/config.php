<?php
class Config {
	private $data  = array();
	private $stores = array();
	private $languages = array();
	private $db;
	private $cache;
	public  $store_id = "";

	public function __construct($registry)
	{
		$this->db    = $registry->get('db');
		$this->cache = $registry->get('cache');


		// Settings
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE status = 1");
		$this->stores = $store_query->rows;

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting  ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$this->set($setting['key'], $setting['value'], $setting['store_id']);
			} else {
				$this->set($setting['key'], unserialize($setting['value']), $setting['store_id']);
			}
			$this->set('config_store_id',$setting['store_id'],$setting['store_id']);
		}
		//default
		$this->set('config_url', HTTP_SERVER,0);
		$this->set('config_ssl', HTTPS_SERVER,0);

		foreach($this->data as $store_id => $d) {
			if($store_id == 0){
				continue;
			}
			$url =  $d['config_secure'] ? $d['config_ssl'] : $d['config_url'];

			if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$temp = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				if(strpos($temp,$url) !== false ){
					$this->store_id = $store_id;
					break;
				}
			} else {
				$temp = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				if(strpos($temp,$url) !== false ){

					$this->store_id = $store_id;
					break;
				}
			}
		}
		//store_id =  0
		if($this->store_id == ""){
			$url =  $this->data[0]['config_secure'] ? $this->data[0]['config_ssl'] : $this->data[0]['config_url'];
			if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$temp = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				if(strpos($temp,$url) !== false ){
					$this->store_id = 0;
				}
			} else {
				$temp = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				if(strpos($temp,$url) !== false ){
					$this->store_id = 0;
				}
			}
		}


		$languages = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
		}

		$this->languages = $languages;

	}

	public function get($key,$store_id = "") {
		if($store_id === ""){
			$store_id = $this->store_id;
		}
		if(isset($this->data[$store_id][$key])){
			return $this->data[$store_id][$key];
		}else if(isset($this->data[0][$key])){
			return $this->data[0][$key];
		}
		return null;
		//return (isset($this->data[$store_id][$key]) ? $this->data[$store_id][$key] : null);
	}

	public function set($key, $value,$store_id = "") {
		if($store_id === ""){
			$store_id = $this->store_id;
		}
		$this->data[$store_id][$key] = $value;
	}

	public function has($key,$store_id = "") {
		if($store_id === ""){
			$store_id = $this->store_id;
		}
		return isset($this->data[$store_id][$key]);
	}

	public function getStores(){
		return $this->stores;
	}

	public function getLanguages(){
		return $this->languages;
	}


	public function getDomain($store_id=""){
		if($store_id === ""){
			$store_id = $this->store_id;
		}
		$url =  $this->get('config_secure',$store_id) ? $this->get('config_ssl',$store_id) : $this->get('config_url',$store_id);
		return $url;
	}

	public function getDomainByLanguage($code){
		$code = strtolower($code);
		foreach($this->data as $store_id => $s){
			if(strtolower($s['config_language']) == $code){
				$url =  $s['config_secure'] ? $s['config_ssl'] : $s['config_url'];
				return $url;
			}
		}
		return false;
	}
	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';

		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->data = array_merge($this->data, $_);
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}
?>