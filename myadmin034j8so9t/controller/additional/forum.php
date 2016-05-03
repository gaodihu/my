<?php 
class ControllerAdditionalForum extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('Forum list'));
		$this->load->model('additional/forum');
		$this->getList();
	} 

    public function add(){
       $this->document->setTitle($this->language->get('插入新渠道'));

		$this->load->model('additional/forum');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_forum->addExclusiveUrl($this->request->post);

			$this->session->data['success'] = "插入成功！";

			$this->redirect($this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }

    public function edit(){
        $this->document->setTitle($this->language->get('编辑新渠道'));

		$this->load->model('additional/forum');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_forum->editExclusiveUrl($this->request->get['id'],$this->request->post);

			$this->session->data['success'] = "编辑成功！";

			$this->redirect($this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }
	public function delete() { 
		$this->document->setTitle($this->language->get('所有渠道'));

		$this->load->model('additional/forum');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $new_p_id) {
				$this->model_additional_forum->deleteForumProgramInfo($new_p_id);
			}

			$this->session->data['success'] = "删除成功";

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('additional/forum', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        if (isset($this->request->get['filter_forum_name'])) {
				$filter['filter_forum_name'] = $this->request->get['filter_forum_name'];
		} else {
				$filter['filter_forum_name']= null;
		}
		if (isset($this->request->get['filter_forum_url'])) {
				$filter['filter_forum_url'] = $this->request->get['filter_forum_url'];
		} else {
				$filter['filter_forum_url']= null;
		}
        if (isset($this->request->get['filter_user_name'])) {
				$filter['filter_user_name'] = $this->request->get['filter_user_name'];
		} else {
				$filter['filter_user_name']= null;
		}
		if (isset($this->request->get['filter_contact_email'])) {
				$filter['filter_contact_email'] = $this->request->get['filter_contact_email'];
		} else {
				$filter['filter_contact_email']= null;
		}
        if (isset($this->request->get['filter_contact_name'])) {
				$filter['filter_contact_name'] = $this->request->get['filter_contact_name'];
		} else {
				$filter['filter_contact_name']= null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
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
			'text'      => $this->language->get('论坛信息'),
			'href'      => $this->url->link('additional/forum', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

        $this->data['delete'] = $this->url->link('additional/forum/delete', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['form_user_list'] = $this->url->link('additional/forum_user', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('additional/forum/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['forum_pro_infolist'] = array();
        $this->data['filter'] = $filter;
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
        $data =array_merge($data,$filter);
		$forum_pro_total = $this->model_additional_forum->getTotalForumProgram();
		$results = $this->model_additional_forum->getForumProgram($data);

		foreach ($results as $result) {
			$action = array();
                $action[] = array(
                    'text' => $this->language->get('编辑'),
                    'href' => $this->url->link('additional/forum/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['forum_program_id'], 'SSL')
                );
			$result['selected'] =isset($this->request->post['selected']) && in_array($result['new_pro_id'], $this->request->post['selected']);
            $result['action'] =$action;
			$this->data['forum_pro_infolist'][] = $result;
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
		$pagination = new Pagination();
		$pagination->total = $forum_pro_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('additional/forum', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->template = 'additional/forum_program_list.tpl';
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
                'href'      => $this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );

			$this->data['action'] = $this->url->link('additional/forum/add', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['action_text'] ='新增渠道';
		} else {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('编辑渠道'),
                'href'      => $this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
		    );
            $this->data['action_text'] ='编辑渠道';
			$this->data['action'] = $this->url->link('additional/forum/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}
		$this->data['cancel'] = $this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id'])) {
			$forum_url_info = $this->model_additional_forum->getExclusiveUrl($this->request->get['id']);
		}
        else{
            $forum_url_info =array();
        }
        $this->data['forum_url_info'] =$forum_url_info;
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
		$this->template = 'additional/forum_url_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'additional/forum')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

   protected function validateForm(){
        if (!$this->user->hasPermission('modify', 'additional/forum')) {
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