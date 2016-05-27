<?php  
class ControllerProductProduct extends Controller {
	private $error = array(); 

	public function index() { 
		$this->language->load('product/product');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'child'		=>false,
			'separator' => $this->language->get('text_separator')
		);
        
        if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/category');	
        $this->load->model('catalog/product');	

            
        $url_product_tmp = $this->model_catalog_product->getProductUrl($product_id);
        $request_uri = $_SERVER['REQUEST_URI'];
        $request_uri = explode('?',$request_uri);
        $static_request_uri = $request_uri[0];
		if(substr($static_request_uri,0,1) == '/'){
			$static_request_uri = substr($static_request_uri,1);
		}
        $url_param = '';
        if(isset($_GET) && count($_GET)>0){
            foreach($_GET as $key => $val){
                if($key != '_route_' && $key != 'route' && $key != 'product_id'){
                    $url_param .= $key . '=' . $val."&";
                }
            }
        }


        $url_param = substr($url_param,0,-1);
        if($url_product_tmp){
            if($static_request_uri != $url_product_tmp){
                if($url_param){
                    $url_product_tmp = $url_product_tmp . '?' . $url_param;
                }
                $url_product_tmp =  $this->config->getDomain() . $url_product_tmp;
                $this->redirect($url_product_tmp);
            }
        }
        
