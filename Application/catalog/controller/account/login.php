<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');
        $this->language->load('account/login');
		//$back_url =isset($this->request->get['back_url'])?htmlspecialchars_decode($this->request->get['back_url']):$this->url->link('account/account', '', 'SSL');
        //$this->session->data['redirect'] =$back_url ;
        //登录送coupon 9.26-9.30
        if(isset($this->request->get['from'])&&$this->request->get['from']=='lgc'){
            //$end_time =strtotime('2014-11-26 23:59:59');
            //if(time()<$end_time){
                $this->data['text_login_to_get_5'] =$this->language->get('text_login_to_get_5');
                $this->data['text_coupon_code'] =$this->language->get('text_coupon_code');
                //$this->session->data['redirect'] =$this->url->link('common/success', '', 'SSL');
            //}
            
        }
        if(!isset($this->session->data['redirect'])){
            $this->session->data['redirect'] =$this->url->link('account/account', '', 'SSL');
        }
		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}
		$this->getTemplete();
	}

	public function login(){

		echo "-----------------<br/>";
		die();

		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}
		if (!empty($this->request->get['token'])) {
			$this->customer->logout();
		}

        
		if(!isset($this->request->get['is_ajax'])){
            
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateLogin()) {
                
				unset($this->session->data['guest']);
                $this->language->load('account/login');
                $text_message_title = $this->language->get('text_login_to_get_5');
                $text_coupon_code =$this->language->get('text_coupon_code');
                $text_coupon_code_expiration=$this->language->get('text_coupon_code_expiration');
                $this->session->data['message_title'] =$text_message_title;
                $lang_code =strtolower($this->session->data['language']);
                $this->session->data['message'] ="<div><img src='images/activity/login_get_coupon/public/code/coupon".$lang_code.".jpg' width=480 height=252><div>
                    <div style='font-size:18px;color:#333'>".$text_coupon_code.":<input type='text' style='font-size:24px;color:red; width:100px;height:30px;line-height:25px; font-weight:bold;padding-left:10px;'' value='login5'><br>
                    ".$text_coupon_code_expiration."</div>
                ";
                $this->redirect($this->session->data['redirect']);
			}
			$this->getTemplete();
		}
		else{
			$this->language->load('account/login');
			$email =isset($this->request->post['email'])?trim($this->request->post['email']):'';
			$password =isset($this->request->post['password'])?$this->request->post['password']:'';
			$redirect =isset($this->request->post['redirect'])?$this->request->post['redirect']:'';
			$json = array();
			$json['message'] ='';
			if (utf8_strlen($email)> 96 || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
				$json['message'] .=$this->language->get('error_email')."<br>";
			}
			if(!$json['message']){
				
				if($this->customer->login($email , $password)){
					if($redirect){
						$json['redirect'] =htmlspecialchars_decode($redirect);
					}
					else{
						$json['redirect'] =isset($this->session->data['redirect'])?htmlspecialchars_decode($this->session->data['redirect']):$this->url->link('account/account', '', 'SSL');
					}
				}
				else{
					$json['message'] .=$this->language->get('error_login');
				}
			}
			
			$this->response->setOutput(json_encode($json));

		}
	}

	public function register(){
		$this->load->model('account/customer');
		//非ajax注册，正常网页形式
		if(!isset($this->request->get['is_ajax'])){
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRegister()) {
				$this->model_account_customer->addCustomer($this->request->post);
				$this->customer->login($this->request->post['email'], $this->request->post['password']);
				if($this->session->data['redirect']){
					$this->redirect($this->session->data['redirect']);
				}
				else{
					$this->redirect($this->url->link('account/success'));
				}
				
			}
			$this->getTemplete();
		}
		//ajax注册
		else{	
			$this->language->load('account/login');
			$nickname =isset($this->request->post['nickname'])?trim($this->request->post['nickname']):'';
			$email =isset($this->request->post['email'])?trim($this->request->post['email']):'';
			$customer_info =$this->model_account_customer->getCustomerByEmail($email);
			$password =isset($this->request->post['password'])?$this->request->post['password']:'';
			$confim_password =isset($this->request->post['confim_password'])?$this->request->post['confim_password']:'';
			$condition =isset($this->request->post['condition'])?$this->request->post['condition']:'';
			$newsletter =isset($this->request->post['newsletter'])?1:0;
			$redirect =isset($this->request->post['redirect'])?trim($this->request->post['redirect']):'';
			$json = array();
			$json['message'] ='';
			if (utf8_strlen($email)> 96 || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
				$json['message'] .=$this->language->get('error_email')."<br>";
			}
			if ($customer_info) {
				$json['message'] .=$this->language->get('error_exists')."<br>";
			}
			if (utf8_strlen($password)< 4) {
				$json['message'] .=$this->language->get('error_password')."<br>";
			}
			if($password!==$confim_password){
				$json['message'] .=$this->language->get('error_confirm')."<br>";
			}
			if(!$condition){
				$json['message'] .=$this->language->get('error_agree');
			}
			$data =array(
				'nickname'=>$nickname,
				'email'	 =>$email,
				'password'	 =>$password,
				'newsletter'  =>$newsletter
			);
			if(!$json['message']){
				$this->model_account_customer->addCustomer($data);

				$this->customer->login($email, $password);

				unset($this->session->data['guest']);
				if($redirect){
					$json['redirect'] =htmlspecialchars_decode($redirect);
				}
				else{
					$json['redirect'] =$this->url->link('account/account', '', 'SSL');
				}
			}
			$this->response->setOutput(json_encode($json));
		}
		
	}

    public function validate(){
        $this->load->model('account/customer');
        $this->language->load('account/login');
        $this->document->setTitle($this->language->get('text_validate_heading_title'));
		$this->data['breadcrumbs'] = array(); 

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => $this->language->get('text_separator')
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('account/account'),
			'text'      => $this->language->get('text_account'),
			'separator' => false
		);
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
	 public function check_email(){
		$this->language->load('account/login_form');
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
	
	public function getTemplete(){
		$this->language->load('account/login');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('css/stylesheet/account.css');
		$this->document->addScript('js/User.js');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' =>$this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_user_login'),
			'href'      => $this->url->link('account/login', '', 'SSL'),      	
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$this->data['text_sign_in'] = $this->language->get('text_sign_in');
        $this->data['text_register_button'] = $this->language->get('text_register_button');
		$this->data['text_forgot_password'] = $this->language->get('text_forgot_password');
		$this->data['text_other_account'] = $this->language->get('text_other_account');
		$this->data['text_sign_facebook'] = $this->language->get('text_sign_facebook');
		$this->data['text_sign_google'] = $this->language->get('text_sign_google');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
        $config_checkout_id =$this->config->get('config_checkout_id');
        $this->load->model('catalog/information');
        $infomation_info =$this->model_catalog_information->getInformation($config_checkout_id);
        if($infomation_info){
            $this->data['text_agree'] =sprintf($this->language->get('text_agree'), $this->url->link('information/information','information_id='.$config_checkout_id),$infomation_info['title'],$infomation_info['title']);
        }else{
            $this->data['text_agree'] = '';
        }
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_nickname'] = $this->language->get('entry_nickname');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		if (isset($this->error['email_login'])) {
			$this->data['error_email_login'] = $this->error['email_login'];
		} else {
			$this->data['error_email_login'] = '';
		}
		if (isset($this->error['email_reg'])) {
			$this->data['error_email_reg'] = $this->error['email_reg'];
		} else {
			$this->data['error_email_reg'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['nickname'])) {
			$this->data['error_nickname'] = $this->error['nickname'];
		} else {
			$this->data['error_nickname'] = '';
		}
		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		if (isset($this->error['conditions'])) {
			$this->data['error_conditions'] = $this->error['conditions'];
		} else {
			$this->data['error_conditions'] = '';
		}
		if (isset($this->error['warning_login'])) {
			$this->data['error_warning_login'] = $this->error['warning_login'];
		} else {
			$this->data['error_warning_login'] = '';
		}
		if (isset($this->error['warning_reg'])) {
			$this->data['error_warning_reg'] = $this->error['warning_reg'];
		} else {
			$this->data['error_warning_reg'] = '';
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

		if (isset($this->request->post['nickname'])) {
			$this->data['nickname'] = $this->request->post['nickname'];
		} else {
			$this->data['nickname'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		if (isset($this->request->post['subscribe'])) {
			$this->data['subscribe'] = $this->request->post['subscribe'];
		} else {
			$this->data['subscribe'] = '';
		}

		if (isset($this->request->post['conditions'])) {
			$this->data['conditions'] = $this->request->post['conditions'];
		} else {
			$this->data['conditions'] = '';
		}
		$this->data['login_error_msg'] = '';
        if(isset($this->session->data['login_error_msg'])){
            $this->data['login_error_msg'] = $this->session->data['login_error_msg'];
			unset($this->session->data['login_error_msg']);

        }
        
		//js 判断
		$this->language->load('account/login_form');
		$this->data['error_email'] =$this->language->get('error_email');
		$this->data['login_url'] = $this->url->link('account/login/login', '', 'SSL'); 
		$this->data['register_url'] = $this->url->link('account/login/register', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/login.tpl';
		} else {
			$this->template = 'default/template/account/login.tpl';
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
	protected function validateRegister() {
		$this->load->model('account/customer');
		$this->language->load('account/login');
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email_reg'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['email_reg'] = $this->language->get('error_exists');
		}
	    if ((utf8_strlen($this->request->post['nickname']) < 1) || (utf8_strlen($this->request->post['nickname']) > 20)) {
			$this->error['nickname'] = $this->language->get('error_nickname');
		}

		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}
		if (!isset($this->request->post['conditions'])) {
			$this->error['conditions'] = $this->language->get('error_agree');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

    
    public function sendSucessEmail($email){
        $this->load->model('tool/email');
        $this->language->load('mail/customer');
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] = $this->config->get('config_name');
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
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/new_customer_sucess.tpl');
        } else {
            $html = $template->fetch('default/template/mail/new_customer_sucess.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }
}
?>