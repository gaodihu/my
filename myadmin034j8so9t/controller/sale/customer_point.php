<?php    
class ControllerSaleCustomerPoint extends Controller { 
	private $error = array();
    private $point_status = array(
        '0'    =>'pending',
        '1'    =>'Available '
    );
	public function index() {
		//$this->language->load('sale/customer_point');

		$this->document->setTitle('Customer_points');

		$this->load->model('sale/customer_point');

		$this->getList();
	}

    public function delete() {
		//$this->language->load('sale/customer');

		$this->document->setTitle('Customer_points');

		$this->load->model('sale/customer_point');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_point_id) {
				$this->model_sale_customer_point->deleteCustomerPoint($customer_point_id);
			}

			$this->session->data['success'] = "删除成功！";

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . urlencode(html_entity_decode($this->request->get['filter_customer_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->redirect($this->url->link('sale/customer_point', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
	protected function getList() {
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_order_number'])) {
			$filter_order_number = $this->request->get['filter_order_number'];
		} else {
			$filter_order_number = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer_reward_id'; 
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

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . urlencode(html_entity_decode($this->request->get['filter_customer_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_number'])) {
			$url .= '&filter_order_number=' . $this->request->get['filter_order_number'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => 'Customer Points',
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		$this->data['customer_points'] = array();
        
        $this->data['delete'] = $this->url->link('sale/customer_point/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data = array(
			'filter_customer_id'              => $filter_customer_id, 
			'filter_email'             => $filter_email, 
			'filter_order_number' => $filter_order_number, 
			'filter_status'            => $filter_status, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$customer_point_total = $this->model_sale_customer_point->getTotalCustomerPoints($data);

		$results = $this->model_sale_customer_point->getCustomerPoints($data);
		foreach ($results as $result) {
			$this->data['customer_points'][] = array(
                'customer_reward_id'    => $result['customer_reward_id'],
				'customer_id'    => $result['customer_id'],
                'order_id'    => $result['order_id'],
				'order_number'           => $result['order_number'],
				'email'          => $result['email'],
				'points' => $result['points'],
				'status'         => $result['status'],
                'status_name'         => $this->point_status[$result['status']],
                'selected'       => isset($this->request->post['selected']) && in_array($result['customer_reward_id'], $this->request->post['selected']),
				'points_spent'             => $result['points_spent']
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');	
		$this->data['text_select'] = $this->language->get('text_select');	
		$this->data['text_default'] = $this->language->get('text_default');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_login'] = $this->language->get('column_login');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . urlencode(html_entity_decode($this->request->get['filter_customer_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_number'])) {
			$url .= '&filter_order_number=' . $this->request->get['filter_order_number'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_customer_id'] = $this->url->link('sale/customer_point', 'token=' . $this->session->data['token'] . '&sort=cr.customer_id' . $url, 'SSL');
		$this->data['sort_order_number'] = $this->url->link('sale/customer_point', 'token=' . $this->session->data['token'] . '&sort=o.order_number' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/customer_point', 'token=' . $this->session->data['token'] . '&sort=cr.status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . urlencode(html_entity_decode($this->request->get['filter_customer_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_number'])) {
			$url .= '&filter_order_number=' . $this->request->get['filter_order_number'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_point_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer_point', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_order_number'] = $filter_order_number;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/customer_points.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


    protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_point')) {
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