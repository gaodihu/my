<?php

class ControllerPaymentGlobebillCredit extends Controller {

    const PAYMENT_CODE = 'Credit Card';

    public function index() {
        $this->language->load('checkout/checkout');
        $this->language->load('payment/globebill_credit');
        
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['merchant_no'] = $this->config->get('globebill_credit_merchant_no');
        $this->data['payment_gateway'] = $this->config->get('globebill_credit_payment_gateway');
        $this->data['signkey_code'] = $this->config->get('globebill_credit_signkey_code');
        $this->data['transport_url'] = $this->config->get('globebill_credit_transport_url');
        $this->data['payment_code'] = self::PAYMENT_CODE;

        $return_url = $this->url->link('payment/globebill_credit/callback', '', 'SSL');
        $this->data['return_url'] = $return_url;

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            //已经支付过的不能再支付
            if($order_info['order_status_id'] != 1){
                 $to  = $this->url->link('checkout/fail','','SSL');
                $this->redirect($to);
            }
            $this->data['total'] = round($order_info['total'], 2);
            $sign = $this->data['merchant_no'] . $this->data['payment_gateway'] . $order_info['order_number'] . $order_info['currency_code'] . $order_info['grand_total'] . $return_url . $this->config->get('globebill_credit_signkey_code');
            $sign_info = hash("sha256", $sign);
            $this->data['signkey_code'] = $sign_info;


            $this->data['order'] = $order_info;
            
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
            $grand_total = $this->currency->onlyformat($order_info['grand_total'],$order_info['currency_code']);
            $this->data['grand_total'] = $grand_total;
            
            //shipping address
            $this->data['text_select'] = $this->language->get('text_select');
            $this->data['text_none'] = $this->language->get('text_none');
            $this->data['entry_firstname'] = $this->language->get('entry_firstname');
            $this->data['entry_lastname'] = $this->language->get('entry_lastname');
            $this->data['entry_address_1'] = $this->language->get('entry_address_1');
            $this->data['entry_address_2'] = $this->language->get('entry_address_2');
            $this->data['entry_city'] = $this->language->get('entry_city');
            $this->data['entry_country'] = $this->language->get('entry_country');
            $this->data['entry_zone'] = $this->language->get('entry_zone');
            $this->data['entry_postcode'] = $this->language->get('entry_postcode');
            $this->data['entry_phone'] = $this->language->get('entry_phone');
            $this->data['entry_set_default'] = $this->language->get('entry_set_default');
            $this->data['text_place_your_order'] = $this->language->get('text_place_your_order');

            $this->data['logged'] = $this->customer->isLogged();

            $this->data['entry_email'] = $this->language->get('entry_email');
            $this->data['shipping_required'] = $this->cart->hasShipping();

            //js 提示语言项
            $this->data['error_select_address'] = $this->language->get('error_select_address');
            $this->data['error_select_shipping'] = $this->language->get('error_select_shipping');
            $this->data['error_select_payment'] = $this->language->get('error_select_payment');
            
            $this->load->model('localisation/country');
            $this->data['countries'] = $this->model_localisation_country->getCountries();
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/globebill_credit.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/globebill_credit.tpl';
            } else {
                $this->template = 'default/template/payment/globebill_credit.tpl';
            }
           
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
            $this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
            $this->data['text_checkout_product_list'] = $this->language->get('text_checkout_product_list');
            $this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
            $this->data['text_order_detalls'] = $this->language->get('text_order_detalls');
            $this->data['text_checkout_confirm_order'] = $this->language->get('text_checkout_confirm_order');
            $this->data['text_checkout_pay_order'] = $this->language->get('text_checkout_pay_order');
            $this->data['text_checkout_success'] = $this->language->get('text_checkout_success');
            $this->data['text_hi'] = $this->language->get('text_hi');
            $this->data['text_add_address'] = $this->language->get('text_add_address');
            
            $this->document->addStyle('css/stylesheet/account.css');
            $this->document->addScript('js/Address.js');
            $this->children = array(
            'common/footer',
            'common/head'
            );
             
