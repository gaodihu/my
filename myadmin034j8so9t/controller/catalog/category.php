<?php 
class ControllerCatalogCategory extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$post_info = $this->request->post;
			$parent_id =$this->request->get['p_id'];
			$post_info['parent_id'] = $parent_id;
			$this->model_catalog_category->addCategory($post_info);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			/*
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
			*/
			$link = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url,'SSL');
			$link =htmlspecialchars_decode($link);
			echo "<script type='text/javascript'> parent.top.location.href= '$link'; </script> ";
		}

		$this->getForm();
	}
	
	public function update() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$link = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url,'SSL');
			$link =htmlspecialchars_decode($link);
			//$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, ));
			//echo "<script> parent.top.location.href= '".HTTP_SERVER."/index.php?route=catalog/category&token=".$this->session->data['token']."'; </script> ";
			echo "<script type='text/javascript'> parent.top.location.href= '$link'; </script> ";
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');
		if (isset($this->request->post['click_id']) && $this->validateDelete()) {
			/*
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}
		*/
			$click_id_arr = explode(',',$this->request->post['click_id']);
			foreach ($click_id_arr as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function repair() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if ($this->validateRepair()) {
			$this->model_catalog_category->repairCategories();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();	
	}

	protected function getList() {
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
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['cat_tree_json_url'] = htmlspecialchars_decode($this->url->link('catalog/category/getCatTreeJson', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		$this->data['categories'] = array();

		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$category_total = $this->model_catalog_category->getTotalCategories();
		$catTree = $this->model_catalog_category->getCatTreeJson('false');
		$this->data['catTree']=$catTree;
		$results = $this->model_catalog_category->getCategories($data);
		
		/*
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
			);

			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
				'child'  => $result['child']
			);
		}
		*/
		
		$this->data['categories']=$results;
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_repair'] = $this->language->get('button_repair');

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

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'catalog/category_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->render());
	}
	

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		//update category
		if (isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'], 'SSL');
		//insert  	subcategory
		} elseif (isset($this->request->get['p_id'])) {
			$this->data['action'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'] . '&p_id=' . $this->request->get['p_id'], 'SSL');
		}
		else{
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
		}
		/*
		if (isset($this->request->get['category_id']) &&!empty($this->request->get['category_id'])&& ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_pro = $this->model_catalog_category->getCatPro($this->request->get);
			$this->data['pro_info'] = $category_pro;
			$page =isset($this->request->get['page'])?$this->request->get['page']:1;
			$pagination = new Pagination();
			$pagination->total = 45;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = "javascript:gopage($page)";
			//$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . "&page={page}", 'SSL');
			$this->data['pagination'] = $pagination->render();
		}
		*/
		$this->data['token'] = $this->session->data['token'];
		if (isset($this->request->get['category_id'])) {
			$this->data['category_id'] = $this->request->get['category_id'];
		}
		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

		if (isset($this->request->post['path'])) {
			$this->data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$this->data['path'] = $category_info['path'];
		} else {
			$this->data['path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
            $this->data['parent_name']=$this->request->post['path'];
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
            $parents_info=$this->model_catalog_category->getCategory($this->data['parent_id']);
            $this->data['parent_name'] =$parents_info['name'];
		} else {
			$this->data['parent_id'] = 0;
            $this->data['parent_name']='';
		}
		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif (isset($this->request->get['category_id'])) {		
			$filters = $this->model_catalog_category->getCategoryFilters($this->request->get['category_id']);
		} else {
			$filters = array();
		}

		$this->data['category_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}	

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$this->data['category_store'] = array(0);
		}			

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['url_path'])) {
			$this->data['url_path'] = $this->request->post['url_path'];
		} elseif (!empty($category_info)) {
			$this->data['url_path'] = $category_info['url_path'];
		} else {
			$this->data['url_path'] = '';
		}

		if (isset($this->request->post['bg_image'])) {
			$this->data['bg_image'] = $this->request->post['bg_image'];
		} elseif (!empty($category_info)) {
			$this->data['bg_image'] = $category_info['bg_image'];
		} else {
			$this->data['bg_image'] = '';
		}

		if (isset($this->request->post['seo_image'])) {
			$this->data['seo_image'] = $this->request->post['seo_image'];
		} elseif (!empty($category_info)) {
			$this->data['seo_image'] = $category_info['seo_image'];
		} else {
			$this->data['seo_image'] = '';
		}

		if (isset($this->request->post['small_image'])) {
			$this->data['small_image'] = $this->request->post['small_image'];
		} elseif (!empty($category_info)) {
			$this->data['small_image'] = $category_info['small_image'];
		} else {
			$this->data['small_image'] = '';
		}


		$this->load->model('tool/image');

		if (isset($this->request->post['bg_image']) && file_exists(DIR_IMAGE . $this->request->post['bg_image'])) {
			$this->data['bg_thumb'] = $this->model_tool_image->resize($this->request->post['bg_image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['bg_image'] && file_exists(DIR_IMAGE . $category_info['bg_image'])) {
			$this->data['bg_thumb'] = $this->model_tool_image->resize($category_info['bg_image'], 100, 100);
		} else {
			$this->data['bg_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		if (isset($this->request->post['seo_image']) && file_exists(DIR_IMAGE . $this->request->post['seo_image'])) {
			$this->data['seo_thumb'] = $this->model_tool_image->resize($this->request->post['seo_image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['seo_image'] && file_exists(DIR_IMAGE . $category_info['seo_image'])) {
			$this->data['seo_thumb'] = $this->model_tool_image->resize($category_info['seo_image'], 100, 100);
		} else {
			$this->data['seo_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		if (isset($this->request->post['small_image']) && file_exists(DIR_IMAGE . $this->request->post['small_image'])) {
			$this->data['small_thumb'] = $this->model_tool_image->resize($this->request->post['small_image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['small_image'] && file_exists(DIR_IMAGE . $category_info['small_image'])) {
			$this->data['small_thumb'] = $this->model_tool_image->resize($category_info['small_image'], 100, 100);
		} else {
			$this->data['small_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$this->data['top'] = $category_info['top'];
		} else {
			$this->data['top'] = 0;
		}

		if (isset($this->request->post['hot'])) {
			$this->data['hot'] = $this->request->post['hot'];
		} elseif (!empty($category_info)) {
			$this->data['hot'] = $category_info['hot'];
		} else {
			$this->data['hot'] = 0;
		}

		if (isset($this->request->post['attrbute_group'])) {
			$this->data['attribute_group_id'] = $this->request->post['attrbute_group'];
		} elseif (!empty($category_info)) {
			$this->data['attribute_group_id'] = $category_info['attribute_group_id'];
		} else {
			$this->data['attribute_group_id'] = '';
		}

		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$this->data['column'] = $category_info['column'];
		} else {
			$this->data['column'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->load->model('catalog/attribute_group');
		$results = $this->model_catalog_attribute_group->getAttributeGroups();
		$this->data['cat_attribute_group'] =$results;
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/category_form.tpl';
		$this->children = array(
				'common/head',
				'common/footer'
			);
	
		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
            if ((utf8_strlen($value['title']) < 2) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
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
			$this->load->model('catalog/category');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_category->getDetailCategories($data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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

	function AjaxGetCatPro(){
		if (isset($this->request->get['category_id']) &&!empty($this->request->get['category_id'])&& ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $filter_sku =isset($this->request->get['filter_sku'])?$this->request->get['filter_sku']:'';
            $filter_name =isset($this->request->get['filter_name'])?$this->request->get['filter_name']:'';
			$this->load->model('catalog/category');
			$category_pro = $this->model_catalog_category->getCatPro($this->request->get);
			$this->data['pro_info'] = $category_pro;
            $this->data['filter_sku'] = $filter_sku;
            $this->data['filter_name'] = $filter_name;
            if(isset($this->request->get['sort_name'])){
                $sort_name =$this->request->get['sort_name'];
                $this->data['sort_name'] = $sort_name;
            }
            else{
                $this->data['sort_name'] = "p.product_id";
            }
            if(isset($this->request->get['sort_order'])){
                $sort_order =$this->request->get['sort_order'];
                 $this->data['sort_order'] = $sort_order;
                if($sort_order=='ASC'){
                    $this->data['order'] = "DESC";
                }
                else{
                    $this->data['order'] = "ASC";
                }
            }else{
                 $this->data['sort_order'] = "DESC";
                 $this->data['order'] = "ASC";
            }
			$current_page =isset($this->request->get['page'])?(int)$this->request->get['page']:'1';
			$pagination = new Pagination();
            $filter =array();
            if($filter_sku){
                $filter['filter_sku'] =$filter_sku;
            }
            if($filter_name){
                $filter['filter_name'] =$filter_name;
            }
			$pagination->total = $this->model_catalog_category->getTotalCatPro($this->request->get['category_id'],$filter);
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->page = $current_page;
			$pagination->url = "javascript:void(0)";
			$this->data['pagination'] =$pagination->render();
			$totalPage =ceil($pagination->total/$pagination->limit) ;
			$this->data['totalPage'] = $totalPage;
			$this->data['current_page'] = (int)$current_page;
			$this->data['token']=$this->session->data['token'];
			$this->template = 'lib/catalog/catalog_product_form.tpl';
			$this->response->setOutput($this->render());
		}
	}
}
?>