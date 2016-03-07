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

		$order_id   = $this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$this->model_checkout_order->update($order_id, $this->config->get('bank_transfer_order_status_id'), $comment, true);
		if($order_info['parent_id '] == 0 && $order_info['is_parent'] == 1){
                $children = $this->model_checkout_order->getOrderChildren($order_id);
                foreach($children as $_item){
                    $this->model_checkout_order->update($_item['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
                }
        }

	}
}
?>