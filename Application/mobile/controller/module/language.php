<?php  
class ControllerModuleLanguage extends Controller {
	protected function index() {
        $LANG =$this->language->load('module/language');
        $this->data = array_merge($this->data,$LANG);
		if (isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
		}
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
		$results = $this->model_localisation_language->getLanguages();
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

        foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image'],
					'base_url' => $result['base_url'].$_SERVER["REQUEST_URI"]
				);	
			}
		}
        $this->data['redirect'] =$redirect;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/language.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/module/language.tpl';
        } else{
            $this->template ='default/template/module/language.tpl';
        }

		$this->render();
	}
}
?>