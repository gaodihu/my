<?php   
class ControllerCommonHead extends Controller {
	protected function index() {
        //var_dump($this->session->data);
		$this->data['title'] = $this->document->getTitle();
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
        $this->data['server'] = $server;
		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
		$this->data['base'] = $server;
        $this->document->addStyle('mobile/view/stylesheet/public.css');
        $this->document->addStyle('mobile/view/stylesheet/font-awesome.min.css');
        $this->document->addStyle('mobile/view/stylesheet/media-query.css');
        $this->document->addScript('mobile/view/js/common.js');
        $this->document->addScript('mobile/view/js/swipe.js');
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
    
		$this->data['direction'] = $this->language->get('direction');
		$this->data['name'] = $this->config->get('config_name');
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

        //如果是订单成功页面
        if(isset($this->request->get['route'])&&$this->request->get['route']=='checkout/success'){
            if (isset($this->session->data['order_id'])) {
                $this->load->model('checkout/order');
                $this->load->model('tool/image');
                $this->load->model('catalog/product');
                $order_info =$this->model_checkout_order->getOrder($this->session->data['order_id']);
                $order_product =array();
                $order_product_info =$this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
                foreach($order_product_info as $key=>$product){
                    $product_image =$this->model_catalog_product->getValue(array('image'),$product['product_id']);
                    $product['price_format'] =$this->currency->format($product['price']);
                    $product['total_format'] =$this->currency->format($product['total']);
                    $product['image_email'] =$this->model_tool_image->resize($product_image['image'], 60, 60);
                    $order_product[$key] =$product;
                }
                $order_total_info =$this->model_checkout_order->getOrderTotal($this->session->data['order_id']);
                $this->data['order_info'] =  $order_info;
                $this->data['order_product_info'] =  $order_product_info;
                $this->data['order_total_info'] =  $order_total_info;
                $this->data['cj_status'] = 1;

		    }
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/head.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/common/head.tpl';
        } else{
            $this->template ='default/template/common/head.tpl';
        }
		$this->render();
	} 	
}
?>
