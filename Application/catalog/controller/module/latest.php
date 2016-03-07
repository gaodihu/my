<?php
class ControllerModuleLatest extends Controller {
	protected function index() {
		$this->language->load('module/latest');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['more'] = $this->language->get('more');
		$this->data['lower'] = $this->language->get('lower');
		$this->data['off'] = $this->language->get('off');
		$this->data['button_cart'] = $this->language->get('button_cart');
				
		$this->load->model('module/home_product');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'sort_order',
			'order' => 'asc',
			'start' => 0,
			'limit' => 10
		);

		$results = $this->model_module_home_product->getHomeProduct(3,$data);
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$image = false;
			}
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
			$discounts = $this->model_catalog_product->getProductDiscounts($result['product_id']);
			if($discounts){
				$count = count($discounts);
                if($result['special']&&($result['special']<$discounts[$count - 1]['price'])){
                    $as_low_as_price = false;
                }
                else{
                   $as_low_as_price = $this->currency->format($this->tax->calculate($discounts[$count - 1]['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                }
			}
			else{
				$as_low_as_price =false;
			}		
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			$is_product_hot_label = $this->model_catalog_product->is_product_hot_label($result['sku']);
			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
                'as_low_as_price' 	 => $as_low_as_price,
                'save_rate' 	 => round($result['save_rate']),
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				'is_product_hot_label' => $is_product_hot_label,
			);
		}
        $this->data['more_link'] =$this->url->link('product/category','path=256','');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/latest.tpl';
		} else {
			$this->template = 'default/template/module/latest.tpl';
		}

		$this->render();
	}
}
?>