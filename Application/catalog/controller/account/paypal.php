<?php

class ControllerAccountPaypal extends Controller {

    const TEST_MODEL = 0;
    const CLIENT_ID = 'AQ4-okOokGPQAMmL_OewXUAVp6CzOx5c6Y_sM7zKwGHBjU8fhIdql4p4PI2u-5jpKTe8LkstNYoqS-yC';
    const SECRET = 'EI5Zl40SXmLZK1djynRCuPqid2sPgjO_siHdl0ErOYQB4Uzcvtqo2Tt_JevFC2BM6U01sl370QD05hLf';

    private $error = array();

    public function index() {
        $this->log($this->request->request,'paypal respone 1:');
        $paypal_auth_code = $this->request->request['code'];
        $paypal_auth_scope = $this->request->request['scope'];

        if(!$paypal_auth_code || !$paypal_auth_scope){
            $redirect = $this->url->link('account/login',"p=1");
            echo "<script>window.opener.location=\"".$redirect."\";window.open('','_self','');window.close();</script>";
            die();

        }

        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $paypal_auth_code,
            'redirect_uri' => $this->config->getDomain().'/paypal_login.php',
            'Client-Id' => self::CLIENT_ID,
            'Secret' => self::SECRET,
        );
        $resutlt = $this->tokenservice($data);

        $this->session->data['paypal_login_token'] = $resutlt;
        $user_info = $this->getUserInfo($resutlt['access_token']);



        $paypal_email = $user_info['email'];
        $paypal_uid = $user_info['user_id'];
        $paypal_first_name = $user_info['given_name'];
        $json = array();
        $this->load->model('account/customer');
        $this->language->load('account/login');

        if(!$paypal_email || !$paypal_uid ){
            $redirect = $this->url->link('account/login',"p=2");
            echo "<script>window.opener.location=\"".$redirect."\";window.open('','_self','');window.close();</script>";
            die();

        }


        /**
         * 1) 判断 email地址是否存在
         * 2）如果存在则判断登陆，登陆成功下一步，如果不成功，就绑定用户
         * 3) 如果不存在，就直接添加一个用户，标记为facebook用户
         */
        if ($this->model_account_customer->getCustomerByEmail($paypal_email)) {

            if ($this->customer->thirdlogin($paypal_email, $paypal_uid, 'paypal')) {
                $this->addAdress($user_info);
                if(strpos($paypal_auth_scope, 'expresscheckout')!==false){
                    $this->session->data['is_paypal_login_pay'] = 1;
                }

                //在绑定其他账户到同一个email
                if(isset($this->session->data['to_binding_third_email']) && $this->session->data['to_binding_third_email'] == $paypal_email){
                    if(isset($this->session->data['to_binding_third_from']) && $this->session->data['to_binding_third_from'] !== 'paypal'){
                        if(isset($this->session->data['to_binding_third_uid']) && $this->session->data['to_binding_third_uid'] ){
                            $this->model_account_customer->bingding($this->customer->getId(),$this->session->data['to_binding_third_from'],$this->session->data['to_binding_third_uid'],$this->session->data['to_binding_third_email']);
                            $to_binding_third_from = $this->session->data['to_binding_third_from'];
                            $redirect = $this->url->link('account/binding/success',"binding=".$to_binding_third_from);
                            unset($this->session->data['to_binding_third_email']);
                            unset($this->session->data['to_binding_third_from']);
                            unset($this->session->data['to_binding_third_uid']);

                            echo "<script>window.opener.location=\"".$redirect."\";window.open('','_self','');window.close();</script>";
                            die();
                        }
                    }
                }


                $redirect = $this->session->data['redirect'];
                if($redirect == ''){
                    $redirect = $this->url->link('/');
                }
                echo "<script>window.opener.location=\"".$redirect."\";window.open('','_self','');window.close();</script>";
                die();
            }else{
                //$this->session->data['login_error_msg'] = $this->language->get('text_binding_paypal_heading_title');
                $this->session->data['third_from'] = 'paypal';
                $this->session->data['third_uid']  = $paypal_uid;
                $this->session->data['third_email'] = $paypal_email;
                $json['ask'] = 0;
                $json['callback'] = $this->url->link('account/binding');

                echo "<script>window.opener.location=\"".$this->url->link('account/binding')."\";window.open('','_self','');window.close();</script>";
                die();
            }
        } else {

            $data = array();
            $data['nickname'] = $paypal_first_name;
            $data['email'] = $paypal_email;
            $data['password'] = mt_rand(0, 100000);
            $data['newsletter'] = 1;
            $data['third_from'] = 'paypal';
            $data['third_uid'] = $paypal_uid;
            $this->model_account_customer->addCustomer($data);
           
            if ($this->customer->isLogged()) {
                $this->customer->logout();
            }
            if ($this->customer->thirdlogin($paypal_email, $paypal_uid, 'paypal')) {
                $this->addAdress($user_info);
                if(strpos($paypal_auth_scope, 'expresscheckout')!==false){
                    $this->session->data['is_paypal_login_pay'] = 1;
                }
            }

        }

