<?php

class ControllerCheckoutCheckout extends Controller {

    public function index() {
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->redirect($this->url->link('checkout/cart'));
        }
        //检查用户是否登录
        if (!$this->customer->isLogged() ) {
            if(!isset($this->session->data['paypal'])){
                if(isset($this->request->get['guest']) && $this->request->get['guest'] == 0 ) {
                    $this->session->data['guest'] = 0;
                    $this->session->data['redirect'] = $this->url->link('checkout/checkout');
                    $this->redirect($this->url->link('account/login'));
                } else if( (isset($this->session->data['guest']) && $this->session->data['guest'] == 1)  ||  (isset($this->request->get['guest']) && $this->request->get['guest'] == 1 )){
                    
                    $this->session->data['guest'] = 1;
                }else{
                    $this->session->data['guest'] = 0;
                    $this->session->data['redirect'] = $this->url->link('checkout/checkout');
                    $this->redirect($this->url->link('account/login'));
                }
            }
        }
        
        // Validate minimum quantity requirments.			
        $products = $this->cart->getProducts();
        $format_products = array();
        $this->load->model('tool/image');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }
            if ($product['minimum'] > $product_total) {
                $this->redirect($this->url->link('checkout/cart'));
            }
            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], 74, 70);
            } else {
                $image = '';
            }
            $format_products[] = array(
                'name' => $product['name'],
                'image' => $image,
                'sku' => $product['model'],
                'price' => $product['price'],
                'price_format' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'total' => $product['total'],
                'total_format' => $this->currency->format($product['total']),
                'quantity' => $product['quantity'],
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );
        }

        $this->data['currency_code'] = $this->currency->getCode();
        $this->data['products'] = $format_products;
        
        
        $lang =$this->language->load('checkout/checkout');
        $this->data =array_merge($this->data,$lang);
        $this->document->setTitle($this->language->get('heading_title'));
      

        $this->data['logged'] = $this->customer->isLogged();
        
        $this->data['shipping_required'] = $this->cart->hasShipping();

        $this->load->model('account/customer');
        $this->load->model('account/address');
        //shipping address
        if($this->customer->isLogged()){
            $shipping_address_list = $this->model_account_address->getAddresses();
            if (is_array($shipping_address_list) && count($shipping_address_list) > 0) {
                //$this->data['shipping_address_list'] = $shipping_address_list;
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
                    }else{
                        $this->session->data['shipping_address'] = $shipping_address_list[$this->session->data['shipping_address']['address_id']];
                    }
                }
            }
        }
        if(isset($this->session->data['shipping_address'])){
            $this->data['default_address'] = $this->session->data['shipping_address'];
        }
        else{
            $this->data['default_address'] =null;
        }
       
        //shipping method 
         if(isset($this->session->data['shipping_method'])){
            $this->data['shipping_method'] =$this->session->data['shipping_method'];
        }
        else{
            $this->data['shipping_method'] =null;
        }
        if ($this->customer->isLogged()) {
            $nickname = $this->customer->getNickName();
            $this->data['nickname'] = $nickname;
        } else {
            $this->data['nickname'] = $this->language->get('text_guest');
        }
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        $this->load->model('checkout/checkout');
        //得到用户的可用总积分
        $this->load->model('account/points');
        
        if ($this->customer->isLogged()) {
            $total_points = $this->model_account_points->getTotalPoints()-$this->model_account_points->getTotalSpentPoints();
            $this->data['totalpoints'] = $total_points;
        } else {
            $this->data['totalpoints'] = 0;
        }
        $config_point_reword = $this->config->get('config_point_reward');
        //$this->data['order_total']=$this->getTotal();
        $this->data['config_point_reword'] = $config_point_reword;
        $this->data['text_have_points'] = sprintf($this->language->get('text_have_points'), $this->data['totalpoints']);
        $this->data['error_points'] = sprintf($this->language->get('error_points'), $config_point_reword);
        $this->data['error_than_points'] = sprintf($this->language->get('error_than_points'), $this->data['totalpoints']);
        if (isset($this->session->data['points'])) {
            $this->data['usered_points'] = $this->session->data['points'];
        } else {
            $this->data['usered_points'] = '';
        }
        $this->data['action'] = $this->url->link('checkout/confirm','','SSL');
        $this->data['address_url'] = $this->url->link('checkout/address');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/checkout.tpl';
        } else {
            $this->template = 'default/template/checkout/checkout.tpl';
        }
      
        
        $this->children = array(
            'checkout/shipping_method',
            'checkout/checkout/getTotal',
            'checkout/payment_method',
            'common/footer',
            'common/header'
        );
        
         
        
        $this->response->setOutput($this->render());
    }

    public function Total() {
        $json = array();
        //得到订单金额信息
        $json['subtol_coutent'] = $this->getTotal();
        $this->response->setOutput(json_encode($json));
    }

    public function country() {

        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status']
            );
        }

        $this->response->setOutput(json_encode($json));
    }

    public function validatePoints() {
        $this->load->model('account/points');
        $this->language->load('checkout/checkout');
        $json = array();
        $total_points = $this->model_account_points->getTotalPoints()-$this->model_account_points->getTotalSpentPoints();
        $config_point_reword = $this->config->get('config_point_reward');
        $points = isset($this->request->get['points']) ? $this->request->get['points'] : 0;
        $points = intval($points);
        $type = isset($this->request->get['type']) ? $this->request->get['type'] : '';
        if ($points > $total_points) {
            $json['error'] = 1;
            $json['meaage'] = $this->language->get('error_than_points');
        } elseif ($points < $config_point_reword) {
            $json['error'] = 1;
            $json['meaage'] = $this->language->get('error_points');
        } else {
            if ($type) {
                unset($this->session->data['points']);
            } else {
                $this->session->data['points'] = $points;
            }
            $json['error'] = 0;
            $this->load->model('checkout/checkout');
            $this->data['totalpoints'] = $total_points;
            $this->data['config_point_reword'] = $config_point_reword;
            $this->data['text_have_points'] = sprintf($this->language->get('text_have_points'), $this->data['totalpoints']);
            $this->data['text_user_points'] = $this->language->get('text_user_points');
            $this->data['text_apply'] = $this->language->get('text_apply');
            $this->data['text_cancel'] = $this->language->get('text_cancel');
            if (isset($this->session->data['points'])) {
                $this->data['usered_points'] = $this->session->data['points'];
            } else {
                $this->data['usered_points'] = '';
            }
            if($this->customer->isLogged()){
                $this->data['guest'] = 1;
            }
            else{
                $this->data['guest'] = 1;
            }
            $this->data['currency_code'] = $this->currency->getCode();
            $this->data['total_data'] = $this->model_checkout_checkout->getCartTotal();
           
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/include/order_subtoal.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/include/order_subtoal.tpl';
            } else {
                $this->template = 'default/template/checkout/include/order_subtoal.tpl';
            }
            $json['subtol_coutent'] = $this->render();
        }
        $this->response->setOutput(json_encode($json));
    }

    public function calShippingFee() {
        $this->load->model('checkout/checkout');
        $this->language->load('checkout/checkout');
        $this->load->model('account/address');
        $json = array();
        
        if(isset($this->request->request['address_id'])){
             $address_id = $this->request->request['address_id']; 
             if($address_id != ''){
                $address_id = intval($address_id);
                $this->load->model('account/address');
                $address = $this->model_account_address->getAddress($address_id);
                if($address){
                    $this->session->data['shipping_address'] = $address;
                }else{
                    $json['error'] = 1;
                    echo json_encode($json);
                    die;
                }
             }
             
        }
       

        $json['error'] = 0;
        $json['shipping_content'] = $this->getChild('checkout/shipping_method');
        //得到订单金额信息
        $json['subtol_coutent'] = $this->getTotal();
        
        $json['payment_content'] = $this->getChild('checkout/payment_method');
        
        $this->response->setOutput(json_encode($json));
    }

    public function getTotal() {
        $this->load->model('checkout/checkout');
        $this->language->load('checkout/checkout');
        $this->load->model('account/points');
        if($this->customer->isLogged()){
            $this->data['guest'] = 1;
            $total_points =  $this->model_account_points->getTotalPoints()-$this->model_account_points->getTotalSpentPoints();
            $config_point_reword = $this->config->get('config_point_reward');
            $this->data['totalpoints'] = $total_points;
            $this->data['config_point_reword'] = $config_point_reword;
            $this->data['text_have_points'] = sprintf($this->language->get('text_have_points'), $this->data['totalpoints']);
        }
        else{
            $total_points =0;
            $config_point_reword = $this->config->get('config_point_reward');
            $this->data['totalpoints'] = $total_points;
            $this->data['config_point_reword'] = $config_point_reword;
            $this->data['text_have_points'] = sprintf($this->language->get('text_have_points'), $this->data['totalpoints']);
            $this->data['guest'] = 0;
        }
        $this->data['currency_code'] = $this->currency->getCode();
        
        $this->data['total_data'] = $this->model_checkout_checkout->getCartTotal();
         //判断订单是否是偏远地区订单
        $shipping_address = $this->session->data['shipping_address'];
        $this->load->model('shipping/myled');
        $is_remote =$this->model_shipping_myled->isRemoteArea($shipping_address);
        if($is_remote){
            $this->data['is_remote'] =1;
            $remote_free =$this->model_shipping_myled->getRemoteFree();
            $remote_free_format =$this->currency->format($remote_free);
            $this->data['text_include_remote_free'] =sprintf($this->language->get('text_include_remote_free'),$remote_free_format);
        }else{
            $this->data['is_remote'] =0;
        }
        if (isset($this->session->data['points'])) {
            $this->data['usered_points'] = $this->session->data['points'];
        } else {
            $this->data['usered_points'] = '';
        }
        $this->data['text_user_points'] = $this->language->get('text_user_points');
        $this->data['text_apply'] = $this->language->get('text_apply');
        $this->data['text_cancel'] = $this->language->get('text_cancel');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/include/order_subtoal.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/include/order_subtoal.tpl';
        } else {
            $this->template = 'default/template/checkout/include/order_subtoal.tpl';
        }
        return $this->render();
    }

    public function updatePaymentMethod() {
        $payment_code = $this->request->post['payment_code'];
    }

    public function changePayment() {
        $this->load->model('checkout/checkout');
        $shipping_address = $this->session->data['shipping_address'];
        $payment_methods = $this->model_checkout_checkout->getPaymentMethod($shipping_address);
        $payment_code = $this->request->post['payment_code'];
        $select = 0;
        foreach ($payment_methods as $payment) {
            if ($payment['code'] == $payment_code) {
                $this->session->data['payment'] = $payment;
                $select = 1;
            }
        }
        $json = array();
        if ($select == 1) {
            $json['error'] = 0;
            $json['content'] = 'success';
        } else {
            $json['error'] = 1;
            $json['content'] = 'fail';
        }

        $this->response->setOutput(json_encode($json));
    }
    
    public function changeShipping() {
        $this->load->model('checkout/checkout');
        $this->language->load('checkout/checkout');
        $this->load->model('account/address');
        $json = array();

        $shipping_method = !empty($this->request->post['shipping_method']) ? $this->request->post['shipping_method'] : '';
        $pk = !empty($this->request->post['pk']) ? $this->request->post['pk'] : '';
        if (empty($shipping_method) || empty($pk)) {
            $json['error'] = 1;
            $json['content'] = 'please select shipping';
            echo json_encode($json);
            exit();
        }
        
        if (!isset($this->session->data['shipping_address'])) {
            $json['error'] = 1;
            $json['content'] = 'please select shipping address';
            echo json_encode($json);
            die();
        }

        $shipping_address = $this->session->data['shipping_address'];
        $_all_packages    = $this->model_checkout_checkout->getShippingMethod($shipping_address);
        $shipping_method_list = $_all_packages['can_shipping'];
        if ($shipping_method_list) {
            //改变运输方式
            $flag = 0;
            if(isset($shipping_method_list[$pk])) {
                foreach ($shipping_method_list[$pk]['methods'] as $item) {
                    if (strtolower($item['delivery_method']) == strtolower($shipping_method)) {
                        $this->session->data['delivery_method'][$pk] = $item;
                        $flag = 1;
                    }
                }
            
            }
            if(!$flag){
                $json['error'] = 1;
                $json['content'] = 'please select shipping address 2';
            }
        }else{
            $json['error'] = 1;
            $json['content'] = 'please select shipping address 3';
        }
      if(empty($json)){
            $json['error'] = 0;
            $json['content'] = 'success';
            //得到订单金额信息
            $json['subtol_coutent'] = $this->getTotal();
       }
       echo json_encode($json);
    }

    public function splitPackage(){
        $customer_split_package = $this->request->post['customer_split_package'];
        $customer_split_package = intval($customer_split_package);
        $this->session->data['customer_split_package'] = $customer_split_package;
        $data = array(
            'flag' =>1,
            'msg'  => '',
        );
        echo json_encode($data);
    }
}

?>