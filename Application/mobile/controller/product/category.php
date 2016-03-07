<?php

class ControllerProductCategory extends Controller {

    public function index() {
        $lang =$this->language->load('product/category');
        $this->data = array_merge($this->data,$lang);
        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $this->document->addScript('mobile/view/js/Cart.js');

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'popularity';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        $limit =6;
        $page =1;
        if (!isset($this->request->get['path'])) {
            $url_404 = $this->url->link('error/not_found');
            header('HTTP/1.1 404 Not Found');
            header("status: 404 not found");
            header("location:" . $url_404 . "");
            exit();
        }
        if (isset($this->request->get['path'])) {
            $path = '';
            $parts = explode('_', (string) $this->request->get['path']);
            $parent_category_id =$parts[0];
            $category_id = (int) array_pop($parts);
            $category_info = $this->model_catalog_category->getCategory($category_id);
            $this->data['category_info'] = $category_info;
            if ($category_info) {
                $this->document->setTitle($category_info['title']);
                $this->document->setDescription($category_info['meta_description']);
                $this->document->setKeywords($category_info['meta_keyword']);
                //$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

                $this->data['heading_title'] = $category_info['name'];

               
                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }
                //分类筛选
                /* 得到该分类对应的属性组 */

               
                $attr_id = isset($this->request->get['attr_id']) ? $this->request->get['attr_id'] : '';
                $option_id = isset($this->request->get['option_id']) ? $this->request->get['option_id'] : '';
                
                
                if ($category_info['level'] == 1) {
                    $filter_group_id = $this->model_catalog_category->getGroupId($category_id);
                }else if ($category_info['level'] == 2){
                    $filter_group_id =$this->model_catalog_category->getGroupId($category_info['parent_id']);
                }
                if($filter_group_id === false){
                    $filter_group_id = 0;
                }
                
                //暂时关闭属性筛选
                if(!ELASTICSEARCH_CATRGORY_ENABLE){
                    $attr_id  ='';
                    $option_id='';
                }
                //得到已选择的属性组合
                $select_option_array = explode('-', $option_id);
                $select_attr_array = explode('-', $attr_id);
                foreach($select_attr_array as $key => $val){
                    $select_attr_array[$key] = intval($val);
                }
                foreach($select_option_array as $key => $val){
                    $select_option_array[$key] = intval($val);
                }
                
                
               $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }
                if(ELASTICSEARCH_CATRGORY_ENABLE){
                  $select_option = array();
                  $filter_price_range = '';
                  if (isset($this->request->get['price_range'])) {
                      $price_range_id = intval($this->request->get['price_range']);
                      $price_range_value = $this->model_catalog_category->getPriceRangeValue($filter_group_id,$price_range_id);
                      
                      if($price_range_value){
                          $filter_price_range = $price_range_value;
                      }
                  }  
                   if(!$filter_price_range){
                        $price_range_option =  array();
                        $price_range_list = $this->model_catalog_category->getPriceRange($filter_group_id);
                        if($price_range_list && is_array($price_range_list)) {
                            foreach($price_range_list as $_item){
                                $href = "";
                                $selected_href = '';
                                if($attr_id || $option_id){
                                   $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $attr_id . '&option_id=' . $option_id ."&price_range=" .$_item['pf_id'] . $url);
                                   $selected_href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $attr_id . '&option_id=' . $option_id . $url);
                                }else{
                                   $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] ."&price_range=" .$_item['pf_id'] . $url);
                                   $selected_href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url);
                                }
                                $option_value = '';
                                if($_item['start'] && $_item['end']){
                                    $option_value = $this->currency->format($_item['start']).'-'. $this->currency->format($_item['end']);
                                }
                                if($_item['start'] && !$_item['end']){
                                    $option_value = $this->currency->format($_item['start']).'+';
                                }
                                $_range_option = array(
                                    'price_range' => $_item['pf_id'],
                                    'start' => $_item['start'],
                                    'end'   => $_item['end'],
                                    'href'  => $href,
                                    'option_id' => 'price_range_' . $_item['pf_id'],
                                    'option_value' => $option_value,
                                    'selected_href' => $selected_href,
                                );
                                $price_range_option[] = $_range_option;

                            }
                        }
                        
                   
                        $_price_range_option = $this->model_catalog_category->getCategoriesPriceRangeFromElasticsearch($filter_group_id,'',$category_id,$select_attr_array,$attr_id,$option_id,$filter_price_range,$price_range_option);

                        //print_r($_price_range_option);
                        $_price_range_list = array();
                        foreach($price_range_option as $_item){
                            $_price_range_k =  $_item['price_range'];
                            if(isset($_price_range_option[$_price_range_k]) && $_price_range_option[$_price_range_k] > 0 ){
                                $_price_range_list[] = $_item;
                            }
                        }
                        $this->data['price_range_list'] = $_price_range_list;
                        
                        
                    }else{
                        $this->data['price_range_list'] = '';
                        
                        
                        $_price_option_info = array(
                                'option_id' => '',
                                'attribute_id' => '',
                                'option_value' => $this->currency->format($filter_price_range['start']).'-'. $this->currency->format($filter_price_range['end']),
                        );
                        if ($attr_id || $option_id) {
                                $_price_option_info['href'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $attr_id . '&option_id=' . $option_id . $url);
                            } else {
                                $_price_option_info['href'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url);
                        }
                        $select_option[] = $_price_option_info;
                        
                        $url .= "&price_range=" .$this->request->get['price_range'];
                    }
                    
                    
           
                   $AttrbuteGroup = $this->model_catalog_category->getCategoriesAttrbuteGroup($filter_group_id,$category_id,$select_attr_array,$attr_id,$option_id,$filter_price_range,$price_range_option);
                    
                    
                }else{
                    $AttrbuteGroup = array();
                }
                $select_option_array_count = count($select_option_array);
                
                if ($option_id) {
                    foreach ($select_option_array as $key => $op_id) {
                        $_item_attr_id = $select_attr_array[$key];
                        $filter_type = $this->model_catalog_category->getGroupAttributeFilterType($filter_group_id,$_item_attr_id);
                        
                        if($filter_type == 1){
                            $op_id = intval($op_id);
                            $option_info = $this->model_catalog_category->getOptionInfo($op_id);
                        }else if($filter_type == 2){
                            $filter_id = intval($op_id);
                            $filter_info = $this->model_catalog_category->getFilterName($filter_id);
                            $option_info = array(
                                'option_id' => $filter_id,
                                'attribute_id' => $_item_attr_id,
                                'option_value' => $filter_info['name'],
                            );
                        }
                        if ($select_option_array_count > 1) {
                            $del_attr = $select_attr_array;
                            $del_option = $select_option_array;
                            unset($del_attr[$key]);
                            unset($del_option[$key]);
                            $del_attr_id = implode('-', $del_attr);
                            $del_option_id = implode('-', $del_option);
                            $option_info['href'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $del_attr_id . '&option_id=' . $del_option_id . $url);
                        } else {
                            $option_info['href'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url);
                        }
                        
                        $select_option[] = $option_info;
                    }
                }
                
                
                //print_r($select_option);
                $this->data['select_option'] = $select_option;
                $this->data['AttrbuteGroup'] = array();
                foreach ($AttrbuteGroup as $k1 => $attr_tmp) {
                    foreach ($attr_tmp as $key => $attr) {
                        $new_option = array();
                        foreach ($attr['option'] as $option) {
                            if ($attr_id || $option_id) {
                                $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $attr_id . '-' . $attr['attribute_id'] . '&option_id=' . $option_id . "-" . $option['option_id'] . $url);
                            } else {
                                $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&attr_id=' . $attr['attribute_id'] . '&option_id=' . $option['option_id'] . $url);
                            }

                            $option['href'] = $href;
                            $new_option[] = $option;
                        }
                        unset($attr['option']);
                        $attr['option'] = $new_option;
                        $this->data['AttrbuteGroup'][] = $attr;
                    }
                }
                //var_dump($this->data['AttrbuteGroup']);exit;
               //var_dump($this->data['price_range_list']);exit;
                //分类商品信息
                $this->data['products'] = array();
                $sort_arr = array(
                    'popularity' => 'sales_num',
                    'price' => 'p.price',
                    'reviews' => 'toal_review',
                    'new_arrivals' => 'p.date_added'
                );
                $filter = array(
                    'category_id' => $category_id,
                    'sort' => $sort,
                    'order' => $order,
                    'attr_id' => $attr_id,
                    'option_id' => $option_id,
                    'page' => $page,
                    'limit' => $limit,
                    'price_range' => $filter_price_range,
                );
                $pro_list =$this->GetProList($filter);
                $this->data['total_product'] =$pro_list['total'] ;
                $this->data['products'] =$pro_list['product'] ;
                if($pro_list['total']>$limit){
                     $this->data['fanye_show'] =1;
                }else{
                     $this->data['fanye_show'] =0;
                }
                
                $url = '';
                /*
                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }
                */
                 if (isset($this->request->get['attr_id'])) {
                    $url .= '&attr_id=' . $this->request->get['attr_id'];
                }
                if (isset($this->request->get['option_id'])) {
                    $url .= '&option_id=' . $this->request->get['option_id'];
                }
                if (isset($this->request->get['price_range'])) {
                    $url .= '&price_range=' . $this->request->get['price_range'];
                }
                
                $this->data['sorts'] = array();
                if ($order && $order == 'ASC') {
                    $desc_order = 'DESC';
                } else {
                    $desc_order = 'ASC';
                }
                $this->data['sorts']['popularity'] = array(
                    'text' => $this->language->get('text_popularity'),
                    'value' => 'p.salesnum',
                    'code' => 'popularity',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=popularity&order=' . $desc_order . $url)
                );
                $this->data['sorts']['price'] = array(
                    'text' => $this->language->get('text_price'),
                    'value' => 'p.price',
                    'code' => 'price',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=price&order=' . $desc_order . $url)
                );
                /*
                if ($this->config->get('config_review_status')) {
                    $this->data['sorts'][] = array(
                        'text' => $this->language->get('text_rating'),
                        'value' => 'reviews',
                        'code' => 'reviews',
                        'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=reviews&order=' . $desc_order . $url)
                    );
                }
                */
                $this->data['sorts']['new_arrivals'] = array(
                    'text' => $this->language->get('text_new_arrivals'),
                    'value' => 'p.date_added',
                    'code' => 'new_arrivals',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=new_arrivals&order=' . $desc_order . $url)
                );
                $this->data['current_sort_info'] =$this->data['sorts'][$sort];
               
                $this->data['sort'] = $sort;
                $this->data['order'] = $order;
                
                $this->data['continue'] = $this->url->link('common/home');

                $this->data['cart_url'] = $this->url->link('checkout/cart');
                $url = '';
                 if (isset($this->request->get['attr_id'])) {
                    $url .= '&attr_id=' . $this->request->get['attr_id'];
                }
                if (isset($this->request->get['option_id'])) {
                    $url .= '&option_id=' . $this->request->get['option_id'];
                }
                if (isset($this->request->get['price_range'])) {
                    $url .= '&price_range=' . $this->request->get['price_range'];
                }
                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }
                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }
                $this->data['json_list_url'] =$this->url->link('product/category/get_product_list', 'cid=' . $category_id . $url);
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
                    $this->template =$this->config->get('config_template') . '/template/product/category.tpl';
                } else{
                    $this->template ='default/template/product/category.tpl';
                }
                $this->children = array(
                    'common/footer',
                    'common/header'
                );

                $this->response->setOutput($this->render());
            } else {
                $url_404 = $this->url->link('error/not_found');
                header('HTTP/1.1 404 Not Found');
                header("status: 404 not found");
                header("location:" . $url_404 . "");
            }
        }
    }


    public function all(){
        
        $lang =$this->language->load('product/all_category');
        $this->data = array_merge($this->data,$lang);
        $this->document->setTitle($this->language->get('text_title'));
        $this->document->setDescription($this->language->get('text_description'));
        $this->document->setKeywords($this->language->get('text_keyword'));
        $this->load->model('catalog/category');
        $this->data['categories'] = array();
		//$this->data['categories'] = $this->cache->get('header.categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
		if(!$this->data['categories']){
			$categories = $this->model_catalog_category->getCategories(0);
			foreach ($categories	as $category) {
				if ($category['top']) {
					// Level 2
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);
                    
					foreach ($children as $child) {
						$data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						//$product_total = $this->model_catalog_product->getTotalProducts($data);
                        $url = '';
                        if($child['url_path']){
                            $url = $child['url_path'];
                        } else {
                           $url =  $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                        }
						$children_data[] = array(
							'name'  => $child['name'],
							'href'  => $url
						);						
					}
					// Level 1
                    $url = '';
                    if($category['url_path']){
                        $url = $category['url_path'];
                    } else {
                       $url =  $this->url->link('product/category', 'path=' . $category['category_id']);
                    }
					$this->data['categories'][]= array(
						'name'     => $category['name'],
						'href'     => $url,
                        'children' => $children_data,
						
					);
					
				}
			}
		}
	    $this->cache->set('header.categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'),$this->data['categories']);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/all_category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/all_category.tpl';
		} else {
			$this->template = 'default/template/product/all_category.tpl';
		}
        $this->children = array(
            'common/footer',
            'common/header'
        );
        $this->response->setOutput($this->render());
    }

    public function GetProList($filter){
        $this->language->load('product/category');
        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');
        $sort_arr = array(
            'popularity' => 'sales_num',
            'price' => 'p.price',
            'reviews' => 'toal_review',
            'new_arrivals' => 'p.date_added'
        );
        $category_id =$filter['category_id'];
        $category_info = $this->model_catalog_category->getCategory($category_id);
        if ($category_info['level'] == 1) {
             $filter_group_id = $this->model_catalog_category->getGroupId($category_id);
        }else if ($category_info['level'] == 2){
            $filter_group_id =$this->model_catalog_category->getGroupId($category_info['parent_id']);
        }
        if($filter_group_id === false){
            $filter_group_id = 0;
        }
        $sort =$filter['sort'];
        $order =$filter['order'];
        $attr_id =$filter['attr_id'];
        $option_id =$filter['option_id'];
        $filter_price_range =$filter['price_range'];
        $page =$filter['page'];
        $limit =$filter['limit'];
        $data = array(
            'filter_category_id' => $category_id,
            'sort' => $sort_arr[$sort],
            'order' => $order,
            'attr_id' => $attr_id,
            'option_id' => $option_id,
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
            'filter_group_id' => $filter_group_id,
            'price_range' => $filter_price_range,
        );
        if(ELASTICSEARCH_CATRGORY_ENABLE){
            $product_data = $this->model_catalog_product->getProductsByElasticsearch($data);
            $product_total = $product_data['number'];
            $results = $product_data['data'];
        }else{
            $product_total = $this->model_catalog_product->getTotalProducts($data);
            $results = $this->model_catalog_product->getProducts($data);
        }
         
        $pro_data =array();
        $pro_data['total'] =$product_total ;
        if($results && is_array($results)) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 170,170);
                } else {
                    $image = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
               
                if ($this->config->get('config_review_status')) {
                    $rating = (int) $result['rating'];
                } else {
                    $rating = false;
                }
                $tmp_product_url = '';

               
                if ($result['url_path']) {
                    $tmp_product_url = $result['url_path'];
                } else {
                    $tmp_product_url = $this->url->link('product/product', 'product_id=' . $result['product_id']);
                }
                
                $battery_type = $this->config->get('battery_type');
                $_is_battery = 0;
                if(in_array($result['battery_type'],$battery_type)){    
                    $_is_battery  = 1;
                } 
                
                $pro_data['product'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'price' => $price,
                    'model' => $result['model'],
                    'special' => $special,
                    'rating' => $result['rating'],
                    'reviews' => (int) $result['reviews'],
                    'href' => $tmp_product_url,
                    'add_cart' =>$this->language->get('button_cart'),
                    'is_battery' => $_is_battery,
                );
            }
        }
        return $pro_data;
    }
    
    public function get_product_list(){
        $category_id =isset($this->request->get['cid'])?$this->request->get['cid']:'';
        $sort =isset($this->request->get['sort'])?$this->request->get['sort']:'';
        $order =isset($this->request->get['order'])?$this->request->get['order']:'';
        $attr_id =isset($this->request->get['attr_id'])?$this->request->get['attr_id']:'';
        $option_id =isset($this->request->get['option_id'])?$this->request->get['option_id']:'';
        $filter_price_range =isset($this->request->get['price_range'])?$this->request->get['price_range']:'';
        $page =isset($this->request->get['page'])?$this->request->get['page']:0;
        $limit =6;
        if($page){
            $filter = array(
                    'category_id' => $category_id,
                    'sort' => $sort,
                    'order' => $order,
                    'attr_id' => $attr_id,
                    'option_id' => $option_id,
                    'page' => $page,
                    'limit' => $limit,
                    'price_range' => $filter_price_range,
                );
            $pro_list =$this->GetProList($filter);
            $json['error']=0;
            $json['data']=$pro_list['product'];
        }else{
            $json['error']=1;
            $json['message']='load fialed! please try again';
        }
        $this->response->setOutput(json_encode($json));
    }

}

?>