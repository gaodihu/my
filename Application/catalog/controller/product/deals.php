<?php  
class ControllerProductDeals extends Controller {
	private $error = array(); 

	public function index() { 
		$this->language->load('product/deals');
        $this->document->addStyle('css/stylesheet/product.css');
		$this->document->addScript('js/jquery/jquery.countdown.js');
		$this->document->addScript('js/jquery/jquery.flexslider.js');
		$this->document->addScript('js/jquery/jquery-1.7.2.min.js');
        $this->document->addStyle('css/stylesheet/flexslider.css');
        
        
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
        $this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/deals'),
			'separator' => false
		);
      
        $this->data['title'] = $this->document->setTitle($this->language->get('heading_title'));
        $this->data['description'] = $this->document->setDescription($this->language->get('description'));
        $this->data['keyword'] = $this->document->setKeywords($this->language->get('keyword'));
        $this->data['text_slso_bought'] = $this->language->get('text_slso_bought');
        $this->data['text_top_seller'] = $this->language->get('text_top_seller');
        $this->data['text_view_details'] = $this->language->get('text_view_details');
        $this->data['text_product_left'] = $this->language->get('text_product_left');
        $this->data['text_special_left'] = $this->language->get('text_special_left');
        $this->data['text_day'] = $this->language->get('text_day');
        $this->data['text_hour'] = $this->language->get('text_hour');
        $this->data['text_min'] = $this->language->get('text_min');
        $this->data['text_sec'] = $this->language->get('text_sec');
        $this->data['text_list_price'] = $this->language->get('text_list_price');
        $this->data['text_discount'] = $this->language->get('text_discount');
        $this->data['text_save'] = $this->language->get('text_save');
        $this->data['text_buy'] = $this->language->get('text_buy');
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_recommendations'] = $this->language->get('text_recommendations');
        $this->data['text_product_added'] = $this->language->get('text_product_added');
        $this->data['text_view_cart'] = $this->language->get('text_view_cart');
        $this->data['text_continue_shopping'] = $this->language->get('text_continue_shopping');
        
        
        //限时抢购列表
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $data =array();
        if(isset($this->request->get['page'])){
            $page =$this->request->get['page'];
        }
        else{
            $page =1;
        }
        if(isset($this->request->get['limit'])){
            $limit =$this->request->get['limit'];
        }
        else{
            $limit =20;
        }
        $data['start'] =($page - 1) * $limit;
        $data['limit'] =$limit;
        $delas_list =$this->model_catalog_product->getProductSpecials($data,date('Y-m-d H:i:s',time()),'');
        $this->data['deals'] =array();
        foreach($delas_list as $product){
            if($product['image']){
                $image =$this->model_tool_image->resize($product['image'], 350, 350);
            }
            else{
                $image =false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$format_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$format_price = false;
			}
			
			if ((float)$product['special']) {
				$format_special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$format_special = false;
			}
			if($product['special']){
				//特价倒计时
				$special_info = $this->model_catalog_product->getProductSpecial($product['product_id']);
				$end_time = $special_info['date_end'];
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
				//价格节省
				$saved =$this->currency->format($this->tax->calculate(($product['price']-$product['special']), $product['tax_class_id'], $this->config->get('config_tax')));
				$discount_rate =$this->model_catalog_product->getDiscountPercent($product['special'],$product['price'],2);
			}
              //限时特卖最大购买数量
            $deals_max_salesnum =$product['deals_limit_number'];
            if($deals_max_salesnum>0){
                $text_limit_buy = sprintf($this->language->get('text_limit_buy'),$deals_max_salesnum);
            }
            else{
                 $text_limit_buy='';
            }
            $this->data['deals'][] =array(
                'product_id' =>   $product['product_id'],
                'name' =>   $product['name'],
                'sku' =>   $product['model'],
                'price' =>   $product['price'],
                'format_price' =>   $format_price,
                'special' =>   $product['special'],
                'format_special' =>   $format_special,
                'image' =>   $image,
                'discount_rate' =>   $discount_rate,
                'save' =>   $saved,
                'quantity' =>$product['quantity'],
                'text_limit_buy' =>$text_limit_buy,
                'description' =>utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                'left_time_js' =>   $left_time_js,
                'href' =>   $this->url->link('product/product','&product_id='.$product['product_id'])
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_catalog_product->getTotalProductSpecials();
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/deals', 'page={page}');
        $this->data['pagination'] = $pagination->render();
         //边栏special 专题banner
        $this->data['text_hot'] = $this->language->get('text_hot');
        $this->load->model('design/banner');
        $special_lists = $this->model_design_banner->getBannerByCode('special_history_baner');
        if($special_lists){
            foreach($special_lists as $side_banner){
                if ($side_banner['image']) {
                    $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                } else {
                    $image = false;
                }
                $this->data['special_lists'][] = array(
                    'link' =>	$side_banner['link'],
                    'image' =>	$image,
                    'title' =>	$side_banner['title']
                );
            }
        }
        else{
            $this->data['special_lists'] = array();
        }
      //商品推荐
       $this->data['recommendations'] =array();
       if(empty($this->data['deals'])){
  
           $recommendations =$this->model_catalog_product->getBestSellerProducts(7,true);
           foreach($recommendations as $item){
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
                $this->data['recommendations'][]=array(
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
       }

        //得到top_selles
        $this->data['top_selles'] =array();
        $top_sellers = $this->model_catalog_product->getBestSellerProductsByDeals(5);
			foreach ($top_sellers as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
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
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
							
			$this->data['top_selles'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}
       //底部幻灯片
			$this->load->model('design/banner');
			$pro_list_foot_banner =$this->model_design_banner->getBannerByCode('pro_list_foot_banner');
			if($pro_list_foot_banner){
				foreach($pro_list_foot_banner as $pro_list_foot){
					if ($pro_list_foot['image']) {
						$image = $this->model_tool_image->resize($pro_list_foot['image'], $pro_list_foot['banner_width'], $pro_list_foot['banner_height']);
					} else {
						$image = false;
					}
					$this->data['pro_list_foot_banner'][] = array(
						'link' =>	$pro_list_foot['link'],
						'image' =>	$image,
						'title' =>	$pro_list_foot['title']
					);
				}
			}
			else{
				$this->data['pro_list_foot_banner'] = array();
			}
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/deals.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/deals.tpl';
        } else {
            $this->template = 'default/template/product/deals.tpl';
        }

        $this->children = array(
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
		
	}

}
?>