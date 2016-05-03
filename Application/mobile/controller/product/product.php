<?php  
class ControllerProductProduct extends Controller {
	private $error = array(); 

	public function index() { 
		$lang =$this->language->load('product/product');
        $this->data = array_merge($this->data,$lang);
        $this->document->addScript('mobile/view/js/Cart.js');
        if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/category');	
        $this->load->model('catalog/product');	
    
		$this->session->data['redirect'] =$this->url->link('product/product','&product_id=' . $product_id);
		$this->load->model('tool/image');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {

            $this->document->setTitle($product_info['name']);
            $this->document->setDescription($product_info['name']);
            $this->document->setKeywords($product_info['meta_keyword']);
        
            $this->data['product_info'] = $product_info;
			$this->data['heading_title'] = $product_info['name'];
         
			$this->load->model('catalog/review');

			$this->data['is_login'] = $this->customer->isLogged()?1:0;

			if (!isset($this->request->get['route'])) {
				$this->data['redirect'] = $this->url->link('common/home');
			} else {
				$data = $this->request->get;

				unset($data['_route_']);

				$route = $data['route'];

				unset($data['route']);

				$url = '';
				
				
				if ($data) {
					$url = '&' . urldecode(http_build_query($data, '', '&'));
				}
				$this->data['redirect'] = $this->url->link($route, $url,'SSL');
			}
            $this->data['spring_arrival'] = '';
            if(($product_info['quantity'])<= 0 || $product_info['stock_status_id']!=7){
                $arrival = $this->model_catalog_product->getProductSpringArrivalBySKU($product_info['model']);
                $time_arrival = strtotime($arrival);
                if($time_arrival > time()){
                    $left_day = ($time_arrival - time())/(24*3600);
                    $left_day = ceil($left_day);
                    $this->data['spring_arrival'] = sprintf($this->language->get('text_spring_arrival'),date('m/d/Y',$time_arrival),$left_day);
                }
                
            }
            
            $this->data['format_price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			$this->data['images'] = array();
            
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$this->data['images'][] = $this->model_tool_image->resize($result['image'], 450, 450);
			}	
            //是否具有链接专属特价
            $exclusive_price_info =$this->model_catalog_product->realy_exclusive_price($product_info['product_id']);
            if(!$product_info['special']&&$exclusive_price_info){
                $product_info['special'] =$exclusive_price_info['price'];
            }
			if($product_info['special']){
                $this->data['special_price']=$product_info['special'];
				$this->data['format_special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                $this->data['currency_special'] = $this->currency->convert($product_info['special'],'USD',$this->data['currency_code']);
				//特价倒计时
                if($exclusive_price_info){
				    $end_time = $exclusive_price_info['end_time'];
                    $this->data['exclusive_price_info'] =true;
                }
                else{
                    $special_info = $this->model_catalog_product->getProductSpecial($product_info['product_id']);
				    $end_time = $special_info['date_end'];
                }
				
				$now =time();
				$end_time_scr =strtotime($end_time);
				if($end_time =='0000-00-00 00:00:00'){
					$end_time_scr	=$now+24*3600;
				}
				$left_time =$end_time_scr-$now;
				$day = floor($left_time/(3600*24));
				$hours =floor(($left_time%(3600*24))/3600);
				$min =floor(($left_time%3600)/60);
				$sec = ($left_time%3600)%60;
				$left_time_js = $day.":".$hours.":".$min.":".$sec;
				$this->data['left_time_js'] = $left_time_js;
				//价格节省
				$saved =$this->currency->format($this->tax->calculate(($product_info['price']-$product_info['special']), $product_info['tax_class_id'], $this->config->get('config_tax')));
				$this->data['saved'] = $saved;
				$this->data['svae_rate'] =$this->model_catalog_product->getDiscountPercent($product_info['special'],$product_info['price'],2);
			}
			else{
                $this->data['currency_special'] = false;
				$this->data['saved'] = false;
				$this->data['svae_rate'] =false;
			}

			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
		    
            //得到商品该组的复合属性选择
            $attr_filter =$this->model_catalog_product->getAttrFilter($product_id);
            $this->data['attr_filter'] =$attr_filter;
            //得到商品的复合属性值
            $product_attr_filter =$this->model_catalog_product->getPorductAttrFilter($product_id);
            $this->data['product_attr_filter'] =$product_attr_filter;
            //var_dump($product_attr_filter);exit;
            //$product_attr_option_filter =$this->model_catalog_product->getPorductAttrOptionFilter($product_id);
            //是否是wishlist 商品
            $this->data['is_wishlist'] =$this->model_catalog_product->isWishlist($product_id);
            
            	//阶梯价格
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
            //var_dump($discounts);
			$this->data['discounts'] = array(); 
			if(!empty($discounts)){
				foreach ($discounts as $discount) {
					$this->data['discounts']['qty'][] = array(
						'quantity' => $discount['quantity']."+"
					);
					$this->data['discounts']['price'][] = array(
						'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
					);
				}
			}
			$end_discount =end($discounts);
			$this->data['discount_low_price'] =$end_discount['price'];

			$review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);
            $this->data['review_total'] =$review_total;
            //best sellers 
            
			$best_sellers =$this->model_catalog_product->getBestSellerProducts(4);
            $this->data['best_sellers'] =array();
            foreach($best_sellers as $best){
                if ($best['image']) {
                    $image = $this->model_tool_image->resize($best['image'], 207, 160);
                } else {
                    $image = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($best['price'], $best['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $best['special']) {
                    $special = $this->currency->format($this->tax->calculate($best['special'], $best['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                $this->data['best_sellers'][]=array(
                    'product_id' =>  $best['product_id'],
                    'name' =>  $best['name'],
                    'image' =>  $image,
                    'special' =>  $special,
                    'price' =>  $price,
                    'href'  =>$best['url_path']
                );
            }
			
            
            if(isset($this->session->data['product_success'])){
                $this->data['success'] =$this->session->data['product_success'];
                unset($this->session->data['product_success']);
            }else{
                $this->data['success'] ='';
             }
            //国家
            $battery_type = $this->config->get('battery_type');
            $_is_battery = 0;
            if(in_array($product_info['battery_type'],$battery_type)){    
                $_is_battery  = 1;
               
                $this->load->model('localisation/country');
                $this->data['countries'] = $this->model_localisation_country->getCountries();
                
                $ship_to_country_code = '';
                 if(isset($_COOKIE['battery_ship_to']) && $_COOKIE['battery_ship_to']){
                    $ship_to_country_code = $_COOKIE['battery_ship_to'];
                }else{
                    require_once  DIR_SYSTEM .'library/ip.php';
                    $ip_class = new Ip();
                    $ip = $ip_class->getIp();
                    $country_code = $ip_class->getCountryCode($ip);
                    if($country_code){
                        $ship_to_country_code = $country_code;
                    }
                }
                $this->data['ship_to_country_code'] = $ship_to_country_code;
            }
            $this->data['is_battery'] = $_is_battery ;

           
            $this->data['desc_info_more'] = $this->url->link('product/product/productInfo','&product_id='.$product_id.'&show=description');
            $this->data['review_info_more'] = $this->url->link('product/product/productInfo','&product_id='.$product_id.'&show=review');
            $this->data['faq_info_more'] = $this->url->link('product/product/productInfo','&product_id='.$product_id.'&show=faq');
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
                $this->template =$this->config->get('config_template') . '/template/product/product.tpl';
            } else{
                $this->template ='default/template/product/product.tpl';
            }
			$this->children = array(
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
		} else {
			$url_404 =$this->url->link('error/not_found');
            header('HTTP/1.1 404 Not Found'); 
            header("status: 404 not found");
            header("location:".$url_404."");
		}
	}
	
    public function Supportreview() {
        $this->load->model('catalog/review');
        $json=array();
        $json['error'] =0;
        $json['message'] ='';
		if (isset($this->request->get['review_id'])) {
			$review_id = $this->request->get['review_id'];
		} else {
			$json['error']= 1;
            $json['message'] ='';
		} 
        if (isset($this->request->get['num'])) {
			$num = $this->request->get['num'];
		} else {
			$json['error']=2;
            $json['message'] ='';
		} 
         if (isset($this->request->get['condition'])) {
			$condition = $this->request->get['condition'];
		} else {
			$json['error']=3;
            $json['message'] ='';
		} 
        if(!$json['error']){
            if(!isset($_COOKIE['support-review'])||!in_array($review_id,explode(',',$_COOKIE['support-review']))){
                $this->model_catalog_review->UpdateReviewSupport($review_id,$condition,$num);
                $json['content'] =$num;
                if(strlen($_COOKIE['support-review'])>0){
                    setcookie('support-review', $_COOKIE['support-review'].",".$review_id, time() +24*3600, '/', COOKIE_DOMAIN);
                }else{
                    setcookie('support-review', $review_id, time() +24*3600, '/', COOKIE_DOMAIN);
                }
                
            }
            else{
                $json['content'] =$num-1;
            }
        }
        $this->response->setOutput(json_encode($json));
	}

    public function productInfo(){
        $lang =$this->language->load('product/product');
        $this->document->addScript('mobile/view/js/Cart.js');
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $this->data =array_merge($this->data,$lang);
        $this->load->model('catalog/review');
        $this->load->model('catalog/product');
        $product_id = isset($this->request->get['product_id'])?(int)$this->request->get['product_id']:0;
        $show =isset($this->request->get['show'])?trim($this->request->get['show']):'description';
        $this->data['show'] =$show;
        if(!$product_id){
            $this->redirect($this->url->link('common/home'));
        }
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $this->document->setTitle($product_info['name']);
        $this->document->setDescription($product_info['name']);
        $this->document->setKeywords($product_info['meta_keyword']);
        if(!$product_info){
            $this->redirect($this->url->link('common/home'));
        }
        $this->data['product_info'] =$product_info;
        $this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id);

        $this->data['reviews_list'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);
        $this->data['review_total'] =$review_total;
        $page=1;
        $pagesize =5;
        if($review_total>$pagesize){
            $this->data['review_fanye'] =1;

        }else{
            $this->data['review_fanye'] =0;
        }

        $results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * $pagesize, $pagesize);

        foreach ($results as $result) {
            $this->data['reviews_list'][] = array(
                'review_id'     => $result['review_id'],
                'author'     => $result['author'],
                'text'       => $result['text'],
                'title'      =>   $result['title'],
                'rating'     => (int)$result['rating'],
                'support'    =>  (int)$result['support'],
                'against'    => (int)$result['against'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }
        //商品FAQS
        $this->load->model('catalog/faq');
        $faq_info = $this->model_catalog_faq->getFaqsByProductId($product_info['product_id'],($page - 1) * $pagesize, $pagesize);
        $this->data['faq_info'] = $faq_info;
        $faq_count =count($faq_info);
        if($faq_count>$pagesize){
            $this->data['faq_fanye'] =1;

        }else{
            $this->data['faq_fanye'] =0;
        }

        $this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
        $this->data['shipping_description'] = html_entity_decode($product_info['shipping_description'], ENT_QUOTES, 'UTF-8');
        $this->data['packaging_list'] = html_entity_decode($product_info['packaging_list'], ENT_QUOTES, 'UTF-8');
        $this->data['read_more'] = html_entity_decode($product_info['read_more'], ENT_QUOTES, 'UTF-8');
        $this->data['application_image'] = html_entity_decode($product_info['application_image'], ENT_QUOTES, 'UTF-8');
        $this->data['size_image'] = html_entity_decode($product_info['size_image'], ENT_QUOTES, 'UTF-8');
        $this->data['features'] = html_entity_decode($product_info['features'], ENT_QUOTES, 'UTF-8');
        $this->data['installation_method'] = html_entity_decode($product_info['installation_method'], ENT_QUOTES, 'UTF-8');
        $this->data['video'] = html_entity_decode($product_info['video'], ENT_QUOTES, 'UTF-8');
        $this->data['notes'] = html_entity_decode($product_info['notes'], ENT_QUOTES, 'UTF-8');
        $this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        $this->data['review_write'] = $this->url->link('product/product/productInfo','&product_id='.$product_id);
        $this->data['nick_name'] =$this->customer->getNickName();

        //填写评论
        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validateForm()){
            
            if(!$this->customer->isLogged()) {
                $this->session->data['redirect'] = $this->url->link('product/product/productInfo','&product_id='.$product_id."&show=review");
                $this->redirect($this->url->link('account/login'));
            }
            $this->load->model('catalog/review');
            $this->model_catalog_review->addReview($product_id,$this->request->post);
            $this->session->data['product_success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('product/product', '&product_id=' . $product_id));
        }
        
       if(count($this->error)>0){
            $this->data['have_error'] =1;
       }else{
            $this->data['have_error'] =0;
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
        $this->data['is_login'] =$this->customer->isLogged();
        $this->data['reviews_list_ajax'] =$this->url->link('product/product/reviewList','&product_id='.$product_id);
        $this->data['faq_list_ajax'] =$this->url->link('product/product/FaqList','&product_id='.$product_id);
        $this->children = array(
            'common/footer',
            'common/header'
        );
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/pro_desc.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/product/pro_desc.tpl';
        } else{
            $this->template ='default/template/product/pro_desc.tpl';
        }

        $this->response->setOutput($this->render());
    }

    private function validateForm(){
        $this->language->load('product/review_write');
        $rating =isset($this->request->post['rating'])?$this->request->post['rating']:0;
        $title = isset($this->request->post['title'])?$this->request->post['title']:'';
        $content = isset($this->request->post['content'])?strip_tags($this->request->post['content']):'';
        $nickname = isset($this->request->post['nickname'])?$this->request->post['nickname']:'';

        if(!$rating){
            $this->error['error_rating'] = $this->language->get('error_rating_empty');
        }
        if(empty($title)||(utf8_strlen($title)<5||utf8_strlen($title)>50)){
            $this->error['error_title'] = $this->language->get('error_title');
        }
        if(empty($content)){
            $this->error['error_content'] = $this->language->get('error_content_empty');
        }

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
    
    public function reviewList(){
        $json['error'] =0;
        $json['message'] ='';
        $page =isset($this->request->get['page'])?(int)$this->request->get['page']:2;
        $product_id =isset($this->request->get['product_id'])?(int)$this->request->get['product_id']:0;
        if(!$product_id){
            $json['error'] =1;
        }
        $this->load->model('catalog/review');
        $pagesize =1;
        $results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * $pagesize, $pagesize);
        $reviews_list =array();
        if($results){
            foreach ($results as $result) {
                $reviews_list[] = array(
                    'review_id'     => $result['review_id'],
                    'author'     => $result['author'],
                    'text'       => $result['text'],
                    'title'      =>   $result['title'],
                    'rating'     => (int)$result['rating'],
                    'support'    =>  (int)$result['support'],
                    'against'    => (int)$result['against'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
                );
            }
        }
        $json['data'] =$reviews_list;
        $this->response->setOutput(json_encode($json));


    }
    public function FaqList(){
        $json['error'] =0;
        $json['message'] ='';
        $page =isset($this->request->get['page'])?(int)$this->request->get['page']:2;
        $product_id =isset($this->request->get['product_id'])?(int)$this->request->get['product_id']:0;
        if(!$product_id){
            $json['error'] =1;
        }
        $this->load->model('catalog/review');
        $pagesize =1;
        $results = $this->model_catalog_faq->getFaqsByProductId($product_id, ($page - 1) * $pagesize, $pagesize);
        $faq_list =array();
        if($results){
            foreach ($results as $result) {
                $faq_list[] = $result;
            }
        }
        $json['data'] =$faq_list;
        $this->response->setOutput(json_encode($json));


    }
     function canShip(){
        $country_code = $this->request->post['country_code'];
        $country_code = strtoupper($country_code);
        $product_id = $this->request->post['product_id'];
        $product_id = intval($product_id);

        $this->load->model('catalog/product');
        $this->language->load('product/product');
        
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $_result  = array();
        $_result['flag'] = 0;
        $_result['msg'] = '';
        if($product_info){
            $battery_type = $this->config->get('battery_type');
            $_is_battery = 0;
            if(in_array($product_info['battery_type'],$battery_type)){ 
                $can_ship = $this->model_catalog_product->canBatteryShipTo($country_code);
                if($can_ship){
                    $_result['flag'] = 1;
                    $_result['msg'] = '';
                    setcookie('battery_ship_to',$country_code,  time() + 365 * 24 *60 *60,'/','moresku.com');
                }else{
                    $_result['flag'] = 0;
                    $_result['msg'] = $this->language->get("can_not_ship_to");
                }
            } else {
                $_result['flag'] = 1;
                $_result['msg'] = '';
            }
        } else {
            $_result['flag'] = 0;
            $_result['msg'] = '';
        }
        $json  = json_encode($_result);
        echo $json;
    }
}
?>