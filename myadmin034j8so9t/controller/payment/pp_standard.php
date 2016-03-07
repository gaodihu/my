<?php
class ControllerPaymentPPStandard extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/pp_standard');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_standard', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_debug'] = $this->language->get('entry_debug');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
		$this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$this->data['entry_denied_status'] = $this->language->get('entry_denied_status');
		$this->data['entry_expired_status'] = $this->language->get('entry_expired_status');
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$this->data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$this->data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$this->data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
		$this->data['entry_voided_status'] = $this->language->get('entry_voided_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
        
        if (isset($this->error['small_email'])) {
			$this->data['error_small_email'] = $this->error['small_email'];
		} else {
			$this->data['error_small_email'] = '';
		}
        
        
       if (isset($this->error['small_limit'])) {
			$this->data['error_small_limit'] = $this->error['small_limit'];
		} else {
			$this->data['error_small_limit'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_standard_email'])) {
			$this->data['pp_standard_email'] = $this->request->post['pp_standard_email'];
		} else {
			$this->data['pp_standard_email'] = $this->config->get('pp_standard_email');
		}
         //小额账号
        if (isset($this->request->post['pp_small_standard_enabled'])) {
			$this->data['pp_small_standard_enabled'] = $this->request->post['pp_small_standard_enabled'];
		} else {
			$this->data['pp_small_standard_enabled'] = $this->config->get('pp_small_standard_enabled');
		}
       
        if (isset($this->request->post['pp_small_standard_limit'])) {
			$this->data['pp_small_standard_limit'] = $this->request->post['pp_small_standard_limit'];
		} else {
			$this->data['pp_small_standard_limit'] = $this->config->get('pp_small_standard_limit');
		}
        
        if (isset($this->request->post['pp_small_standard_email'])) {
			$this->data['pp_small_standard_email'] = $this->request->post['pp_small_standard_email'];
		} else {
			$this->data['pp_small_standard_email'] = $this->config->get('pp_small_standard_email');
		}
        

		if (isset($this->request->post['pp_standard_test'])) {
			$this->data['pp_standard_test'] = $this->request->post['pp_standard_test'];
		} else {
			$this->data['pp_standard_test'] = $this->config->get('pp_standard_test');
		}

		if (isset($this->request->post['pp_standard_transaction'])) {
			$this->data['pp_standard_transaction'] = $this->request->post['pp_standard_transaction'];
		} else {
			$this->data['pp_standard_transaction'] = $this->config->get('pp_standard_transaction');
		}

		if (isset($this->request->post['pp_standard_debug'])) {
			$this->data['pp_standard_debug'] = $this->request->post['pp_standard_debug'];
		} else {
			$this->data['pp_standard_debug'] = $this->config->get('pp_standard_debug');
		}

		if (isset($this->request->post['pp_standard_total'])) {
			$this->data['pp_standard_total'] = $this->request->post['pp_standard_total'];
		} else {
			$this->data['pp_standard_total'] = $this->config->get('pp_standard_total'); 
		} 

		if (isset($this->request->post['pp_standard_canceled_reversal_status_id'])) {
			$this->data['pp_standard_canceled_reversal_status_id'] = $this->request->post['pp_standard_canceled_reversal_status_id'];
		} else {
			$this->data['pp_standard_canceled_reversal_status_id'] = $this->config->get('pp_standard_canceled_reversal_status_id');
		}

		if (isset($this->request->post['pp_standard_completed_status_id'])) {
			$this->data['pp_standard_completed_status_id'] = $this->request->post['pp_standard_completed_status_id'];
		} else {
			$this->data['pp_standard_completed_status_id'] = $this->config->get('pp_standard_completed_status_id');
		}	

		if (isset($this->request->post['pp_standard_denied_status_id'])) {
			$this->data['pp_standard_denied_status_id'] = $this->request->post['pp_standard_denied_status_id'];
		} else {
			$this->data['pp_standard_denied_status_id'] = $this->config->get('pp_standard_denied_status_id');
		}

		if (isset($this->request->post['pp_standard_expired_status_id'])) {
			$this->data['pp_standard_expired_status_id'] = $this->request->post['pp_standard_expired_status_id'];
		} else {
			$this->data['pp_standard_expired_status_id'] = $this->config->get('pp_standard_expired_status_id');
		}

		if (isset($this->request->post['pp_standard_failed_status_id'])) {
			$this->data['pp_standard_failed_status_id'] = $this->request->post['pp_standard_failed_status_id'];
		} else {
			$this->data['pp_standard_failed_status_id'] = $this->config->get('pp_standard_failed_status_id');
		}	

		if (isset($this->request->post['pp_standard_pending_status_id'])) {
			$this->data['pp_standard_pending_status_id'] = $this->request->post['pp_standard_pending_status_id'];
		} else {
			$this->data['pp_standard_pending_status_id'] = $this->config->get('pp_standard_pending_status_id');
		}

		if (isset($this->request->post['pp_standard_processed_status_id'])) {
			$this->data['pp_standard_processed_status_id'] = $this->request->post['pp_standard_processed_status_id'];
		} else {
			$this->data['pp_standard_processed_status_id'] = $this->config->get('pp_standard_processed_status_id');
		}

		if (isset($this->request->post['pp_standard_refunded_status_id'])) {
			$this->data['pp_standard_refunded_status_id'] = $this->request->post['pp_standard_refunded_status_id'];
		} else {
			$this->data['pp_standard_refunded_status_id'] = $this->config->get('pp_standard_refunded_status_id');
		}

		if (isset($this->request->post['pp_standard_reversed_status_id'])) {
			$this->data['pp_standard_reversed_status_id'] = $this->request->post['pp_standard_reversed_status_id'];
		} else {
			$this->data['pp_standard_reversed_status_id'] = $this->config->get('pp_standard_reversed_status_id');
		}

		if (isset($this->request->post['pp_standard_voided_status_id'])) {
			$this->data['pp_standard_voided_status_id'] = $this->request->post['pp_standard_voided_status_id'];
		} else {
			$this->data['pp_standard_voided_status_id'] = $this->config->get('pp_standard_voided_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_standard_geo_zone_id'])) {
			$this->data['pp_standard_geo_zone_id'] = $this->request->post['pp_standard_geo_zone_id'];
		} else {
			$this->data['pp_standard_geo_zone_id'] = $this->config->get('pp_standard_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_standard_status'])) {
			$this->data['pp_standard_status'] = $this->request->post['pp_standard_status'];
		} else {
			$this->data['pp_standard_status'] = $this->config->get('pp_standard_status');
		}

		if (isset($this->request->post['pp_standard_sort_order'])) {
			$this->data['pp_standard_sort_order'] = $this->request->post['pp_standard_sort_order'];
		} else {
			$this->data['pp_standard_sort_order'] = $this->config->get('pp_standard_sort_order');
		}

		$this->template = 'payment/pp_standard.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_standard')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_standard_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}
        
        if ($this->request->post['pp_small_standard_enabled'] == 1) {
			if ($this->request->post['pp_small_standard_limit'] == '' || floatval($this->request->post['pp_small_standard_limit']) <=0) {
                $this->error['small_limit'] = "小额最高金额错误";
            }
        
            if (!$this->request->post['pp_small_standard_email']) {
                $this->error['small_email'] = "小额账号不能为空";
            }
		}
        
        
        

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

 	public function refund() {

		$this->load->language('payment/pp_standard_refund');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['btn_cancel'] = $this->language->get('btn_cancel');
		$this->data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$this->data['entry_full_refund'] = $this->language->get('entry_full_refund');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['btn_refund'] = $this->language->get('btn_refund');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_pp_standard'),
			'href'      => $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_standard/refund', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		//button actions
		$this->data['action'] = $this->url->link('payment/pp_standard/doRefund', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['transaction_id'] = $this->request->get['transaction_id'];

		$this->load->model('payment/pp_standard');
		$pp_transaction = $this->model_payment_pp_standard->getTransaction($this->request->get['transaction_id']);

		$this->data['amount_original'] = $pp_transaction['AMT'];
		$this->data['currency_code'] = $pp_transaction['CURRENCYCODE'];

		$refunded = number_format($this->model_payment_pp_standard->totalRefundedTransaction($this->request->get['transaction_id']), 2);

		if($refunded != 0.00) {
			$this->data['refund_available'] = number_format($this->data['amount_original'] + $refunded, 2);
			$this->data['attention'] = $this->language->get('text_current_refunds').': '.$this->data['refund_available'];
		} else {
			$this->data['refund_available'] = '';
			$this->data['attention'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

		if(isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->template = 'payment/pp_standard_refund.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		if(isset($this->request->post['transaction_id']) && isset($this->request->post['refund_full'])) {

			$this->load->model('payment/pp_standard');
			$this->load->language('payment/pp_standard_refund');

			if($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_partial_amt');
			} else {
				$order_id = $this->model_payment_pp_standard->getOrderId($this->request->post['transaction_id']);
				$paypal_order = $this->model_payment_pp_standard->getOrder($order_id);

				if ($paypal_order) {
					$call_data = array();
					$call_data['METHOD'] = 'RefundTransaction';
					$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
					$call_data['NOTE'] = urlencode($this->request->post['refund_message']);
					$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

					$current_transaction = $this->model_payment_pp_standard->getLocalTransaction($this->request->post['transaction_id']);

					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE'] = 'Full';
					} else {
						$call_data['REFUNDTYPE'] = 'Partial';
						$call_data['AMT'] = number_format($this->request->post['amount'], 2);
						$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
					}

					$result = $this->model_payment_pp_standard->call($call_data);

					$transaction = array(
						'paypal_order_id' => $paypal_order['paypal_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $this->request->post['transaction_id'],
						'note' => $this->request->post['refund_message'],
						'msgsubid' => $call_data['MSGSUBID'],
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Refunded',
						'transaction_entity' => 'payment',
						'pending_reason' => '',
						'amount' => '-' . (isset($call_data['AMT']) ? $call_data['AMT'] : $current_transaction['amount']),
						'debug_data' => json_encode($result),
					);

					if ($result == false) {
						$transaction['payment_status'] = 'Failed';
						$this->model_payment_pp_standard->addTransaction($transaction, $call_data);
						$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {

						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
						$transaction['pending_reason'] = $result['PENDINGREASON'];
						$transaction['amount'] = '-' . $result['GROSSREFUNDAMT'];

						$this->model_payment_pp_standard->addTransaction($transaction);

						//update transaction to refunded status
						if ($result['TOTALREFUNDEDAMOUNT'] == $this->request->post['amount_original']) {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($this->request->post['transaction_id']) . "' LIMIT 1");
						} else {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($this->request->post['transaction_id']) . "' LIMIT 1");
						}

						//redirect back to the order
						$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else {
						$this->model_payment_pp_standard->log(json_encode($result));
						$this->session->data['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error') . (isset($result['L_LONGMESSAGE0']) ? '<br />' . $result['L_LONGMESSAGE0'] : '');
						$this->redirect($this->url->link('payment/pp_standard/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
					}
				} else {
					$this->session->data['error'] = $this->language->get('error_data_missing');
					$this->redirect($this->url->link('payment/pp_standard/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->redirect($this->url->link('payment/pp_standard/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
		}
	}

	
	

	public function orderAction() {
		if ($this->config->get('pp_standard_status')) {
			$this->load->model('payment/pp_standard');
			$this->load->language('payment/pp_standard_order');

			$paypal_order = $this->model_payment_pp_standard->getOrder($this->request->get['order_id']);

			if ($paypal_order) {
				$this->data['text_payment_info'] = $this->language->get('text_payment_info');
				$this->data['text_capture_status'] = $this->language->get('text_capture_status');
				$this->data['text_amount_auth'] = $this->language->get('text_amount_auth');
				$this->data['btn_void'] = $this->language->get('btn_void');
				$this->data['btn_capture'] = $this->language->get('btn_capture');
				$this->data['text_amount_captured'] = $this->language->get('text_amount_captured');
				$this->data['text_amount_refunded'] = $this->language->get('text_amount_refunded');
				$this->data['text_capture_amount'] = $this->language->get('text_capture_amount');
				$this->data['text_complete_capture'] = $this->language->get('text_complete_capture');
				$this->data['text_transactions'] = $this->language->get('text_transactions');
				$this->data['text_complete'] = $this->language->get('text_complete');
				$this->data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$this->data['error_capture_amt'] = $this->language->get('error_capture_amt');
				$this->data['text_view'] = $this->language->get('text_view');
				$this->data['text_refund'] = $this->language->get('text_refund');
				$this->data['text_resend'] = $this->language->get('text_resend');
				$this->data['column_trans_id'] = $this->language->get('column_trans_id');
				$this->data['column_amount'] = $this->language->get('column_amount');
				$this->data['column_type'] = $this->language->get('column_type');
				$this->data['column_status'] = $this->language->get('column_status');
				$this->data['column_pend_reason'] = $this->language->get('column_pend_reason');
				$this->data['column_created'] = $this->language->get('column_created');
				$this->data['column_action'] = $this->language->get('column_action');

				$this->data['paypal_order'] = $paypal_order;
				$this->data['order_id'] = $this->request->get['order_id'];
				$this->data['token'] = $this->session->data['token'];

				$captured = number_format($this->model_payment_pp_standard->totalCaptured($this->data['paypal_order']['paypal_order_id']), 2);
				$refunded = number_format($this->model_payment_pp_standard->totalRefundedOrder($this->data['paypal_order']['paypal_order_id']), 2);

				$this->data['paypal_order']['captured'] = $captured;
				$this->data['paypal_order']['refunded'] = $refunded;
				$this->data['paypal_order']['remaining'] = number_format($this->data['paypal_order']['total'] - $captured, 2);

				if ($paypal_order) {
					$captured = number_format($this->model_payment_pp_standard->totalCaptured($paypal_order['paypal_order_id']), 2);
					$refunded = number_format($this->model_payment_pp_standard->totalRefundedOrder($paypal_order['paypal_order_id']), 2);

					$this->data['paypal_order'] = $paypal_order;

					$this->data['paypal_order']['captured'] = $captured;
					$this->data['paypal_order']['refunded'] = $refunded;
					$this->data['paypal_order']['remaining'] = number_format($paypal_order['total'] - $captured, 2);
				}

				$this->data['refund_link'] = $this->url->link('payment/pp_standard/refund', 'token=' . $this->session->data['token'], 'SSL');
				$this->data['view_link'] = $this->url->link('payment/pp_standard/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
				$this->data['resend_link'] = $this->url->link('payment/pp_standard/resend', 'token=' . $this->session->data['token'], 'SSL');

				$this->template = 'payment/pp_standard_order.tpl';
				$this->response->setOutput($this->render());
			}
		}
	}

	public function search() {

		$this->load->language('payment/pp_standard_search');
		$this->load->model('payment/pp_standard');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['btn_search'] = $this->language->get('btn_search');
		$this->data['btn_edit_search'] = $this->language->get('btn_edit_search');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_date_to'] = $this->language->get('entry_date_to');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$this->data['entry_transaction_status'] = $this->language->get('entry_transaction_status');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_email_buyer'] = $this->language->get('entry_email_buyer');
		$this->data['entry_email_merchant'] = $this->language->get('entry_email_merchant');
		$this->data['entry_receipt'] = $this->language->get('entry_receipt');
		$this->data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$this->data['entry_invoice_no'] = $this->language->get('entry_invoice_no');
		$this->data['entry_auction'] = $this->language->get('entry_auction');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_profile_id'] = $this->language->get('entry_profile_id');
		$this->data['text_buyer_info'] = $this->language->get('text_buyer_info');
		$this->data['entry_salutation'] = $this->language->get('entry_salutation');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_middlename'] = $this->language->get('entry_middlename');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_suffix'] = $this->language->get('entry_suffix');
		$this->data['text_searching'] = $this->language->get('text_searching');
		$this->data['text_view'] = $this->language->get('text_view');

		$this->data['entry_status_all'] = $this->language->get('entry_status_all');
		$this->data['entry_status_pending'] = $this->language->get('entry_status_pending');
		$this->data['entry_status_processing'] = $this->language->get('entry_status_processing');
		$this->data['entry_status_success'] = $this->language->get('entry_status_success');
		$this->data['entry_status_denied'] = $this->language->get('entry_status_denied');
		$this->data['entry_status_reversed'] = $this->language->get('entry_status_reversed');

		$this->data['entry_trans_all'] = $this->language->get('entry_trans_all');
		$this->data['entry_trans_sent'] = $this->language->get('entry_trans_sent');
		$this->data['entry_trans_received'] = $this->language->get('entry_trans_received');
		$this->data['entry_trans_masspay'] = $this->language->get('entry_trans_masspay');
		$this->data['entry_trans_money_req'] = $this->language->get('entry_trans_money_req');
		$this->data['entry_trans_funds_add'] = $this->language->get('entry_trans_funds_add');
		$this->data['entry_trans_funds_with'] = $this->language->get('entry_trans_funds_with');
		$this->data['entry_trans_referral'] = $this->language->get('entry_trans_referral');
		$this->data['entry_trans_fee'] = $this->language->get('entry_trans_fee');
		$this->data['entry_trans_subscription'] = $this->language->get('entry_trans_subscription');
		$this->data['entry_trans_dividend'] = $this->language->get('entry_trans_dividend');
		$this->data['entry_trans_billpay'] = $this->language->get('entry_trans_billpay');
		$this->data['entry_trans_refund'] = $this->language->get('entry_trans_refund');
		$this->data['entry_trans_conv'] = $this->language->get('entry_trans_conv');
		$this->data['entry_trans_bal_trans'] = $this->language->get('entry_trans_bal_trans');
		$this->data['entry_trans_reversal'] = $this->language->get('entry_trans_reversal');
		$this->data['entry_trans_shipping'] = $this->language->get('entry_trans_shipping');
		$this->data['entry_trans_bal_affect'] = $this->language->get('entry_trans_bal_affect');
		$this->data['entry_trans_echeque'] = $this->language->get('entry_trans_echeque');

		$this->data['tbl_column_date'] = $this->language->get('tbl_column_date');
		$this->data['tbl_column_type'] = $this->language->get('tbl_column_type');
		$this->data['tbl_column_email'] = $this->language->get('tbl_column_email');
		$this->data['tbl_column_name'] = $this->language->get('tbl_column_name');
		$this->data['tbl_column_transid'] = $this->language->get('tbl_column_transid');
		$this->data['tbl_column_status'] = $this->language->get('tbl_column_status');
		$this->data['tbl_column_currency'] = $this->language->get('tbl_column_currency');
		$this->data['tbl_column_amount'] = $this->language->get('tbl_column_amount');
		$this->data['tbl_column_fee'] = $this->language->get('tbl_column_fee');
		$this->data['tbl_column_netamt'] = $this->language->get('tbl_column_netamt');
		$this->data['tbl_column_action'] = $this->language->get('tbl_column_action');

		$this->data['currency_codes'] = $this->model_payment_pp_standard->currencyCodes();
		$this->data['default_currency'] = $this->config->get('pp_standard_currency');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_pp_standard'),
			'href'      => $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_standard/search', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);


		$this->data['token'] = $this->session->data['token'];
		$this->data['date_start'] = date("Y-m-d", strtotime('-30 days'));
		$this->data['view_link'] = $this->url->link('payment/pp_standard/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'payment/pp_standard_search.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function doSearch() {
		/**
		 * used to search for transactions from a user account
		 */
		if(isset($this->request->post['date_start'])) {

			$this->load->model('payment/pp_standard');

			$call_data = array();
			$call_data['METHOD'] = 'TransactionSearch';
			$call_data['STARTDATE'] = gmdate($this->request->post['date_start']."\TH:i:s\Z");

			if(!empty($this->request->post['date_end'])) {
				$call_data['ENDDATE'] = gmdate($this->request->post['date_end']."\TH:i:s\Z");
			}

			if(!empty($this->request->post['transaction_class'])) {
				$call_data['TRANSACTIONCLASS'] = $this->request->post['transaction_class'];
			}

			if(!empty($this->request->post['status'])) {
				$call_data['STATUS'] = $this->request->post['status'];
			}

			if(!empty($this->request->post['buyer_email'])) {
				$call_data['EMAIL'] = $this->request->post['buyer_email'];
			}

			if(!empty($this->request->post['merchant_email'])) {
				$call_data['RECEIVER'] = $this->request->post['merchant_email'];
			}

			if(!empty($this->request->post['receipt_id'])) {
				$call_data['RECEIPTID'] = $this->request->post['receipt_id'];
			}

			if(!empty($this->request->post['transaction_id'])) {
				$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
			}

			if(!empty($this->request->post['invoice_number'])) {
				$call_data['INVNUM'] = $this->request->post['invoice_number'];
			}

			if(!empty($this->request->post['auction_item_number'])) {
				$call_data['AUCTIONITEMNUMBER'] = $this->request->post['auction_item_number'];
			}

			if(!empty($this->request->post['amount'])) {
				$call_data['AMT'] = number_format($this->request->post['amount'], 2);
				$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
			}

			if(!empty($this->request->post['profile_id'])) {
				$call_data['PROFILEID'] = $this->request->post['profile_id'];
			}

			if(!empty($this->request->post['name_salutation'])) {
				$call_data['SALUTATION'] = $this->request->post['name_salutation'];
			}

			if(!empty($this->request->post['name_first'])) {
				$call_data['FIRSTNAME'] = $this->request->post['name_first'];
			}

			if(!empty($this->request->post['name_middle'])) {
				$call_data['MIDDLENAME'] = $this->request->post['name_middle'];
			}

			if(!empty($this->request->post['name_last'])) {
				$call_data['LASTNAME'] = $this->request->post['name_last'];
			}

			if(!empty($this->request->post['name_suffix'])) {
				$call_data['SUFFIX'] = $this->request->post['name_suffix'];
			}

			$result = $this->model_payment_pp_standard->call($call_data);

			if($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$response['error'] = false;
				$response['result'] = $this->formatRows($result);
				$this->response->setOutput(json_encode($response));
			} else {
				$response['error'] = true;
				$response['error_msg'] = $result['L_LONGMESSAGE0'];
			}

			$this->response->setOutput(json_encode($response));
		} else {
			$response['error'] = true;
			$response['error_msg'] = 'Enter a start date';
			$this->response->setOutput(json_encode($response));
		}
	}

	public function viewTransaction() {
		$this->load->model('payment/pp_standard');
		$this->load->language('payment/pp_standard_view');

		$this->data['transaction'] = $this->model_payment_pp_standard->getTransaction($this->request->get['transaction_id']);
		$this->data['lines'] = $this->formatRows($this->data['transaction']);
		$this->data['view_link'] = $this->url->link('payment/pp_standard/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_product_lines'] = $this->language->get('text_product_lines');
		$this->data['text_ebay_txn_id'] = $this->language->get('text_ebay_txn_id');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_qty'] = $this->language->get('text_qty');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_number'] = $this->language->get('text_number');
		$this->data['text_coupon_id'] = $this->language->get('text_coupon_id');
		$this->data['text_coupon_amount'] = $this->language->get('text_coupon_amount');
		$this->data['text_coupon_currency'] = $this->language->get('text_coupon_currency');
		$this->data['text_loyalty_currency'] = $this->language->get('text_loyalty_currency');
		$this->data['text_loyalty_disc_amt'] = $this->language->get('text_loyalty_disc_amt');
		$this->data['text_options_name'] = $this->language->get('text_options_name');
		$this->data['text_tax_amt'] = $this->language->get('text_tax_amt');
		$this->data['text_currency_code'] = $this->language->get('text_currency_code');
		$this->data['text_amount'] = $this->language->get('text_amount');
		$this->data['text_gift_msg'] = $this->language->get('text_gift_msg');
		$this->data['text_gift_receipt'] = $this->language->get('text_gift_receipt');
		$this->data['text_gift_wrap_name'] = $this->language->get('text_gift_wrap_name');
		$this->data['text_gift_wrap_amt'] = $this->language->get('text_gift_wrap_amt');
		$this->data['text_buyer_email_market'] = $this->language->get('text_buyer_email_market');
		$this->data['text_survey_question'] = $this->language->get('text_survey_question');
		$this->data['text_survey_chosen'] = $this->language->get('text_survey_chosen');
		$this->data['text_receiver_business'] = $this->language->get('text_receiver_business');
		$this->data['text_receiver_email'] = $this->language->get('text_receiver_email');
		$this->data['text_receiver_id'] = $this->language->get('text_receiver_id');
		$this->data['text_buyer_email'] = $this->language->get('text_buyer_email');
		$this->data['text_payer_id'] = $this->language->get('text_payer_id');
		$this->data['text_payer_status'] = $this->language->get('text_payer_status');
		$this->data['text_country_code'] = $this->language->get('text_country_code');
		$this->data['text_payer_business'] = $this->language->get('text_payer_business');
		$this->data['text_payer_salute'] = $this->language->get('text_payer_salute');
		$this->data['text_payer_firstname'] = $this->language->get('text_payer_firstname');
		$this->data['text_payer_middlename'] = $this->language->get('text_payer_middlename');
		$this->data['text_payer_lastname'] = $this->language->get('text_payer_lastname');
		$this->data['text_payer_suffix'] = $this->language->get('text_payer_suffix');
		$this->data['text_address_owner'] = $this->language->get('text_address_owner');
		$this->data['text_address_status'] = $this->language->get('text_address_status');
		$this->data['text_ship_sec_name'] = $this->language->get('text_ship_sec_name');
		$this->data['text_ship_name'] = $this->language->get('text_ship_name');
		$this->data['text_ship_street1'] = $this->language->get('text_ship_street1');
		$this->data['text_ship_street2'] = $this->language->get('text_ship_street2');
		$this->data['text_ship_city'] = $this->language->get('text_ship_city');
		$this->data['text_ship_state'] = $this->language->get('text_ship_state');
		$this->data['text_ship_zip'] = $this->language->get('text_ship_zip');
		$this->data['text_ship_country'] = $this->language->get('text_ship_country');
		$this->data['text_ship_phone'] = $this->language->get('text_ship_phone');
		$this->data['text_ship_sec_add1'] = $this->language->get('text_ship_sec_add1');
		$this->data['text_ship_sec_add2'] = $this->language->get('text_ship_sec_add2');
		$this->data['text_ship_sec_city'] = $this->language->get('text_ship_sec_city');
		$this->data['text_ship_sec_state'] = $this->language->get('text_ship_sec_state');
		$this->data['text_ship_sec_zip'] = $this->language->get('text_ship_sec_zip');
		$this->data['text_ship_sec_country'] = $this->language->get('text_ship_sec_country');
		$this->data['text_ship_sec_phone'] = $this->language->get('text_ship_sec_phone');
		$this->data['text_trans_id'] = $this->language->get('text_trans_id');
		$this->data['text_receipt_id'] = $this->language->get('text_receipt_id');
		$this->data['text_parent_trans_id'] = $this->language->get('text_parent_trans_id');
		$this->data['text_trans_type'] = $this->language->get('text_trans_type');
		$this->data['text_payment_type'] = $this->language->get('text_payment_type');
		$this->data['text_order_time'] = $this->language->get('text_order_time');
		$this->data['text_fee_amount'] = $this->language->get('text_fee_amount');
		$this->data['text_settle_amount'] = $this->language->get('text_settle_amount');
		$this->data['text_tax_amount'] = $this->language->get('text_tax_amount');
		$this->data['text_exchange'] = $this->language->get('text_exchange');
		$this->data['text_payment_status'] = $this->language->get('text_payment_status');
		$this->data['text_pending_reason'] = $this->language->get('text_pending_reason');
		$this->data['text_reason_code'] = $this->language->get('text_reason_code');
		$this->data['text_protect_elig'] = $this->language->get('text_protect_elig');
		$this->data['text_protect_elig_type'] = $this->language->get('text_protect_elig_type');
		$this->data['text_store_id'] = $this->language->get('text_store_id');
		$this->data['text_terminal_id'] = $this->language->get('text_terminal_id');
		$this->data['text_invoice_number'] = $this->language->get('text_invoice_number');
		$this->data['text_custom'] = $this->language->get('text_custom');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['text_sales_tax'] = $this->language->get('text_sales_tax');
		$this->data['text_buyer_id'] = $this->language->get('text_buyer_id');
		$this->data['text_close_date'] = $this->language->get('text_close_date');
		$this->data['text_multi_item'] = $this->language->get('text_multi_item');
		$this->data['text_sub_amt'] = $this->language->get('text_sub_amt');
		$this->data['text_sub_period'] = $this->language->get('text_sub_period');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_pp_standard'),
			'href'      => $this->url->link('payment/pp_standard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_standard/viewTransaction', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'], 'SSL'),
			'separator' => ' :: '
		);

		$this->template = 'payment/pp_standard_view.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function formatRows($data) {
		$return = array();

		foreach($data as $k=>$v) {
			$elements = preg_split("/(\d+)/", $k, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			if(isset($elements[1]) && isset($elements[0])) {
				if($elements[0] == 'L_TIMESTAMP') {
					$v = str_replace('T', ' ', $v);
					$v = str_replace('Z', '', $v);
				}
				$return[$elements[1]][$elements[0]] = $v;
			}
		}

		return $return;
	}

	public function recurringCancel() {
		//cancel an active profile

		$this->load->model('sale/recurring');
		$this->load->model('payment/pp_standard');
		$this->language->load('sale/recurring');

		$profile = $this->model_sale_recurring->getProfile($this->request->get['order_recurring_id']);

		if($profile && !empty($profile['profile_reference'])) {

			$result = $this->model_payment_pp_standard->recurringCancel($profile['profile_reference']);

			if(isset($result['PROFILEID'])) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$profile['order_recurring_id'] . "', `created` = NOW(), `type` = '5'");
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int)$profile['order_recurring_id'] . "' LIMIT 1");

				$this->session->data['success'] = $this->language->get('success_cancelled');
			} else {
				$this->session->data['error'] = sprintf($this->language->get('error_not_cancelled'), $result['L_LONGMESSAGE0']);
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_not_found');
		}

		$this->redirect($this->url->link('sale/recurring/info', 'order_recurring_id=' . $this->request->get['order_recurring_id'].'&token='.$this->request->get['token'], 'SSL'));
	}
   
     
}
?>