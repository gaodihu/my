<?php 
class ControllerFestivalLottery extends Controller {
	private $error = array();
	private $type_arr = array(
		'1' => '砸蛋',
		'2' => '转盘',
	);

	public function index() {
		$this->document->setTitle('抽奖活动设置');
		$this->load->model('festival/lottery');
		$this->getList();
	}
    public function insert(){
        $this->document->setTitle('增加抽奖活动');
		$this->load->model('festival/lottery');
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_festival_lottery->addPrizeName($this->request->post);

			$this->session->data['success'] ="Add Success";

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getListNameForm();
    }
    public function update(){
        $this->document->setTitle('编辑抽奖活动');
		$this->load->model('festival/lottery');
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_festival_lottery->editPrizeName($this->request->get['id'],$this->request->post);

			$this->session->data['success'] ="Update Success";

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getListNameForm();
    }
	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('抽奖活动设置'),
			'href'      => $this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
        
        $this->data['add'] = $this->url->link('festival/lottery/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('festival/lottery/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('festival/lottery/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['lottery_lists'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$total_lottery_lists = $this->model_festival_lottery->getTotalPrizeName($data);
		$results = $this->model_festival_lottery->getPrizeNameList($data);
		foreach ($results as $result) {
			$action = array();
            $action[] = array(
                'text' => $this->language->get('编辑'),
                'href' => $this->url->link('festival/lottery/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
            );
            $action[] = array(
                'text' => $this->language->get('设置活动规则'),
                'href' => $this->url->link('festival/lottery/rule', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
            );
			$this->data['lottery_lists'][] = array(
				'id'  => $result['id'],
				'name'       => $result['name'],
				'start_time'     => $result['start_time'],
                'end_time'     => $result['end_time'],
				'type'       => $result['type'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	

         if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
        else{
            $this->data['error_warning'] ='';
        }
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_id'] = $this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . '&sort=id' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $total_lottery_lists;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->data['type_arr'] = $this->type_arr;
		$this->template = 'festival/lottery_name_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	protected function getListNameForm() {
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('抽奖活动'),
			'href'      => $this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('festival/lottery/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('festival/lottery/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('festival/lottery', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['id'])) {
			$prize_name_info = $this->model_festival_lottery->getPrizeName($this->request->get['id']);
		}
        else{
            $prize_name_info =array();
        }
        $this->data['prize_name_info'] =$prize_name_info;
		$this->data['type_arr'] = $this->type_arr;
		//得到语言项
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'festival/lottery_name_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function rule(){
        $this->load->model('festival/lottery');
        $this->document->setTitle('设置抽奖活动规则');
        $prize_name_id =$this->request->get['id'];
        $this->data['form_action'] =1;
       
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => '抽奖活动',
			'href'      => $this->url->link('festival/lottery', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
        $this->data['breadcrumbs'][] = array(
			'text'      => '设置抽奖活动规则',
			'href'      => $this->url->link('festival/lottery/rule', 'token=' . $this->session->data['token']."&id=".$prize_name_id, 'SSL'),
			'separator' => ' :: '
		);
        $this->data['action'] = $this->url->link('festival/lottery/rule', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		$this->data['cancel'] = $this->url->link('festival/lottery', 'token=' . $this->session->data['token'], 'SSL');
	    $prize_set_info = $this->model_festival_lottery->getPrizeSet($this->request->get['id']);
        $this->data['prize_set_info'] =$prize_set_info;


         if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_festival_lottery->editPrizeSet($this->request->get['id'],$this->request->post);

			$this->session->data['success'] ="Add Success";

			$this->redirect($this->url->link('festival/lottery', 'token=' . $this->session->data['token'], 'SSL'));
		}
        $this->template = 'festival/lottery_set_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }
}
?>