		if (isset($this->request->get['path'])) {
			$url = '';
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);	
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
				$category_info = $this->model_catalog_category->getCategory($path_id);
				if ($category_info) {
					$child_cat = array();
					$child_cat_info =$this->model_catalog_category->getCategories($path_id);
					foreach($child_cat_info as $key=>$item){
							$child_cat[$key]['name'] =$item['name'];
							$child_cat[$key]['href'] =$this->url->link('product/category', 'path=' . $item['category_id']);
					}
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path . $url),
						'child'        =>$child_cat,
						'separator' => $this->language->get('text_separator')
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {			
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}	

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path'].$url),
					'child'		=>false,
					'separator' => $this->language->get('text_separator')
				);
			}
		}
        else{
             $top_catalog =array();
             $level_2_catalog =array();
             $Categories  =  $this->model_catalog_product->getCategories($product_id);
             //取首选项分类
             $category_info = $this->model_catalog_category->getCategory($Categories[0]['category_id']);
             
             if($category_info['level']==1){
                $top_catalog['category_id'] =$category_info['category_id'];
                $top_catalog['name'] =$category_info['name'];
                $top_catalog['sort_order'] =$category_info['sort_order'];
                foreach($Categories as $key =>$Categorie){
                    $two_category_info = $this->model_catalog_category->getCategory($Categorie['category_id']);
                    if(!$level_2_catalog&&$two_category_info['level']==2&&$two_category_info['parent_id'] ==$category_info['category_id']){
                        $level_2_catalog['category_id'] =$two_category_info['category_id'];
                        $level_2_catalog['name'] =$two_category_info['name'];
                        $level_2_catalog['sort_order'] =$two_category_info['sort_order'];
                    }
                }
             }
             else{
                $parent_catgory_id =$category_info['parent_id'];
                $parent_category_info = $this->model_catalog_category->getCategory($parent_catgory_id);
                if($parent_category_info){
                    $top_catalog['category_id'] =$parent_category_info['category_id'];
                    $top_catalog['name'] =$parent_category_info['name'];
                    $top_catalog['sort_order'] =$parent_category_info['sort_order'];
                    $level_2_catalog['category_id'] =$category_info['category_id'];
                    $level_2_catalog['name'] =$category_info['name'];
                    $level_2_catalog['sort_order'] =$category_info['sort_order'];
                }
                
             }
             
             if ($top_catalog) {
                    
					$child_cat = array();
                    $top_catalog['en_name'] =$this->model_catalog_category->get_category_en_name($top_catalog['category_id']);
					$child_cat_info =$this->model_catalog_category->getCategories($top_catalog['category_id']);
					foreach($child_cat_info as $key=>$item){
							$child_cat[$key]['name'] =$item['name'];
							$child_cat[$key]['href'] =$this->url->link('product/category', 'path=' .$top_catalog['category_id']."_".$item['category_id']);
					}
					$this->data['breadcrumbs'][] = array(
						'text'      => $top_catalog['name'],
						'href'      => $this->url->link('product/category', 'path=' . $top_catalog['category_id']),
						'child'        =>$child_cat,
						'separator' => $this->language->get('text_separator')
					);
			}
            $is_show_led_advantage = 0;
            if($top_catalog['category_id'] == 119 ){
                $is_show_led_advantage = 1;
            }
			$this->data['lang_code'] = strtolower($this->session->data['language']);
            $this->data['is_show_led_advantage'] = $is_show_led_advantage;
            if($level_2_catalog){
                $this->data['breadcrumbs'][] = array(
                    'text'      => $level_2_catalog['name'],
                    'href'      => $this->url->link('product/category', 'path=' . $level_2_catalog['category_id']),
                    'child'        =>false,
                    'separator' => $this->language->get('text_separator')
				);
            }
        }
         //var_dump($this->data['breadcrumbs']);exit;
		$this->load->model('catalog/manufacturer');	

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'child'		=>false,
				'separator' => $this->language->get('text_separator')
			);	

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {	
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),	
					'child'		=>false,
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}	

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'child'		=>false,
				'separator' => $this->language->get('text_separator')
			); 	
		}

		
		
		$this->session->data['redirect'] =$this->url->link('product/product','&product_id=' . $product_id);
		$this->load->model('tool/image');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		//把商品加入浏览列表中
		$history =isset($this->session->data['history'])?$this->session->data['history']:array();
		if(!in_array($product_id,$history)){
			$history[] =$product_id; 
		}
		
		$this->session->data['history']=$history;
		$this->data['history'] =array();
		
		if($history){
            $history = array_reverse($history);
			foreach($history as $pro_id){
                if(count($this->data['history']) >= 4){
                    break;
                }
				$pro_info =$this->model_catalog_product->getProduct($pro_id);
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
					if ($pro_info['image']) {
						$image =$this->model_tool_image->resize($pro_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					} else {
						$image  = false;
					}
					
					$this->data['history'][]=array(
						'product_id' =>$pro_id,
						'name' =>$pro_info['name'],
						'image' =>$image,
						'format_price'  =>$format_price,
						'format_special'  =>$format_special,
						'href'  =>$this->url->link('product/product','&product_id=' . $pro_id)
					);
				}	
			}
		}
		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}	

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}	

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
				'child'		=>false,
				'separator' => false
			);			
			//$this->document->setTitle($product_info['meta_title']);
            //$this->document->setDescription($product_info['meta_description']);
            $this->document->setTitle($product_info['name']);
            $this->document->setDescription($product_info['name']);
            $this->document->setKeywords($product_info['meta_keyword']);
            /*
            if($level_2_catalog){
                $this->document->setKeywords($level_2_catalog[0]['name']);
            }else{
                $this->document->setKeywords($product_info['meta_keyword']);
            }
            */
            $this->data['product_info'] = $product_info;
            $this->data['level_2_catalog'] = $level_2_catalog;
            $this->data['top_catalog'] = $top_catalog;
			
            $canonical_link = ''; 
            $main_product_id = $this->model_catalog_product->getPorductAttrFilterMainProduct($product_id);
            if($main_product_id){
                $canonical_link = $this->url->link('product/product', 'product_id=' . $main_product_id);
            }else{
                $canonical_link = $this->url->link('product/product', 'product_id=' . $this->request->get['product_id']);
            }
            
			$this->document->addLink($canonical_link, 'canonical');
			$this->document->addStyle('css/stylesheet/product.css');
			$this->document->addStyle('css/stylesheet/flexslider.css');
			$this->document->addStyle('css/stylesheet/icheck.css');
			$this->document->addScript('js/jquery/jquery.icheck.min.js');
			$this->document->addScript('js/jquery/jquery.countdown.js');
			$this->document->addScript('js/jquery/jquery.flexslider.js');
			$this->document->addScript('js/jquery/jquery-1.7.2.min.js');
			$this->document->addScript('js/jquery/jquery.artDialog.js');
			$this->document->addScript('js/jquery/jcarousellite_1.0.1.pack.js');


			$this->data['heading_title'] = $product_info['name'];
         
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');	
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['limited_time'] = $this->language->get('limited_time');
			$this->data['text_add_reviews'] = $this->language->get('text_add_reviews');
			$this->data['text_off'] = $this->language->get('text_off');
			$this->data['text_save'] = $this->language->get('text_save');
			$this->data['text_day'] = $this->language->get('text_day');
			$this->data['text_hour'] = $this->language->get('text_hour');
			$this->data['text_min'] = $this->language->get('text_min');
			$this->data['text_sec'] = $this->language->get('text_sec');
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_subtotal'] = $this->language->get('text_subtotal');
			$this->data['text_add_to_cart'] = $this->language->get('text_add_to_cart');
			$this->data['text_add_to_wish_list'] = $this->language->get('text_add_to_wish_list');
			$this->data['text_buy_more'] = $this->language->get('text_buy_more');
			$this->data['text_contact_customer_service'] = $this->language->get('text_contact_customer_service');
			$this->data['text_recent_history'] = $this->language->get('text_recent_history');
			$this->data['text_more_questions'] = $this->language->get('text_more_questions');
			$this->data['text_can_write'] = $this->language->get('text_can_write');
			$this->data['text_loading'] = $this->language->get('text_loading');
			$this->data['text_customer_buy'] = $this->language->get('text_customer_buy');
            $this->data['text_empty_questions'] = $this->language->get('text_empty_questions');
            $this->data['text_ask_questions'] = $this->language->get('text_ask_questions');
            $this->data['text_product_added'] = $this->language->get('text_product_added');
            $this->data['text_view_cart'] = $this->language->get('text_view_cart');
            $this->data['text_continue_shopping'] = $this->language->get('text_continue_shopping');
            //免税说明文字
            $this->data['text_custom_tax'] = $this->language->get('text_custom_tax');
            $this->data['text_exclusive_price'] = $this->language->get('text_exclusive_price');
            $this->data['text_specifications'] = $this->language->get('text_specifications');
            $this->data['text_packaging_list'] = $this->language->get('text_packaging_list');
            $this->data['text_read_more'] = $this->language->get('text_read_more');
            $this->data['text_application_image'] = $this->language->get('text_application_image');
            $this->data['text_size_image'] = $this->language->get('text_size_image');
            $this->data['text_features'] = $this->language->get('text_features');
            $this->data['text_installation_method'] = $this->language->get('text_installation_method');
            $this->data['text_video'] = $this->language->get('text_video');
            $this->data['text_notes'] = $this->language->get('text_notes');
            $this->data['text_recommed'] = $this->language->get('text_recommed');
			

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');			
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');
			$this->data['tab_faqs'] = $this->language->get('tab_faqs');
			$this->data['tab_shipping_infomation'] = $this->language->get('tab_shipping_infomation');
			
			$this->data['product_id'] = $this->request->get['product_id'];
            $this->data['name'] = $product_info['name'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['points'] = $product_info['points'];
			$this->data['sku'] = $product_info['model'];
			$this->data['is_login'] = $this->customer->isLogged()?1:0;
			
			//货币
			$this->data['currency_code'] = $this->currency->getCode(); 
			
			$this->load->model('localisation/currency');

			$this->data['currencies'] = array();

			$results = $this->model_localisation_currency->getCurrencies();	

			foreach ($results as $result) {
				if ($result['status']) {
					$this->data['currencies'][] = array(
						'title'        => $result['title'],
						'code'         => $result['code'],
						'symbol_left'  => $result['symbol_left'],
						'symbol_right' => $result['symbol_right']				
					);
				}
			}

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
            /*
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $product_info['stock_status'];//$this->language->get('text_instock');
			}
		    */   
               
            //显示商品活动说明信息

            $set_id =$this->model_catalog_product->if_action_sku($product_info['model']);
            $this->data['action_desc_info'] =array();
            if($set_id){
                $action_desc_info =$this->model_catalog_product->get_sku_action_desc($set_id);
                $this->data['action_desc_info'] =$action_desc_info;
                
            }
             $this->data['stock'] = $product_info['stock_status'];
            
             $this->data['out_of_stock'] = $this->language->get('text_outstock');
            
            $this->data['out_of_stock'] = $this->language->get('text_outstock');
            
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
            
            
			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}
			if ($product_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}

			$this->data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}	
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['format_price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),'','',false);
                $this->data['currency_price'] = $this->currency->convert($product_info['price'],'USD',$this->data['currency_code']);
			} else {
				$this->data['format_price'] = false;
                $this->data['currency_price'] = false;
			}

            //是否具有链接专属特价
            $exclusive_price_info =$this->model_catalog_product->realy_exclusive_price($product_info['product_id']);
            if(!$product_info['special']&&$exclusive_price_info){
                $product_info['special'] =$exclusive_price_info['price'];
            }
			if($product_info['special']){
                $this->data['special_price']=$product_info['special'];
				$this->data['format_special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')),'','',false);
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
			$this->data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();

					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}

							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}

					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}
			
			//商品FAQS
            $this->load->model('catalog/faq');
			$faq_info = $this->model_catalog_faq->getFaqsByProductId($product_info['product_id'],20);
			$this->data['faq_info'] = $faq_info;

            $this->data['write_new_faq'] =$this->url->link('product/faq','&product_id='.$product_id);
			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}
            
			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = $product_info['reviews'];
			$this->data['text_reviews'] =sprintf($this->language->get('text_reviews'), $product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];

            $_description = $product_info['description'];
            $this->data['description'] = html_entity_decode($_description, ENT_QUOTES, 'UTF-8');
            
            $_read_more = $product_info['read_more'];

           
            $this->data['read_more'] = html_entity_decode($_read_more, ENT_QUOTES, 'UTF-8');
            
            $_features = $product_info['features'];
            
           
           $this->data['features'] = html_entity_decode($_features, ENT_QUOTES, 'UTF-8');
            
            
            $this->data['shipping_description'] = html_entity_decode($product_info['shipping_description'], ENT_QUOTES, 'UTF-8');
            $this->data['packaging_list'] = html_entity_decode($product_info['packaging_list'], ENT_QUOTES, 'UTF-8');
           
            $this->data['application_image'] = html_entity_decode($product_info['application_image'], ENT_QUOTES, 'UTF-8');
            $this->data['size_image'] = html_entity_decode($product_info['size_image'], ENT_QUOTES, 'UTF-8');
            
            $this->data['installation_method'] = html_entity_decode($product_info['installation_method'], ENT_QUOTES, 'UTF-8');
            $this->data['video'] = html_entity_decode($product_info['video'], ENT_QUOTES, 'UTF-8');
            $this->data['notes'] = html_entity_decode($product_info['notes'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
            $this->data['review_write'] = $this->url->link('product/review_write','&id='.$product_id);
            
            
            
            $this->load->model('catalog/review');

            $this->data['text_on'] = $this->language->get('text_on');
            $this->data['text_no_reviews'] = sprintf($this->language->get('text_no_reviews'),$this->url->link('service/points'));
            $this->data['text_get_reviews'] = sprintf($this->language->get('text_get_reviews'),$this->url->link('service/points'));
            $this->data['text_recent_reviews'] = $this->language->get('text_recent_reviews');
            $this->data['text_by'] = $this->language->get('text_by');
            $this->data['text_replay'] = $this->language->get('text_replay');
            $this->data['text_review_helpful'] = $this->language->get('text_review_helpful');
            $this->data['text_review_share'] = $this->language->get('text_review_share');

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }  

            $this->data['reviews_list'] = array();
            $this->data['reviews_rating_info'] = array();
            $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
            $rating_level = array(5,4,3,2,1);
            foreach($rating_level as $rating){
                $rating_review =$this->model_catalog_review->getTotalReviewsByRating($this->request->get['product_id'],$rating);
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
            $pagesize =10;
            $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * $pagesize, $pagesize);
            $this->load->model('tool/image');
            
            foreach ($results as $result) {
                $review_image =array();
                if($result['image']){
                    foreach($result['image'] as $key=>$img){
                        $review_image[$key]['thumb_image'] =$this->model_tool_image->resize($img, 80,60);
                        $review_image[$key]['origin_image'] =$img;
                    }
                }
                $this->data['reviews_list'][] = array(
                    'review_id'     => $result['review_id'],
                    'author'     => $result['author'],
                    'title'     => $result['title'],
                    'text'       => $result['text'],
                    'rating'     => (int)$result['rating'],
                    'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                    'support'    =>  (int)$result['support'],
                    'against'    => (int)$result['against'],
                    'image'     =>$review_image,
                    'reply_count' =>$this->model_catalog_review->getCountReplyByReview($result['review_id']),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'href' => $this->url->link('product/reviews/info', '&sku='.$product_info['model'].'&review_id='.$result['review_id'])
                );
            }
            $pagination = new Pagination();
            $pagination->total = $review_total;
            $pagination->page = $page;
            $pagination->limit = $pagesize; 
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

            $this->data['pagination'] = $pagination->render();

            //商品的质检报告
            $this->data['text_info_guides'] = $this->language->get('text_info_guides');
            $this->data['text_no_documents'] = $this->language->get('text_no_documents');
            $this->data['text_spectrum'] = $this->language->get('text_spectrum');
            $all_brochures=$this->model_catalog_product->getAllProductBrochures($this->request->get['product_id']);
            $this->data['all_brochures'] =array();
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }
            foreach($all_brochures as $brochures){
                $this->data['all_brochures'][] =array(
                    'brochures_path'    =>$brochures['brochures_path'],
                    'href'                   =>$server."pdf/brochures/".$brochures['brochures_path']
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
			//边栏banner
			$side_banner_info = $this->model_design_banner->getBannerByCode('side_banner');
			if($side_banner_info){
				foreach($side_banner_info as $side_banner){
					if ($side_banner['image']) {
						$image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
					} else {
						$image = false;
					}
					$this->data['side_banner'][] = array(
						'link' =>	$side_banner['link'],
						'image' =>	$image,
						'title' =>	$side_banner['title']
					);
				}
			}
			else{
				$this->data['side_banner'] = array();
			}
			
			//Customers Also Bought
			/*
			* 1.取得商品所在分类
			* 2.取得对应时间内该分类下销量最高的5个商品
			* 3.
			*/
			$this->data['customers_also_bought'] =array();
			$customers_also_bought = $this->model_catalog_product->getProductAlsoBought($this->request->get['product_id'],5);
            if($customers_also_bought){
                foreach($customers_also_bought as $bought_pro){
                    $special_info =$this->model_catalog_product->getProductSpecial($bought_pro['product_id']);
                    if($special_info&&$special_info['price']){
                        $bought_pro['special'] =$special_info['price'];
                    }
                    else{
                        $bought_pro['special'] =false;
                    }
                    if ($bought_pro['image']) {
                        $image = $this->model_tool_image->resize($bought_pro['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                    } else {
                        $image = false;
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($bought_pro['price'], $bought_pro['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ($bought_pro['special']) {
                        $special = $this->currency->format($this->tax->calculate($bought_pro['special'], $bought_pro['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }
                    $this->data['customers_also_bought'][] =array(
                        'product_id' =>$bought_pro['product_id'],
                        'name'  =>$bought_pro['name'],
                        'image'  =>$image,
                        'price'  =>$price,
                        'special'  =>$special,
                        'href'  =>$this->url->link('product/product', 'product_id=' . $bought_pro['product_id'])
                    );
                }
            }else{
                $best_seller =$this->model_catalog_product->getBestSellerProducts(10);
                foreach($best_seller as $bought_pro){
                    $special_info =$this->model_catalog_product->getProductSpecial($bought_pro['product_id']);
                    if($special_info&&$special_info['price']){
                        $bought_pro['special'] =$special_info['price'];
                    }
                    else{
                        $bought_pro['special'] =false;
                    }
                    if ($bought_pro['image']) {
                        $image = $this->model_tool_image->resize($bought_pro['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                    } else {
                        $image = false;
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($bought_pro['price'], $bought_pro['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ($bought_pro['special']) {
                        $special = $this->currency->format($this->tax->calculate($bought_pro['special'], $bought_pro['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }
                    $this->data['customers_also_bought'][] =array(
                        'product_id' =>$bought_pro['product_id'],
                        'name'  =>$bought_pro['name'],
                        'image'  =>$image,
                        'price'  =>$price,
                        'special'  =>$special,
                        'href'  =>$this->url->link('product/product', 'product_id=' . $bought_pro['product_id'])
                    );
                }
            }

			$this->data['products'] = array();
			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}	
            /*
			$this->data['tags'] = array();

			if ($product_info['tag']) {		
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}
            
			$this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$this->data['profiles'] = $this->model_catalog_product->getProfiles($product_info['product_id']);
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);
            */
            
            if (isset($this->session->data['product_success'])) {
                $this->data['success'] = $this->session->data['product_success'];

                unset($this->session->data['product_success']);
            } else {
                $this->data['success'] = '';
            }
            $this->data['current_stock'] = $this->language->get('current_stock');
            if($product_info['quantity'] > 30){
                $this->data['warn_product_stock'] = 0;
            }else{
                 $this->data['warn_product_stock'] = 1;
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

			//prduct hot label
			$is_product_hot_label = $this->model_catalog_product->is_product_hot_label($product_info['model']);
			$this->data['is_product_hot_label'] = $is_product_hot_label;

			if ($product_info['special']) {
				$discount_rate = $this->model_catalog_product->getDiscountPercent($product_info['special'],$product_info['price']);
			} else {
				$discount_rate = false;
			}
			$this->data['discount_rate'] = $discount_rate;

            $this->data['reviews_list_link'] = '/reviews/'.$product_info['model'].'.html';

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
			} else {
				$this->template = 'default/template/product/product.tpl';
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
	
    public function get_subtotal(){
        $this->load->model('catalog/product');
        $this->language->load('product/product');
        $product_id =$this->request->post['product_id']?$this->request->post['product_id']:'';
        $qty =$this->request->post['quantity']?$this->request->post['quantity']:1;
        $product_id = intval($product_id);
        $qty = intval($qty);
        $json =array();
        //阶梯价格
		$discounts = $this->model_catalog_product->getProductDiscounts($product_id);
        $product_info =$this->model_catalog_product->getProduct($product_id);
        $price =$product_info['price'];
        $special_price = $product_info['special'];
        $product_stock = $product_info['quantity'];
        $last = end($discounts);
        if($product_stock >= $qty){
            $json['error'] = 0;
        }  else {
            $qty = $product_stock;
            $json['error'] = 1;
            $json['qty'] = $qty;
            $json['msg'] = sprintf($this->language->get('text_maxmum'),$product_stock);
           
        }
        if(!empty($discounts)){
            foreach ($discounts as $key =>$discount) {
                if(isset($discounts[$key+1])&&$qty>=$discount['quantity'] &&$qty<$discounts[$key+1]['quantity']){
                    $price = $discount['price'];
                }
                elseif($qty>=$last['quantity']){
                    $price = $last['price'];
                }
            }
        }
        $price =round($price,2);
        if($special_price){
            $price =min($price,$special_price);
        }
        $currency_price = $this->currency->convert($price,'USD',$this->currency->getCode());
        $currency_total = $currency_price * $qty;
        $currency_total = $this->currency->convert($currency_total,$this->currency->getCode(),'USD');
        $json['subtotal'] =$this->currency->format($currency_total);
        $this->response->setOutput(json_encode($json));
       

    }
	public function review() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');
		$this->data['text_recent_reviews'] = $this->language->get('text_recent_reviews');
		$this->data['text_by'] = $this->language->get('text_by');
		$this->data['text_replay'] = $this->language->get('text_replay');
		$this->data['text_review_helpful'] = $this->language->get('text_review_helpful');
		$this->data['text_review_share'] = $this->language->get('text_review_share');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  

		$this->data['reviews_list'] = array();
    
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
        $pagesize =10;
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) *$pagesize, $pagesize);

		foreach ($results as $result) {
			$this->data['reviews_list'][] = array(
                'review_id'     => $result['review_id'],
				'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                'support'     => (int)$result['support'],
                'against'     => (int)$result['against'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $pagesize; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}

		$this->response->setOutput($this->render());
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
        }
        $this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->language->load('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['profile_id'])) {
			$profile_id = $this->request->post['profile_id'];
		} else {
			$profile_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$profile_info = $this->model_catalog_product->getProfile($product_id, $profile_id);

		$json = array();

		if ($product_info && $profile_info) {

			if (!$json) {
				$frequencies = array(
					'day' => $this->language->get('text_day'),
					'week' => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month' => $this->language->get('text_month'),
					'year' => $this->language->get('text_year'),
				);

				if ($profile_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($profile_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $profile_info['trial_cycle'], $frequencies[$profile_info['trial_frequency']], $profile_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($profile_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($profile_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	

	public function captcha() {
		$this->load->library('captcha');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();
	}

	public function upload() {
		$this->language->load('product/product');

		$json = array();

		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types		
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());

			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

			$json['success'] = $this->language->get('text_upload');
		}	

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

				$shipping_address_country = $country_code;
				$battery_package_limit_weight_country = $this->config->get('battery_package_limit_weight_country');
				if($battery_package_limit_weight_country[$shipping_address_country] > $product_info['weight']){

				}else if($this->config->get('battery_package_limit_weight') > $product_info['weight']){

				}else{
					$can_ship = false;
				}

                if($can_ship){
                    $_result['flag'] = 1;
                    $_result['msg'] = '';
                    setcookie('battery_ship_to',$country_code,  time() + 365 * 24 *60 *60,'/',COOKIE_DOMAIN);
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