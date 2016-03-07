<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');
        $lang =$this->language->load('account/login');
        $this->data = array_merge($this->data,$lang);
		
        if(!isset($this->session->data['redirect'])){
            $this->session->data['redirect'] =$this->url->link('account/account', '', 'SSL');
        }
		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}
       if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateLogin()) {
                
				unset($this->session->data['guest']);
                
                $this->redirect($this->session->data['redirect']);
	    }
		$this->getTemplete();
	}
	public function getTemplete(){
		$this->language->load('account/login');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('catalog/view/theme/default/stylesheet/account.css');
		$this->document->addScript('catalog/view/javascript/User.js');
		

		$this->data['heading_title'] = $this->language->get('heading_title');
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
        if(isset($this->error['email_login'])){
		    $this->data['error_login'] =$this->error['email_login'];
        }else{
            $this->data['error_login'] ='';
        }
		$this->data['login_error_msg'] = '';
        if(isset($this->session->data['login_error_msg'])){
            $this->data['login_error_msg'] = $this->session->data['login_error_msg'];
            $this->data['third_email'] = $this->session->data['third_email'];
        }
		$this->data['login_url'] = $this->url->link('account/login', '', 'SSL'); 
		$this->data['register_url'] = $this->url->link('account/register', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
       
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
            $this->template=$this->config->get('config_template') . '/template/account/login.tpl';
        } else{
             $this->template='default/template/account/login.tpl';
        }
		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
	}
	protected function validateLogin() {
		$this->load->model('account/customer');
		$this->language->load('account/login');
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email_login'] = $this->language->get('error_email');
		}
		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
			$this->error['email_login'] = $this->language->get('error_login');
		}
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning_login'] = $this->language->get('error_approved');
		}
        $third_from = $this->session->data['third_from'];
        $third_uid  = $this->session->data['third_uid'];
        $this->model_account_customer->bingding($customer_info['customer_id'],$third_from,$third_uid);
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
     public function validate(){
        $this->load->model('account/customer');
        $this->language->load('account/login');
        $this->document->setTitle($this->language->get('text_validate_heading_title'));
		$this->data['heading_title'] = $this->language->get('text_validate_heading_title');
        $validate_code =isset($this->request->get['validate_code'])?$this->request->get['validate_code']:'';
        $customer_id =isset($this->request->get['customer_id'])?$this->request->get['customer_id']:'';
        if(!empty($validate_code)||!empty($customer_id)){
            if(!$this->model_account_customer->hasActive($customer_id,$validate_code)){
                if($customer_info = $this->model_account_customer->validateCustomer($customer_id,$validate_code)){
                    //添加用户验证成功邮件到邮件队列
                    $this->sendSucessEmail($customer_info['email']);
                    $this->data['text_message']=$this->language->get('validate_sucess');
                }
                else{
                    $this->data['text_message'] =$this->language->get('validate_fail');
                }
                
            }
            else{
                 $this->data['text_message'] =$this->language->get('validate_actived');
            }
        }
        else{
            $this->redirect($this->url->link('account/login'));
        }
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->link('common/home');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/common/success.tpl';
        } else{
            $this->template ='default/template/common/success.tpl';
        }
        
		$this->children = array(
			'common/footer',
			'common/header'			
		);
        
		$this->response->setOutput($this->render());
        
    }
     public function sendSucessEmail($email){
        $this->load->model('tool/email');
        $this->language->load('mail/customer');
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] ='MyLED ';
        $email_data['email_to'] =$email;
        $template = new Template();
        $template->data['title'] =$this->language->get('text_subject_sucess');
        $template->data['subject'] =$this->language->get('text_subject_sucess');  
        $template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');		
        $template->data['store_id'] = $this->config->get('config_store_id');
        $template->data['store_name'] = $this->config->get('config_name');
        if ($this->config->get('config_store_id')) {
            $template->data['store_url'] = $this->config->get('config_url');		
        } else {
            $template->data['store_url'] = HTTP_SERVER;	
        }
        $template->data['text_home'] =$this->language->get('text_home');
        $template->data['text_menu_new_arrivals'] =$this->language->get('text_menu_new_arrivals');
        $template->data['text_menu_top_sellers'] =$this->language->get('text_menu_top_sellers');
        $template->data['text_menu_deals'] =$this->language->get('text_menu_deals');
        $template->data['text_menu_clearance'] =$this->language->get('text_menu_clearance');

        $template->data['text_footer'] = $this->language->get('text_edm_foot');
        $template->data['text_main_content'] = sprintf($this->language->get('text_main_content_sucess'),$this->url->link('account/login'),$this->url->link('account/account'));
        $email_data['email_subject'] =$this->language->get('text_subject_sucess');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/new_customer_sucess.tpl')) {
            $html =$template->fetch($this->config->get('config_template') . '/template/mail/new_customer_sucess.tpl');
        } else{
            $html  = $template->fetch('default/template/mail/new_customer_sucess.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }
}
?>