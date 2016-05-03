<?php 
class ControllerModuleHomeProduct extends Controller {
	private $error = array();
	private $product_type =array('1'=>'Special','2'=>'Best Sellers','3'=>'New Arrivals');
	public function index() {
		$this->language->load('module/home_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/home_product');
		$this->getList();
	}

	public function insert() {
		$this->language->load('module/home_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/home_product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_home_product->addHomeProduct($this->request->post);

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
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' .$this->request->get['filter_type'];
			} 
			 if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			 } 
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} 
			if (isset($this->request->get['filter_start_time_from'])) {
				$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
			} 
			if (isset($this->request->get['filter_start_time_to'])) {
				$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
			} 
			if (isset($this->request->get['filter_end_time_from'])) {
				$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
			} 
			if (isset($this->request->get['filter_end_time_to'])) {
				$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
			} 

			$this->redirect($this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('module/home_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/home_product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//var_dump($this->request->post);exit;
			$this->model_module_home_product->editHomeProduct($this->request->get['rec_id'], $this->request->post);

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
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
			} 
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			} 
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} 
			if (isset($this->request->get['filter_start_time_from'])) {
				$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
			} 
			if (isset($this->request->get['filter_start_time_to'])) {
				$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
			} 
			if (isset($this->request->get['filter_end_time_from'])) {
				$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
			} 
			if (isset($this->request->get['filter_end_time_to'])) {
				$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
			} 

			$this->redirect($this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('module/home_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/home_product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $banner_id) {
				$this->model_module_home_product->deleteHomeProduct($banner_id);
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
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
			} 
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			} 
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} 
			if (isset($this->request->get['filter_start_time_from'])) {
				$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
			} 
			if (isset($this->request->get['filter_start_time_to'])) {
				$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
			} 
			if (isset($this->request->get['filter_end_time_from'])) {
				$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
			} 
			if (isset($this->request->get['filter_end_time_to'])) {
				$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
			} 

			$this->redirect($this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'hp.type';
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = 0;
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
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = '';
		}
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_start_time_from'])) {
			$filter_start_time_from = $this->request->get['filter_start_time_from'];
		} else {
			$filter_start_time_from = '';
		}
		if (isset($this->request->get['filter_start_time_to'])) {
			$filter_start_time_to = $this->request->get['filter_start_time_to'];
		} else {
			$filter_start_time_to = '';
		}
		if (isset($this->request->get['filter_end_time_from'])) {
			$filter_end_time_from = $this->request->get['filter_end_time_from'];
		} else {
			$filter_end_time_from = '';
		}
		if (isset($this->request->get['filter_end_time_to'])) {
			$filter_end_time_to = $this->request->get['filter_end_time_to'];
		} else {
			$filter_end_time_to = '';
		}


		$url = '';
		
		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		} 
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		} 
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_start_time_from'])) {
			$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
		} 
		if (isset($this->request->get['filter_start_time_to'])) {
			$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
		} 
		if (isset($this->request->get['filter_end_time_from'])) {
			$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
		} 
		if (isset($this->request->get['filter_end_time_to'])) {
			$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
		} 

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('module/home_product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('module/home_product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['home_products'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'filter_product_id' =>$filter_product_id,
			'filter_type' =>$filter_type,
			'filter_model' =>$filter_model,
			'filter_name' =>$filter_name,
			'filter_start_time_from' =>$filter_start_time_from,
			'filter_start_time_to' =>$filter_start_time_to,
			'filter_end_time_from' =>$filter_end_time_from,
			'filter_end_time_to' =>$filter_end_time_to,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$banner_total = $this->model_module_home_product->getTotalHomeProducts($filter_type);

		$results = $this->model_module_home_product->getHomeProducts($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/home_product/update', 'token=' . $this->session->data['token'] . '&rec_id=' . $result['rec_id'] . $url, 'SSL')
			);
			$this->data['home_products'][] = array(
				'rec_id' => $result['rec_id'],
				'product_id'      => $result['product_id'],
				'type'      => $result['type'],
				'model'      => $result['model'],
				'name'      => $result['name'],
				'type_name'      => $this->product_type[$result['type']] ,
				'start_time'      => $result['start_time'],
				'end_time'      => $result['end_time'],
				'sort_order'      => $result['sort_order'],	
				'selected'  => isset($this->request->post['selected']) && in_array($result['rec_id'], $this->request->post['selected']),	
				'action'    => $action
			);
		}

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['heading_title'] = $this->language->get('heading_title');

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
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		} 
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		} 
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_start_time_from'])) {
			$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
		} 
		if (isset($this->request->get['filter_start_time_to'])) {
			$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
		} 
		if (isset($this->request->get['filter_end_time_from'])) {
			$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
		} 
		if (isset($this->request->get['filter_end_time_to'])) {
			$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
		} 

		$this->data['sort_product_id'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . '&sort=product_id' . $url, 'SSL');
		$this->data['sort_type'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . '&sort=type' . $url, 'SSL');
		$this->data['sort_start_time'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . '&sort=start_time' . $url, 'SSL');
		$this->data['sort_end_time'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . '&sort=end_time' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		} 
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		} 
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_start_time_from'])) {
			$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
		} 
		if (isset($this->request->get['filter_start_time_to'])) {
			$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
		} 
		if (isset($this->request->get['filter_end_time_from'])) {
			$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
		} 
		if (isset($this->request->get['filter_end_time_to'])) {
			$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
		} 

		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['token'] = $this->session->data['token'];

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'module/home_product_list.tpl';
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
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_banner_code'] = $this->language->get('entry_banner_code');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_image'] = $this->language->get('entry_image');		
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_banner'] = $this->language->get('button_add_banner');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}
		if (isset($this->error['exit'])) {
			$this->data['error_exit'] = $this->error['exit'];
		} else {
			$this->data['error_exit'] = '';
		}
		if (isset($this->error['type'])) {
			$this->data['error_type'] = $this->error['type'];
		} else {
			$this->data['error_type'] = '';
		}
		if (isset($this->error['not_special'])) {
			$this->data['error_not_special'] = $this->error['not_special'];
		} else {
			$this->data['error_not_special'] = '';
		}
		


		if (isset($this->error['banner_image'])) {
			$this->data['error_banner_image'] = $this->error['banner_image'];
		} else {
			$this->data['error_banner_image'] = array();
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
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		} 
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		} 
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_start_time_from'])) {
			$url .= '&filter_start_time_from=' . $this->request->get['filter_start_time_from'];
		} 
		if (isset($this->request->get['filter_start_time_to'])) {
			$url .= '&filter_start_time_to=' . $this->request->get['filter_start_time_to'];
		} 
		if (isset($this->request->get['filter_end_time_from'])) {
			$url .= '&filter_end_time_from=' . $this->request->get['filter_end_time_from'];
		} 
		if (isset($this->request->get['filter_end_time_to'])) {
			$url .= '&filter_end_time_to=' . $this->request->get['filter_end_time_to'];
		} 

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['rec_id'])) { 
			$this->data['action'] = $this->url->link('module/home_product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/home_product/update', 'token=' . $this->session->data['token'] . '&rec_id=' . $this->request->get['rec_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/home_product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['product_types'] =$this->product_type;

		if (isset($this->request->get['rec_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$home_product_info = $this->model_module_home_product->getHomeProduct($this->request->get['rec_id']);
		}
		else{
			$home_product_info =array();
		}
		$this->data['home_product_info'] =$home_product_info;
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'module/home_product_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		$this->load->model('catalog/product');
		if (!$this->user->hasPermission('modify', 'module/home_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['product_id']) <0) || (utf8_strlen($this->request->post['product_id']) > 64)) {
			$this->error['product_id'] = $this->language->get('error_product_id');
		}
		if($this->request->post['type'] =='-1'){
			$this->error['type'] = $this->language->get('error_type');
		}

		if(!$this->error){
			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
			if(empty($product_info)){
				$this->error['exit'] = $this->language->get('error_exit');
			}
			if($product_info&&$this->request->post['type']=='1'){
				$special_info =$this->model_catalog_product->getProductSpecials($product_info['product_id']);
				if(empty($special_info)){
					$this->error['not_special'] = $this->language->get('not_special');
				}
				
			}
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/home_product')) {
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