        $redirect = $this->session->data['redirect'];
        if($redirect == ''){
            $redirect = $this->url->link('/');
        }
        echo "<script>window.opener.location=\"".$redirect."\";window.open('','_self','');window.close();</script>";

    }

    public function getUserInfo($access_token) {
         if (self::TEST_MODEL) {
            $api_endpoint = 'https://api.sandbox.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid';
         }else{
            $api_endpoint = 'https://api.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid';
         }

        $headers = array(
            "Content-Type:application/json",
            "Authorization: Bearer {$access_token}"
        );

        $defaults = array(
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $api_endpoint,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => $headers,
        );
        $this->log(var_export($defaults,true), 'getUserInfo request');
        $ch = curl_init();

        curl_setopt_array($ch, $defaults);

        if (!$result = curl_exec($ch)) {
            $this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
        }
        $this->log($result, 'getUserInfo Result');

        curl_close($ch);
        $result_data = json_decode($result, true);
        return $result_data;
    }

    public function tokenService($data) {
        if (self::TEST_MODEL) {
            $api_endpoint = 'https://api.sandbox.paypal.com/v1/identity/openidconnect/tokenservice';
        } else {
            $api_endpoint = 'https://api.paypal.com/v1/identity/openidconnect/tokenservice';
        }


        $this->log($data, 'Call data');


        $t =  self::CLIENT_ID.":".self::SECRET;

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $api_endpoint,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $t,
            CURLOPT_POSTFIELDS => http_build_query($data, '', "&")
        );

        $ch = curl_init();

        curl_setopt_array($ch, $defaults);

        if (!$result = curl_exec($ch)) {
            $this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
        }

        $this->log($result, 'tokenservice Result');

        curl_close($ch);
        $result_data = json_decode($result, true);
        return $result_data;
    }
    

    public function log($data,$title='') {
        $this->log->write($title.":" . json_encode($data));
    }
    
    public function addAdress($data){
        $shipping_address = array();
        $shipping_address['address_id'] = '';
        $shipping_address['customer_id'] = '';
        
        $name = trim($data['name']);
        $name_arr = explode(' ',$name,2);
        $shipping_address['firstname'] = trim($name_arr[0]);
        $shipping_address['lastname'] = trim($name_arr[1]);
        
        $shipping_address['company'] = '';
        $shipping_address['company_id'] = '';
        $shipping_address['phone'] = trim($data['phone_number']);
     
        $street_address = trim($data['address']['street_address']);
        $street_address_arr = explode(',',$street_address,2);
        
        $shipping_address['address_1'] = trim($street_address_arr[0]);
       
        if (isset($street_address_arr[1])) {
            $shipping_address['address_2'] = trim($street_address_arr[1]);
        } else {
            $shipping_address['address_2'] = '';
        }

        $shipping_address['city']       = trim($data['address']['locality']);
        $shipping_address['postcode']   = trim($data['address']['postal_code']);
        $shipping_address['iso_code_2'] = trim($data['address']['country']);
        $shipping_address['zone_code']  = trim($data['address']['region']);  
        
        //处理china海外注册帐号
        if(strtoupper($shipping_address['iso_code_2']) == 'C2'){
            $shipping_address['iso_code_2'] = 'CN';
        }
 
        $shipping_address['zone_name'] = trim($data['address']['region']); 
        

        $shipping_address['country_id'] = '';
        $shipping_address['zone_id'] = '';

        $paypal_email = trim($data['email']);

        $country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $shipping_address['iso_code_2'] . "' AND `status` = '1' LIMIT 1")->row;
        if ($country_info) {
            $shipping_address['country_id'] = $country_info['country_id'];
            $shipping_address['country'] = $country_info['name'];
        
            //地区匹配
            $zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE  `status` = '1' AND `country_id` = '" . (int) $country_info['country_id'] . "'");
            foreach($zone_info->rows as $zone_info_row){
                if(strtoupper($shipping_address['zone_name']) == strtoupper($zone_info_row['code'])){
                    $shipping_address['zone_id'] = $zone_info_row['zone_id'];
                    $shipping_address['zone'] = $zone_info_row['name'];
                    $shipping_address['zone_code']  = $zone_info_row['code'];
                    $shipping_address['zone_name'] = $zone_info_row['name'];
                }
                if(strtoupper($shipping_address['zone_name']) == strtoupper($zone_info_row['name'])){
                    $shipping_address['zone_id'] = $zone_info_row['zone_id'];
                    $shipping_address['zone'] = $zone_info_row['name'];
                    $shipping_address['zone_code']  = $zone_info_row['code'];
                    $shipping_address['zone_name'] = $zone_info_row['name'];
                }
            }
        }

        $this->load->model('account/customer');
        $this->load->model('account/address');
        if ($this->customer->isLogged()) {
            $address_data = array(
                'firstname' => $shipping_address['firstname'],
                'lastname' => $shipping_address['lastname'],
                'company' => $shipping_address['company'],
                'company_id' => $shipping_address['company_id'],
                'tax_id' => '',
                'address_1' => $shipping_address['address_1'],
                'address_2' => $shipping_address['address_2'],
                'postcode' => $shipping_address['postcode'],
                'city' => $shipping_address['city'],
                'zone_id' => $shipping_address['zone_id'],
                'zone_name' => $shipping_address['zone_name'],
                'country_id' => $shipping_address['country_id'],
                'phone' => $shipping_address['phone'],
                'is_paypal' => 1,
            );
            $current_address_id = 0;
            $address_list =  $this->model_account_address->getAddresses();
            foreach($address_list as $item){

                if(
                    strtolower($item['firstname'])  == strtolower($address_data['firstname']) && 
                    strtolower($item['lastname'])  == strtolower($address_data['lastname']) && 
                    strtolower($item['address_1'])  == strtolower($address_data['address_1']) && 
                    strtolower($item['address_2'])  == strtolower($address_data['address_2']) && 
                    strtolower($item['postcode'])  == strtolower($address_data['postcode']) && 
                    strtolower($item['city'])  == strtolower($address_data['city']) &&
                    intval($item['zone_id'])  == intval($address_data['zone_id']) &&
                    intval($item['country_id'])  == intval($address_data['country_id']) &&
                    strtolower($item['phone'])  == strtolower($address_data['phone'])
                   ){
                    $current_address_id = $item['address_id'];
                }
            }
            if($current_address_id<=0){
                $address_id = $this->model_account_address->addAddress($address_data);
                $current_address_id = $address_id;
            }
        } else {
        }

    }
}

?>