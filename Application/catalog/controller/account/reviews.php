<?php 
class ControllerAccountReviews extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/reviews');
		$this->load->model('account/reviews');
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		$customer_id =$this->session->data['customer_id'];

		$this->document->setTitle($this->language->get('heading_title'));	

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/reviews'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->data['text_empty'] = sprintf($this->language->get('text_empty'),$this->url->link('common/home'));
		$this->data['text_riviews_verify'] = $this->language->get('text_riviews_verify');	

		
		
		$this->data['column_products'] = $this->language->get('column_products');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_gained_points'] = $this->language->get('column_gained_points');
		$this->data['column_reviews'] = $this->language->get('column_reviews');
        $this->data['text_write_reviews'] = $this->language->get('text_write_reviews');
	

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$limit =12;
		$start =($page-1)*$limit;
		
		$this->data['reviews'] = array();
		$data =array(
			'sort'=>'date_added',
			'start'=>$start,
			'limit'=>$limit
		);
		$reviews = $this->model_account_reviews->getReviews($customer_id,$data);
		foreach ($reviews as  $review) {
			$product_info = $this->model_catalog_product->getProduct($review['product_id']);

			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], 85, 85);
				} else {
					$image = false;
				}
                if($review['status']==0){
                    $status_text =$this->language->get('column_reviews_status_0');
                }elseif($review['status']==1){
                    $status_text =$this->language->get('column_reviews_status_1');
                }
				$this->data['reviews'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'text'			=>$review['text'],
                    'status'			=>$review['status'],
                    'status_text'			=>$status_text,
  					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}	

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		$total =$this->model_account_reviews->getTotalReviews($customer_id);
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/reviews', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
        
        //取得最后下单的2个订单，
        $this->load->model('account/order');
        $last_complete_orders = $this->model_account_order->getLastCustomerNoReviewOrders(2);
        if($last_complete_orders){
            $_order_text = '';
            foreach($last_complete_orders as $item){
                $_order_text .= "<a href='" . $this->url->link('account/order/info','order_id='.$item['order_id']). "'>".$item['order_number']."</a>,";
            }
            $_order_text = substr($_order_text,0,-1);
            $text_order_no_reviews = $this->language->get('text_order_no_reviews');
            $text_order_no_reviews = sprintf($text_order_no_reviews,$_order_text);
            $this->data['text_order_no_reviews'] = $text_order_no_reviews;
        }
        
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/reviews.tpl';
		} else {
			$this->template = 'default/template/account/reviews.tpl';
		}

		$this->children = array(
			'account/menu',
			'account/right_top',
			'account/right_bottom',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());		
	}
}
?>