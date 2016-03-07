<?php

class ControllerCheckoutAddress extends Controller {

    public function index() {
        $html = $this->renderAddressList();
        $this->response->setOutput($html['html']);
    }

    private function renderAddressList($default_address_id = '') {
        $this->load->model('account/customer');
        $this->load->model('account/address');
        $is_empty_address = 0;
        if ($this->customer->isLogged()) {
            $shipping_address_list = $this->model_account_address->getAddresses();
            if (is_array($shipping_address_list) && count($shipping_address_list) > 0) {
                $this->data['shipping_address_list'] = $shipping_address_list;
                $customer_id = $this->session->data['customer_id'];
                $customer_info = $this->model_account_customer->getCustomer($customer_id);
                //初始化运输地址session值
                if (!isset($this->session->data['shipping_address'])) {
                    //取得默认的寄送地址
                    if (isset($shipping_address_list[$customer_info['address_id']])) {
                        $this->session->data['shipping_address'] = $shipping_address_list[$customer_info['address_id']];
                    } else {
                        reset($shipping_address_list);
                        $last_address_number = end($shipping_address_list);
                        $current_address = $last_address_number;
                        $this->session->data['shipping_address'] = $current_address;
                    }
                } else {
                    if (!isset($shipping_address_list[$this->session->data['shipping_address']['address_id']])) {
                        $last_address_number = end($shipping_address_list);
                        $current_address = $last_address_number;
                        $this->session->data['shipping_address'] = $current_address;
                    }
                }
            } else {
                $is_empty_address = 1;
                $this->data['shipping_address_list'] = null;
                $this->session->data['shipping_address'] = null;
            }
        } else {
            if (isset($this->session->data['shipping_address'])) {
                $this->data['shipping_address_list'] = array();
                $this->data['shipping_address_list'][] = $this->session->data['shipping_address'];
            } else {
                $is_empty_address = 1;
                $this->data['shipping_address_list'] = null;
                $this->session->data['shipping_address'] = null;
            }
        }
        $this->data['logged'] = $this->customer->isLogged();
        $this->data['default_address'] = $this->session->data['shipping_address'];
        $this->data['checked_address'] = $this->session->data['shipping_address'];

        $this->template = 'default/template/checkout/include/shipping_address_list.tpl';
        $html = $this->render();
        $d = array('html' => $html,'empty'=>$is_empty_address);
        return $d;
    }

