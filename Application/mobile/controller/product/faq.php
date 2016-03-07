<?php 
class ControllerProductFaq extends Controller { 
    private $error =array();

	public function index() {
        $this->language->load('product/faq');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/reviews_write.css');
        //$this->document->addStyle('catalog/view/theme/default/stylesheet/validform.css');
        //$this->document->addScript('catalog/view/javascript/jquery/Validform_v5.3.1_min.js');
        $this->load->model('catalog/product');
        if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
        $product_info = $this->model_catalog_product->getProduct($product_id);
        //商品信息
        $this->load->model('tool/image');
        $image = $this->model_tool_image->resize($product_info['image'], 120, 180);
        $product_info['thumb_image'] = $image;
        $discounts = $this->model_catalog_product->getProductDiscounts($product_id);
        if($product_info['special']){
            $special_format =$this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        }
        else{
            $special_format =false;
        }
        $price_format =$this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        if($discounts){
            $count = count($discounts);
            $as_low_as_price = $this->currency->format($this->tax->calculate($discounts[$count-1]['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        }
        else{
            $as_low_as_price =false;
        }
        $product_info['as_low_as_price'] = $as_low_as_price;
        $product_info['special_format'] = $special_format;
        $product_info['price_format'] = $price_format;
        $this->data['product_info'] = $product_info;
        $this->document->setTitle(sprintf($this->language->get('title'),$product_info['name']));
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
	    $this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', '&product_id=' . $product_id),
			    'separator' => $this->language->get('text_separator')
		);
         $this->data['breadcrumbs'][] = array(
				'text'      =>$this->language->get('heading_title'),
				'href'      => $this->url->link('product/faq', '&product_id=' . $product_id),
			    'separator' => false
		);	
        
        $this->data['heading_title'] =  $this->language->get('heading_title');
        $this->data['text_name'] =  $this->language->get('text_name');
        $this->data['review_title_note'] =  $this->language->get('review_title_note');
        $this->data['text_review_content_note'] =  $this->language->get('text_review_content_note');
        $this->data['text_faq_about'] =  $this->language->get('text_faq_about');
        $this->data['text_faq_about_product'] =  $this->language->get('text_faq_about_product');
        $this->data['text_faq_abou_shippingt'] =  $this->language->get('text_faq_abou_shippingt');
        $this->data['text_faq_about_customer'] =  $this->language->get('text_faq_about_customer');
        $this->data['text_ask_question'] =  $this->language->get('text_ask_question');
        $this->data['text_add_additional'] =  $this->language->get('text_add_additional');
        $this->data['text_as_low_as'] =  $this->language->get('text_as_low_as');
        $this->data['product_information'] =  $this->language->get('product_information');
        $this->data['text_average_rating'] =  $this->language->get('text_average_rating');
        $this->data['text_reviews'] =  $this->language->get('text_reviews');

        $this->data['text_submit'] =  $this->language->get('text_submit');
        $this->data['text_cancel'] =  $this->language->get('text_cancel');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validateForm()){
            $this->load->model('catalog/faq');
            $nickname =$this->customer->getNickName();
            $this->request->post['name'] = $nickname;
            $this->model_catalog_faq->addFaq($product_id,$this->request->post);

			$this->session->data['product_success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('product/product', '&product_id=' . $product_id));
        }
        
        //错误信息
        if(isset($this->error['error_title'])){
            $this->data['error_title'] =$this->error['error_title'];
        }
        else{
             $this->data['error_title'] ='';
        }
        if(isset($this->error['error_content'])){
            $this->data['error_content'] =$this->error['error_content'];
        }
        else{
             $this->data['error_content'] ='';
        }
        //填写信息
        if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] ='';
		}
        if (isset($this->request->post['faq_type'])) {
			$this->data['faq_type'] = $this->request->post['faq_type'];
		} else {
			$this->data['faq_type'] ='1';
		}
        if (isset($this->request->post['faq_title'])) {
			$this->data['faq_title'] = $this->request->post['faq_title'];
		} else {
			$this->data['faq_title'] ='';
		}
        if (isset($this->request->post['faq_content'])) {
			$this->data['faq_content'] = $this->request->post['faq_content'];
		} else {
			$this->data['faq_content'] ='';
		}

      
        $this->data['action'] =$this->url->link('product/faq', '&product_id=' . $product_id);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/faq_write.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/faq_write.tpl';
		} else {
			$this->template = 'default/template/product/faq_write.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

    private function validateForm(){
        $faq_title = isset($this->request->post['faq_title'])?$this->request->post['faq_title']:'';
        $faq_content = isset($this->request->post['faq_content'])?strip_tags($this->request->post['faq_content']):'';
        if(empty($faq_title)||(utf8_strlen($faq_title)<5||utf8_strlen($faq_title)>200)){
            $this->error['error_title'] = $this->language->get('error_title');
        }
        if(empty($faq_content)){
             $this->error['error_content'] = $this->language->get('error_content_empty');
        }
        if($faq_content!==trim($this->request->post['faq_content'])){
             $this->error['error_content'] = $this->language->get('error_content_html');   
        }
        if(!$this->error){
            return true;
        }
        else{
            return false;
        }
    }
}
?>