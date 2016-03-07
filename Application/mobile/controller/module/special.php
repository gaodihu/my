<?php
class ControllerModuleSpecial extends Controller {
	protected function index() {
		$this->language->load('module/special');

		

		$this->data['heading_title'] = $this->language->get('heading_title');
        
		$this->load->model('module/home_product');
		
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
		$this->data['left_time_js'] = $left_time;
		$results = $this->model_module_home_product->getHomeProduct(1,$data);
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
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}

    
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/module/special.tpl';
        } else{
            $this->template ='default/template/module/special.tpl';
        }

		$this->render();
	}
}
?>