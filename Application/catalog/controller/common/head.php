<?php   
class ControllerCommonHead extends Controller {
	protected function index() {
        //var_dump($this->session->data);
		$this->data['title'] = $this->document->getTitle();
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
        $this->data['server'] = $server;
		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
		$this->data['base'] = $server;
        $this->document->addStyle('css/stylesheet/base.css');
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
    
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		
		// Daniel's robot detector
		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// A dirty hack to try to set a cookie for the multi-store feature
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$this->data['stores'] = array();


        $self_url_request =$_SERVER['REQUEST_URI'];

		$stores = $this->model_setting_store->getStores();
		$alternate = array(
			array(
				'lang' => $this->config->get('config_language'),
				'url'  => $this->config->get('config_secure') ? $this->config->get('config_ssl') : $this->config->get('config_url'),
			)
		);
		foreach($stores as $s){
			$store_id = $s['store_id'];
			$d = $this->model_setting_setting->getSettingByKey('config_secure',$store_id);
			$config_language = $this->model_setting_setting->getSettingByKey('config_language',$store_id);
			$host = '';
			if($d['config_secure']){
				$host = $s['ssl'];
			}else{
				$host = $s['url'];
			}
			$alternate[] = array(
				'lang' => $config_language['config_language'],
				'url'  => $host.$self_url_request,
			);
		}

        $this->data['alternate'] = $alternate;

        $this->data['config_name'] = $this->config->get('config_name');


        
        

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/head.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/head.tpl';
		} else {
			$this->template = 'default/template/common/head.tpl';
		}
		$this->render();
	} 	
}
?>
