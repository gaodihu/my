<?php

class ControllerPaymentPPExpress extends Controller {

    public function start() {
        unset($this->session->data['paypal_ec_setting']);
        if (!$this->cart->hasProducts() || !$this->cart->hasStock()) {
            $this->log->write('No product redirect');
            $this->redirect($this->url->link('checkout/cart','','SSL'));
        }

        if ($this->customer->isLogged()) {
            /**
             * If the customer is already logged in
             */
            $this->session->data['paypal']['guest'] = false;
            unset($this->session->data['guest']);
        } else {
            $this->session->data['paypal']['guest'] = true;
        }

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);

        $this->load->model('payment/pp_express');
        $this->load->model('tool/image');

        if ($this->cart->hasShipping()) {
            $shipping = 2;
        } else {
            $shipping = 1;
        }
        
        $language = $this->session->data['language'];
        if(empty($language) ||  $language == 'EN'){
            $language = 'US';
        }

        $data = array(
            'METHOD' => 'SetExpressCheckout',
            'RETURNURL' => $this->url->link('payment/pp_express/getshipping', '', 'SSL'),
            'CANCELURL' => $this->url->link('payment/pp_express/cancel', '', 'SSL'),
            'REQCONFIRMSHIPPING' => 0,
            'NOSHIPPING' => $shipping,
            'ALLOWNOTE' => $this->config->get('pp_express_allow_note'),
            'LOCALECODE' => $language,
            'LANDINGPAGE' => 'Login',
            'HDRIMG' => $this->model_tool_image->resize($this->config->get('pp_express_logo'), 790, 90),
            'HDRBORDERCOLOR' => $this->config->get('pp_express_border_colour'),
            'HDRBACKCOLOR' => $this->config->get('pp_express_header_colour'),
            'PAYFLOWCOLOR' => $this->config->get('pp_express_page_colour'),
            'CHANNELTYPE' => 'Merchant',
        );

        $data = array_merge($data, $this->model_payment_pp_express->paymentRequestInfo());

        $result = $this->model_payment_pp_express->call($data);



        /**
         * If a failed PayPal setup happens, handle it.
         */
        if (!isset($result['TOKEN'])) {
            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
            /**
             * Unable to add error message to user as the session errors/success are not
             * used on the cart or checkout pages - need to be added?
             * If PayPal debug log is off then still log error to normal error log.
             */
            if ($this->config->get('pp_express_debug')) {
                $this->log->write(serialize($result));
            }

            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->session->data['paypal']['token'] = $result['TOKEN'];
        $this->session->data['paypal']['code'] =  "pp_express";

        if ($this->config->get('pp_express_test') == 1) {
            header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
        } else {
            header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
        }
    }

