<?php 
class ControllerProductReviews extends Controller { 
    private $error =array();

    public function common(){
        $this->document->addStyle('css/stylesheet/jqzoom.css');
        $this->document->addScript('js/jquery/jquery.jqzoom.js');
        $this->document->addScript('js/jquery/review.js');
        $this->load->model('catalog/review');
        $this->load->model('catalog/product');
        //得到评论最多的5个商品
        $max_review_products =$this->model_catalog_review->getMaxCountProduct(5);
        $this->data['max_review_products'] =array();
        $this->load->model('tool/image');
        foreach($max_review_products as $max_product){
            $product_info =$this->model_catalog_product->getProduct($max_product['product_id']);
            $image =$this->model_tool_image->resize($product_info['image'],170,170);
            $this->data['max_review_products'][] =array(
                'product_id' =>    $max_product['product_id'],
                'name' =>    $product_info['name'],
                'image' =>    $image,
                'url_path' => $product_info['url_path'],
                'count' => $max_product['total']
            );
        }
       //边栏bananer 
            $this->load->model('design/banner');
            $this->data['top_seller'] = $this->language->get('top_seller');
            $side_banner_info = $this->model_design_banner->getBannerByCode('side_banner');
            if ($side_banner_info) {
                foreach ($side_banner_info as $side_banner) {
                    if ($side_banner['image']) {
                        $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                    } else {
                        $image = false;
                    }
                    $this->data['side_banner'][] = array(
                        'link' => $side_banner['link'],
                        'image' => $image,
                        'title' => $side_banner['title']
                    );
                }
            } else {
                $this->data['side_banner'] = array();
            }
      
    }
    //所有评论
	public function index() {
        //$this->language->load('product/reviews');
        $this->language->load('product/reviews_info');
        if (isset($this->request->get['category'])) {
			$category_id = (int)$this->request->get['category'];
		} else {
			$category_id = 0;
		}
        $this->document->addStyle('css/stylesheet/product.css');
        $this->document->addStyle('css/stylesheet/reviews_write.css');
        /*
        **  
        **
        **  得到各分类下的评论情况
        **
        */
        //得到所有的一级分类
        $this->load->model('catalog/category');
        $this->load->model('catalog/review');
        $this->load->model('catalog/product');
        $top_catagorys =$this->model_catalog_category->getCategories();
        $menu_catalog =array('new_arrivals.html','top-sellers.html','deals.html','clearance.html');
        $this->data['catalog_reviews'] =array();
        foreach($top_catagorys as $top_catalog){
            if(!in_array($top_catalog['url_path'],$menu_catalog)){
                $child_cats_in =$this->model_catalog_category->getCatgoryInStr($top_catalog['category_id']);
                $num =$this->model_catalog_review->getCountReviesByCatagory($child_cats_in);
                $this->data['catalog_reviews'][]=array(
                    'catalog_id' =>$top_catalog['category_id'], 
                    'catalog_name' =>$top_catalog['name'],  
                    'num' =>$num,
                    'href' =>$this->url->link('product/reviews','category='.$top_catalog['category_id'])
                );
            }
        }
        $this->session->data['redirect'] =$this->url->link('product/reviews');
        $this->document->setTitle('reviews');
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
	    $this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_reviews'),
				'href'      => $this->url->link('product/reviews'),
			    'separator' => false
		);
        
