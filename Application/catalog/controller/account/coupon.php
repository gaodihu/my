<?php
class ControllerAccountCoupon extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/coupon', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('account/customer');
			$this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/password', '', 'SSL'));
		}
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/coupon', '', 'SSL'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_password'] = $this->language->get('text_password');
		
		$this->data['entry_old_password'] = $this->language->get('entry_old_password');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['password'])) { 
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['error_password'])) { 
			$this->data['error_old_password'] = $this->error['error_password'];
		} else {
			$this->data['error_old_password'] = '';
		}

		if (isset($this->error['confirm'])) { 
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}

		$this->data['action'] = $this->url->link('account/password', '', 'SSL');

		if (isset($this->request->post['old_password'])) {
			$this->data['old_password'] = $this->request->post['old_password'];
		} else {
			$this->data['old_password'] = '';
		}


		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/password.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/password.tpl';
		} else {
			$this->template = 'default/template/account/password.tpl';
		}

		$this->children = array(
			'account/menu',
			'account/right_top',
			'account/right_bottom',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());			
	}

	protected function validate() {
		$this->load->model('account/customer');
		if ((utf8_strlen($this->request->post['old_password']) < 4) || (utf8_strlen($this->request->post['old_password']) > 20)) {
			$this->error['old_password'] = $this->language->get('error_password');
		}
		//验证old password 是不是正确
		if(!$this->model_account_customer->validatePassword($this->session->data['customer_id'],$this->request->post['old_password'])){
			$this->error['error_password'] = $this->language->get('error_old_password');
		}
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
