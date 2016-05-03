<?php
class ControllerPaymentBankTransfer extends Controller {
	public function index() {
		$this->redirect($this->url->link('checkout/success','','SSL'));
	}

	public function confirm() {
		$this->language->load('payment/bank_transfer');

		$this->load->model('checkout/order');

		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->language->get('text_payment');

		$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
	}
}
?>