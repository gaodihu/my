<?php
class ModelPaymentPPExpress extends Model {
	public function cleanReturn($data) {
		$data = explode('&', $data);

		$arr = array();

		foreach($data as $k=>$v) {
			$tmp = explode('=', $v);
			$arr[$tmp[0]] = urldecode($tmp[1]);
		}

		return $arr;
	}

	public function call($data) {

		if ($this->config->get('pp_express_test') == 1) {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
		}
       $total = $this->cart->getSubTotal();
       $pp_express_small_enabled =  $this->config->get('pp_express_small_enabled');
       $pp_express_small_limit =  $this->config->get('pp_express_small_limit');
       $pp_express_username_small =  $this->config->get('pp_express_username_small');
       $pp_express_password_small =  $this->config->get('pp_express_password_small');
       $pp_express_signature_small =  $this->config->get('pp_express_signature_small');
       if(isset($this->session->data['paypal_ec_setting'])){
           $settings = $this->session->data['paypal_ec_setting'];
       } else {
            if($pp_express_small_enabled && $pp_express_small_limit>0  && $pp_express_small_limit >= $total  && $pp_express_username_small && $pp_express_password_small  && $pp_express_signature_small   ){
                 $settings = array(
                   'USER' => $this->config->get('pp_express_username_small'),
                   'PWD' => $this->config->get('pp_express_password_small'),
                   'SIGNATURE' => $this->config->get('pp_express_signature_small'),
                   'VERSION' => '65.2',
                   'BUTTONSOURCE' => 'opencart',
                );
            }else{
                 $settings = array(
                     'USER' => $this->config->get('pp_express_username'),
                     'PWD' => $this->config->get('pp_express_password'),
                     'SIGNATURE' => $this->config->get('pp_express_signature'),
                     'VERSION' => '65.2',
                     'BUTTONSOURCE' => 'opencart',
                 );
            }
            $this->session->data['paypal_ec_setting'] = $settings;
       }
        
		$this->log($data, 'Call data');

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
			CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
			CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		if( ! $result = curl_exec($ch)) {
			$this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
		}

		$this->log($result, 'Result');

		curl_close($ch);

		return $this->cleanReturn($result);
	}

	public function createToken($len = 32) {
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		while (strlen($activatecode)<$len+1)
			$activatecode.=$base{mt_rand(0,$max)};

		return $activatecode;
	}

	public function log($data, $title = null) {
		if($this->config->get('pp_express_debug')) {
			$this->log->write('PayPal Express debug ('.$title.'): '.json_encode($data));
		}
	}

	public function getMethod($address, $total) {

		$this->load->language('payment/pp_express');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('pp_express_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if ( $total > 5000) {
			$status = false;
		} elseif (!$this->config->get('pp_express_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'pp_express',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('pp_express_sort_order'),
                'desc'       => $this->language->get('text_description'),
			);
		}

		return $method_data;
	}

	public function addOrder($order_data) {
		/**
		 * 1 to 1 relationship with order table (extends order info)
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order` SET
			`order_id` = '".(int)$order_data['order_id']."',
			`created` = NOW(),
			`modified` = NOW(),
			`capture_status` = '".$this->db->escape($order_data['capture_status'])."',
			`currency_code` = '".$this->db->escape($order_data['currency_code'])."',
			`total` = '".(double)$order_data['total']."',
			`authorization_id` = '".$this->db->escape($order_data['authorization_id'])."'");

		return $this->db->getLastId();
	}

	public function addTransaction($transaction_data) {
		/**
		 * 1 to many relationship with paypal order table, many transactions per 1 order
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order_transaction` SET
			`paypal_order_id` = '".(int)$transaction_data['paypal_order_id']."',
			`transaction_id` = '".$this->db->escape($transaction_data['transaction_id'])."',
			`parent_transaction_id` = '".$this->db->escape($transaction_data['parent_transaction_id'])."',
			`created` = NOW(),
			`note` = '".$this->db->escape($transaction_data['note'])."',
			`msgsubid` = '".$this->db->escape($transaction_data['msgsubid'])."',
			`receipt_id` = '".$this->db->escape($transaction_data['receipt_id'])."',
			`payment_type` = '".$this->db->escape($transaction_data['payment_type'])."',
			`payment_status` = '".$this->db->escape($transaction_data['payment_status'])."',
			`pending_reason` = '".$this->db->escape($transaction_data['pending_reason'])."',
			`transaction_entity` = '".$this->db->escape($transaction_data['transaction_entity'])."',
			`amount` = '".(double)$transaction_data['amount']."',
			`debug_data` = '".$this->db->escape($transaction_data['debug_data'])."'");
	}

	public function paymentRequestInfo() {

		$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->currency->getCode();
		$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->config->get('pp_express_method');

		$i = 0;
		$item_total = 0;

		foreach ($this->cart->getProducts() as $item) {
			$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';



			$item_price = $this->currency->format($item['price'], false, false, false);

			$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
			$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $item['model'];
			$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;
			$item_total += round($item_price,2) * $item['quantity'];
			$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];

			$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $this->url->link('product/product', 'product_id=' . $item['product_id']);


			$i++;
		}


		// Totals
		$this->load->model('setting/extension');

		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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

				$sort_order = array();

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
			}
		}

		foreach ($total_data as $total_row) {
			if (!in_array($total_row['code'], array('total', 'sub_total'))) {
				if ($total_row['value'] != 0) {
					$item_price = $this->currency->format($total_row['value'], false, false, false);
					$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $total_row['code'];
					$data['L_PAYMENTREQUEST_0_NAME' . $i] = $total_row['title'];
					$data['L_PAYMENTREQUEST_0_AMT' . $i] = $this->currency->format($total_row['value'], false, false, false);
					$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
					$item_total = $item_total +  round($item_price, 2);
					$i++;
				}
			}
		}

		$data['PAYMENTREQUEST_0_ITEMAMT'] = round($item_total, 2);
		$data['PAYMENTREQUEST_0_AMT'] = round($item_total, 2);



		return $data;
	}

	public function isMobile() {
		/*
		 * This will check the user agent and "try" to match if it is a mobile device
		 */
		if (preg_match("/Mobile|Android|BlackBerry|iPhone|Windows Phone/", $this->request->server['HTTP_USER_AGENT'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getTransactionRow($transaction_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_order_transaction` `pt` LEFT JOIN `" . DB_PREFIX . "paypal_order` `po` ON `pt`.`paypal_order_id` = `po`.`paypal_order_id`  WHERE `pt`.`transaction_id` = '" . $this->db->escape($transaction_id) . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function totalCaptured($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `pending_reason` != 'authorization' AND `pending_reason` != 'paymentreview' AND (`payment_status` = 'Partially-Refunded' OR `payment_status` = 'Completed' OR `payment_status` = 'Pending') AND `transaction_entity` = 'payment'");

		return $qry->row['amount'];
	}

	public function totalRefundedOrder($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `payment_status` = 'Refunded'");

		return $qry->row['amount'];
	}

	public function updateOrder($capture_status, $order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order` SET `modified` = now(), `capture_status` = '".$this->db->escape($capture_status)."' WHERE `order_id` = '".(int)$order_id."'");
	}

	public function recurringCancel($ref) {

		$data = array(
			'METHOD' => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $ref,
			'ACTION' => 'Cancel'
		);

		return $this->call($data);
	}

	public function recurringPayments() {
		/*
		 * Used by the checkout to state the module
		 * supports recurring profiles.
		 */
		return true;
	}
}
?>
