<?php
class ControllerModuleSpecial extends Controller {
	protected function index() {
		$this->language->load('module/special');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['coming_soon'] = $this->language->get('coming_soon');
        $this->data['next_todays_deals'] = $this->language->get('next_todays_deals');
        $this->data['back'] = $this->language->get('back');
		$this->data['time_limit'] = $this->language->get('time_limit');
        $this->data['time_start_limit'] = $this->language->get('time_start_limit');
		$this->data['more'] = $this->language->get('more');
		$this->data['lower'] = $this->language->get('lower');
		$this->data['off'] = $this->language->get('off');
		$this->document->addScript('js/jquery/jquery.countdown.js');
		$this->load->model('module/home_product');
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
        $now =date('Y-m-d H:i:s',time());
		$end = date('Y-m-d',time())." 24:00:00";
		$data = array(
			'sort'  => 'sort_order',
			'order' => 'ASC',
			'start' => 0,
			'limit' => '4',
            'start_time'=>$now,
            'end_time'=>$end
		);
		
		$left_time = strtotime($end)-time();
		$day = floor($left_time/(3600*24));
		$hours =floor(($left_time%(3600*24))/3600);
		$min =floor(($left_time%3600)/60);
		$sec = ($left_time%3600)%60;
		$left_time_js = $day.":".$hours.":".$min.":".$sec;
		$this->data['left_time_js'] = $left_time_js;
		$results = $this->model_module_home_product->getHomeProduct(1,$data);
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'],$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$image = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) { 
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
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

        //下一轮特价商品，come soon

        $this->data['next_products'] = array();
        $start =date('Y-m-d',strtotime('+1 days'))." 00:00:00";;
		$end = date('Y-m-d',strtotime('+1 days'))." 24:00:00";
		$data = array(
			'sort'  => 'sort_order',
			'order' => 'ASC',
			'start' => 0,
			'limit' => '4',
            'start_time'=>$start,
            'end_time'=>$end
		);		

		$results = $this->model_module_home_product->getHomeProduct(1,$data,true);
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'],$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$image = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
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
			
			$this->data['next_products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
                'save_rate' 	 => floor($result['save_rate']),
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				'is_product_hot_label' => $is_product_hot_label,
				'sku' => $result['sku'],
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}

		$this->render();
	}
}
?>