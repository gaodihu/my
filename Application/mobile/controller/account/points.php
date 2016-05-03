<?php
class ControllerAccountPoints extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/points', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$lang =$this->language->load('account/points');
        $this->data =array_merge($this->data,$lang);
		$this->document->setTitle($this->language->get('heading_title'));

		
		$this->load->model('account/points');
		
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