        $this->data['heading_title'] =  $this->language->get('heading_title');
        $this->data['text_review_category'] =$this->language->get('text_review_category');
        $this->data['text_all_review'] =$this->language->get('text_all_review');
        $this->data['text_image_only'] =$this->language->get('text_image_only');
        $this->data['text_read_more'] =$this->language->get('text_read_more');
        $this->data['text_close'] =$this->language->get('text_close');
        $this->common();
        //得到分类下评论商品
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
            if($sort=='latest'){
                $sort_code ='date_added';
                $this->data['sort_text'] =$this->language->get('text_latest_reviews');
            }elseif($sort=='most_helpful'){
                $sort_code ='support';
                $this->data['sort_text'] =$this->language->get('text_most_helpful_reviews');
            }
        } else {
            $sort_code ='support';
            $this->data['sort_text'] =$this->language->get('text_sort_by');
        }
        
        if (isset($this->request->get['view'])) {
            $view = $this->request->get['view'];
        } else {
            $view = 0;
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 20;
        }
        $url = '';
        if (isset($this->request->get['category'])) {
            $url .= '&category=' . $this->request->get['category'];
        }
         if (isset($this->request->get['view'])) {
            $url .= '&view=' . $this->request->get['view'];
        }
        $this->data['sorts'][] = array(
            'text' => $this->language->get('text_latest_reviews'),
            'value' => 'date_added',
            'code' => 'date_added',
            'href' => $this->url->link('product/reviews', '&sort=latest'. $url)
        );
        $this->data['sorts'][] = array(
            'text' => $this->language->get('text_most_helpful_reviews'),
            'value' => 'support',
            'code' => 'support',
            'href' => $this->url->link('product/reviews', '&sort=most_helpful'. $url)
        );
        if($category_id){
            $category_cats_in =$this->model_catalog_category->getCatgoryInStr($category_id);
        }
        else{
            $category_cats_in =false;
        } 
        $fiter_data = array(
            'filter_category_id' => $category_cats_in,
            'sort' => $sort_code,
            'order' => $order,
            'view' => $view,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
         );
        $this->data['reviews_list'] =array();
        $review_products =$this->model_catalog_review->getReviesProductByCatagory($fiter_data);
        $total_review_products =$this->model_catalog_review->getTotalRevies($fiter_data);
        foreach($review_products as $rev_pro){
            $review_image =$this->model_catalog_review->getReviewsImage($rev_pro['review_id']);
            $get_value_array =array('model','image','url_path');
            $rev_pro_info =$this->model_catalog_product->getValue($get_value_array,$rev_pro['product_id']);
            $image = $this->model_tool_image->resize($rev_pro_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            $review_img =array();
            if(isset($review_image)){
                foreach($review_image as $key=>$img){
                    $review_img[$key]['thumb_image'] =$this->model_tool_image->resize($img['image_path'], 70,70);
                    $review_img[$key]['origin_image']=$img['image_path'];
                }
            }
            $this->data['reviews_list'][] =array(
                'product_id' =>$rev_pro['product_id'],
                'image' =>$image,
                'href' =>$rev_pro_info['url_path'],
                'review_id' =>$rev_pro['review_id'],
                'author' =>$rev_pro['author'],
                'title' =>$rev_pro['title'],
                'text' => utf8_substr(strip_tags(html_entity_decode($rev_pro['text'], ENT_QUOTES, 'UTF-8')), 0, 180) . '...',
                'rating' =>$rev_pro['rating'],
                'support' =>$rev_pro['support']?$rev_pro['support']:0,
                'against' =>$rev_pro['against']?$rev_pro['against']:0,
                'review_image'    =>$review_img,
                'reply_count' =>$this->model_catalog_review->getCountReplyByReview($rev_pro['review_id']),
                'date_added' =>date('d/m/Y',strtotime($rev_pro['date_added'])),
                'detail_href' =>$this->url->link('product/reviews/info', '&sku='.$rev_pro_info['model'].'&review_id='.$rev_pro['review_id'])
            );
        }
       
        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['category'])) {
            $url .= '&category=' . $this->request->get['category'];
        }
        $this->data['all_review'] = $this->url->link('product/reviews', '&view=0'. $url);
        $this->data['image_only'] = $this->url->link('product/reviews', '&view=1'. $url);
        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['category'])) {
            $url .= '&category=' . $this->request->get['category'];
        }
         if (isset($this->request->get['view'])) {
            $url .= '&view=' . $this->request->get['view'];
        }
        $pagination = new Pagination();
        $pagination->total = $total_review_products;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/reviews',  $url . '&page={page}');
        $this->data['pagination'] = $pagination->render();
        $this->data['sort'] = $sort;
        $this->data['view'] = $view;
        $this->data['category_id'] = $category_id;
        $this->data['order'] = $order;
        $this->data['page'] = $page;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/all_reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/all_reviews.tpl';
		} else {
			$this->template = 'default/template/product/all_reviews.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}
    

    //商品下的所有评论
    public function product(){
        $this->common();
        $this->getInfo();
        $product_id =$this->data['product_id'];
        $sku =$this->data['sku'];
         //得到分类下评论商品
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
            if($sort=='latest'){
                $sort_code ='date_added';
                $this->data['sort_text'] =$this->language->get('text_latest_reviews');
            }elseif($sort=='most_helpful'){
                $sort_code ='support';
                $this->data['sort_text'] =$this->language->get('text_most_helpful_reviews');
            }
        } else {
            $sort_code ='support';
            $this->data['sort_text'] =$this->language->get('text_sort_by');
        }
        
        if (isset($this->request->get['view'])) {
            $view = $this->request->get['view'];
        } else {
            $view = 0;
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 20;
        }
        $url = '';
        if (isset($this->request->get['view'])) {
            $url .= '&view=' . $this->request->get['view'];
        }
        $this->data['sorts'][] = array(
            'text' => $this->language->get('text_latest_reviews'),
            'value' => 'date_added',
            'code' => 'date_added',
            'href' => $this->url->link('product/reviews/product', '&sku='.$sku.'&sort=latest'. $url)
        );
        $this->data['sorts'][] = array(
            'text' => $this->language->get('text_most_helpful_reviews'),
            'value' => 'support',
            'code' => 'support',
            'href' => $this->url->link('product/reviews/product',  '&sku='.$sku.'&sort=most_helpful'. $url)
        );
        $fiter_data = array(
            'sort' => $sort_code,
            'order' => $order,
            'view' => $view,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
         );
        $this->data['reviews_list'] =array();
        $review_products =$this->model_catalog_review->getReviesByProduct($product_id,$fiter_data);
        $total_review_products =$this->model_catalog_review->getTotalReviesByProduct($product_id,$fiter_data);
        if($review_products){
            foreach($review_products as $rev_pro){
                $review_images =array();
                $review_image =$this->model_catalog_review->getReviewsImage($rev_pro['review_id']);
                if($review_image){
                    foreach($review_image as $key=>$img){
                        $review_images[$key]['thumb_image'] =$this->model_tool_image->resize($img['image_path'], 70,70);
                        $review_images[$key]['origin_image'] =$img['image_path'];
                    }
                }
                $this->data['reviews_list'][] =array(
                    'review_id' =>$rev_pro['review_id'],
                    'author' =>$rev_pro['author'],
                    'title' =>$rev_pro['title'],
                    'text' => utf8_substr(strip_tags(html_entity_decode($rev_pro['text'], ENT_QUOTES, 'UTF-8')), 0, 180) . '...',
                    'rating' =>$rev_pro['rating'],
                    'support' =>$rev_pro['support'],
                    'against' =>$rev_pro['against'],
                    'review_image'    =>$review_images,
                    'reply_count' =>$this->model_catalog_review->getCountReplyByReview($rev_pro['review_id']),
                    'date_added' =>date('d/m/Y',strtotime($rev_pro['date_added'])),
                    'detail_href' =>$this->url->link('product/reviews/info', '&sku='.$sku.'&review_id='.$rev_pro['review_id'])
                );
            }
        }
        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        $this->data['all_review'] = $this->url->link('product/reviews/product', '&sku='.$sku.'&view=0'. $url);
        $this->data['image_only'] = $this->url->link('product/reviews/product', '&sku='.$sku.'&view=1'. $url);

        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
         if (isset($this->request->get['view'])) {
            $url .= '&view=' . $this->request->get['view'];
        }
        $pagination = new Pagination();
        $pagination->total = $total_review_products;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/reviews/product',  '&sku='.$sku.$url . '&page={page}');
        $this->data['pagination'] = $pagination->render();
        $this->data['sort'] = $sort;
        $this->data['view'] = $view;
        $this->data['category_id'] = $category_id;
        $this->data['order'] = $order;
        $this->data['page'] = $page;
        $this->data['add_review'] =$this->url->link('product/review_write','id='.$product_id);
        
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/sku_reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/sku_reviews.tpl';
		} else {
			$this->template = 'default/template/product/sku_reviews.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());

    }
    //评论详情
    public function info(){
        $this->common();
        $this->getInfo();
        $product_id =$this->data['product_id'];
        $sku =$this->data['sku'];
        $review_id = $this->data['review_id'];
        $review_id = $this->data['review_id'];
        //商品的具体评论信息
        $review_info =$this->model_catalog_review->getReviewInfo($product_id,$review_id);
        
        if(!$review_info){
            $url_404 = $this->url->link('error/not_found');
            header('HTTP/1.1 404 Not Found');
            header("status: 404 not found");
            header("location:" . $url_404 . "");
        }
        
        $image_info =$this->model_catalog_review->getReviewsImage($review_info['review_id']);
        if($image_info){
            foreach($image_info as $key=>$img){
                $review_info['review_image'][$key]['thumb_image'] =$this->model_tool_image->resize($img['image_path'], 70,70);
                $review_info['review_image'][$key]['origin_image']=$img['image_path'];
               
            }
        }else{
            $review_info['review_image']=array();
        }
        $this->data['review_info'] =$review_info;
        //跳转
        $this->data['form_action'] =$this->url->link('product/reviews/info','&sku='.$sku."&review_id=".$review_id);
        $this->session->data['redirect'] =$this->url->link('product/reviews/info','&sku='.$sku."&review_id=".$review_id);
        if (($this->request->server['REQUEST_METHOD'] == 'POST')){
            if (!$this->customer->isLogged()) {  
			    $this->redirect($this->url->link('product/reviews/info','&sku='.$sku."&review_id=".$review_id));
		    }
            $data =array();
            $data['text'] =strip_tags($this->request->post['comment']);
            $data['customer_id'] =$this->session->data['customer_id'];
            $data['review_id'] =$review_id;
            $this->model_catalog_review->addReviewReply($data);
			$this->session->data['success'] = $this->language->get('text_comment_success');
			$this->redirect($this->url->link('product/reviews/info','&sku='.$sku."&review_id=".$review_id));
        }
        //该评论的用户回复信息
        
        $this->data['reply_info_list'] =array();
        $reply_info_list =$this->model_catalog_review->getReplyByReview($review_id);
        foreach($reply_info_list as $reply_info){
            $reply_info['date_added'] =date('d/m/Y',strtotime($reply_info['date_added']));
            $this->data['reply_info_list'][] =$reply_info;
        }
        
        $this->data['add_review'] =$this->url->link('product/review_write','id='.$product_id);
        if($this->session->data['success']){
            $this->data['success'] =$this->session->data['success'];
            unset($this->session->data['success']);
        }else{
            $this->data['success'] ='';
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review_info.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review_info.tpl';
		} else {
			$this->template = 'default/template/product/review_info.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
    }
    

    public function getInfo(){
        $this->language->load('product/reviews_info');
        $this->document->setTitle($this->language->get('text_sku_rviews'));
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
	    $this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_reviews'),
				'href'      => $this->url->link('product/reviews'),
			    'separator' => $this->language->get('text_separator')
		);
        
        $this->document->addStyle('css/stylesheet/product.css');
        $this->document->addStyle('css/stylesheet/reviews_write.css');
        $sku =isset($this->request->get['sku'])?trim($this->request->get['sku']):'';
        $review_id =isset($this->request->get['review_id'])?trim($this->request->get['review_id']):'';
        if(!$sku){
            $this->redirect($this->url->link('product/reviews'));
        }
        $this->data['sku'] =$sku;
        $this->data['review_id'] =$review_id;
        $this->load->model('catalog/review');
        $this->load->model('catalog/product');
        $this->data['text_popular_products'] = $this->language->get('text_popular_products');
        $this->data['text_total_reviews'] = $this->language->get('text_total_reviews');
        $this->data['text_create_review'] = $this->language->get('text_create_review');
        $this->data['text_share_review'] = $this->language->get('text_share_review');
        $this->data['text_all_review'] = $this->language->get('text_all_review');
        $this->data['text_image_only'] = $this->language->get('text_image_only');
        $this->data['text_post_comment'] =$this->language->get('text_post_comment');
        $this->data['text_submit'] =$this->language->get('text_submit');
        $this->data['text_posted_by'] =$this->language->get('text_posted_by');
        $this->data['text_on'] =$this->language->get('text_on');
        $this->data['text_see_all'] =$this->language->get('text_see_all');
        $this->data['text_reviews'] =$this->language->get('text_reviews');
        $this->data['text_review'] =$this->language->get('text_review');
        $this->data['button_cart'] =$this->language->get('button_cart');
        $product_info =$this->model_catalog_product->getProductBySKU($sku);
        $this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/reviews/product','&sku='.$sku),
			    'separator' => false
		);
         if(!$product_info){
            $this->redirect($this->url->link('product/reviews'));
        }
        $product_id =$product_info['product_id'];
        $this->data['product_id'] =$product_id;
        $this->data['all_pro_review'] =$this->url->link('product/reviews/product','&sku='.$sku);
        $this->load->model('tool/image');
        if ((float)$product_info['price']) {
            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $price = false;
        }
        if ((float)$product_info['special']) {
            $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $special = false;
        }
        if ($special) {
            $discount_rate = $this->model_catalog_product->getDiscountPercent($product_info['special'],$product_info['price']);
        } else {
            $discount_rate = false;
        }
        $image =$this->model_tool_image->resize($product_info['image'], 120, 180);
        //商品信息
        $this->data['product_info'] =array(
            'product_id' =>  $product_info['product_id'],
            'image' =>  $image,
            'price' =>  $price,
            'special' =>  $special,
            'discount_rate' =>  $discount_rate,
            'name' =>  $product_info['name'],
            'reviews' =>  $product_info['reviews'],
            'rating' =>  $product_info['rating'],
            'href' =>  $product_info['url_path']
        );
        //商品对应的评分信息
        $this->data['reviews_rating_info'] = array();
        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);
        $rating_level = array(5,4,3,2,1);
        foreach($rating_level as $rating){
            $rating_review =$this->model_catalog_review->getTotalReviewsByRating($product_id,$rating);
            if(!$review_total){
                $percent =0; 
            }
            else{
                $percent =ceil(($rating_review/$review_total)*100);     
            }
            
            $this->data['reviews_rating_info'][] =array(
                'rating'     =>$rating,
                'rating_total' =>    $rating_review,
                'rating_percent' => $percent
            );
        }
    }
    
}
?>