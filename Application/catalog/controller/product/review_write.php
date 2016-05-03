<?php 
class ControllerProductReviewWrite extends Controller { 
    private $error =array();

	public function index() {
        
        if (isset($this->request->get['id'])) {
			$product_id = (int)$this->request->get['id'];
		} else {
			$product_id = 0;
		}
        $this->load->model('catalog/review');
        $this->data['can_write_review'] = 1 ;
        if(!$this->customer->isLogged()){
            $this->session->data['redirect'] = $this->url->link('product/review_write','id='.$product_id);
            $this->redirect($this->url->link('account/login'));
            $this->data['show_order'] = 1;
        }
        else{
            $this->data['show_order'] = 0;
            $this->load->model('account/customer');
            //必须是购买过这种商品的用户才可以发表评论
            $if_buy_orders = $this->model_account_customer->getCustomerOrdersByProduct($this->session->data['customer_id'],$product_id);
            $review_orders = $this->model_catalog_review->getReviewOrders($this->session->data['customer_id'],$product_id);
            if($if_buy_orders){
                $this->data['show_order'] = 0;
                $product_orders = array();
                foreach($if_buy_orders as $item){
                    $product_orders[] = $item['order_number'];
                }
                $product_reviews_orders = array();
                foreach($review_orders as $item){
                    $product_reviews_orders[] = $item['order_number'];
                }
                $not_used_orders = array_diff($product_orders, $product_reviews_orders);
                if($not_used_orders && count($not_used_orders)>=1){
                    $order_number = array_pop($not_used_orders);
                    $this->data['can_write_review'] = 1;
                    $this->data['order_number'] = $order_number;
                } else {
                    //$this->data['can_write_review'] = 0;
                    //$this->data['error_max_write_review_limit'] = "您已经发布过review，不能再发不了";
                }
            }
            else{
                //$this->data['can_write_review'] = 0;
                //$this->data['error_max_write_review_limit'] = "没有购买改产品不能进行评论";
            }
        }
        $this->language->load('product/review_write');
        $this->document->addStyle('css/stylesheet/reviews_write.css');
        $this->load->model('catalog/product');
        
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
        $product_info['url'] = $this->url->link('product/product', '&product_id=' . $product_id);
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
				'text'      =>$this->language->get('breadcrumbs_title'),
				'href'      => $this->url->link('product/review_write', '&id=' . $product_id),
			    'separator' => false
		);	
        
