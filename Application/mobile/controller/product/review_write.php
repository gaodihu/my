<?php 
class ControllerProductReviewWrite extends Controller { 
    private $error =array();

	public function index() {
        
        if (isset($this->request->get['id'])) {
			$product_id = (int)$this->request->get['id'];
		} else {
			$product_id = 0;
		}
        if(!$this->customer->isLogged()){
            $this->session->data['redirect'] =$this->url->link('product/review_write','&product_id='.$product_id);
            $this->redirect($this->url->link('account/login'));
        }
      
        $lang =$this->language->load('product/review_write');
        $this->data =array_merge($this->data,$lang);
        $this->load->model('catalog/product');
        
        $product_info = $this->model_catalog_product->getProduct($product_id);
        
       
        $this->document->setTitle(sprintf($this->language->get('title'),$product_info['name']));

        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validateForm()){
            $this->load->model('catalog/review');
            $this->model_catalog_review->addReview($product_id,$this->request->post);
			$this->session->data['product_success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('product/product', '&product_id=' . $product_id));
        }
       
        //错误信息
        if(isset($this->error['error_rating'])){
            $this->data['error_rating'] =$this->error['error_rating'];
        }
        else{
             $this->data['error_rating'] ='';
        }
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
        if(isset($this->error['error_nickname'])){
            $this->data['error_nickname'] =$this->error['error_nickname'];
        }
        else{
             $this->data['error_nickname'] ='';
        }

        //填写信息
        if (isset($this->request->post['rating'])) {
			$this->data['rating'] = $this->request->post['rating'];
		} else {
			$this->data['rating'] =0;
		}
        if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} else {
			$this->data['title'] ='';
		}
        if (isset($this->request->post['content'])) {
			$this->data['content'] = $this->request->post['content'];
		} else {
			$this->data['content'] ='';
		}
        if (isset($this->request->post['nickname'])) {
			$this->data['nickname'] = $this->request->post['nickname'];
		} else {
			$this->data['nickname'] ='';
		}

      
        $this->data['action'] =$this->url->link('product/review_write', '&id=' . $product_id);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review_write.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review_write.tpl';
		} else {
			$this->template = 'default/template/product/review_write.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

    private function validateForm(){
       
        $rating =isset($this->request->post['rating'])?$this->request->post['rating']:0;
        $title = isset($this->request->post['title'])?$this->request->post['title']:'';
        $content = isset($this->request->post['content'])?strip_tags($this->request->post['content']):'';
        $nickname = isset($this->request->post['nickname'])?$this->request->post['nickname']:'';
        if(!$this->customer->isLogged()){
            $this->redirect($this->url->link('account/login', '&back_url=' .rawurlencode($this->url->link('product/review_write','&id='.$product_id))));
        }
        if(!$rating){
            $this->error['error_rating'] = $this->language->get('error_rating_empty');
        }
        if(empty($title)||(utf8_strlen($title)<5||utf8_strlen($title)>50)){
            $this->error['error_title'] = $this->language->get('error_title');
        }
        if(empty($content)){
             $this->error['error_content'] = $this->language->get('error_content_empty');
        }
        /*
        if($content!==trim($this->request->post['content'])){
             $this->error['error_content'] = $this->language->get('error_content_html');   
        }
        */
        if(!$nickname||(utf8_strlen($nickname)<3||utf8_strlen($nickname)>30)){
            $this->error['error_nickname'] = $this->language->get('error_nickname'); 
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