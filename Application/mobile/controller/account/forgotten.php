<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$lang =$this->language->load('account/forgotten');
        $this->data =array_merge($this->data,$lang);
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->language->load('mail/forgotten');

			
            $this->load->model('tool/email');
            $validate_code =uniqid();
            $this->session->data['resetpassword']['token'] =$validate_code;
            $this->session->data['resetpassword']['time'] =time();
            $validate_link =$this->url->link('account/forgotten/resetPwd','query_token='.$validate_code."&email=".$this->request->post['email']);
            $email_data =array();
            $email_data['store_id'] = $this->config->get('config_store_id');
            $email_data['email_from'] =$this->config->get('config_name');
            $email_data['email_to'] =$this->request->post['email'];
            $template = new Template();
            $template->data['title'] =$this->language->get('text_subject_validate');
            $template->data['subject'] =$this->language->get('text_subject_validate');  
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
            $template->data['text_main_content'] = sprintf($this->language->get('text_main_content_validate'),$validate_link,$validate_link);
            $email_data['email_subject'] =$this->language->get('text_subject_validate');
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/reset_passowrd.tpl')) {
                $html = $template->fetch($this->config->get('config_template') . '/template/mail/reset_passowrd.tpl');
            } else {
                $html = $template->fetch('default/template/mail/reset_passowrd.tpl');
            }
            $email_data['email_content'] =addslashes($html);
            $email_data['is_html'] =1;
            $email_data['attachments'] ='';
            $this->model_tool_email->addEmailList($email_data);
			$this->session->data['message'] = $this->language->get('text_validate');
            $this->session->data['message_title'] = $this->language->get('text_validate_title');
			$this->redirect($this->url->link('common/success', '', 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = $this->url->link('account/forgotten', '', 'SSL');

		$this->data['back'] = $this->url->link('account/login', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten.tpl';
		} else {
			$this->template = 'default/template/account/forgotten.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());		
	}
    

    public function resetPwd(){
        $this->language->load('account/forgotten');
        $this->load->model('account/customer');
        $token =isset($this->request->get['query_token'])?trim($this->request->get['query_token']):'';
        $email = isset($this->request->get['email'])?trim($this->request->get['email']):'';
        if($this->session->data['resetpassword']['token']==$token && time()<=($this->session->data['resetpassword']['time']+2*3600)){
            $this->document->setTitle($this->language->get('text_set_new_password'));
            if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSetForm()){
                $new_password = $this->request->post['new_password'];
                $confim_new_password = $this->request->post['confim_new_password'];
                $email = $this->request->post['email'];
                $this->model_account_customer->editPassword($email, $new_password); 
                unset($this->session->data['resetpassword']);
                $this->session->data['message'] = $this->language->get('text_success');
                $this->session->data['message_title'] = $this->language->get('text_success_title');
                $this->redirect($this->url->link('common/success', '', 'SSL'));
             }
            $this->data['entry_new_password'] = $this->language->get('text_set_new_password');
            $this->data['entry_confim_new_password'] = $this->language->get('entry_confim_new_password');
		    $this->data['heading_title'] = $this->language->get('text_set_new_password');
            if (isset($this->error['warning'])) {
			    $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }
            $url ='';
            $url ='query_token='.$token;
            $url .="&email=".$email;
            $this->data['action'] =$this->url->link('account/forgotten/resetPwd',$url);
            $this->data['email'] = $email;
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reset_password.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/reset_password.tpl';
            } else {
                $this->template = 'default/template/account/reset_password.tpl';
            }

            $this->children = array(
                'common/footer',
                'common/header'	
            );
            $this->response->setOutput($this->render());
             
        }else{
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
        
    }
	protected function validate() {
		if (!isset($this->request->post['email'])||empty($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
    protected function validateSetForm() {
		if (empty($this->request->post['new_password'])) {
			$this->error['warning'] = $this->language->get('error_empty_new_password');
		} 
        if(empty($this->request->post['confim_new_password'])){
            $this->error['warning'] = $this->language->get('error_empty_confim_new_password');
        }
        if($this->request->post['new_password']!=$this->request->post['confim_new_password']){
            $this->error['warning'] = $this->language->get('error_not_eq_password');
        }

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>