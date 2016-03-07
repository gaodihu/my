<?php  
class ControllerCommonSuccess extends Controller {
	public function index() {
		$this->document->setTitle($this->session->data['page_title']);

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' =>false
		);
		$this->data['heading_title'] = $this->session->data['message_title'];
        $this->data['text_message'] =$this->session->data['message'];
		$this->data['button_continue'] = $this->language->get('button_continue');

		if ($this->cart->hasProducts()) {
			$this->data['continue'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['continue'] = $this->url->link('common/home', '', 'SSL');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());	
	}
}
?>