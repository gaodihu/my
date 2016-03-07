<?php 
class ControllerAccountRightBottom extends Controller { 
	public function index() {
        $this->language->load('account/account');
        $this->load->model('catalog/product');
		$this->load->model('tool/image');

        $this->data['text_you_may_also_like'] =$this->language->get('text_you_may_also_like');
        $this->data['text_you_recent_history'] =$this->language->get('text_you_recent_history');
		//You may also like
        /* 随机得到商品中的数据*/
        $this->data['also_like'] =array();
        $also_like =$this->model_catalog_product->getBestSellerProducts(7,true);
        foreach($also_like as $item){
            $format_price = $this->currency->format($this->tax->calculate($item['price'], $item['tax_class_id'], $this->config->get('config_tax')));
            if ((float)$item['special']) {
                $format_special = $this->currency->format($this->tax->calculate($item['special'], $item['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $format_special  = false;
            }
            if ($item['image']) {
                $image =$this->model_tool_image->resize($item['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $image  = false;
            }
            $this->data['also_like'][]=array(
					'product_id' =>$item['product_id'],
					'name' =>$item['name'],
					'image' =>$image,
                    'price' =>$item['price'],
                    'special' =>$item['special'],
					'format_price'  =>$format_price,
					'format_special'  =>$format_special,
					'href'  =>$this->url->link('product/product','&product_id=' .$item['product_id'])
			);
        }
		//浏览历史
		$this->data['historys'] =array();
		if(isset($this->session->data['history'])&&!empty($this->session->data['history'])){
			foreach($this->session->data['history'] as $pro_id){
				$pro_info =$this->model_catalog_product->getProduct($pro_id);
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$format_price = $this->currency->format($this->tax->calculate($pro_info['price'], $pro_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$format_price  = false;
				}
			
				if ((float)$pro_info['special']) {
					$format_special = $this->currency->format($this->tax->calculate($pro_info['special'], $pro_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$format_special  = false;
				}
				if ($pro_info['image']) {
					$image =$this->model_tool_image->resize($pro_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$image  = false;
				}
				
				$this->data['historys'][]=array(
					'product_id' =>$pro_id,
					'name' =>$pro_info['name'],
					'image' =>$image,
					'format_price'  =>$format_price,
					'format_special'  =>$format_special,
					'href'  =>$this->url->link('product/product','&product_id=' .$pro_id)
				);
			}
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/right_bottom.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/right_bottom.tpl';
		} else {
			$this->template = 'default/template/account/right_bottom.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>