<?php 
class ControllerCatalogAttributeGroup extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_group->addAttributeGroup($this->request->post);

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

			$this->redirect($this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_group->editAttributeGroup($this->request->get['attribute_group_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/attribute_group/update', 'token=' . $this->session->data['token'] . '&attribute_group_id='.$this->request->get['attribute_group_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/attribute_group');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_attribute_group->deleteAttributeGroup($attribute_group_id);
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

			$this->redirect($this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/attribute_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/attribute_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['attribute_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$attribute_group_total = $this->model_catalog_attribute_group->getTotalAttributeGroups();

		$results = $this->model_catalog_attribute_group->getAttributeGroups($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/attribute_group/update', 'token=' . $this->session->data['token'] . '&attribute_group_id=' . $result['attribute_group_id'] . $url, 'SSL')
			);

			$this->data['attribute_groups'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'code'               => $result['attribute_group_code'],
				'sort_order'         => $result['sort_order'],
				'selected'           => isset($this->request->post['selected']) && in_array($result['attribute_group_id'], $this->request->post['selected']),
				'action'             => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $attribute_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/attribute_group_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

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
			'href'      => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['attribute_group_id'])) {
			$this->data['action'] = $this->url->link('catalog/attribute_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/attribute_group/update', 'token=' . $this->session->data['token'] . '&attribute_group_id=' . $this->request->get['attribute_group_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['attribute_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($this->request->get['attribute_group_id']);
		}
		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_group_info)) {
			$this->data['sort_order'] = $attribute_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		if (isset($this->request->post['attribute_group_code'])) {
			$this->data['attribute_group_code'] = $this->request->post['attribute_group_code'];
		} elseif (!empty($attribute_group_info)) {
			$this->data['attribute_group_code'] = $attribute_group_info['attribute_group_code'];
		} else {
			$this->data['attribute_group_code'] = '';
		}



        $attribute_group_id = $this->request->request['attribute_group_id'];
        $attribute_group_id = intval($attribute_group_id);
        $attribute_list = $this->model_catalog_attribute_group->getAttributeListByGroup($attribute_group_id);
        $this->data['attribute_list'] = $attribute_list;
        
        $range_price = $this->model_catalog_attribute_group->getGroupPriceRange($attribute_group_id);
        
        $this->data['range_price_list'] = $range_price;


		$this->load->model('catalog/attribute');
		$all_attributes = $this->model_catalog_attribute->getAttributes();

		$all_attribute_option = "";
		foreach($all_attributes as $att){
			if($att['value_type'] != 'text'){
				$all_attribute_option .= "<option value='" .$att['attribute_id']. "' value_type='".$att['value_type']."'>".$att['attribute_code']."</option>";
			}

		}
		$this->data['all_attribute_option'] = $all_attribute_option ;


        
		$this->template = 'catalog/attribute_group_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$language_id = $this->config->get('config_language_id');
		if ((utf8_strlen($this->request->post['attribute_group_code']) < 1) || (utf8_strlen($this->request->post['attribute_group_code']) > 64)) {
			$this->error['name'][$language_id] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/attribute');

		foreach ($this->request->post['selected'] as $attribute_group_id) {
			$attribute_total = $this->model_catalog_attribute->getTotalAttributesByAttributeGroupId($attribute_group_id);

			if ($attribute_total) {
				$this->error['warning'] = sprintf($this->language->get('error_attribute'), $attribute_total);
			}
		}

		if (!$this->error) { 
			return true;
		} else {
			return false;
		}
	}

  
    public function filter(){
        $this->language->load('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');
        $this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

			$attribute_id = $this->request->request['attribute_id'];
			$attribute_id = intval($attribute_id);
			$attributte = $this->model_catalog_attribute->getAttribute($attribute_id);

			if($attributte['value_type'] == 'numerical'){
				$this->model_catalog_attribute_group->addGroupAttributeNumericalRangeFilter($this->request->get['attribute_group_id'],$attribute_id, $this->request->post);
			}



			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';



			$this->redirect($this->url->link('catalog/attribute_group/filter', 'token=' . $this->session->data['token'] . '&attribute_group_id='.$this->request->get['attribute_group_id']."&attribute_id=".$attribute_id, 'SSL'));
		}

		

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

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
			'href'      => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);



		$this->data['cancel'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
	
		


        $attribute_group_id = $this->request->request['attribute_group_id'];
        $attribute_group_id = intval($attribute_group_id);
        $attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($attribute_group_id);
        $this->data['attributte_group'] = $attribute_group_info;
        
        $attribute_id = $this->request->request['attribute_id'];
        $attribute_id = intval($attribute_id);
        $attributte = $this->model_catalog_attribute->getAttribute($attribute_id);

		$attribute_description = $this->model_catalog_attribute->getAttributeDescriptions($attribute_id);
		$this->data['attribute_description'] = $attribute_description;



        
        $this->data['attributte'] = $attributte;

        $option_list = $this->model_catalog_attribute->getAttributeOptions($attribute_id);

        
        $this->data['attribute_option'] = $option_list;



		$group_attribute = $this->model_catalog_attribute_group->getGroupAttribute($attribute_group_id,$attribute_id);
		$this->data['group_attribute'] = $group_attribute;

		if($attributte['value_type'] == 'numerical'){
			$range_numerical_list = $this->model_catalog_attribute_group->getGroupAttributeNumericalRangeFilter($attribute_group_id,$attribute_id);
			$this->data['range_numerical_list'] = $range_numerical_list;
		}
        
		$this->template = 'catalog/attribute_group_filter.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
    }



	public function deleteAttrGroupAttribute(){
		$attribute_group_id = $this->request->get['attribute_group_id'];
		$attribute_id = $this->request->get['attribute_id'];
		$this->load->model('catalog/attribute_group');
		$this->model_catalog_attribute_group->deleteAttributeGroupUnderAttribute($attribute_group_id,$attribute_id);

		$this->redirect($this->url->link('catalog/attribute_group/update', 'token=' . $this->session->data['token'] . '&attribute_group_id='.$this->request->get['attribute_group_id'], 'SSL'));

	}
}
?>