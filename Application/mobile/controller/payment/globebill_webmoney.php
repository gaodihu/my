<?php

class ControllerPaymentGlobebillWebmoney extends Controller {

    const PAYMENT_CODE = 'webmoney';

    public function index() {
        $this->language->load('payment/globebill_webmoney');

        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['merchant_no'] = $this->config->get('globebill_webmoney_merchant_no');
        $this->data['payment_gateway'] = $this->config->get('globebill_webmoney_payment_gateway');
        $this->data['signkey_code'] = $this->config->get('globebill_webmoney_signkey_code');
        $this->data['transport_url'] = $this->config->get('globebill_webmoney_transport_url');
        $this->data['payment_code'] = self::PAYMENT_CODE;

        $return_url = $this->url->link('payment/globebill_webmoney/callback', '', 'SSL');
        $this->data['return_url'] = $return_url;

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            
            if(strtoupper($order_info['currency_code']) == 'USD'){
                $this->data['merchant_no'] = $this->config->get('globebill_webmoney_merchant_no_usd');
                $this->data['payment_gateway'] = $this->config->get('globebill_webmoney_payment_gateway_usd');
                $this->data['signkey_code'] = $this->config->get('globebill_webmoney_signkey_code_usd');
            }
            //已经支付过的不能再支付
            if($order_info['order_status_id'] != 1){
                 $to  = $this->url->link('checkout/fail','','SSL');
                $this->redirect($to);
            }
            $this->data['total'] = round($order_info['total'], 2);
            $sign = $this->data['merchant_no'] . $this->data['payment_gateway'] . $order_info['order_number'] . $order_info['currency_code'] . $order_info['grand_total'] . $return_url . $this->data['signkey_code'];
            $sign_info = hash("sha256", $sign);
            $this->data['signkey_code'] = $sign_info;


            $this->data['order'] = $order_info;
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/globebill_webmoney.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/globebill_webmoney.tpl';
            } else {
                $this->template = 'default/template/payment/globebill_webmoney.tpl';
            }

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
            $merchant_no = $this->config->get('globebill_webmoney_merchant_no');
            $payment_gateway = $this->config->get('globebill_webmoney_payment_gateway');
            $signkey_code = $this->config->get('globebill_webmoney_signkey_code');
            
            if(strtoupper($order_info['currency_code']) == 'USD'){
                $merchant_no = $this->config->get('globebill_webmoney_merchant_no_usd');
                $payment_gateway = $this->config->get('globebill_webmoney_payment_gateway_usd');
                $signkey_code = $this->config->get('globebill_webmoney_signkey_code_usd');
            }
            
            $total = round($order_info['total'], 2);

            $return_url = $this->url->link('payment/globebill_webmoney/callback', '', 'SSL');
            $sign = $merNo . $gatewayNo . $tradeNo . $orderNo.$order_info['currency_code'] . $orderAmount . $orderStatus . $orderInfo . $signkey_code;
            $sign_info = hash("sha256", $sign);
            $order_status_id = $this->config->get('globebill_safety_pending_status_id');
            if (strtoupper($sign_info) == strtoupper($signInfo)) {
                switch ($orderStatus) {
                    case 1:
                        $order_status_id = $this->config->get('globebill_webmoney_processed_status_id');
                        break;
                    case 0:
                        $order_status_id = $this->config->get('globebill_webmoney_failed_status_id');
                        break;
                    case -1:
                        $order_status_id = $this->config->get('globebill_webmoney_pending_status_id');
                        break;
                    case -2:
                        $order_status_id = $this->config->get('globebill_webmoney_payment_review_status_id');
                        break;
                    default:
                        $order_status_id = $this->config->get('globebill_webmoney_pending_status_id');
                }
                $this->model_checkout_order->update($order_id, $order_status_id);
            }
            $is_push = 0;
            if (!isset($data['isPush'])) {
                 $is_push = 0;
            }else{
                $is_push = 1;
            }
            $this->model_checkout_order->savePaymentInfo($order_id, $order_info['payment_method'], $orderAmount, $authTypeStatus, $tradeNo,$orderInfo, $orderCurrency, '', $cardNo, $data,$is_push);

            if (!isset($data['isPush'])) {
                if($order_status_id == $this->config->get('globebill_webmoney_failed_status_id')){
                    $to  = $this->url->link('checkout/fail','','SSL');
                }else{
                     $to  = $this->url->link('checkout/success','','SSL');
                }
                
                $this->redirect($to);
            }
        }else{
            $to  = $this->url->link('/','','SSL');
            $this->redirect($to);
        }
    }

}

?>