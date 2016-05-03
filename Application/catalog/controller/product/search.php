<?php 
class ControllerProductSearch extends Controller { 	
	public function index() {

        $this->config->set('config_catalog_limit',48);

		$this->language->load('product/search');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
        
        $this->load->model('search/search');
        
		$this->load->model('tool/image'); 

		if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
            $search = urldecode($search);
            $search = trim($search);
            $this->request->get['search'] = $search;
		} else {
			$search = '';
		} 
       


		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		} 



		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = '';
		} 

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'asc';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$this->document->addScript('js/jquery/jquery.total-storage.min.js');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' =>$this->language->get('text_separator')
		);

		$url = '';
		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/search', $url),
			'separator' => false
		);

		if (isset($this->request->get['search'])) {
			$this->data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}

		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_category'] = $this->language->get('text_category');
        $this->data['text_any_category'] = $this->language->get('text_any_category');
        $this->data['text_as_low'] = $this->language->get('text_as_low');
        $this->data['text_description'] = $this->language->get('text_description');
        $this->data['text_product_detail'] = $this->language->get('text_product_detail');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');		
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');

		$this->data['entry_search'] = $this->language->get('entry_search');
		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['button_search'] = $this->language->get('button_search');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');

        $this->data['text_no_res'] = sprintf($this->language->get('text_no_res'),$search);
        $this->data['text_suggestions'] = $this->language->get('text_suggestions');
        $this->data['text_suggestions_01'] = $this->language->get('text_suggestions_01');
        $this->data['text_suggestions_02'] = $this->language->get('text_suggestions_02');
        $this->data['text_suggestions_03'] = $this->language->get('text_suggestions_03');

		$this->data['compare'] = $this->url->link('product/compare');
        


        //top sellers
			$top_sellers = $this->model_catalog_product->getBestSellerProducts(5);
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
				'reviews'    => $result['reviews'],
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		//边栏bananer 
		$this->load->model('design/banner');
		$this->data['top_seller']= $this->language->get('top_seller');
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


		$this->load->model('catalog/category');

		// 3 Level Category Search
        /*
		$this->data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}

			$this->data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
        */
		$this->data['products'] = array();
        $sort_arr = array(
                
				'popularity'=>'product.sales_num',
				'price'=>'product.price',	
				'reviews'=>'product.review_rating',	
				'new_arrivals'=>'product.date_added'
		);
		
        $data = array(
            'filter_name'         => $search, 
            'filter_tag'          => $tag, 
            'filter_description'  => $description,
            'filter_category_id'  => $category_id, 
            'filter_sub_category' => $sub_category, 
            'sort'                => $sort_arr[$sort],
            'order'               => $order,
            'start'               => ($page - 1) * $limit,
            'limit'               => $limit
        );
        $keyword  = $search;
        
        $cat_id = '';
        if($category_id>0){
             $cat_id = $category_id;
        }
        $search_cat = array();
        if($cat_id){
            $search_cat = array($cat_id);
        }
        $order_by = array();
        if(isset($sort_arr[$sort])){
             $order = strtolower($order);
            if(in_array($order,array('desc','asc'))){
                $order_by[$sort_arr[$sort]] = strtolower($order);
            }
        } else {
            $order_by['_score'] = 'desc';
        }
        $order_by['sales_num'] = 'desc';
        $start = ($page - 1) * $limit;
        
        $search_sku_product = $this->model_catalog_product->getProductBySKU($keyword);
        if($search_sku_product){
            $search_data =  array();
            $search_data['total'] = 1;
            $search_data['data'] = array($search_sku_product['product_id']);
        }else{
            $search_data = $this->model_search_search->search($keyword,$search_cat,array(),$order_by,$start,$limit);
        }

        $product_total = $search_data['total'];
        
        $catagory_products =array();
        $results = $search_data['data'];
        $hightlight_name = $search_data['hightlight'];
        $this->data['res_count'] = $product_total ;
        foreach ($results as $tmp_product_id) {
            $result = $this->model_catalog_product->getProduct($tmp_product_id);
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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
            if($special ){
                $discount_rate =$this->model_catalog_product->getDiscountPercent($result['special'],$result['price']);
            }
            else{
                $discount_rate=false;
            }
            
            $discounts = $this->model_catalog_product->getProductDiscounts($result['product_id']);
            if($discounts){
                $count = count($discounts);
                 if($result['special']&&($result['special']<$discounts[$count - 1]['price'])){
                    $as_low_as_price = false;
                }
                else{
                   $as_low_as_price = $this->currency->format($this->tax->calculate($discounts[$count - 1]['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                }
            }
            else{
                $as_low_as_price =false;
            }
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
            } else {
                $tax = false;
            }				

            if ($this->config->get('config_review_status')) {
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }
            $product_name = $result['name'];
            $product_hightlight_name = $result['name'];
            if(isset($hightlight_name[$result['product_id']])){
               $product_hightlight_name = $hightlight_name[$result['product_id']];
            }
            //product hot label
            $is_product_hot_label = $this->model_catalog_product->is_product_hot_label($result['model']);

            $this->data['products'][] = array(
                'product_id'  => $result['product_id'],
                'thumb'       => $image,
                'name'        => $product_name,
                'hightlight'  => $product_hightlight_name,
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                'price'       => $price,
                'model'       => $result['model'],
                'special'     => $special,
                'discount_rate'     =>$discount_rate,
                'as_low_as_price'=>$as_low_as_price,
                'tax'         => $tax,
                'rating'      => $result['rating'],
                'reviews'     => (int)$result['reviews'],
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'status'      => $result['status'],
                'stock_status_id' => $result['stock_status_id'],
                'stock_status' => $result['stock_status'],
                'is_product_hot_label' => $is_product_hot_label,
                );
        }  
        if($search_sku_product){
            $search_category = $this->model_catalog_product->getCategories($search_sku_product['product_id']);
            $cat_search_data = array();
            $cat_search_data['data'] = array();
            foreach($search_category as $_row){
                $cat_search_data['data'][$_row['category_id']] = 1;
            }
            $catagory_products = $cat_search_data['data'];
        }else{
            $cat_search_data = $this->model_search_search->getCategoryProductsNumber($keyword,$search_cat,array());
        
            $catagory_products = $cat_search_data;
            
        }
        $this->data['good_lists'] =array();
        if(!$this->data['products']){
            $list_goods =$this->model_catalog_product->getBestSellerProducts(16,true);
            foreach($list_goods as $pro_info){
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
                     if ((float)$pro_info['special']) {
                        $discount_rate = $this->model_catalog_product->getDiscountPercent($pro_info['special'],$pro_info['price']);
                    }
                    else{
                        $discount_rate=false;
                    }  
					if ($pro_info['image']) {
						$image =$this->model_tool_image->resize($pro_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
					} else {
						$image  = false;
					}
					
					$this->data['good_lists'][]=array(
						'product_id' =>$pro_info['product_id'],
						'name' =>$pro_info['name'],
						'image' =>$image,
                        'discount_rate' =>$discount_rate,
                        'is_new'   =>$pro_info['is_new'],
						'format_price'  =>$format_price,
						'format_special'  =>$format_special,
						'href'  =>$this->url->link('product/product','&product_id=' . $pro_info['product_id'])
					);
				}
            }
        }  
        
        
        
        //搜索结果商品的分类分布情况
        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['tag'])) {
            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['description'])) {
            $url .= '&description=' . $this->request->get['description'];
        }
        if (isset($this->request->get['sub_category'])) {
            $url .= '&sub_category=' . $this->request->get['sub_category'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }
        $res_catalog_pro =array();
        ksort($catagory_products);
        foreach($catagory_products as $key=>$item){
            
            $catagory_info =$this->model_catalog_category->getCategory($key);
            if($catagory_info['top']==1){
                 if($catagory_info['level']==1){
                    $res_catalog_pro[$key]=array(
                        'category_id' =>  $catagory_info['category_id'],
                        'name' =>  $catagory_info['name'],
                        'href' =>  $this->url->link('product/search', 'category_id=' . $catagory_info['category_id'].$url),
                        'count'=>  $item,
                        'child' => array()
                    );
                }
                else{
                    if(!isset($res_catalog_pro[$catagory_info['parent_id']])){
                        
                        $parent_catagory_info =$this->model_catalog_category->getCategory($catagory_info['parent_id']);
                        $res_catalog_pro[$catagory_info['parent_id']] =array(
                           'category_id' =>  $parent_catagory_info['category_id'],
                            'name' =>  $parent_catagory_info['name'],
                            'href' =>  $this->url->link('product/search', 'category_id=' . $parent_catagory_info['category_id'].$url),
                            'count'=>  0,
                            'child' =>array()
                        );
                    }
                    //$res_catalog_pro[$catagory_info['parent_id']]['count'] +=count($item);
                    $res_catalog_pro[$catagory_info['parent_id']]['child'][$catagory_info['category_id']] = array(
                           'category_id' =>  $catagory_info['category_id'], 
                           'name' =>  $catagory_info['name'],
                           'href' =>  $this->url->link('product/search', 'category_id=' . $catagory_info['category_id'].$url),
                           'count'=>  $item,
                    );
                    
                }
            } 
        }
        if($category_id){
            $new_res_catalog_pro =array();
            foreach($res_catalog_pro as $key=>$info){
               foreach($info['child'] as $child_key=>$child_cat){
                    if($category_id==$key||$category_id==$child_key){
                        $new_res_catalog_pro[$key] =$res_catalog_pro[$key];
                        $res_catalog_pro =$new_res_catalog_pro;
                    }
               }
            }
        }
        $this->data['res_catalog_pro'] = $res_catalog_pro;
        $this->data['any_category_href'] = $this->url->link('product/search', $url);
        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }



        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $this->data['sorts'] = array();

        if($order&&$order=='asc'){
            $desc_order = 'desc';
        }
        else{
            $desc_order = 'asc';
        }
        
       $this->data['default_sort'] = array(
            'text'  => $this->language->get('text_relevance'),
            'href'  => $this->url->link('product/search',   $url)
        );
        
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_popularity'),
            'value' => 'p.salesnum',
            'code' =>'popularity',
            'href'  => $this->url->link('product/search',  'sort=popularity&order='.$desc_order . $url)
        );
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_price'),
            'value' => 'p.price',
            'code' =>'price',
            'href'  => $this->url->link('product/search', 'sort=price&order='.$desc_order . $url)
        ); 
        if ($this->config->get('config_review_status')) {
            $this->data['sorts'][] = array(
                'text'  => $this->language->get('text_rating'),
                'value' => 'reviews',
                'code' =>'reviews',
                'href'  => $this->url->link('product/search',  'sort=reviews&order='.$desc_order . $url)
            ); 
        }
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_new_arrivals'),
            'value' => 'p.date_added',
            'code' =>'new_arrivals',
            'href'  => $this->url->link('product/search', 'sort=new_arrivals&order=' .$desc_order. $url)
        );

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }



        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }	

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $this->data['limits'] = array();

        $limits = array_unique(array($this->config->get('config_catalog_limit'), 36,48,72));

        sort($limits);

        foreach($limits as $value){
            $this->data['limits'][] = array(
                'text'  => $value,
                'value' => $value,
                'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
            );
        }

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }



        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }



        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }	

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/search', $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();
			
		$this->data['search'] = $search;
		$this->data['description'] = $description;
		$this->data['category_id'] = $category_id;
		$this->data['sub_category'] = $sub_category;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/search.tpl';
		} else {
			$this->template = 'default/template/product/search.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

    public function suggest(){
        $keyword = $_GET['q'];
        $keyword = trim($keyword);
        if(strlen($keyword)<2){
            $keyword = $keyword.'*';
        }
        $this->load->model('search/search');
        $data = $this->model_search_search->suggest($keyword);
        foreach($data['data'] as $item){
            echo $item ."\n";
        }
    }
}
?>