            $this->response->setOutput($this->render());
        }
    }

    public function callback() {
        $data = $this->request->post;
        $merNo = $data['merNo'];
        $gatewayNo = $data['gatewayNo'];
        $tradeNo = $data['tradeNo'];
        $orderNo = $data['orderNo'];
        $orderCurrency = $data['orderCurrency'];
        $orderAmount = $data['orderAmount'];
        $cardNo = $data['cardNo'];
        $orderStatus = $data['orderStatus'];
        $orderInfo = $data['orderInfo'];
        $authTypeStatus = $data['authTypeStatus'];
        $signInfo = $data['signInfo'];
        $riskInfo = $data['riskInfo'];
        $remark = $data['remark'];
        if(isset($data['EbankBarCode'])){
            $EbankBarCode = $data['EbankBarCode'];
        }
        

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrderByNumber($orderNo);
        if ($order_info) {
            $order_id = $order_info['order_id'];
            $merchant_no = $this->config->get('globebill_credit_merchant_no');
            $payment_gateway = $this->config->get('globebill_credit_payment_gateway');
            $signkey_code = $this->config->get('globebill_credit_signkey_code');
            $total = round($order_info['total'], 2);

            $return_url = $this->url->link('payment/globebill_credit/callback', '', 'SSL');
            $sign = $merNo . $gatewayNo . $tradeNo . $orderNo.$order_info['currency_code'] . $orderAmount . $orderStatus . $orderInfo . $this->config->get('globebill_credit_signkey_code');
            $sign_info = hash("sha256", $sign);
            $order_status_id = $this->config->get('globebill_safety_pending_status_id');
            if (strtoupper($sign_info) == strtoupper($signInfo)) {
                switch ($orderStatus) {
                    case 1:
                        $order_status_id = $this->config->get('globebill_credit_processed_status_id');
                        break;
                    case 0:
                        $order_status_id = $this->config->get('globebill_credit_failed_status_id');
                        break;
                    case -1:
                        $order_status_id = $this->config->get('globebill_credit_pending_status_id');
                        break;
                    case -2:
                        $order_status_id = $this->config->get('globebill_credit_payment_review_status_id');
                        break;
                    default:
                        $order_status_id = $this->config->get('globebill_credit_pending_status_id');
                }
                $is_duplicate = 0;
                if(($order_info['order_status_id'] == 2 || $order_info['order_status_id'] == 5) && stripos($orderInfo,'Duplicate Order') !==false){
                    $is_duplicate = 1;
                }else{
                    $this->model_checkout_order->update($order_id, $order_status_id);
                    if($order_info['parent_id '] == 0 && $order_info['is_parent'] == 1){
                        $children = $this->model_checkout_order->getOrderChildren($order_id);
                        foreach($children as $_item){
                            $this->model_checkout_order->update($_item['order_id'], $order_status_id);
                        }
                    }
                }
            }
            $is_push = 0;
            if (!isset($data['isPush'])) {
                 $is_push = 0;
            }else{
                $is_push = 1;
            }

            $this->model_checkout_order->savePaymentInfo($order_id, $order_info['payment_method'], $orderAmount, $authTypeStatus, $tradeNo,$orderInfo, $orderCurrency, '', $cardNo, $data,$is_push);
            
            if (!isset($data['isPush'])) {
                if(!$is_duplicate && $order_status_id == $this->config->get('globebill_credit_failed_status_id')){
                    $to  = $this->url->link('checkout/fail','','SSL');
                }else{
                     $to  = $this->url->link('checkout/success','','SSL');
                }
                
                //$this->redirect($to);
                $script = '<script type="text/javascript">window.parent.location="'.$to.'";</script>';
                echo $script;
            }
        }else{
            $to  = $this->url->link('/','','SSL');
            //$this->redirect($to);
             $script = '<script type="text/javascript"> window.parent.location="/";</script>';
                echo $script;
        }
    }

    public function  pay(){
        $order_id = $this->session->data['order_id'];
        $billing_address = array();
        
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        if(isset($this->request->post['use_shipping_address']) ){
            $use_shipping_address = intval($this->request->post['use_shipping_address']);
            if($use_shipping_address == 1){
                $this->load->model('checkout/order');
                $billing_address['firstname'] =  $order_info['shipping_firstname'];
                $billing_address['lastname'] =  $order_info['shipping_lastname'];
                $billing_address['address_1'] =  $order_info['shipping_address_1'];
                $billing_address['address_2'] =  $order_info['shipping_address_2'];
                $billing_address['city'] =  $order_info['shipping_city'];
                $billing_address['postcode'] =  $order_info['shipping_postcode'];
                $billing_address['country'] =  $order_info['shipping_country'];
                $billing_address['country_id'] =  $order_info['shipping_country_id'];
                $billing_address['country_code'] =  $order_info['shipping_country_code'];
                $billing_address['zone'] =  $order_info['shipping_zone'];
                $billing_address['zone_id'] =  $order_info['shipping_zone_id'];
                $billing_address['zone_code'] =  $order_info['shipping_zone_code'];
                $billing_address['phone'] =  $order_info['shipping_phone'];
 
            }else{
                $billing_address['firstname'] =  $this->request->post['firstname'];
                $billing_address['lastname'] =  $this->request->post['lastname'];
                $billing_address['address_1'] =  $this->request->post['address_1'];
                $billing_address['address_2'] =  $this->request->post['address_2'];
                $billing_address['city'] =  $this->request->post['city'];
                $billing_address['postcode'] =  $this->request->post['postcode'];
                $billing_address['country'] =  $this->request->post['country'];
                $billing_address['country_id'] =  $this->request->post['country_id'];
                $billing_address['country_code'] =  $this->request->post['country_code'];
                $billing_address['zone'] =  $this->request->post['zone'];
                $billing_address['zone_id'] =  $this->request->post['zone_id'];
                $billing_address['zone_code'] =  $this->request->post['zone_code'];
                $billing_address['phone'] =  $this->request->post['phone'];
            }
        }
          
    }
}

?>