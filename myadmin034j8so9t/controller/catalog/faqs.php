<?php
class ControllerCatalogFaqs extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		$this->getList();
	} 

	public function insert() {
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_faqs->addFaq($this->request->post);
			
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

			$this->redirect($this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_faqs->editFaq($this->request->get['faq_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}


	public function reply() {
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRplyForm()) {
			$this->model_catalog_faqs->editReply($this->request->get['faq_id'],$this->request->post);

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

			$this->redirect($this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getReplyForm();
	}
	public function reply_delete() {
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		if ($this->validateDelete()) {
			$this->model_catalog_faqs->deleteReply($this->request->get['faq_id']);

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

			$this->redirect($this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}

	public function delete() { 
		$this->language->load('catalog/faqs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/faqs');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $faqs_id) {
				$this->model_catalog_faqs->deleteReview($faqs_id);
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

			$this->redirect($this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->request->get['filter_id'])) {
				$filter['filter_id'] = $this->request->get['filter_id'];
		} else {
				$filter['filter_id']= null;
		}
		if (isset($this->request->get['filter_sku'])) {
				$filter['filter_sku'] = $this->request->get['filter_sku'];
		} else {
				$filter['filter_sku']= null;
		}
        if (isset($this->request->get['filter_email'])) {
				$filter['filter_email'] = $this->request->get['filter_email'];
		} else {
				$filter['filter_email']= null;
		}
		if (isset($this->request->get['filter_author'])) {
				$filter['filter_author'] = $this->request->get['filter_author'];
		} else {
				$filter['filter_author']= null;
		}
		if (isset($this->request->get['filter_store_id'])) {
				$filter['filter_store_id'] = $this->request->get['filter_store_id'];
		} else {
				$filter['filter_store_id']= null;
		}
		if (isset($this->request->get['filter_pass'])) {
				$filter['filter_pass'] = $this->request->get['filter_pass'];
		} else {
				$filter['filter_pass']= null;
		}
		if (isset($this->request->get['filter_reply'])) {
				$filter['filter_reply'] = $this->request->get['filter_reply'];
		} else {
				$filter['filter_reply']= null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'f.faq_id';
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
		foreach($filter as $key=>$filter_list){
			if($filter_list){
				$url .= '&'.$key.'=' . $filter_list;
			}
			
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/faqs/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/faqs/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('catalog/faqs/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['faqs'] = array();
		$this->data['filter'] = $filter;
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$data =array_merge($data,$filter);
		$faqs_total = $this->model_catalog_faqs->getTotalFaqs($data);

		$results = $this->model_catalog_faqs->getFaqs($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/faqs/update', 'token=' . $this->session->data['token'] . '&faq_id=' . $result['faq_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => $this->language->get('text_reply'),
				'href' => $this->url->link('catalog/faqs/reply', 'token=' . $this->session->data['token'] . '&faq_id=' . $result['faq_id'] . $url, 'SSL')
			);
			
		    $this->load->model('setting/store');
		    $store_info =$this->model_setting_store->getStore($result['store_id']);
			$this->data['faqs'][] = array(
				'faq_id'  => $result['faq_id'],
                'store_id'     => $result['store_id'],
				'sku'       => $result['model'],
				'customer_id'     => $result['customer_id'],
				'author'     => $result['author'],
				'faq_text'     => $result['faq_text'],
				'add_time'     => $result['add_time'],
				'store_code'     => isset($store_info['name'])?$store_info['name']:'default',
				'is_pass'     => $result['is_pass'],
				'is_reply' =>$result['is_reply'] ,
				'selected'   => isset($this->request->post['selected']) && in_array($result['faqs_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		/*
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_rating'] = $this->language->get('column_rating');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		*/	
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

		$this->data['sort_faq_id'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . '&sort=f.faq_id' . $url, 'SSL');
		$this->data['sort_is_pass'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . '&sort=f.is_pass' . $url, 'SSL');
		$this->data['sort_store_id'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . '&sort=f.store_id' . $url, 'SSL');
		$this->data['sort_is_reply'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . '&sort=f.is_reply' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . '&sort=f.add_time' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $faqs_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/faqs_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['product'])) {
			$this->data['error_product'] = $this->error['product'];
		} else {
			$this->data['error_product'] = '';
		}
        if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['author'])) {
			$this->data['error_author'] = $this->error['author'];
		} else {
			$this->data['error_author'] = '';
		}

		if (isset($this->error['text'])) {
			$this->data['error_text'] = $this->error['text'];
		} else {
			$this->data['error_text'] = '';
		}
		if (isset($this->error['reply_text'])) {
			$this->data['error_reply_text'] = $this->error['reply_text'];
		} else {
			$this->data['error_reply_text'] = '';
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
			'href'      => $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['faq_id'])) { 
			$this->data['action'] = $this->url->link('catalog/faqs/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/faqs/update', 'token=' . $this->session->data['token'] . '&faq_id=' . $this->request->get['faq_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['faq_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$faq_info = $this->model_catalog_faqs->getFaq($this->request->get['faq_id']);
            
		}

		//得到语言项
		$this->load->model('setting/store');
		$this->data['stores'] =$this->model_setting_store->getStores();
		$this->data['token'] = $this->session->data['token'];

		$this->load->model('catalog/product');
        
        if (!empty($faq_info)) {
			$this->data['customer_email'] = $faq_info['email'];
		} else {
			$this->data['customer_email'] = '';
		}
		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($faq_info)) {
			$this->data['product_id'] = $faq_info['product_id'];
		} else {
			$this->data['product_id'] = '';
		}
        if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (!empty($faq_info)) {
			$this->data['title'] = $faq_info['faq_title'];
		} else {
			$this->data['title'] = '';
		}
		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($faq_info)) {
			$this->data['store_id'] = $faq_info['store_id'];
		} else {
			$this->data['store_id'] = '';
		}

		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (!empty($faq_info)) {
			$this->data['author'] = $faq_info['author'];
		} else {
			$this->data['author'] = '';
		}

		if (isset($this->request->post['text'])) {
			$this->data['text'] = $this->request->post['text'];
		} elseif (!empty($faq_info)) {
			$this->data['text'] = $faq_info['faq_text'];
		} else {
			$this->data['text'] = '';
		}

		if (isset($this->request->post['reply_text'])) {
			$this->data['reply_text'] = $this->request->post['reply_text'];
		} elseif (!empty($faq_info)) {
			$this->data['reply_text'] = $faq_info['reply_text'];
		} else {
			$this->data['reply_text'] = '';
		}
		if (isset($this->request->post['is_pass'])) {
			$this->data['is_pass'] = $this->request->post['is_pass'];
		} elseif (!empty($faq_info)) {
			$this->data['is_pass'] = $faq_info['is_pass'];
		} else {
			$this->data['is_pass'] = 1;
		}
		 if (!empty($faq_info)) {
			$this->data['is_reply'] = $faq_info['is_reply'];
		} else {
			$this->data['is_reply'] = 0;
		}

		$this->template = 'catalog/faqs_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getReplyForm() {
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['reply_text'])) {
			$this->data['error_reply_text'] = $this->error['reply_text'];
		} else {
			$this->data['error_reply_text'] = '';
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
			'href'      => $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		
		$this->data['delete'] = $this->url->link('catalog/faqs/reply_delete', 'token=' . $this->session->data['token'] . '&faq_id=' . $this->request->get['faq_id'] . $url, 'SSL');
		$this->data['action'] = $this->url->link('catalog/faqs/reply', 'token=' . $this->session->data['token'] . '&faq_id=' . $this->request->get['faq_id'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('catalog/faqs', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['faq_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$faq_info = $this->model_catalog_faqs->getFaq($this->request->get['faq_id']);
			$reply_info = $this->model_catalog_faqs->getReply($this->request->get['faq_id']);
		}

		//得到语言项
		$this->load->model('localisation/language');
		$this->data['languages'] =$this->model_localisation_language->getLanguages();
		$this->data['token'] = $this->session->data['token'];

		$this->load->model('catalog/product');


		if (!empty($faq_info)) {
			$this->data['faq_text'] = $faq_info['faq_text'];
		} else {
			$this->data['faq_text'] = '';
		}

		if (isset($this->request->post['reply_text'])) {
			$this->data['reply_text'] = $this->request->post['reply_text'];
		} elseif (!empty($reply_info)) {
			$this->data['reply_text'] = $reply_info['reply_text'];
		} else {
			$this->data['reply_text'] = '';
		}

		$this->template = 'catalog/faqs_reply_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
        $this->load->model('catalog/product');
		if (!$this->user->hasPermission('modify', 'catalog/faqs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}
        $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
        if (!$product_info) {
			$this->error['product'] = $this->language->get('error_no_product');
		}

		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}
        if (utf8_strlen($this->request->post['title']) < 1||utf8_strlen($this->request->post['title'])>100) {
			$this->error['title'] = $this->language->get('error_title');
		}
		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateRplyForm() {
		if (!$this->user->hasPermission('modify', 'catalog/faqs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (utf8_strlen($this->request->post['reply_text']) < 1) {
			$this->error['reply_text'] = $this->language->get('error_reply_text');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/faqs')) {
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