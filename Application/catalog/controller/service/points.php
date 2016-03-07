<?php 
class ControllerServicePoints extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('service/points');

		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setDescription($this->language->get('text_description'));
        $this->document->setKeywords($this->language->get('text_keywords'));
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' =>$this->language->get('text_separator')
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('service/points', $url, 'SSL'),        	
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
        $this->data = array_merge($this->data,$this->language->load('service/points'));
	
        
        

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/service/points.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/service/points.tpl';
		} else {
			$this->template = 'default/template/service/points.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());				
	}
}
?>