    //增加用户地址信息得到用户地址列表
    public function addAddress() {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $json = array();
        $json = $this->validateAddress();
        if (!$json) {
            $json['error'] = 0;
            if ($this->customer->isLogged()) {
                $address_id = $this->model_account_address->addAddress($this->request->post);
                $shipping_address = $this->model_account_address->getAddress($address_id);
            } else {
                $this->load->model('localisation/country');
                $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
                $shipping_address = $this->request->post;
                $shipping_address['country'] = $country_info['name'];
                $shipping_address['iso_code_2'] = $country_info['iso_code_2'];
                
                $this->load->model('localisation/zone');
                $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
                if($zone_info){
                    $zone = $zone_info['name'];
                    $shipping_address['zone'] = $zone;
                    $shipping_address['zone_code'] = $zone_info['code'];
                }else{
                    $shipping_address['zone'] = $this->request->post['zone'];
                }
                
                
                $this->session->data['shipping_address'] = $shipping_address;
                
            }
            $content = $this->renderAddressList();
            $json['content'] = $content['html'];
            if ($this->request->post['from'] == 'account') {
                $json['redirect'] = $this->url->link('account/address', '', 'SSL');
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    //显示要更改的地址信息
    public function editAddress() {
        $this->load->model('account/customer');
        $this->load->model('account/address');

        $this->language->load('checkout/checkout');
        //$this->language->load('account/address');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
        $json = array();
        if ($this->customer->isLogged()) {
            $address_id = $this->request->post['id'];
            $address_info = $this->model_account_address->getAddress($address_id);
            $this->data['is_default'] = 0;
            if ($address_info) {
                $customer_id = $this->session->data['customer_id'];
                $customer_info = $this->model_account_customer->getCustomer($customer_id);
                if ($customer_info['address_id'] == $address_id) {
                    $this->data['is_default'] = 1;
                }
            }
        } else {
             $this->data['is_default'] = 0;
             $address_info = $this->session->data['shipping_address'];
             $this->data['guest_email'] = $this->session->data['guest_email'];
        }

        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_street'] = $this->language->get('entry_street');
        $this->data['entry_city'] = $this->language->get('entry_city');
        $this->data['entry_country'] = $this->language->get('entry_country');
        $this->data['entry_zone'] = $this->language->get('entry_zone');
        $this->data['entry_postcode'] = $this->language->get('entry_postcode');
        $this->data['entry_phone'] = $this->language->get('entry_phone');
        $this->data['entry_company'] = $this->language->get('entry_company');
        $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
        $this->data['entry_set_default'] = $this->language->get('entry_set_default');
        $this->data['address_info'] = $address_info;
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        if ($address_info['country_id']) {
            $this->load->model('localisation/zone');
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($address_info['country_id']);
        }
        $this->data['logged'] = $this->customer->isLogged();
        $this->template = 'default/template/checkout/include/shipping_address_from.tpl';
        $content = $this->render();
        $json['content'] = $content;
        $this->response->setOutput(json_encode($json));
    }

    //修改地址信息
    public function updateAddress() {
        $this->language->load('checkout/cart');
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $type = $this->request->post['type'];
        $json = array();
        
        $json = $this->validateAddress($type);
        if (!$json) {
            $json['error'] = 0;
             if ($this->customer->isLogged()) {
                $address_id = $this->request->post['address_id'];
                $address_id = intval($address_id);
                if($address_id){
                    $data = $this->request->post;
                    $address_info = $this->model_account_address->editAddress($address_id, $data);
                }else{
                    $address_id = $this->model_account_address->addAddress($this->request->post);
                }
                $json['address_id'] = $address_id;
            }else{
                $this->load->model('localisation/country');
                $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
                $shipping_address = $this->request->post;
                $shipping_address['country'] = $country_info['name'];
                $shipping_address['iso_code_2'] = $country_info['iso_code_2'];
                
                $this->load->model('localisation/zone');
                $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
                if($zone_info){
                    $zone = $zone_info['name'];
                    $shipping_address['zone'] = $zone;
                    $shipping_address['zone_code'] = $zone_info['code'];
                }
                
                $this->session->data['shipping_address'] = $shipping_address;
                $json['address_id'] = '';
            }
            
            $content = $this->renderAddressList();
            
            $json['content'] = $content['html'];
            if ($this->request->post['from'] == 'account') {
                $json['redirect'] = $this->url->link('account/address', '', 'SSL');
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    //删除地址信息
    public function delAddress() {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $json = array();

        $json['error'] = 0;
        if ($this->customer->isLogged()) {
            $address_id = $this->request->post['address_id'];
            $address_info = $this->model_account_address->deleteAddress($address_id);
            
        } else {
            unset($this->session->data['shipping_address']);
        }
        $content = $this->renderAddressList();
        $json['content'] = $content['html'];
        $json['empty'] = $content['empty'];
       
        $this->response->setOutput(json_encode($json));
    }

    //删除地址信息
    public function defaultAddress() {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $json = array();
        $json['error'] = 0;
        $address_id = $this->request->post['address_id'];
        $address_info = $this->model_account_address->setDefaultAddress($address_id);
        $this->session->data['shipping_address'] = null;
        //得到默认地址ID
        $content = $this->renderAddressList();

        $json['content'] = $content['html'];
        $this->response->setOutput(json_encode($json));
    }

    public function validateAddress($type='shipping') {
        $this->language->load('checkout/checkout');
        //$this->language->load('checkout/address');
        $this->load->model('account/address');
        $json = array();
        if ($this->customer->isLogged()) {
            if (!empty($this->request->post['address_id'])) {
                if($type == 'shipping' || $type == ''){
                    if (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }
                }else if($type == 'billing'){
                    $billing_address = $this->model_account_address->getBillingAddress();
                    if ($this->request->post['address_id'] != $billing_address['address_id']) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }
                }
            }
        }else{ 
            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
                $json['error']['email'] = $this->language->get('error_email');
            }
            $this->session->data['guest_email'] = $this->request->post['email'];
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $json['error']['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $json['error']['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
            $json['error']['address_1'] = $this->language->get('error_address_1');
        }
       

        if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
            $json['error']['city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
            $json['error']['postcode'] = $this->language->get('error_postcode');
        }

        if ($this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
        }

        if ( (!isset($this->request->post['zone']) && !isset($this->request->post['zone_id'])) || ($this->request->post['zone_id'] == '' && $this->request->post['zone'] == '')) {
            $json['error']['zone'] = $this->language->get('error_zone');
        }
        return $json;
    }

    
    
    //增加用户地址信息得到用户地址列表
    public function saveBillingAddress() {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $json = array();
        $json = $this->validateAddress('billing');
        $this->request->post['type'] = 'billing';
        if (!$json) {
            $json['error'] = 0;
            $is_update = 0;
            if ($this->customer->isLogged()) {
                $address = $this->model_account_address->getBillingAddress();
                if($address){
                    $this->model_account_address->editAddress($address['address_id'],$this->request->post);
                    $address_id = $address['address_id'];
                    $is_update = 1;
                }
                if( !$is_update){
                    $address_id = $this->model_account_address->addAddress($this->request->post);
                }
                $this->session->data['billing_address'] = $this->model_account_address->getBillingAddress();
            } else {
                $this->load->model('localisation/country');
                $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
                $shipping_address = $this->request->post;
                $shipping_address['country'] = $country_info['name'];
                $shipping_address['iso_code_2'] = $country_info['iso_code_2'];
                
                $this->load->model('localisation/zone');
                $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
                if($zone_info){
                    $zone = $zone_info['name'];
                    $shipping_address['zone'] = $zone;
                    $shipping_address['zone_code'] = $zone_info['code'];
                }
                $this->session->data['billing_address'] = $shipping_address;
            }
            $json['billing_address'] = $this->session->data['billing_address'];
        }
        $this->response->setOutput(json_encode($json));
    }
    
    
}

?>