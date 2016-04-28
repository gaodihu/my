<?php 
class ControllerCheckoutShippingMethod extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');

		$this->load->model('account/address');
        $this->load->model('checkout/checkout');
        if(!$this->session->data['shipping_address']){
           //return '';
        }
        $shipping_address = $this->session->data['shipping_address'];
        
		if (!empty($shipping_address)) {
			// Shipping Methods
            $_all_package = $this->model_checkout_checkout->getShippingMethod($shipping_address);
            $shipping_methods = $_all_package['can_shipping'];
            $no_shipping = $_all_package['no_shipping'];
            if(count($no_shipping) > 0){
                $this->session->data['no_shipping'] = $no_shipping;
                $redirect = $this->url->link('checkout/cart', '', 'SSL');
                $this->redirect($redirect);
             }
		}
        $this->data['shipping_methods'] = $shipping_methods;
       
        if(isset($this->session->data['battery_can_split'])){
            $battery_can_split = $this->session->data['battery_can_split'];
            $this->data['battery_can_split'] = $battery_can_split;
        } else {
            $this->data['battery_can_split'] = 0;
        }
        
        if(isset($this->session->data['customer_split_package'])){
            $this->data['customer_split_package'] = $this->session->data['customer_split_package'];
        }else{
            $this->data['customer_split_package'] = 0;
        }
        
         //判断订单是否是偏远地区订单
        $this->load->model('shipping/myled');
        $is_remote =$this->model_shipping_myled->isRemoteArea($shipping_address);
        if($is_remote){
            $this->data['is_remote'] =1;
            $remote_free =$this->model_shipping_myled->getRemoteFree();
            $remote_free_format =$this->currency->format($remote_free);
            $this->data['text_remote_free'] =sprintf($this->language->get('text_remote_free'),$remote_free_format);
        }else{
            $this->data['is_remote'] =0;
        }
        //file_put_contents('test.log',  var_export($this->session->data['delivery_method'],true),FILE_APPEND);
        $this->session->data['shipping_methods'] = $shipping_methods;
        if(isset($this->session->data['delivery_method'])){
            foreach($shipping_methods as $_pk => $_methods){
                if(isset($this->session->data['delivery_method'][$_pk])){
                    $_method = $this->session->data['delivery_method'][$_pk]['delivery_method'];
                    if(isset($_methods['methods'][$_method])){
                        $this->session->data['delivery_method'][$_pk] = $_methods['methods'][$_method];
                    }else{
                        reset($_methods['methods']);
                        $this->session->data['delivery_method'][$_pk] = current($_methods['methods']);
                    }
                    
                    
                }else{
                    reset($_methods['methods']);
                    $this->session->data['delivery_method'][$_pk] = current($_methods['methods']);
                }
            }
            //去掉多余的包裹寄送信息，主要是分包和合并包裹会出现
            foreach($this->session->data['delivery_method'] as $_pk => $_ss){
                if(!isset($shipping_methods[$_pk])){
                   unset($this->session->data['delivery_method'][$_pk]);
                }
            }
        }else{
            foreach($shipping_methods as $_pk => $_package){
                reset($shipping_methods[$_pk]['methods']);
                $this->session->data['delivery_method'][$_pk] = current($shipping_methods[$_pk]['methods']);
            }
        }
        $this->data['default_shipping_methods'] = $this->session->data['delivery_method'];
        //print_r($this->session->data['delivery_method']);
        //file_put_contents('test.log',  var_export($this->session->data['delivery_method'],true),FILE_APPEND);
        //Column
        $this->data['text_checkout_product_list'] = $this->language->get('text_checkout_product_list');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_total'] = $this->language->get('column_total');
        
        $this->data['currency_code'] = $this->currency->getCode();


        $this->data['text_battery_split_order_tips'] = $this->language->get('text_battery_split_order_tips');
        $this->data['text_battery_merge_order_tips'] = $this->language->get('text_battery_merge_order_tips');
        $this->data['text_split_order_help'] = $this->language->get('text_split_order_help');
        $this->data['text_checkout_shipping_method'] = $this->language->get("text_checkout_shipping_method");
        $this->data['can_not_ship_to'] = $this->language->get('can_not_ship_to');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/include/shipping_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/include/shipping_method.tpl';
		} else {
			$this->template = 'default/template/checkout/include/shipping_method.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();		

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.		
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (empty($shipping_address)) {								
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
			if (!isset($this->request->post['shipping_method'])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
			}

			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);

				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}							
		}

		$this->response->setOutput(json_encode($json));	
	}
}
?>