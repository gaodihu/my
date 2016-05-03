<?php
class ControllerSaleOrderAction extends Controller {
	private $error = array();
	public function join(){
        $this->document->setTitle('订单归并'); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "order",
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);
        $this->load->model('sale/order');
		$this->data['back'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action'] = $this->url->link('sale/order_action/join', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['token'] = $this->session->data['token'];
	    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateJoinForm()) {
			$this->model_sale_order->joinOrder($this->request->post);

			$this->session->data['success'] = "订单归并成功";

			$url = '';
			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
        if (isset($this->request->post['order_number'])) {
			$this->data['order_number'] = $this->request->post['order_number'];
		} else {
			$this->data['order_number'] = '';
		}
        if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
	    $this->template = 'sale/order_join.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

	public function validateJoinForm(){
        $order_number =isset($this->request->post['order_number'])?$this->request->post['order_number']:'';
        $email =isset($this->request->post['email'])?$this->request->post['email']:'';
        if(!$order_number||!$email){
            $this->error['warning'] ="请填写订单号和email";
        }
        if($this->error){
            return false;
        }
        else{
            return true;
        }
    }
}

?>