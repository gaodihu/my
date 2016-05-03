<?php
class ControllerPaymentPPStandard extends Controller {
	public function index() {
		$this->language->load('payment/pp_standard');

		$this->data['text_testmode'] = $this->language->get('text_testmode');		

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['testmode'] = $this->config->get('pp_standard_test');

		if (!$this->config->get('pp_standard_test')) {
			$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$this->data['business'] = $this->config->get('pp_standard_email');
            //小额支付
            $pp_small_standard_enabled = $this->config->get('pp_small_standard_enabled');
            $pp_small_standard_limit = $this->config->get('pp_small_standard_limit');
            $pp_small_standard_limit = floatval($pp_small_standard_limit);
            $pp_small_standard_email = $this->config->get('pp_small_standard_email');
            if($pp_small_standard_enabled && $pp_small_standard_email  && $pp_small_standard_limit > 0 && $pp_small_standard_limit >= $order_info['total'] ){
                $this->data['business'] = $this->config->get('pp_small_standard_email');
            }
           
			$this->data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');				

			$this->data['products'] = array();
            $products_data = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
			foreach ($products_data as $product) {
				$option_data = array();
				$this->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
					'quantity' => $product['quantity'],
					'option'   => $option_data,
					//'weight'   => $product['weight']
				);
			}	

			$this->data['discount_amount_cart'] = 0;

            if($order_info['discount_amount']!=0){
                $this->data['discount_amount_cart'] = abs($order_info['discount_amount']);
               
            }
			$shipping  = $order_info['shipping_amount'];

			if ($shipping > 0) {
				$this->data['products'][] = array(
					'name'     => $order_info['shipping_method'],
					'model'    => 'Shipping',
					'price'    => $shipping,
					'quantity' => 1,
					'option'   => array(),
					//'weight'   => 0
				);	
			} 

			$this->data['currency_code'] = $order_info['currency_code'];
			$this->data['first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');	
			$this->data['last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');	
			$this->data['address1'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');	
			$this->data['address2'] = html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');	
			$this->data['city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');	
			$this->data['zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');	
			$this->data['state'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');	
			$this->data['country'] = $order_info['shipping_iso_code_2'];
			$this->data['email'] = $order_info['email'];
			$this->data['invoice'] =$order_info['order_number'];
			$this->data['lc'] = $this->session->data['language'];
			$this->data['return'] = $this->url->link('checkout/success','','SSL');
			$this->data['notify_url'] = $this->url->link('payment/pp_standard/callback', '', 'SSL');
			$this->data['cancel_return'] = $this->url->link('checkout/checkout', '', 'SSL');

			if (!$this->config->get('pp_standard_transaction')) {
				$this->data['paymentaction'] = 'authorization';
			} else {
				$this->data['paymentaction'] = 'sale';
			}

			$this->data['custom'] = $order_info['order_number'];
            
            $this->log->write('paypal standard request:' .  var_export($this->data,true));
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_standard.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/pp_standard.tpl';
			} else {
				$this->template = 'default/template/payment/pp_standard.tpl';
			}

			$this->response->setOutput($this->render());
		}
	}

