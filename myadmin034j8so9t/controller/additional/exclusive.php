<?php 
class ControllerAdditionalExclusive extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('所有渠道'));
		$this->load->model('additional/exclusive');
		$this->getList();
	} 

    public function add(){
       $this->document->setTitle($this->language->get('插入新渠道'));

		$this->load->model('additional/exclusive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_exclusive->addExclusiveUrl($this->request->post);

			$this->session->data['success'] = "插入成功！";

			$this->redirect($this->url->link('additional/exclusive', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }

    public function edit(){
        $this->document->setTitle($this->language->get('编辑新渠道'));

		$this->load->model('additional/exclusive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_exclusive->editExclusiveUrl($this->request->get['id'],$this->request->post);

			$this->session->data['success'] = "编辑成功！";

			$this->redirect($this->url->link('additional/exclusive', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }
	public function delete() { 
		$this->document->setTitle($this->language->get('所有渠道'));

		$this->load->model('additional/exclusive');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $new_p_id) {
				$this->model_additional_exclusive->deleteExclusiveUrl($new_p_id);
			}

			$this->session->data['success'] = "删除成功";

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

			$this->redirect($this->url->link('additional/exclusive', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 's_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
			'text'      => $this->language->get('渠道URL'),
			'href'      => $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['add_url'] = $this->url->link('additional/exclusive/add', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['delete'] = $this->url->link('additional/exclusive/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('additional/exclusive/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['exclusive_url'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$exclusive_url_total = $this->model_additional_exclusive->getTotalExclusiveUrl();
		$results = $this->model_additional_exclusive->getExclusiveUrls($data);

		foreach ($results as $result) {
			$action = array();
                $action[] = array(
                    'text' => $this->language->get('编辑'),
                    'href' => $this->url->link('additional/exclusive/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['s_id'], 'SSL')
                );
			
			$this->data['exclusive_url'][] = array(
				's_id'  => $result['s_id'],
                'url'     => $result['url'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['new_pro_id'], $this->request->post['selected']),
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

		$this->data['sort_id'] = $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'] . '&sort=s_id' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $exclusive_url_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'additional/exclusive_url.tpl';
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

		
        
        if (!isset($this->request->get['id'])) {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('新增渠道'),
                'href'      => $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );

			$this->data['action'] = $this->url->link('additional/exclusive/add', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['action_text'] ='新增渠道';
		} else {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('编辑渠道'),
                'href'      => $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
		    );
            $this->data['action_text'] ='编辑渠道';
			$this->data['action'] = $this->url->link('additional/exclusive/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}
		$this->data['cancel'] = $this->url->link('additional/exclusive', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id'])) {
			$exclusive_url_info = $this->model_additional_exclusive->getExclusiveUrl($this->request->get['id']);
		}
        else{
            $exclusive_url_info =array();
        }
        $this->data['exclusive_url_info'] =$exclusive_url_info;
        if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
        elseif(isset($this->error['reply_content'])){
            $this->data['error_warning'] =$this->error['reply_content'];
        }
        else{
            $this->data['error_warning'] ='';
        }

		$this->data['token'] = $this->session->data['token'];
		$this->template = 'additional/exclusive_url_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'additional/exclusive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

   protected function validateForm(){
        if (!$this->user->hasPermission('modify', 'additional/exclusive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
   }
}
?>