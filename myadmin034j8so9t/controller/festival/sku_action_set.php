<?php 
class ControllerFestivalSkuActionSet extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle('商品页面活动设置');
		$this->load->model('festival/sku_action_set');
		$this->getList();
	}
    public function insert(){
        $this->document->setTitle('增加商品页面活动设置');
		$this->load->model('festival/sku_action_set');
        if ($this->request->server['REQUEST_METHOD'] == 'POST'&&$this->validateForm()) {
			$this->model_festival_sku_action_set->addSkuActionSet($this->request->post);
			$this->session->data['success'] ="Add Success";
			$this->redirect($this->url->link('festival/sku_action_set', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->getForm();
    }
    public function update(){
        $this->document->setTitle('编辑抽奖活动');
		$this->load->model('festival/sku_action_set');
        if ($this->request->server['REQUEST_METHOD'] == 'POST'&&$this->validateForm()) {
			$this->model_festival_sku_action_set->editSkuActionSet($this->request->get['id'],$this->request->post);

			$this->session->data['success'] ="Update Success";
			$this->redirect($this->url->link('festival/sku_action_set', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getForm();
    }
	protected function getList() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('商品页面活动设置'),
			'href'      => $this->url->link('festival/sku_action_set', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
        
        $this->data['add'] = $this->url->link('festival/sku_action_set/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('festival/sku_action_set/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('festival/sku_action_set/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['sku_set_lists'] = array();
	
		$results = $this->model_festival_sku_action_set->getActionSetList();
		foreach ($results as $result) {
			$action = array();
            $action[] = array(
                'text' => $this->language->get('编辑'),
                'href' => $this->url->link('festival/sku_action_set/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
            );
			$this->data['sku_set_lists'][] = array(
				'id'  => $result['id'],
                'sku'   =>$result['all_sku'],
				'start_time'     => $result['start_time'],
                'end_time'     => $result['end_time'],
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
		$this->template = 'festival/sku_action_set_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	protected function getForm() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('商品页面活动设置'),
			'href'      => $this->url->link('festival/sku_action_set', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('festival/sku_action_set/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('festival/sku_action_set/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('festival/sku_action_set', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['id'])) {
			$sku_set_action_info = $this->model_festival_sku_action_set->getSkuActionSet($this->request->get['id']);
            $sku_set_desc =$this->model_festival_sku_action_set->getSkuActionSetDesc($this->request->get['id']);
            $sku_set_desc_info =array();
            foreach($sku_set_desc as $desc){
                $sku_set_desc_info[$desc['lang_id']] =$desc;
            }
		}
        else{
            $sku_set_action_info =array();
            $sku_set_desc_info =array();
        }
        $this->load->model('localisation/language');
        //得到语言项
        $languages =$this->model_localisation_language->getLanguages();
        $this->data['languages'] =$languages;

        $this->data['sku_set_action_info'] =$sku_set_action_info;
        $this->data['sku_set_desc_info'] =$sku_set_desc_info;
         if(isset($this->error['error_sku'])){
            $this->data['error_sku'] =$this->error['error_sku'];
        }
        else{
            $this->data['error_sku'] ='';
        }
         if(isset($this->error['error_start_time'])){
            $this->data['error_start_time'] =$this->error['error_start_time'];
        }
        else{
            $this->data['error_start_time'] ='';
        }
         if(isset($this->error['error_end_time'])){
            $this->data['error_end_time'] =$this->error['error_end_time'];
        }
        else{
            $this->data['error_end_time'] ='';
        }
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'festival/sku_action_set_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function validateForm(){
      $all_sku =isset($this->request->post['all_sku'])?$this->request->post['all_sku']:'';
      $start_time =isset($this->request->post['start_time'])?$this->request->post['start_time']:'';
      $end_time =isset($this->request->post['end_time'])?$this->request->post['end_time']:'';
      $end_time =isset($this->request->post['end_time'])?$this->request->post['end_time']:'';
      if(!$all_sku){
         $this->error['error_sku'] ="请填写sku信息";
      }
      if(!$start_time){
         $this->error['error_start_time'] ="请填写start time信息";
      }
      if(!$end_time){
         $this->error['error_end_time'] ="请填写end time信息";
      }
      if(!$this->error){
          return true;
      }else{
        return false;
      }
    }

}
?>