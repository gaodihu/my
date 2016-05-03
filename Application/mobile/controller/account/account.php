<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$lang =$this->language->load('account/account');
		$this->data =array_merge($this->data,$lang);
        $this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
		$this->data['nickname'] =$customer_info['nickname']?$customer_info['nickname']:$customer_info['firstname'];
        $this->data['logout'] = $this->url->link('account/logout', '', 'SSL');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
		}
		$this->children = array(
			'account/menu',
			'common/footer',
			'common/header'		
		);
		$this->response->setOutput($this->render());
	}
}
?>