<?php

class ControllerCheckoutConfirm extends Controller {

    public function index() {
        //验证购物车
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $redirect = $this->url->link('checkout/cart','','SSL');
            $this->redirect($redirect);
        }
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/cart','','SSL');
                $this->redirect($redirect);
                break;
            }
        }

        if ($this->cart->hasShipping()) {
            // Validate if shipping address has been set.		
            $this->load->model('account/address');
            $shipping_address = $this->session->data['shipping_address'];
            if ($this->customer->isLogged() && isset($this->session->data['shipping_address'])) {
                $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id']);
            }
            if (empty($shipping_address)) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
                $this->redirect($redirect);
            }
            if(!isset($this->session->data['delivery_method']) || empty($this->session->data['delivery_method'])){
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
                $this->redirect($redirect);
            }
            $this->load->model('checkout/checkout');
            $_all_packages = $this->model_checkout_checkout->getShippingMethod($shipping_address);
            $shipping_packages = $_all_packages['can_shipping'];
            if(count($_all_packages['no_shipping'])>0){
                $this->session->data['no_shipping'] = $_all_packages['no_shipping'];
                $redirect = $this->url->link('checkout/cart', '', 'SSL');
                $this->redirect($redirect);
            }
            foreach($shipping_packages as $_pk => $_package){
                if(!isset($this->session->data['delivery_method'][$_pk])){
                    $redirect = $this->url->link('checkout/checkout', '', 'SSL');
                    $this->redirect($redirect);
                }
                $methods_list = array_keys($shipping_packages[$_pk]['methods']);
               
                if(!in_array($this->session->data['delivery_method'][$_pk]['delivery_method'],$methods_list)){
                    $redirect = $this->url->link('checkout/checkout', '', 'SSL');
                   
                    $this->redirect($redirect);
                }
                if($this->session->data['delivery_method'][$_pk]['price'] != $shipping_packages[$_pk]['methods'][$this->session->data['delivery_method'][$_pk]['delivery_method']]['price']){
                    $redirect = $this->url->link('checkout/checkout', '', 'SSL');
                    $this->redirect($redirect);
                }
            }
            $this->session->data['shipping_packages'] = $shipping_packages;
            
            
        } else {
            //unset($this->session->data['shipping_method']);
        }
        
        
        $payment_code = '';
        $current_payment = $this->session->data['payment'];
        if (empty($current_payment)) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            $this->redirect($redirect);
        }
        $payment_code = $current_payment['code'];
        $this->load->model('checkout/checkout');
        $payment_methods = $this->model_checkout_checkout->getPaymentMethod($shipping_address);
        $select = 0;
        foreach ($payment_methods as $payment) {
            if ($payment['code'] == $payment_code) {
                $this->session->data['payment'] = $payment;
                $select = 1;
            }
        }
        if (!$select) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            $this->redirect($redirect);
        }


        $total_data = array();
        $total = 0;
        unset($this->session->data['package_total_data']);
        unset($this->session->data['package_total']);
        $taxes = $this->cart->getTaxes();

        $this->load->model('setting/extension');

        $sort_order = array();
        $results = $this->model_setting_extension->getExtensions('total');
        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);
        $this->language->load('checkout/checkout');

        $data = array();

        $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $data['store_id'] = $this->config->get('config_store_id');
        $data['store_name'] = $this->config->get('config_name');

        if ($data['store_id']) {
            $data['store_url'] = $this->config->get('config_url');
        } else {
            $data['store_url'] = HTTP_SERVER;
        }

        if ($this->customer->isLogged()) {
            $data['customer_id'] = $this->customer->getId();
            $data['customer_group_id'] = $this->customer->getCustomerGroupId();
            $data['firstname'] = $this->customer->getFirstName();
            $data['lastname'] = $this->customer->getLastName();
            $data['nickmane'] = $this->customer->getNickName();
            $data['email'] = $this->customer->getEmail();
            $data['telephone'] = $this->customer->getTelephone();
            $data['fax'] = $this->customer->getFax();
            if (isset($this->session->data['payment_address_id'])) {
                $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
            } else {
                $payment_address = '';
            }
        }else{
            $data['firstname'] = $shipping_address['firstname'];
            $data['lastname'] = $shipping_address['lastname'];
            $data['nickmane'] = '';
            $data['email'] = isset($this->session->data['guest_email']) ? $this->session->data['guest_email']:$shipping_address['email'];
            $data['telephone'] = '';
            $data['fax'] = '';
        }
        if (isset($this->session->data['billing_address'])) {
            $data['payment_firstname'] = $this->session->data['billing_address']['firstname'];
            $data['payment_lastname'] = $this->session->data['billing_address']['lastname'];
            $data['payment_company'] = $this->session->data['billing_address']['company'];
            $data['payment_company_id'] = $this->session->data['billing_address']['company_id'];
            $data['payment_tax_id'] = $this->session->data['billing_address']['tax_id'];
            $data['payment_address_1'] = $this->session->data['billing_address']['address_1'];
            $data['payment_address_2'] = $this->session->data['billing_address']['address_2'];
            $data['payment_city'] = $this->session->data['billing_address']['city'];
            $data['payment_postcode'] = $this->session->data['billing_address']['postcode'];
            $data['payment_zone'] = $this->session->data['billing_address']['zone'];
            $data['payment_zone_id'] = $this->session->data['billing_address']['zone_id'];
            $data['payment_zone_code'] = $this->session->data['billing_address']['zone_code']; 
            $data['payment_country'] = $this->session->data['billing_address']['country'];
            $data['payment_country_id'] = $this->session->data['billing_address']['country_id'];
            $data['payment_country_code'] = $this->session->data['billing_address']['iso_code_2'];
            $data['payment_address_format'] = '';
            $data['payment_phone'] = $this->session->data['billing_address']['phone'];
        } else {
            $data['payment_firstname'] = '';
            $data['payment_lastname'] = '';
            $data['payment_company'] = '';
            $data['payment_company_id'] = '';
            $data['payment_tax_id'] = '';
            $data['payment_address_1'] = '';
            $data['payment_address_2'] = '';
            $data['payment_city'] = '';
            $data['payment_postcode'] = '';
            $data['payment_zone'] = '';
            $data['payment_zone_id'] = '';
            $data['payment_zone_code'] = ''; 
            $data['payment_country'] = '';
            $data['payment_country_id'] = '';
            $data['payment_country_code'] = '';
            $data['payment_address_format'] = '';
            $data['payment_phone'] = '';
        }
        if (isset($this->session->data['payment'])) {
            $data['payment_method'] = $this->session->data['payment']['title'];
            $data['payment_code'] = $this->session->data['payment']['code'];
        } else {
            $data['payment_method'] = '';
            $data['payment_code'] = '';
        }


        $data['shipping_firstname'] = $shipping_address['firstname'];
        $data['shipping_lastname'] = $shipping_address['lastname'];
        $data['shipping_company'] = $shipping_address['company'];
        $data['shipping_address_1'] = $shipping_address['address_1'];
        $data['shipping_address_2'] = $shipping_address['address_2'];
        $data['shipping_city'] = $shipping_address['city'];
        $data['shipping_postcode'] = $shipping_address['postcode'];
        $data['shipping_zone'] = $shipping_address['zone'];
        $data['shipping_zone_id'] = $shipping_address['zone_id'];
        $data['shipping_zone_code'] = $shipping_address['zone_code']; 
        $data['shipping_country'] = $shipping_address['country'];
        $data['shipping_country_id'] = $shipping_address['country_id'];
        $data['shipping_country_code'] = $shipping_address['iso_code_2'];
        $data['shipping_address_format'] = $shipping_address['address_format'];
        $data['shipping_phone'] = $shipping_address['phone'];
        $data['order_tax_id'] = $shipping_address['tax_id'];


        //判断订单是否是偏远地区订单
        $this->load->model('shipping/myled');
        $is_remote =$this->model_shipping_myled->isRemoteArea($shipping_address);
        if($is_remote){
            $data['is_remote'] =1;
        }else{
            $data['is_remote'] =0;
        }

        if (isset($this->request->cookie['tracking'])) {
            $this->load->model('affiliate/affiliate');

            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
            $subtotal = $this->cart->getSubTotal();

            if ($affiliate_info) {
                $data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }
        } else {
            $data['affiliate_id'] = 0;
            $data['commission'] = 0;
        }

        $data['language_id'] = $this->config->get('config_language_id');
        $data['currency_id'] = $this->currency->getId();
        $data['currency_code'] = $this->currency->getCode();
        $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
        $data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $data['accept_language'] = '';
        }
        ///////////////////////////////////生产主订单/////////////////////////////////////
        /*
         * 生产主订单，如果只有一个包，就只生产主订单
         */
        $order_data = array();
        
        $this->load->model('checkout/order');
        $shipping_packages = $this->session->data['shipping_packages'];
        if(count($shipping_packages)>1){
            $order_data['shipping_method'] = '';
            $order_data['shipping_code']   = '';
            $order_data['parent_id'] = '0';
            $order_data['is_parent'] = '1';
        }else{
            
        
            $_delivery_method = $this->session->data['delivery_method'];
            foreach($_delivery_method as $_pk => $_method){
                $order_data['shipping_method'] = $this->session->data['delivery_method'][$_pk]['delivery_type'];
                $order_data['shipping_code']   = $this->session->data['delivery_method'][$_pk]['delivery_method'];
            }
            $order_data['parent_id'] = '0';
            $order_data['is_parent'] = '0';
        }  

        $product_data = array();
       
        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $value = $this->encryption->decrypt($option['option_value']);
                }

                $option_data[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id' => $option['option_id'],
                    'option_value_id' => $option['option_value_id'],
                    'name' => $option['name'],
                    'value' => $value,
                    'type' => $option['type']
                );
            }
            $this->load->model('tool/image');
            $product_data[] = array(
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'image' => $product['image'],
                'image_email' => $this->model_tool_image->resize($product['image'], 60, 60),
                'model' => $product['model'],
                'option' => $option_data,
                'download' => $product['download'],
                'quantity' => $product['quantity'],
                'subtract' => $product['subtract'],
                'original_price' => $product['original_price'],
                'price' => $product['price'],
                'price_format' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'trie_price' => $product['trie_price'],
                'special_price' => $product['special_price'],
                'total' => $product['total'],
                'total_format' => $this->currency->format($product['total']),
                'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward' => $product['reward'],
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL')
            );
        }
        // Gift Voucher
        $voucher_data = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $voucher_data[] = array(
                    'description' => $voucher['description'],
                    'code' => substr(md5(mt_rand()), 0, 10),
                    'to_name' => $voucher['to_name'],
                    'to_email' => $voucher['to_email'],
                    'from_name' => $voucher['from_name'],
                    'from_email' => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message' => $voucher['message'],
                    'amount' => $voucher['amount']
                );
            }
        }

        $order_data['products'] = $product_data;
        $order_data['vouchers'] = $voucher_data;
        $order_data['totals'] = $total_data;
        $order_data['comment'] = '';
        $order_data['total'] = $total;

        foreach($order_data['totals'] as $item){
            if($item['code'] == 'sub_total'){
                $order_data['base_subtotal'] = $item['value'];
            }
            if($item['code'] == 'shipping'){
                $order_data['base_shipping_amount'] = $item['value'];
            }
            if($item['value'] < 0 ){
                $order_data['base_discount_amount'] += $item['value'];
            }
        }
        $order_data['base_discount_amount'] = isset($order_data['base_discount_amount'])?abs($order_data['base_discount_amount']):0;
        $order_data['base_grand_total'] = $total ;
        
        $order_data['discount_amount'] = $this->currency->format($order_data['base_discount_amount'], $this->currency->getCode(), false, false);;
        $order_data['shipping_amount'] = $this->currency->format($order_data['base_shipping_amount'], $this->currency->getCode(), false, false);;
        $order_data['subtotal'] = $this->currency->format($order_data['base_subtotal'], $this->currency->getCode(), false, false);;
        $order_data['grand_total'] = $this->currency->format($order_data['base_grand_total'], $this->currency->getCode(), false, false);

        
       
        $this->load->model('checkout/order');
        $this->session->data['order_id']  = null;
        $this->session->data['order_number']  = null;
        
        $all_data_order = array_merge($data,$order_data);
        $this->session->data['order_id'] = $this->model_checkout_order->addOrder($all_data_order);
         
        ///////////////////////////分包代码////////////////////////////////////////////
        //分包代码，生产
        unset($this->session->data['package_order_id']);
        $shipping_packages = $this->session->data['shipping_packages'];
        if(count($shipping_packages)>1){
            foreach($shipping_packages as $_pk => $_package ){
                $_package_data = array();

                if (isset($this->session->data['delivery_method'][$_pk])) {
                    $_package_data['shipping_method'] = $this->session->data['delivery_method'][$_pk]['delivery_type'];
                    $_package_data['shipping_code'] = $this->session->data['delivery_method'][$_pk]['delivery_method'];
                } else {
                    $_package_data['shipping_method'] = '';
                    $_package_data['shipping_code'] = '';
                }
                $_package_product_data = array();
                $_package_total = 0;
                foreach ($_package['package'] as $product) {
                    $this->load->model('tool/image');
                    $_package_product_data[] = array(
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'image' => $product['image'],
                        'image_email' => $this->model_tool_image->resize($product['image'], 60, 60),
                        'model' => $product['model'],
                        'option' => $option_data,
                        'download' => $product['download'],
                        'quantity' => $product['quantity'],
                        'subtract' => $product['subtract'],
                        'original_price' => $product['original_price'],
                        'price' => $product['price'],
                        'price_format' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                        'trie_price' => $product['trie_price'],
                        'special_price' => $product['special_price'],
                        'total' => $product['total'],
                        'total_format' => $this->currency->format($product['total']),
                        'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                        'reward' => $product['reward'],
                        'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL')
                    );
                    $_package_total += $product['quantity'] * $product['price'];
                }

                $_package_data['products'] = $_package_product_data;
                $_package_data['vouchers'] = array();
                
                
                $_package_data['comment'] = '';
                $_package_data['total'] = $this->session->data['package_total'][$_pk];
                

                $sort_order = array();
                $_total_data = $this->session->data['package_total_data'][$_pk];
                foreach ($_total_data as $_key => $_value) {
                    $sort_order[$_key] = $_value['sort_order'];
                }
                if(is_array($_sort_order) && $_sort_order && is_array($_total_data) && $_total_data){
                    array_multisort($_sort_order, SORT_ASC, $_total_data);
                }
                
                $_package_data['totals'] = $_total_data;

                
                $_package_data['base_subtotal'] = $_package_total;
                $_package_data['base_shipping_amount'] = $this->session->data['delivery_method'][$_pk]['price'];
                $_package_data['base_discount_amount'] = 0;
                
                
                $_package_data['base_discount_amount'] = isset($_package_data['base_discount_amount'])?abs($_package_data['base_discount_amount']):0;
                $_package_data['base_grand_total'] = $_package_data['total'] ;

                $_package_data['discount_amount'] = $this->currency->format($_package_data['base_discount_amount'], $this->currency->getCode(), false, false);;
                $_package_data['shipping_amount'] = $this->currency->format($_package_data['base_shipping_amount'], $this->currency->getCode(), false, false);;
                $_package_data['subtotal'] = $this->currency->format($_package_data['base_subtotal'], $this->currency->getCode(), false, false);;
                $_package_data['grand_total'] = $this->currency->format($_package_data['base_grand_total'], $this->currency->getCode(), false, false);
                
                $_package_data['is_parent'] = '0';
                $_package_data['parent_id'] = $this->session->data['order_id'];
                
                $_package_order_data = array_merge($data, $_package_data);
                $_pack_order_id = $this->model_checkout_order->addOrder($_package_order_data,1);
                $this->session->data['package_order_id'][] = $_pack_order_id;
            }
           
        }

        if ($this->session->data['order_id']) {
            unset($this->session->data['coupon']);
            unset($this->session->data['points']);
            //$this->cart->clear();
            
            //$order_number = $this->model_checkout_order->getColume($this->session->data['order_id'], 'order_number');
             
           
            //
            if($payment_code == 'globebill_qiwi'){
                $this->session->data['globebill_qiwi_qiwiUsername'] =  $_POST['qiwiUsername'];
            }
           if($payment_code == 'globebill_giropay'){
                $this->session->data['globebill_qiwi_payAccountnumber'] =  $_POST['payAccountnumber'];
                $this->session->data['globebill_qiwi_payBankcode'] =  $_POST['payBankcode'];
            }
            
            if($payment_code == 'pp_onestep'){
                if(isset($_POST['is_paypal_onestep'])){
                    $is_one_step = intval($_POST['is_paypal_onestep']);
                    $this->session->data['is_paypal_onestep'] = $is_one_step;
                } else {
                    $this->session->data['is_paypal_onestep'] = 0;
                }
                
            }
            
            $payment_url = $this->url->link('payment/' . $this->session->data['payment']['code'],'','SSL' );
            $this->redirect($payment_url);
           
           
            
        }else{
              $redirect = $this->url->link('checkout/checkout', '', 'SSL');
              $this->redirect($redirect);
              
        }

    }

}

?>