	public function callback() {
		if (isset($this->request->post['custom'])) {
			$order_number = $this->request->post['custom'];
		} else {
			$order_number = 0;
		}		

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrderByNumber($order_number);
                   
		if ($order_info) {
            $order_id = $order_info['order_id']; 
			$request = 'cmd=_notify-validate';

			foreach ($this->request->post as $key => $value) {
                //$value = urlencode($value);
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
                //$request .= '&' . $key . '=' . $value ;
			}
            $this->log->write('paypal standard post:'.var_export($this->request->post,true));
            $c_i = 0;
            $request_200 = 0;
            while($c_i < 3) {
                if (!$this->config->get('pp_standard_test')) {
                    $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
                } else {
                    $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
                }

                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE); 
                curl_close($curl);
                if('200' == $httpCode){
                    $request_200 = 1;
                    break;
                }
                
                $c_i  ++ ;
            }

			if (!$response) {
				$this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}

			if ($this->config->get('pp_standard_debug')) {
				$this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
				$this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
			}
            $this->load->model('payment/pp_express');
             //add order to paypal table
            $result = $this->request->post;
            $paypal_order_data = array(
                'order_id' => $order_id,
                'capture_status' => ($this->config->get('pp_standard_transaction') == 1 ? 'Sale' : 'Authorization'),
                'currency_code' => $result['mc_currency'],
                'authorization_id' => $result['txn_id'],
                'total' => $result['mc_gross'],
            );
            $this->log->write('PP_STANDARD :: IPN RESPONSE: ' . var_export($paypal_order_data,true));
            $paypal_order_id = $this->model_payment_pp_express->addOrder($paypal_order_data);
            $this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $paypal_order_id);
           
            //add transaction to paypal transaction table
            $paypal_transaction_data = array(
                'paypal_order_id' => $paypal_order_id,
                'transaction_id' => $result['txn_id'],
                'parent_transaction_id' => '',
                'note' => '',
                'msgsubid' => '',
                'receipt_id' => (isset($result['receiver_id']) ? $result['receiver_id'] : ''),
                'payment_type' => $result['payment_type'],
                'payment_status' => $result['payment_status'],
                'pending_reason' => '',
                'transaction_entity' => 'Sale' ,
                'amount' => $result['mc_gross'],
                'debug_data' => json_encode($result),
            );
            $this->log->write('PP_STANDARD :: IPN RESPONSE: ' . var_export($paypal_transaction_data,true));
            $this->model_payment_pp_express->addTransaction($paypal_transaction_data);
            
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				//$order_status_id = $this->config->get('config_order_status_id');
                $order_status_id = false;
				switch($this->request->post['payment_status']) {
					case 'Canceled_Reversal':
						$order_status_id = $this->config->get('pp_standard_canceled_reversal_status_id');
						break;
					case 'Completed':
						//if ((strtolower($this->request->post['receiver_email']) == strtolower($this->config->get('pp_standard_email')) || strtolower($this->request->post['receiver_email']) == strtolower($this->config->get('pp_small_standard_email')) ) && ((float)$this->request->post['mc_gross'] == $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false))) {
							$order_status_id = $this->config->get('pp_standard_completed_status_id');
						//} else {
						//	$this->log->write('PP_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower($this->request->post['receiver_email']));
						//}
						break;
					case 'Denied':
						$order_status_id = $this->config->get('pp_standard_denied_status_id');
						break;
					case 'Expired':
						$order_status_id = $this->config->get('pp_standard_expired_status_id');
						break;
					case 'Failed':
						$order_status_id = $this->config->get('pp_standard_failed_status_id');
						break;
					case 'Pending':
						$order_status_id = $this->config->get('pp_standard_pending_status_id');
						break;
					case 'Processed':
						$order_status_id = $this->config->get('pp_standard_processed_status_id');
						break;
					
					//暂不处理
					case 'Refunded':
						$order_status_id = $this->config->get('pp_standard_refunded_status_id');
						return;
						break;
					case 'Reversed':
						$order_status_id = $this->config->get('pp_standard_reversed_status_id');
						return;
						break;
					
					case 'Voided':
						$order_status_id = $this->config->get('pp_standard_voided_status_id');
						break;								
				}
                if($order_status_id !== false ){
                    if (!$order_info['order_status_id']) {
                        $this->model_checkout_order->update($order_id, $order_status_id);
                    } else {
                        $this->model_checkout_order->update($order_id, $order_status_id);
                    }
                }
			} else {
                if($request_200 == 0){
                    $this->model_checkout_order->update($order_id, 17);
                }else{
                    //$this->model_checkout_order->update($order_id, $this->config->get('config_order_status_id'));
                }
			}

			
		}	
	}
}
?>