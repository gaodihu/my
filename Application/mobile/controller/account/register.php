<?php 
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}
		
		$lang =$this->language->load('account/register');
        $this->data =array_merge($this->data,$lang);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_customer->addCustomer($this->request->post);
			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			if(isset($this->session->data['redirect'])){
                $redirect =$this->session->data['redirect'];
            }else{
                $redirect =$this->url->link('account/address');
            }
			$this->redirect($redirect);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
        if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		$this->data['action'] = $this->url->link('account/register', '', 'SSL');

		if (isset($this->request->post['nickname'])) {
			$this->data['name'] = $this->request->post['nickname'];
		} else {
			$this->data['name'] = '';
		}
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
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

         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/register.tpl')) {
            $this->template=$this->config->get('config_template') . '/template/account/register.tpl';
        } else{
             $this->template='default/template/account/register.tpl';
        }
		$this->children = array(
			'common/footer',
			'common/header'	
		);
		$this->response->setOutput($this->render());	
	}
	protected function validateForm() {
		if ((utf8_strlen($this->request->post['nickname']) < 1) || (utf8_strlen($this->request->post['nickname']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
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
	 public function check_email(){
		$this->language->load('account/register');
		$this->load->model('account/customer');
		$json = array();
		$json['message'] ='';
		$email =isset($this->request->post['email'])?$this->request->post['email']:'';
		$customer_info =$this->model_account_customer->getCustomerByEmail($email);
		if ($customer_info) {
			$json['message'] .=$this->language->get('error_exists');
		}
		if (utf8_strlen($email)> 96 || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
			$json['message'] .=$this->language->get('error_email');
		}
		$this->response->setOutput(json_encode($json));
	 }
}
?>