<?php
class ControllerAccountReward extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/reward', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/reward');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_reward'),
			'href'      => $this->url->link('account/reward', '', 'SSL'),
			'separator' => false
		);

		$this->load->model('account/reward');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_confirm'] = $this->language->get('column_date_confirm');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_points'] = $this->language->get('column_points');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_order_number'] = $this->language->get('column_order_number');
		$this->data['column_points_spent'] = $this->language->get('column_points_spent');
		$this->data['column_view'] = $this->language->get('column_view');

		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_empty'] = $this->language->get('text_empty');

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}		

		$this->data['rewards'] = array();

		$data = array(				  
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$reward_total = $this->model_account_reward->getTotalRewards($data);

		$results = $this->model_account_reward->getRewards($data);

		foreach ($results as $result) {
			if($result['status']==0){
				$status_des = $this->language->get('column_status_0');
			}
			elseif($result['status']==1){
				$status_des = $this->language->get('column_status_1');
			}
			$this->data['rewards'][] = array(
				'order_id'    => $result['order_id'],
				'order_number'    => $result['order_number'],
				'points'      => $result['points'],
				'points_spent'      => $result['points_spent'],
				'status'      => $result['status'],
				'status_des'      => $status_des,
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_confirm'  => $result['date_confirm']!=='0000-00-00 00:00:00'?date($this->language->get('date_format_short'), strtotime($result['date_confirm'])):'N/A',
				'href'        => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL')
			);
		}	

		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/reward', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['total'] = (int)$this->customer->getRewardPoints();

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reward.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/reward.tpl';
		} else {
			$this->template = 'default/template/account/reward.tpl';
		}

		$this->children = array(
			'account/menu',
			'account/right_top',
			'account/right_bottom',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());		
	} 		
}
?>