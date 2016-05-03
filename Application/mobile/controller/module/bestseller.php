<?php
class ControllerModuleBestSeller extends Controller {
	protected function index() {
		$this->language->load('module/bestseller');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['best_sellers'] = $this->language->get('best_sellers');
		
		$this->load->model('module/home_product');
	
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
		$data = array(
			'sort'  => 'sort_order',
			'order' => 'asc',
			'start' => 0,
			'limit' => 4
		);
		$results = $this->model_module_home_product->getHomeProduct(2,$data);
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'],170,170);
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
			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/module/bestseller.tpl';
        } else{
            $this->template ='default/template/module/bestseller.tpl';
        }

		$this->render();
	}
}
?>