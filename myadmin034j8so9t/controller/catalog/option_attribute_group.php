<?php 
class ControllerCatalogOptionAttributeGroup extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/option_attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_option_to_group');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->addAttribute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/option_attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_option_to_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_option_to_group->editOptionAttributeGroup($this->request->get['option_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$this->redirect($this->url->link('catalog/option_attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/option_attribute_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/option_attribute_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['option_attribute_groups'] = array();

		$data = array(
            'filter_name'=>$filter_name,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$attribute_total = $this->model_catalog_attribute_option_to_group->getTotalOptionAttributesGroups();
		$results = $this->model_catalog_attribute_option_to_group->getOptionAttributeGroups($data);
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/option_attribute_group/update', 'token=' . $this->session->data['token'] . '&option_id=' . $result['option']['option_id'] . $url, 'SSL')
			);
            $attribute_code =$this->model_catalog_attribute_option_to_group->getOptionAttributeCode($result['option']['option_id']);
			$this->data['option_attribute_groups'][] = array(
				'option_id'    => $result['option']['option_id'],
                'attribute_code' =>$attribute_code['attribute_code'],
				'name'            => $result['option']['option_value'],
				'to_group' => $result['attribute_group'],
				'selected'        => (isset($this->request->post['selected']) && in_array($result['option']['option_id'], $this->request->post['selected']))?true:false,
				'action'          => $action
			);
		}	
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_attribute_group'] = $this->language->get('column_attribute_group');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['token'] = $this->session->data['token'];
        $this->data['filter_name'] = $filter_name;

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


		$url = '';
		$pagination = new Pagination();
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/option_attribute_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'catalog/option_attribute_group_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_attribute_group'] = $this->language->get('entry_attribute_group');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_attribute_code'] = $this->language->get('column_attribute_code');
		$this->data['entry_action_attribute_value'] = $this->language->get('column_action_attribute_value');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/option_attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['option_id'])) {
			$this->data['action'] = $this->url->link('catalog/option_attribute_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/option_attribute_group/update', 'token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/option_attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$option_to_attribute_group__info = $this->model_catalog_attribute_option_to_group->getOptionAttributeGroup($this->request->get['option_id']);
		}
		$this->load->model('localisation/language');
        $this->data['option_to_attribute_group__info'] =$option_to_attribute_group__info;
		$attribute_code =$this->model_catalog_attribute_option_to_group->getOptionAttributeCode($this->request->get['option_id']);
        $attribute_value =$this->model_catalog_attribute_option_to_group->getOptionValue($this->request->get['option_id']);
        $this->data['attribute_code'] =$attribute_code['attribute_code'];
        $this->data['attribute_value'] =$attribute_value['option_value'];   
		
		$this->template = 'catalog/option_attribute_group_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$this->error) { 
			return true;
		} else {
			return false;
		}
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/attribute');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$json = array();

			$results = $this->model_catalog_attribute->getAttributes($data);

			foreach ($results as $result) {
				$json[] = array(
					'attribute_id'    => $result['attribute_id'], 
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
				);		
			}		
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}		  
}
?>