    public function getshipping() {
        /**
         * This is the url when PayPal has completed the auth.
         *
         * It has no output, instead it sets the data and locates to checkout
         */
        $this->load->model('payment/pp_express');
        $data = array(
            'METHOD' => 'GetExpressCheckoutDetails',
            'TOKEN' => $this->session->data['paypal']['token'],
        );

        $result = $this->model_payment_pp_express->call($data);

        $this->session->data['paypal']['payerid'] = $result['PAYERID'];
        $this->session->data['paypal']['result'] = $result;

        $this->session->data['comment'] = '';
        if (isset($result['PAYMENTREQUEST_0_NOTETEXT'])) {
            $this->session->data['comment'] = $result['PAYMENTREQUEST_0_NOTETEXT'];
        }

        $shipping_address = array();
        $shipping_address['address_id'] = '';
        $shipping_address['customer_id'] = '';
        
        
        if(isset($result['SHIPTONAME']) && trim($result['SHIPTONAME'])){
            $ship_to_name = trim($result['SHIPTONAME']);
            if( strpos($ship_to_name,' ') !== -1){
                $ship_to_name_arr = explode(' ', $ship_to_name,2);
                $shipping_address['firstname'] = $ship_to_name_arr[0];
                $shipping_address['lastname']  = $ship_to_name_arr[1];
            }else{
                $shipping_address['firstname'] = trim($result['SHIPTONAME']);
                $shipping_address['lastname']  = '';
            }
        }else{
            if (isset($result['FIRSTNAME'])) {
                $shipping_address['firstname'] = trim($result['FIRSTNAME']);
            } else {
                $shipping_address['firstname'] = '';
            }
            if (isset($result['LASTNAME'])) {
                $shipping_address['lastname'] = trim($result['LASTNAME']);
            } else {
                $shipping_address['lastname'] = '';
            }
        }
        
        if (isset($result['BUSINESS'])) {
            $shipping_address['company'] = trim($result['BUSINESS']);
        } else {
            $shipping_address['company'] = '';
        }
        $shipping_address['company_id'] = '';

        if (isset($result['PHONENUM'])) {
            $shipping_address['phone'] = trim($result['PHONENUM']);
        } else {
            $shipping_address['phone'] = '';
        }
        if (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET'])) {
            $shipping_address['address_1'] = trim($result['PAYMENTREQUEST_0_SHIPTOSTREET']);
        } else {
            $shipping_address['address_1'] = '';
        }
        if (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2'])) {
            $shipping_address['address_2'] = trim($result['PAYMENTREQUEST_0_SHIPTOSTREET2']);
        } else {
            $shipping_address['address_2'] = '';
        }

        if (isset($result['PAYMENTREQUEST_0_SHIPTOCITY'])) {
            $shipping_address['city'] = trim($result['PAYMENTREQUEST_0_SHIPTOCITY']);
        } else {
            $shipping_address['city'] = '';
        }

        if (isset($result['PAYMENTREQUEST_0_SHIPTOZIP'])) {
            $shipping_address['postcode'] = trim($result['PAYMENTREQUEST_0_SHIPTOZIP']);
        } else {
            $shipping_address['postcode'] = '';
        }
        if (isset($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'])) {
            $shipping_address['iso_code_2'] = trim($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']);
        } else {
            $shipping_address['iso_code_2'] = '';
        }
        //if (isset($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'])) {
        //    $shipping_address['zone_code'] = trim($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']);
        //} else {
            $shipping_address['zone_code'] = '';
       // }
        
        if (isset($result['SHIPTOSTATE'])) {
            $shipping_address['zone_name'] = trim($result['SHIPTOSTATE']);
        } else {
            $shipping_address['zone_name'] = '';
        }

        $shipping_address['country_id'] = '';
        $shipping_address['zone_id'] = '';

        $paypal_email = trim($result['EMAIL']);
        $this->session->data['guest_email'] = $paypal_email;

        $country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "' AND `status` = '1' LIMIT 1")->row;
        if ($country_info) {
            $shipping_address['country_id'] = $country_info['country_id'];
            $shipping_address['country'] = $country_info['name'];
        }
        //地区匹配
        $zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE  `status` = '1' AND `country_id` = '" . (int) $country_info['country_id'] . "'");
        foreach($zone_info->rows as $zone_info_row){
            if(strtoupper($result['SHIPTOSTATE']) == strtoupper($zone_info_row['code'])){
                $shipping_address['zone_id'] = $zone_info_row['zone_id'];
                $shipping_address['zone'] = $zone_info_row['name'];
                $shipping_address['zone_code']  = $zone_info_row['code'];
                $shipping_address['zone_name'] = $zone_info_row['name'];
            }
            if(strtoupper($result['SHIPTOSTATE']) == strtoupper($zone_info_row['name'])){
                $shipping_address['zone_id'] = $zone_info_row['zone_id'];
                $shipping_address['zone'] = $zone_info_row['name'];
                $shipping_address['zone_code']  = $zone_info_row['code'];
                $shipping_address['zone_name'] = $zone_info_row['name'];
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
            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($current_address_id);
        } else {
            $this->session->data['shipping_address'] = $shipping_address;
        }
        
        //金额超出不能使用小额支付，改用标准支付
        if(isset($this->session->data['paypal_ec_setting'])){
            $this->load->model('checkout/checkout');
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
                $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
            }
        }

        $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
    }

    public function cancel(){
        unset($this->session->data['paypal']);
        $this->redirect($this->url->link('checkout/cart', '', 'SSL'));
        
    }    
    
    public function index() {
        $this->language->load('payment/pp_express');
        $redirect = '';
        
        if(!isset($this->session->data['paypal']) ||  !isset($this->session->data['paypal']['token'])){
            $this->redirect($this->url->link('checkout/cart', '', 'SSL'));
        }
        
        $this->load->model('payment/pp_express');
        $this->load->model('checkout/order');
        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_id);
        if (!$order_info) {
            $this->redirect($this->url->link('checkout/fail', '', 'SSL'));
        }
        //如果不是最后小额变大额，使用标准支付进行支付
        if(isset($this->session->data['paypal_ec_setting'])){
            $pp_express_small_enabled =  $this->config->get('pp_express_small_enabled');
            $pp_express_small_limit =  $this->config->get('pp_express_small_limit');
            $pp_express_username_small =  $this->config->get('pp_express_username_small');
            $pp_express_password_small =  $this->config->get('pp_express_password_small');
            $pp_express_signature_small =  $this->config->get('pp_express_signature_small');
            $setting = $this->session->data['paypal_ec_setting'];
            if($order_info  && $order_info['total'] > $pp_express_small_limit && $setting['USER']  == $pp_express_username_small ){
                unset($this->session->data['paypal']);
                unset($this->session->data['paypal_ec_setting']);
                $this->redirect($this->url->link('payment/pp_standard', '', 'SSL'));
            }
        }
        // Validate minimum quantity requirments.
        $products = $this->model_checkout_order->getOrderProducts($order_id);
        $order_total = $this->model_checkout_order->getOrderTotal($order_id);

        $this->load->model('payment/pp_express');

        $paypal_data = array(
            'TOKEN' => $this->session->data['paypal']['token'],
            'PAYERID' => $this->session->data['paypal']['payerid'],
            'METHOD' => 'DoExpressCheckoutPayment',
            'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('payment/pp_express/ipn', '', 'SSL'),
            'RETURNFMFDETAILS' => 1,
        );

        $paypal_data = array_merge($paypal_data, $this->paymentRequestInfo($order_info,$products,$order_total));

        $result = $this->model_payment_pp_express->call($paypal_data);
        
        unset($this->session->data['paypal']);
        unset($this->session->data['shipping_address']);
        unset($this->session->data['payment_method']);
        if (strtolower($result['ACK']) == 'success') {
            //handle order status
            switch ($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
                case 'Canceled_Reversal':
                    $order_status_id = $this->config->get('pp_express_canceled_reversal_status_id');
                    break;
                case 'Completed':
                    $order_status_id = $this->config->get('pp_express_completed_status_id');
                    break;
                case 'Denied':
                    $order_status_id = $this->config->get('pp_express_denied_status_id');
                    break;
                case 'Expired':
                    $order_status_id = $this->config->get('pp_express_expired_status_id');
                    break;
                case 'Failed':
                    $order_status_id = $this->config->get('pp_express_failed_status_id');
                    break;
                case 'Pending':
                    $order_status_id = $this->config->get('pp_express_pending_status_id');
                    break;
                case 'Processed':
                    $order_status_id = $this->config->get('pp_express_processed_status_id');
                    break;
                case 'Refunded':
                    $order_status_id = $this->config->get('pp_express_refunded_status_id');
                    break;
                case 'Reversed':
                    $order_status_id = $this->config->get('pp_express_reversed_status_id');
                    break;
                case 'Voided':
                    $order_status_id = $this->config->get('pp_express_voided_status_id');
                    break;
            }

            $this->model_checkout_order->update($order_id, $order_status_id);
            if($order_info['parent_id '] == 0 && $order_info['is_parent'] == 1){
                $children = $this->model_checkout_order->getOrderChildren($order_id);
                foreach($children as $_item){
                    $this->model_checkout_order->update($_item['order_id'], $order_status_id);
                }
            }
            //add order to paypal table
            $paypal_order_data = array(
                'order_id' => $order_id,
                'capture_status' => ($this->config->get('pp_express_method') == 'Sale' ? 'Complete' : 'NotComplete'),
                'currency_code' => $result['PAYMENTINFO_0_CURRENCYCODE'],
                'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                'total' => $result['PAYMENTINFO_0_AMT'],
            );
            $paypal_order_id = $this->model_payment_pp_express->addOrder($paypal_order_data);

            //add transaction to paypal transaction table
            $paypal_transaction_data = array(
                'paypal_order_id' => $paypal_order_id,
                'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                'parent_transaction_id' => '',
                'note' => '',
                'msgsubid' => '',
                'receipt_id' => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
                'payment_type' => $result['PAYMENTINFO_0_PAYMENTTYPE'],
                'payment_status' => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
                'pending_reason' => $result['PAYMENTINFO_0_PENDINGREASON'],
                'transaction_entity' => ($this->config->get('pp_express_method') == 'Sale' ? 'payment' : 'auth'),
                'amount' => $result['PAYMENTINFO_0_AMT'],
                'debug_data' => json_encode($result),
            );
            $this->model_payment_pp_express->addTransaction($paypal_transaction_data);

            $this->redirect($this->url->link('checkout/success','','SSL'));

        } else {
            if ($result['L_ERRORCODE0'] == '10486') {
                if (isset($this->session->data['paypal_redirect_count'])) {

                    if ($this->session->data['paypal_redirect_count'] == 2) {
                        $this->session->data['paypal_redirect_count'] = 0;
                        $this->session->data['error'] = $this->language->get('error_too_many_failures');
                        $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
                    } else {
                        $this->session->data['paypal_redirect_count'] ++;
                    }
                } else {
                    $this->session->data['paypal_redirect_count'] = 1;
                }

                if ($this->config->get('pp_express_test') == 1) {
                    $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                } else {
                    $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                }
            }

            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
            $this->redirect($this->url->link('checkout/fail', '', 'SSL'));
        }
    }

