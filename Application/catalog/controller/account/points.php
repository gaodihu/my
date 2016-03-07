<?php
class ControllerAccountPoints extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/points', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/points');

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
			'href'      => $this->url->link('account/points', '', 'SSL'),
			'separator' => false
		);

		$this->load->model('account/points');
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['column_total_points'] = $this->language->get('column_total_points');
		$this->data['column_reward_points'] = $this->language->get('column_reward_points');
		$this->data['column_available_points'] = $this->language->get('column_available_points');
		$this->data['column_pending_points'] = $this->language->get('column_pending_points');
		$this->data['column_used_points'] = $this->language->get('column_used_points');
		$this->data['column_points_for'] = $this->language->get('column_points_for');

		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_point'] = $this->language->get('column_point');
		$this->data['column_from'] = $this->language->get('column_from');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_notes'] = $this->language->get('column_notes');

		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_how_to_get_bonus_point_in_myled'] = $this->language->get('text_how_to_get_bonus_point_in_myled');


		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$limit =10;

		$this->data['rewards'] = array();
		
		$this->data['total_points'] =$this->model_account_points->getTotalPoints();
		$this->data['used_points'] =$this->model_account_points->getTotalSpentPoints();
		$this->data['pending_points'] =$this->model_account_points->getTotalValidationPoints();
		$available_points =$this->data['total_points']-$this->data['used_points'];
		$this->data['available_points'] =$available_points>0?$available_points:0;
		
		
		//得到积分的历史记录
		$this->data['rewards'] =$this->getPointList($page,$limit,'total');
		//可用积分列表
		$this->data['available_list'] =$this->getPointList($page,$limit,'available');
		//待验证积分记录
		$this->data['pending_list'] =$this->getPointList($page,$limit,'pending');
		//已用积分记录
		$this->data['used_list'] =$this->getPointList($page,$limit,'used');

		$this->data['total'] = (int)$this->customer->getRewardPoints();

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/points.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/points.tpl';
		} else {
			$this->template = 'default/template/account/points.tpl';
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
	

	protected function getPointList($page,$limit,$status){
		$data = array(				  
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => $limit
		);
		$points =array();
		$reward_total = $this->model_account_points->getTotalRewards();

		$results = $this->model_account_points->getRewards($data,$status);
		if($results){
			foreach ($results as $result) {
				if($result['status']==0){
					$status_des = $this->language->get('column_status_0');
				}
				elseif($result['status']==1){
					$status_des = $this->language->get('column_status_1');
				}
				
				if($result['description']){
					$from =$result['description'];
				}
				
				if($result['order_id']){
					$from =$result['order_number'];
				}
                elseif($result['description']=="Double points for reviews, only in 2nd Anniversary"){
                    $from =$this->language->get('text_product_review');
                    $result['description'] =$this->language->get('text_double_points');
                }elseif($result['description']=='product reviews'){
                    $from ='';
                    $result['description'] =$this->language->get('text_product_reviews');
                }
				else{
					$from ='';
				}
				
				$points['list'][] = array(
					'order_id'    => $result['order_id'],
					'order_number'    => $result['order_number'],
					'points'      => $result['points'],
					'points_spent'      => $result['points_spent'],
					'status'      => $result['status'],
					'status_des'      => $status_des,
					'description' => $result['description'],
					'from' => $from,
					'note' => $result['description']?$result['description']:$this->language->get('text_no_records'),
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
			$pagination->url = $this->url->link('account/points', 'page={page}', 'SSL');
			$points['pagination']=$pagination->render();
		}
		else{
			$points['list'] =array();
			$points['pagination'] ='';
		}
		
		return $points;
	}
}
?>