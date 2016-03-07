<?php

class ControllerAccountGoogle extends Controller {

    
    private $error = array();

    public function index() {
        $email = $this->request->request['email'];
        $gid = $this->request->request['gid'];
        $given_name = $this->request->request['given_name'];
        $google_email = $email;
        $google_uid = $gid;
        $google_first_name = $given_name;
        $json = array();
        $this->load->model('account/customer');
        $this->language->load('account/login');
        

        /**
         * 1) 判断 email地址是否存在
         * 2）如果存在则判断登陆，登陆成功下一步，如果不成功，就绑定用户
         * 3) 如果不存在，就直接添加一个用户，标记为facebook用户
         */
        if ($this->model_account_customer->getCustomerByEmail($google_email)) {
            if ($this->customer->thirdlogin($google_email, $google_uid, 'google')) {
                 $json['flag'] = 1;
                $redirect = $this->session->data['redirect'];
                if(!$redirect){
                    $redirect = '/';
                }
                $json['callback'] = $redirect;

                //在绑定其他账户到同一个email
                if(isset($this->session->data['to_binding_third_email']) && $this->session->data['to_binding_third_email'] == $google_email){
                    if(isset($this->session->data['to_binding_third_from']) && $this->session->data['to_binding_third_from'] !== 'google'){
                        if(isset($this->session->data['to_binding_third_uid']) && $this->session->data['to_binding_third_uid'] ){
                            $this->model_account_customer->bingding($this->customer->getId(),$this->session->data['to_binding_third_from'],$this->session->data['to_binding_third_uid'],$this->session->data['to_binding_third_email']);
                            $to_binding_third_from = $this->session->data['to_binding_third_from'];
                            $redirect = $this->url->link('account/binding/success',"binding=".$to_binding_third_from);

                            $json['flag'] = 1;
                            $json['redirect'] = $redirect;
                            unset($this->session->data['to_binding_third_email']);
                            unset($this->session->data['to_binding_third_from']);
                            unset($this->session->data['to_binding_third_uid']);
                        }
                    }
                }

            }else{
                $this->session->data['login_error_msg'] = $this->language->get('text_binding_google_heading_title');
                $this->session->data['third_from'] = 'google';
                $this->session->data['third_uid']  = $google_uid;
                $this->session->data['third_email'] = $google_email;
                $json['d'] = $this->session->data;
                $json['flag'] = 0;
                $json['callback'] = $this->url->link('account/binding');
            }  
        } else {
            $data = array();
            $data['nickname'] = $google_first_name;
            $data['email'] = $google_email;
            $data['password'] = mt_rand(0, 100000);
            $data['newsletter'] = 1;
            $data['third_from'] = 'google';
            $data['third_uid'] = $google_uid;
            $this->model_account_customer->addCustomer($data);
           
            if ($this->customer->isLogged()) {
                $this->customer->logout();
            }
            if ($this->customer->thirdlogin($google_email, $google_uid, 'google')) {
               $json['flag'] = 1;
                $redirect = $this->session->data['redirect'];
                if(!$redirect){
                    $redirect = '/';
                }
                $json['callback'] = $redirect;
            }
        }

       echo json_encode($json);
    }



    public function log($data,$title='') {
        $this->log->write($title.":" . json_encode($data));
    }
 
}

?>