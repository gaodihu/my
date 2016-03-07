<?php
class ControllerAccountNewsletter extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/newsletter', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        
        $customer_id =$this->session->data['customer_id'];
		$this->language->load('account/newsletter');
        $this->load->model('newsletter/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));
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
			'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', '', 'SSL'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $this->data['text_newsletter_frist'] = $this->language->get('text_newsletter_frist');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('newsletter/newsletter', '', 'SSL');
        $this->data['unsub_action'] = $this->url->link('newsletter/newsletter/unsubcribe', '', 'SSL');
		$newslette_info=$this->model_newsletter_newsletter->getActiveNewsletterByCustomer($customer_id);
		$this->data['newsletter'] = $newslette_info['email'];
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');
        $this->data['redirect_url'] = $this->url->link('account/newsletter', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
		} else {
			$this->template = 'default/template/account/newsletter.tpl';
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
}
?>