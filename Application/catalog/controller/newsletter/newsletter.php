<?php
class ControllerNewsletterNewsletter extends Controller {
    private $error = array();
	public function index() {
        $this->document->addStyle('css/stylesheet/account.css');
		$this->language->load('newsletter/newsletter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('newsletter/newsletter');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' &&$this->validateForm()){
            //随机得到一段字符串作为验证字符串
            $validate_code =uniqid();
            $data =array();
            if(isset($this->session->data['customer_id'])){
                $data['customer_id'] =$this->session->data['customer_id'];
                $newsletter_info =$this->model_newsletter_newsletter->getNewsletterByCustomer($data['customer_id']);
            }
            else{
                $data['customer_id'] =0;
                $newsletter_info=array();
            }
            $data['email'] =$this->request->post['newsletter_email'];
            $data['validate_code'] =$validate_code;
            if($newsletter_info){
                $this->model_newsletter_newsletter->editNewsletter($data['customer_id'],$data['email'],$validate_code,1);
            }
            else{
                $this->model_newsletter_newsletter->addNewsletter($data);
            }
            

            //添加订阅验证邮件到邮件队列
            $this->sendEmail($validate_code,$this->request->post['newsletter_email']);
            $this->data['heading_title'] = $this->language->get('heading_title');
	            
            $this->data['Newsletter'] = $this->request->post['newsletter_email'];
            $this->session->data['success'] =sprintf($this->language->get('newsletter_sucess'), $this->data['Newsletter']);
            $this->data['suceess'] = sprintf($this->language->get('newsletter_sucess'), $this->data['Newsletter']);
            
        }
        else{
            $this->data['heading_title'] = $this->language->get('heading_title_error');
            $this->data['error_email'] =isset($this->error['email'])?$this->error['email']:$this->language->get('error_email');
            $this->session->data['success'] =isset($this->error['email'])?$this->error['email']:$this->language->get('error_email');
            $this->data['suceess'] =isset($this->error['email'])?$this->error['email']:$this->language->get('error_email');
        }

        if(isset($this->request->post['redirect'])){
            $this->redirect($this->request->post['redirect']);
        }
        $this->document->setTitle($this->language->get('text_title'));
	    $this->document->setDescription($this->language->get('text_description'));
	    $this->document->setKeywords($this->language->get('text_keyword'));
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsletter/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/newsletter/newsletter.tpl';
		} else {
			$this->template = 'default/template/newsletter/newsletter.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'		
		);

		$this->response->setOutput($this->render());
	}
    
    //对邮箱进行验证
    public function validate(){
        $this->load->model('newsletter/newsletter');
        $this->language->load('newsletter/newsletter');
        $validate_code =isset($this->request->get['code'])?$this->request->get['code']:'';
        if(!empty($validate_code)){
            if(!$this->model_newsletter_newsletter->hasActive($validate_code)){
                if($newsletter_info = $this->model_newsletter_newsletter->validateNewsletter($validate_code)){
                    //添加订阅成功邮件到邮件队列
                    $this->sendSucessEmail($newsletter_info['email']);
                    $this->data['suceess'] =$this->language->get('validate_sucess');
                }
                else{
                    $this->data['suceess'] =$this->language->get('validate_fail');
                }
                
            }
            else{
                
                 $this->data['suceess'] =$this->language->get('validate_actived');
            }
        }
        else{
             $this->data['suceess'] =$this->language->get('validate_unlawful');
        }
        $this->document->setTitle($this->language->get('text_title_validate'));
	    $this->document->setDescription($this->language->get('text_description_validate'));
	    $this->document->setKeywords($this->language->get('text_keyword_validate'));
        $this->document->addStyle('css/stylesheet/account.css');
        $this->data['heading_title'] = $this->language->get('validate_heading_title');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsletter/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/newsletter/newsletter.tpl';
		} else {
			$this->template = 'default/template/newsletter/newsletter.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'		
		);

		$this->response->setOutput($this->render());
    }

    public function unsubcribe(){
        $this->load->model('newsletter/newsletter');
        $this->language->load('newsletter/newsletter');
        $email =$this->request->post['newsletter_email'];
        if(!$this->model_newsletter_newsletter->getNewsletter($email )){
            $this->data['suceess'] =$this->language->get('no_subscribe');
            $this->session->data['success'] =$this->language->get('no_subscribe');
        }
        else{
            $this->model_newsletter_newsletter->removeNewsletter($email );
            //发送退订邮件
            $this->sendUnsubcribeEmail($email);
            $this->data['suceess'] =$this->language->get('nosubscribe_sucess');
            $this->session->data['success'] =$this->language->get('nosubscribe_sucess'); 
        }
        if(isset($this->request->post['redirect'])){
            $this->redirect($this->request->post['redirect']);
        }
        $this->document->addStyle('css/stylesheet/account.css');
        $this->data['heading_title'] = $this->language->get('validate_heading_title');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsletter/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/newsletter/newsletter.tpl';
		} else {
			$this->template = 'default/template/newsletter/newsletter.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'		
		);

		$this->response->setOutput($this->render());
    }

    public function validateForm(){
        if ((utf8_strlen($this->request->post['newsletter_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['newsletter_email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
       if(!$this->error){
            $newsletter_info =$this->model_newsletter_newsletter->getNewsletter($this->request->post['newsletter_email']);
            if($newsletter_info&&$newsletter_info['status']==2){
                $this->error['email'] = $this->language->get('error_email_exist');
            }
            elseif($newsletter_info&&$newsletter_info['status']==1){
                $this->error['email'] = $this->language->get('error_email_not_active');
                $this->sendEmail($newsletter_info['validate_code'],$newsletter_info['email']);
            }
       }
        
        if (!$this->error) {
			return true;
		} else {
			return false;
		}
    }


    public function sendEmail($validate_code,$email){
        $this->load->model('tool/email');
        $this->language->load('mail/newsletter');
        $validate_link =$this->url->link('newsletter/newsletter/validate','code='.$validate_code);
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] = $this->config->get('config_name');
        $email_data['email_to'] =$email;
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
        $template->data['text_main_content'] = sprintf($this->language->get('text_main_content_validate'),$validate_link);
        $email_data['email_subject'] =$this->language->get('text_subject_validate');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/newsletter_validate.tpl')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/newsletter_validate.tpl');
        } else {
            $html = $template->fetch('default/template/mail/newsletter_validate.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }

    public function sendSucessEmail($email){
        $this->load->model('tool/email');
        $this->language->load('mail/newsletter');
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] =$this->config->get('config_name');
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
        $template->data['text_main_content'] = $this->language->get('text_main_content_sucess');
        $email_data['email_subject'] =$this->language->get('text_subject_sucess');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/newsletter_sucess.tpl')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/newsletter_sucess.tpl');
        } else {
            $html = $template->fetch('default/template/mail/newsletter_sucess.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }

     public function sendUnsubcribeEmail($email){
        $this->load->model('tool/email');
        $this->language->load('mail/newsletter');
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] =$this->config->get('config_name');
        $email_data['email_to'] =$email;
        $template = new Template();
        $template->data['title'] =$this->language->get('text_subject_unsubcribe');
        $template->data['subject'] =$this->language->get('text_subject_unsubcribe');  
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
        $template->data['text_main_content'] = sprintf($this->language->get('text_main_content_unsubcribe'),$this->url->link('account/newsletter',''));
        $email_data['email_subject'] =$this->language->get('text_subject_unsubcribe');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/newsletter_unsubcribe.tpl')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/newsletter_unsubcribe.tpl');
        } else {
            $html = $template->fetch('default/template/mail/newsletter_unsubcribe.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }
}
?>