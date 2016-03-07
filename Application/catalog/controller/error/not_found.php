<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {	
		$this->language->load('error/not_found');
        
		$this->document->settitle($this->language->get('heading_title'));
        $this->document->setDescription($this->language->get('description'));
        $this->document->setKeywords($this->language->get('keyword'));
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_error'] = $this->language->get('text_error');
        $this->data['text_sorry'] = $this->language->get('text_sorry');
        
        $this->data['text_to_proceed'] = $this->language->get('text_to_proceed');
        $this->data['text_go_homepage'] = sprintf($this->language->get('text_go_homepage'),$this->url->link('common/home'));
        $this->data['text_go_lastpage'] = sprintf($this->language->get('text_go_lastpage'),$this->request->server['HTTP_REFERER']);
        $this->data['text_send_emial'] = $this->language->get('text_send_emial');

	
		$this->response->addheader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 not found');

		$this->data['continue'] = sprintf($this->language->get('text_try_again'),$this->url->link('common/home'));
        
        $goods_list =$this->get_new_special(5);
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->data['goods_list'] =array();
        foreach($goods_list as $good){
            	$pro_info =$this->model_catalog_product->getProduct($good['product_id']);
				if($pro_info){
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
                     
                    $discount_rate = $this->model_catalog_product->getDiscountPercent($pro_info['special'],$pro_info['price']);
                   
					if ($pro_info['image']) {
						$image =$this->model_tool_image->resize($pro_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					} else {
						$image  = false;
					}
					
					$this->data['goods_list'][]=array(
						'product_id' =>$pro_id,
						'name' =>$pro_info['name'],
						'image' =>$image,
                        'discount_rate'=>$discount_rate,
						'format_price'  =>$format_price,
						'format_special'  =>$format_special,
						'href'  =>$this->url->link('product/product','&product_id=' . $good['product_id'])
					);
				}
        }
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/404.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/error/404.tpl';
		} else {
			$this->template = 'default/template/error/404.tpl';
		}
        $this->children = array(
				'common/footer',
				'common/header'
			);

	    $this->response->setOutput($this->render());	
	}

    private function get_new_special($num){
        $sql ='select product_id from '.DB_PREFIX."product_special where date_start<=NOW() and date_end>=NOW() order by date_start desc limit ".$num;
        $query =$this->db->query($sql);
        return $query->rows;
    }
}
?>