    public function ipn() {
        $this->load->model('payment/pp_express');
        $this->load->model('account/recurring');

        $request = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $request .= '&' . $key . '=' . urlencode(stripslashes($value));
        }

        if ($this->config->get('pp_express_test') == 1) {
            $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
        } else {
            $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
        }

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = trim(curl_exec($curl));

        if (!$response) {
            $this->model_payment_pp_express->log(array('error' => curl_error($curl), 'error_no' => curl_errno($curl)), 'Curl failed');
        }

        $this->model_payment_pp_express->log(array('request' => $request, 'response' => $response), 'IPN data');
        $response = strtoupper($request);
        if ($response == "VERIFIED") {

            $this->log->write((isset($this->request->post['transaction_entity']) ? $this->request->post['transaction_entity'] : ''));

            if (isset($this->request->post['txn_id'])) {
                $transaction = $this->model_payment_pp_express->getTransactionRow($this->request->post['txn_id']);
            } else {
                $transaction = false;
            }

            if (isset($this->request->post['parent_txn_id'])) {
                $parent_transaction = $this->model_payment_pp_express->getTransactionRow($this->request->post['parent_txn_id']);
            } else {
                $parent_transaction = false;
            }

            if ($transaction) {
                //transaction exists, check for cleared payment or updates etc
                $this->log->write('Transaction exists');

                //if the transaction is pending but the new status is completed
                if ($transaction['payment_status'] != $this->request->post['payment_status']) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = '" . $this->request->post['payment_status'] . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");
                } elseif ($transaction['payment_status'] == 'Pending' && ($transaction['pending_reason'] != $this->request->post['pending_reason'])) {
                    //payment is still pending but the pending reason has changed, update it.
                    $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `pending_reason` = '" . $this->request->post['pending_reason'] . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");
                }
            } else {
                $this->log->write('Transaction does not exist');
                if ($parent_transaction) {
                    $this->log->write('Parent transaction exists');
                    //parent transaction exists
                    //insert new related transaction
                    $transaction = array(
                        'paypal_order_id' => $parent_transaction['paypal_order_id'],
                        'transaction_id' => $this->request->post['txn_id'],
                        'parent_transaction_id' => $this->request->post['parent_txn_id'],
                        'note' => '',
                        'msgsubid' => '',
                        'receipt_id' => (isset($this->request->post['receipt_id']) ? $this->request->post['receipt_id'] : ''),
                        'payment_type' => (isset($this->request->post['payment_type']) ? $this->request->post['payment_type'] : ''),
                        'payment_status' => (isset($this->request->post['payment_status']) ? $this->request->post['payment_status'] : ''),
                        'pending_reason' => (isset($this->request->post['pending_reason']) ? $this->request->post['pending_reason'] : ''),
                        'amount' => $this->request->post['mc_gross'],
                        'debug_data' => json_encode($this->request->post),
                        'transaction_entity' => (isset($this->request->post['transaction_entity']) ? $this->request->post['transaction_entity'] : ''),
                    );

                    $this->model_payment_pp_express->addTransaction($transaction);

                    /**
                     * If there has been a refund, log this against the parent transaction.
                     */
                    if (isset($this->request->post['payment_status']) && $this->request->post['payment_status'] == 'Refunded') {
                        if (($this->request->post['mc_gross'] * -1) == $parent_transaction['amount']) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
                        } else {
                            $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
                        }
                    }

                    /**
                     * If the capture payment is now complete
                     */
                    if (isset($this->request->post['auth_status']) && $this->request->post['auth_status'] == 'Completed' && $parent_transaction['payment_status'] == 'Pending') {
                        $captured = round($this->model_payment_pp_express->totalCaptured($parent_transaction['paypal_order_id']), 2);
                        $refunded = round($this->model_payment_pp_express->totalRefundedOrder($parent_transaction['paypal_order_id']), 2);
                        $remaining = round($parent_transaction['amount'] - $captured + $refunded, 2);

                        $this->log->write('Captured: ' . $captured);
                        $this->log->write('Refunded: ' . $refunded);
                        $this->log->write('Remaining: ' . $remaining);

                        if ($remaining > 0.00) {
                            $transaction = array(
                                'paypal_order_id' => $parent_transaction['paypal_order_id'],
                                'transaction_id' => '',
                                'parent_transaction_id' => $this->request->post['parent_txn_id'],
                                'note' => '',
                                'msgsubid' => '',
                                'receipt_id' => '',
                                'payment_type' => '',
                                'payment_status' => 'Void',
                                'pending_reason' => '',
                                'amount' => '',
                                'debug_data' => 'Voided after capture',
                                'transaction_entity' => 'auth'
                            );

                            $this->model_payment_pp_express->addTransaction($transaction);
                        }

                        $this->model_payment_pp_express->updateOrder('Complete', $parent_transaction['order_id']);
                    }
                } else {
                    //parent transaction doesn't exists, need to investigate?
                    $this->log->write('Parent transaction not found');
                }
            }

            /*
             * Subscription payments
             *
             * profile ID should always exist if its a recurring payment transaction.
             *
             * also the reference will match a recurring payment ID
             */
            if (isset($this->request->post['txn_type'])) {
                //payment
                if ($this->request->post['txn_type'] == 'recurring_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `amount` = '" . (float) $this->request->post['amount'] . "', `type` = '1'");

                        //as there was a payment the profile is active, ensure it is set to active (may be been suspended before)
                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //suspend
                if ($this->request->post['txn_type'] == 'recurring_payment_suspended') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '6'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 3 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //suspend due to max failed
                if ($this->request->post['txn_type'] == 'recurring_payment_suspended_due_to_max_failed_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '7'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 3 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //payment failed
                if ($this->request->post['txn_type'] == 'recurring_payment_failed') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '4'");
                    }
                }

                //outstanding payment failed
                if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment_failed') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '8'");
                    }
                }

                //outstanding payment
                if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `amount` = '" . (float) $this->request->post['amount'] . "', `type` = '2'");

                        //as there was a payment the profile is active, ensure it is set to active (may be been suspended before)
                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //created
                if ($this->request->post['txn_type'] == 'recurring_payment_profile_created') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '0'");

                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //cancelled
                if ($this->request->post['txn_type'] == 'recurring_payment_profile_cancel') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false && $profile['status'] != 3) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '5'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //skipped
                if ($this->request->post['txn_type'] == 'recurring_payment_skipped') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '3'");
                    }
                }

                //expired
                if ($this->request->post['txn_type'] == 'recurring_payment_expired') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '9'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 5 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }
            }
        } elseif ((string) $response == "INVALID") {
            $this->model_payment_pp_express->log(array('IPN was invalid'), 'IPN fail');
        } else {
            $this->log->write('string unknown ');
        }

        header("HTTP/1.1 200 Ok");
    }

    public function shipping() {
        $this->shippingValidate($this->request->post['shipping_method']);

        $this->redirect($this->url->link('payment/pp_express/expressConfirm','','SSL'));
    }

    protected function shippingValidate($code) {
        $this->language->load('checkout/cart');
        $this->language->load('payment/pp_express');

        if (empty($code)) {
            $this->session->data['error_warning'] = $this->language->get('error_shipping');
            return false;
        } else {
            $shipping = explode('.', $code);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->session->data['error_warning'] = $this->language->get('error_shipping');
                return false;
            } else {
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                $this->session->data['success'] = $this->language->get('text_shipping_updated');
                return true;
            }
        }
    }

    public function recurringCancel() {
        //cancel an active profile

        $this->load->model('account/recurring');
        $this->load->model('payment/pp_express');
        $this->language->load('account/recurring');

        $profile = $this->model_account_recurring->getProfile($this->request->get['recurring_id']);

        if ($profile && !empty($profile['profile_reference'])) {

            $result = $this->model_payment_pp_express->recurringCancel($profile['profile_reference']);

            if (isset($result['PROFILEID'])) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '5'");
                $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");

                $this->session->data['success'] = $this->language->get('success_cancelled');
            } else {
                $this->session->data['error'] = sprintf($this->language->get('error_not_cancelled'), $result['L_LONGMESSAGE0']);
            }
        } else {
            $this->session->data['error'] = $this->language->get('error_not_found');
        }

        $this->redirect($this->url->link('account/recurring/info', 'recurring_id=' . $this->request->get['recurring_id'], 'SSL'));
    }

    protected function validateCoupon() {
        $this->load->model('checkout/coupon');

        $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

        $error = '';

        if (!$coupon_info) {
            $error = $this->language->get('error_coupon');
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $error;
            return false;
        }
    }

    protected function validateVoucher() {
        $this->load->model('checkout/voucher');

        $voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

        $error = '';

        if (!$voucher_info) {
            $error = $this->language->get('error_voucher');
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $this->language->get('error_voucher');
            ;
            return false;
        }
    }

    protected function validateReward() {
        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        $error = '';

        if (empty($this->request->post['reward'])) {
            $error = $this->language->get('error_reward');
        }

        if ($this->request->post['reward'] > $points) {
            $error = sprintf($this->language->get('error_points'), $this->request->post['reward']);
        }

        if ($this->request->post['reward'] > $points_total) {
            $error = sprintf($this->language->get('error_maximum'), $points_total);
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $error;
            return false;
        }
    }
    
	public function paymentRequestInfo($order_info,$order_product,$order_total) {

		$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $order_info['currency_code'];
		$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->config->get('pp_express_method');

		$i = 0;
		$item_total = 0;

		foreach ($order_product as $item) {
			
			$item_price = $this->currency->format($item['price'], false, false, false);

			$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
			$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $item['model'];
			$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;
			$item_total += round($item_price * $item['quantity'], 2);
			$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];

			$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $this->url->link('product/product', 'product_id=' . $item['product_id']);

			$i++;
		}


		foreach ($order_total as $total_row) {
			if (!in_array($total_row['code'], array('total', 'sub_total'))) {
				if ($total_row['value'] != 0) {
					$item_price = $this->currency->format($total_row['value'], false, false, false);
					$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $total_row['code'];
					$data['L_PAYMENTREQUEST_0_NAME' . $i] = $total_row['title'];
					$data['L_PAYMENTREQUEST_0_AMT' . $i] = $this->currency->format($total_row['value'], false, false, false);
					$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
					$item_total = round($item_total + $item_price, 2);
					$i++;
				}
			}
		}

		$data['PAYMENTREQUEST_0_ITEMAMT'] = round($item_total, 2);
		$data['PAYMENTREQUEST_0_AMT'] = round($item_total, 2);
        $data['PAYMENTREQUEST_0_INVNUM'] = $order_info['order_number']; 
        
		return $data;
    }


}

?>