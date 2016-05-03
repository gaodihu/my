<?php 
class ControllerAccountWishList extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/wishlist');
		$this->load->model('account/wishlist');
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		$customer_id =$this->session->data['customer_id'];
		/*
		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}
		*/
		if (isset($this->request->get['remove'])) {
			$remove_id=$this->request->get['remove'];
			
			$this->model_account_wishlist->deleteWishlist($remove_id,$customer_id);
			$this->session->data['success'] = $this->language->get('text_remove');

			$this->redirect($this->url->link('account/wishlist'));
		}
	
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
			'href'      => $this->url->link('account/wishlist'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');	

		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_add_cart'] = $this->language->get('text_add_cart');
		$this->data['text_as_low_as'] = $this->language->get('text_as_low_as');
		/*
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_stock'] = $this->language->get('column_stock');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');
		*/
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_remove'] = $this->language->get('button_remove');

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
		$limit =10;
		$start =($page-1)*$limit;
		
		$this->data['products'] = array();
		$wishlists = $this->model_account_wishlist->getWishlists($customer_id,$start,$limit);
		foreach ($wishlists as  $product) {
			$product_info = $this->model_catalog_product->getProduct($product['product_id']);

			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = 0;
				}
				$discounts = $this->model_catalog_product->getProductDiscounts($product_info['product_id']);
				if($discounts){
					$count = count($discounts);
					$as_low_as_price = $this->currency->format($this->tax->calculate($discounts[$count-1]['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
				else{
					$as_low_as_price =false;
				}
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'rating'     => $rating,
					'reviews'    => (int)$product_info['reviews'],
					'as_low_as_price' =>$as_low_as_price,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $product['wish_id'])
				);
			} else {
				unset($this->session->data['wishlist'][$key]);
			}
		}	

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		$total =$this->model_account_wishlist->getTotalWishlists($customer_id);
		$pagination = new Pagination();
		$pagination->total = $total['total'];
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/wishlist', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/wishlist.tpl';
		} else {
			$this->template = 'default/template/account/wishlist.tpl';
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

	public function add() {
		if($this->customer->isLogged()){
			$this->language->load('account/wishlist');

			$json = array();

			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else {
				$product_id = 0;
			}
			$customer_id =$this->session->data['customer_id'];
			$this->load->model('account/wishlist');
			$data =array(
				'customer_id'=>	$customer_id,
				'product_id'=>	$product_id,
			);
			$wish_info = $this->model_account_wishlist->getWishlist($customer_id,$product_id);
			if($wish_info){
				$this->model_account_wishlist->deleteWishlist($wish_info['wish_id'],$customer_id);
				$json['error'] = 2;
				$json['message'] = $this->language->get('text_repeat');
			}
			else{
				$this->model_account_wishlist->addWishlist($data);
				$json['error'] =0;
				$json['message'] =sprintf($this->language->get('text_success'),$this->url->link('account/wishlist', 'SSL'));
			}
			
				
		}
		else{
			$json['error'] =1;
			$json['erro_type'] =1;
		}
		$this->response->setOutput(json_encode($json));
	}	
}
?>