        $this->data['heading_title'] =  $this->language->get('heading_title');
        $this->data['text_input_order'] =  $this->language->get('text_input_order');
        $this->data['text_rating'] =  $this->language->get('text_rating');
        $this->data['text_review_title'] =  $this->language->get('text_review_title');
        $this->data['review_title_note'] =  $this->language->get('review_title_note');
        $this->data['text_review_content'] =  $this->language->get('text_review_content');
        $this->data['text_review_content_note'] =  $this->language->get('text_review_content_note');
        $this->data['text_nickname'] =  $this->language->get('text_nickname');
        $this->data['text_as_low_as'] =  $this->language->get('text_as_low_as');
        $this->data['product_information'] =  $this->language->get('product_information');
        $this->data['text_average_rating'] =  $this->language->get('text_average_rating');
        $this->data['text_reviews'] =  $this->language->get('text_reviews');
        $this->data['text_review_posting_guidelines'] =  $this->language->get('text_review_posting_guidelines');
        $this->data['text_review_order_number_note'] =  $this->language->get('text_review_order_number_note');
        $this->data['text_submit'] =  $this->language->get('text_submit');
        $this->data['text_cancel'] =  $this->language->get('text_cancel');
        $this->data['text_uplaod_image'] =  $this->language->get('text_uplaod_image');
        $this->data['review_content_note'] = $this->language->get('review_content_note');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$valid_data =$this->validateForm()){
            $this->request->post['image']=NULL;
            if(is_array($valid_data)){
                $this->request->post['image'] =$valid_data;
            }
            
            $review_id = $this->model_catalog_review->addReview($product_id,$this->request->post);
            
            $points_send =20;
            if(isset($this->session->data['2nd_anniversary']) &&$this->session->data['2nd_anniversary']){
                $points_send =40;
            }
            $this->model_catalog_review->autoCheck($review_id,$points_send);
			$this->session->data['product_success'] = sprintf($this->language->get('text_success'),$points_send,$this->url->link('account/reviews'));
			$this->redirect($this->url->link('product/product', '&product_id=' . $product_id));
        }
       
        //错误信息
        if(isset($this->error['error_order_number'])){
            $this->data['error_order_number'] =$this->error['error_order_number'];
        }
        else{
             $this->data['error_order_number'] ='';
        }
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

        if(isset($this->error['error_product_image_type'])){
            $this->data['error_product_image_type'] =$this->error['error_product_image_type'];
        }
        else{
             $this->data['error_product_image_type'] ='';
        }
        if(isset($this->error['error_product_image_size'])){
            $this->data['error_product_image_size'] =$this->error['error_product_image_size'];
        }
        else{
             $this->data['error_product_image_size'] ='';
        }
        if(isset($this->error['error_product_image_upload'])){
            $this->data['error_product_image_upload'] =$this->error['error_product_image_upload'];
        }
        else{
             $this->data['error_product_image_upload'] ='';
        }
        
        //填写信息
        if (isset($this->request->post['order_number'])) {
			$this->data['order_number'] = $this->request->post['order_number'];
		} elseif(isset($this->data['order_number']) && $this->data['order_number']) {
			
		}else{
            $this->data['order_number'] ='';
        }
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
			$this->data['nickname'] =$this->customer->getNickName();
		}
        
        $this->data['review_list_link'] = '/reviews/'.$product_info['model'].'.html';
      
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
        $customer_id =$this->session->data['customer_id'];
        $order_number =isset($this->request->post['order_number'])?trim($this->request->post['order_number']):0;
        $rating =isset($this->request->post['rating'])?$this->request->post['rating']:0;
        $title = isset($this->request->post['title'])?$this->request->post['title']:'';
        $content = isset($this->request->post['content'])?strip_tags($this->request->post['content']):'';
        $nickname = isset($this->request->post['nickname'])?$this->request->post['nickname']:'';
        $uplaod_image = isset($_FILES['fileImg']['name'])?$_FILES['fileImg']['name']:false;
        $this->load->model('checkout/order');
        $this->load->model('catalog/review');
        $product_id =(int)$this->request->get['id'];
        
        $order_info = $this->model_checkout_order->getOrderByNumber($order_number);
        if(!$order_info){
            $this->error['error_order_number'] = $this->language->get('error_order_number');
        }else{
            $haveReviews = $this->model_catalog_review->haveReviews($product_id,$order_number);
            if($haveReviews){
                $this->error['error_order_number'] = $this->language->get('error_hava_reviews');
            }else{
                $if_have_product =$this->model_checkout_order->haveProductForOrder($order_number,$product_id);

                if(isset($this->request->post['order_number']) && !$if_have_product){
                    $this->error['error_order_number'] = $this->language->get('error_order_number');
                }else{
                    if(!$this->customer->isLogged()){
                        if($order_info['customer_id'] != ''){
                            $this->error['error_order_number'] = $this->language->get('error_order_number');
                        }

                    }else{
                        if($order_info['customer_id'] != $customer_id){
                            $this->error['error_order_number'] = $this->language->get('error_order_number');
                        }
                    }
                }
            }
        }
        


        if(!$this->customer->isLogged()){
            $this->redirect($this->url->link('account/login', '&back_url=' .rawurlencode($this->url->link('product/review_write','&id='.$product_id))));
        }
        if(!$rating){
            $this->error['error_rating'] = $this->language->get('error_rating_empty');
        }
        if(empty($title)){
            $this->error['error_title'] = $this->language->get('error_title');
        }
        if(empty($content) || utf8_strlen($content)<20){
             $this->error['error_content'] = $this->language->get('error_content_empty');
        }
        /*
        if($content!==trim($this->request->post['content'])){
             $this->error['error_content'] = $this->language->get('error_content_html');   
        }
        */
        /*
        if(!$nickname||(utf8_strlen($nickname)<3||utf8_strlen($nickname)>30)){
            $this->error['error_nickname'] = $this->language->get('error_nickname'); 
        }
        */
        $new_image_path =array();
        if($uplaod_image){
            $count =count($uplaod_image);
            $uplaod_fiel_type =array();
            for($i=0;$i<$count;$i++){
                if($uplaod_image[$i]){
                    $file_type =substr(strrchr($uplaod_image[$i], '.'), 1);
                    $uplaod_fiel_type[] =$file_type;
                    $accept_type = array('jpg','jpeg','bmp','gif','png');
                    if(!in_array(strtolower($file_type),$accept_type)){
                        $this->error['error_product_image_type'] = $this->language->get('error_product_image_type');
                    }
                    
                    //图片大小限制 3MB
                    if($_FILES['fileImg']['size'][$i]>3145728){
                        $this->error['error_product_image_size'] = $this->language->get('error_product_image_size');
                    }
                }
            }
           if(!$this->error){
                 //处理图片
                $save_image_path =DIR_IMAGE."customer_upload/reviews/";
                if(!is_dir($save_image_path)){
                    mkdir($save_image_path,0777,1);
                }
                foreach($_FILES['fileImg']['tmp_name'] as $key=>$tmp_name){
                    if($tmp_name){
                        $new_file_name ='reviews_'.$product_id.'_'.$customer_id.'_'.$key.'_'.time().".".$file_type; 
                        if(!move_uploaded_file($tmp_name, $save_image_path.$new_file_name)){
                            $this->error['error_product_image_upload'] = $this->language->get('error_product_image_upload');
                        }
                        else{
                            $new_image_path[] = 'customer_upload/reviews/'.$new_file_name;
                        }
                    }
                }
            }
        }
        if(!$this->error){
            if($new_image_path){
                return $new_image_path;
            }
            else{
                return true;
            }
            
        }
        else{
            return false;
        }
    }
}
?>