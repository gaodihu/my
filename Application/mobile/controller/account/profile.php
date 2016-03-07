<?php
class ControllerAccountProfile extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/profile', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $this->document->addScript('mobile/view/js/validform.js');
		$lang=$this->language->load('account/profile');
        $this->data =array_merge($this->data,$lang);
		$this->load->model('account/customer');
		$customer =$this->model_account_customer->getCustomer($this->session->data['customer_id']);
		$this->data['customer'] =$customer;
		$this->document->setTitle($this->language->get('heading_title'));
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('account/customer');
 
            $this->request->post['avatar'] =$customer['avatar'];
			$this->model_account_customer->editCustomer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/profile', '', 'SSL'));
		}
		$this->load->model('localisation/country');
	
		$this->data['countries'] = $this->model_localisation_country->getCountries();


		$this->data['action'] = $this->url->link('account/profile', '', 'SSL');


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/profile.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/profile.tpl';
		} else {
			$this->template = 'default/template/account/profile.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());			
	}
}
?>
