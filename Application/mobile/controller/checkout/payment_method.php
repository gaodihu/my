<?php  
class ControllerCheckoutPaymentMethod extends Controller {
	public function index() {
		$lang =$this->language->load('checkout/checkout');
        $this->data =array_merge($this->data,$lang);
		$this->load->model('account/address');
        $this->load->model('checkout/checkout');

        $shipping_address = $this->session->data['shipping_address'];

        
        $this->data['pp_express']  = false;
		if (!empty($shipping_address)) {
			$method_data = $this->model_checkout_checkout->getPaymentMethod($shipping_address); 
           
            //paypal 快速支付
            if(isset($this->session->data['paypal']['token']) && $this->session->data['paypal']['token'] && $this->session->data['paypal']['code']=="pp_express"){
                $paypal_express = $this->session->data['paypal'];
                //金额超出不能使用小额支付，改用标准支付
                if(isset($this->session->data['paypal_ec_setting'])){
                    $total = $this->model_checkout_checkout->getCartTotalMoney();
                    
                    $pp_express_small_enabled =  $this->config->get('pp_express_small_enabled');
                    $pp_express_small_limit =  $this->config->get('pp_express_small_limit');
                    $pp_express_username_small =  $this->config->get('pp_express_username_small');
                    $pp_express_password_small =  $this->config->get('pp_express_password_small');
                    $pp_express_signature_small =  $this->config->get('pp_express_signature_small');
                    $setting = $this->session->data['paypal_ec_setting'];
                    if($pp_express_small_enabled && $total > $pp_express_small_limit && $setting['USER']  == $pp_express_username_small ){
                        unset($this->session->data['paypal']);
                        unset($this->session->data['paypal_ec_setting']);
                        $this->data['pp_express'] = false;
                        $paypal_express =false;
                        unset($method_data['pp_express']);
                    }
                }
            }else{
                $paypal_express = false;
                unset($method_data['pp_express']);
            }
            if($paypal_express && array_key_exists('pp_express',$method_data) ){
                   $this->session->data['payment']= $method_data['pp_express'];
                   $this->data['payment_methods']= array('pp_express' => $method_data['pp_express']);
                   $this->data['pp_express'] = $paypal_express;
            }else{
                $this->data['payment_methods']= $method_data;
                $method_data_code =array();
                if(isset($this->session->data['payment']['code'])){
                    if(!array_key_exists($this->session->data['payment']['code'],$method_data)){
                       $this->session->data['payment']= current($method_data);
                    }
                }
                else{
                    reset($method_data);
                    $this->session->data['payment']= current($method_data);
                }
            }
		}else{
            $this->data['payment_methods']= '';
        }
        if ($this->customer->isLogged()) {
            $billing_address = $this->model_account_address->getBillingAddress();
            $this->data['have_billing_address'] = 0;

            if($billing_address){
                $this->data['address_info'] = $billing_address;
                $this->data['have_billing_address'] = 1;
            }else{
                $this->data['address_info'] = $shipping_address;
            }
        }else{
            $this->data['have_billing_address'] = 0;
            $this->data['address_info'] = $shipping_address;
        }
        $this->session->data['billing_address'] = $this->data['address_info'];
        
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        if ($this->data['address_info']['country_id']) {
            $this->load->model('localisation/zone');
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['address_info']['country_id']);
        }
        
       

        $this->data['logged'] = $this->customer->isLogged();
        
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['shipping_required'] = $this->cart->hasShipping();
     
        
        $this->data['text_paypal_onestep_install_tips'] = $this->language->get('text_paypal_onestep_install_tips');
        
        
        
        $this->data['is_binding_onestep'] = 0;
        if ($this->customer->isLogged() && $this->data['payment_methods']) {
            foreach($this->data['payment_methods'] as $payment_method){
                if($payment_method['code'] == 'pp_onestep'){
                    $user_id = $this->customer->getId();
                    $user_id = intval($user_id);
                    $total = $this->model_checkout_checkout->getCartTotalMoney();
                    
                    // print_r($merchant);
                    $this->load->model('payment/pp_onestep');
                    $is_binding_onestep = $this->model_payment_pp_onestep->getOneStep($user_id,$merchant['USER']);
                    $this->data['is_binding_onestep']  = $is_binding_onestep;
                    break;
                }
            }
        }
        
        
    
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/include/payment_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/include/payment_method.tpl';
		} else {
			$this->template = 'default/template/checkout/include/payment_method.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}	

		if (empty($payment_address)) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}		

		// Validate cart has products and has stock.			
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	

		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}				
		}

		if (!$json) {
			if (!isset($this->request->post['payment_method'])) {
				$json['error']['warning'] = $this->language->get('error_payment');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error']['warning'] = $this->language->get('error_payment');
			}	

			if ($this->config->get('config_checkout_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

				if ($information_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}							
		}

		$this->response->setOutput(json_encode($json));
	}
 
}
?>