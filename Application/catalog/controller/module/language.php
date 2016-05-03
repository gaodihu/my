<?php  
class ControllerModuleLanguage extends Controller {
	protected function index() {
		if (isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];

			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
		}

		$this->language->load('module/language');

		$this->data['text_language'] = $this->language->get('text_language');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}

		$this->data['action'] = $this->url->link('module/language', '', $connection);

		$this->data['language_code'] = $this->session->data['language'];

		$this->load->model('localisation/language');

		$this->data['languages'] = array();
       // echo $_SERVER["QUERY_STRING"];exit;
		$languages = $this->model_localisation_language->getLanguages();
		if (!isset($this->request->get['route'])) {
			$redirect = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			unset($data['_route_']);

			$route = $data['route'];

			unset($data['route']);

			$url = '';

			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	

			$redirect = $this->url->link($route, $url, $connection);
		}

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		//default
		$store_id = 0;
		$site_domain  = $this->config->getDomain($store_id);
		$language_code = $this->config->get("config_language",$store_id);
		$language_name  = $languages[$language_code]['name'];
		$language_image = $languages[$language_code]['image'];

		$this->data['languages'][] = array(
			'name'  => $language_name,
			'code'  => $language_code,
			'image' => $language_image,
			'base_url' => $site_domain,
		);


		foreach ($results as $result) {
			if ($result['status']) {
				$store_id = $result['store_id'];
				$site_domain  = $this->config->getDomain($store_id);
				$language_code = $this->config->get("config_language",$store_id);
				$language_name  = $languages[$language_code]['name'];
				$language_image = $languages[$language_code]['image'];

				$this->data['languages'][] = array(
					'name'  => $language_name,
					'code'  => $language_code,
					'image' => $language_image,
					'base_url' => $site_domain,
				);	
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/language.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/language.tpl';
		} else {
			$this->template = 'default/template/module/language.tpl';
		}

		$this->render();
	}
}
?>