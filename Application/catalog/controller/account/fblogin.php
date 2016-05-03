<?php 
class ControllerAccountFblogin extends Controller {
	private $error = array();

	public function index() {

        $fb_id = $this->request->post['fbid'];
        $fb_first_name = $this->request->post['fname'];
        $fb_last_name = $this->request->post['lname'];
        $fb_email = $this->request->post['email'];
        $fb_redirect = $this->request->post['redirect'];
        $fb_type = $this->request->post['type'];
        
        $json = array();
		$this->load->model('account/customer');
        $this->language->load('account/login');
        
        /**
         * 1) 判断 email地址是否存在
         * 2）如果存在则判断登陆，登陆成功下一步，如果不成功，就绑定用户
         * 3) 如果不存在，就直接添加一个用户，标记为facebook用户
         */
        if($this->model_account_customer->getCustomerByEmail($fb_email)){
            if ($this->customer->thirdlogin($fb_email,$fb_id,'facebook')) {
              $back_url =isset($this->request->post['redirect'])?htmlspecialchars_decode($this->request->post['redirect']):'/';
              $json['ask'] = 1;
              $json['callback'] = $back_url;

                //在绑定其他账户到同一个email
                if(isset($this->session->data['to_binding_third_email']) && $this->session->data['to_binding_third_email'] == $fb_email){
                    if(isset($this->session->data['to_binding_third_from']) && $this->session->data['to_binding_third_from'] !== 'facebook'){
                        if(isset($this->session->data['to_binding_third_uid']) && $this->session->data['to_binding_third_uid'] ){
                            $this->model_account_customer->bingding($this->customer->getId(),$this->session->data['to_binding_third_from'],$this->session->data['to_binding_third_uid'],$this->session->data['to_binding_third_email']);
                            $to_binding_third_from = $this->session->data['to_binding_third_from'];
                            $redirect = $this->url->link('account/binding/success',"binding=".$to_binding_third_from);
                            $json['ask'] = 1;
                            $json['callback'] = $redirect;
                            unset($this->session->data['to_binding_third_email']);
                            unset($this->session->data['to_binding_third_from']);
                            unset($this->session->data['to_binding_third_uid']);
                        }
                    }
                }

            }else{
                $this->session->data['back_url'] = $fb_redirect;
                $this->session->data['login_error_msg'] = $this->language->get('text_binding_facebook_heading_title');
               
                $this->session->data['third_from'] = 'facebook';
                $this->session->data['third_uid']  = $fb_id;
                $this->session->data['third_email'] = $fb_email;

                $json['ask'] = 0;
                $json['callback'] = $this->url->link('account/binding');
                
            }  
        }else{
            $data = array();
            $data['nickname'] = $fb_first_name;
            $data['email'] = $fb_email;
            $data['password'] = mt_rand(0, 100000);
            $data['newsletter'] = 1;
            $data['nickname'] = $fb_first_name;
            $data['nickname'] = $fb_first_name;
            $data['nickname'] = $fb_first_name;
            $data['third_from'] = 'facebook';
            $data['third_uid'] = $fb_id;
            $this->model_account_customer->addCustomer($data);
            
            if ($this->customer->isLogged()) {  
                 $this->customer->logout();
            }
            if ($this->customer->thirdlogin($fb_email,$fb_id,'facebook')) {
              $back_url =isset($this->request->post['redirect'])?htmlspecialchars_decode($this->request->post['redirect']):'/';
              $json['ask'] = 1;
              $json['callback'] = $back_url;
            }
            
        }
        $this->response->setOutput(json_encode($json));
	}


}
?>