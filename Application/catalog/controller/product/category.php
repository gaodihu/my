<?php

class ControllerProductCategory extends Controller {

    public function index() {
        $this->language->load('product/category');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');
        $this->document->addStyle('css/stylesheet/icheck.css');
        $this->document->addScript('js/jquery/jquery.icheck.min.js');
        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

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

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => '/',
            'child' => false,
            'separator' => $this->language->get('text_separator')
        );
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
            if (!$category_info) {
                $url_404 = $this->url->link('error/not_found');
                header('HTTP/1.1 404 Not Found');
                header("status: 404 not found");
                header("location:" . $url_404 . "");
                exit();
            }
            $current_category_info = $category_info;
            $path = $category_info['path'];
            if (substr($path, 0, 2) == '0/') {
                $path = substr($path, 2);
            }
            $parts = explode('/', $path);
            array_pop($parts);
            $category_breadcrumbs = $this->cache->get('category-breadcrumbs-' . $category_id . '.' . $this->config->get('config_language_id') . '.' . $this->config->get('config_store_id'));
            if ($category_breadcrumbs) {
                $this->data['breadcrumbs'] = $category_breadcrumbs;
            } else {
                foreach ($parts as $path_id) {
                    if (!$path) {
                        $path = (int) $path_id;
                    } else {
                        $path .= '_' . (int) $path_id;
                    }
                    
                    $category_info = $this->model_catalog_category->getCategory($path_id);
                     
                    if ($category_info) {
                        $this->data['parent_category_info'] = $category_info;
                        $child_cat = array();
                        $child_cat_info = $this->model_catalog_category->getCategories($path_id);
                        foreach ($child_cat_info as $key => $item) {
                            $child_cat[$key]['name'] = $item['name'];
                            if ($item['url_path']) {
                                $child_cat[$key]['href'] = $item['url_path'];
                            } else {
                                $child_cat[$key]['href'] = $this->url->link('product/category', 'path=' . $category_info['category_id'] . "_" . $item['category_id']);
                            }
                        }
                        $cat_url = '';
                        if ($category_info['url_path']) {
                            $cat_url = $category_info['url_path'];
                        } else {
                            $cat_url = $this->url->link('product/category', 'path=' . $category_info['category_id']);
                        }
                        $this->data['breadcrumbs'][] = array(
                            'text' => $category_info['name'],
                            'href' => $cat_url,
                            'child' => $child_cat,
                            'separator' => $this->language->get('text_separator')
                        );
                    }
                }
                $this->cache->set('category-breadcrumbs-' . $category_id . '.' . $this->config->get('config_language_id') . '.' . $this->config->get('config_store_id'),$this->data['breadcrumbs']);
            }
        }
        $category_info = $current_category_info;
        $this->data['category_info'] = $category_info;
        if ($category_info) {
            $this->document->setTitle($category_info['title']);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);
            //$this->document->addScript('js/jquery/jquery.total-storage.min.js');

            $this->data['heading_title'] = $category_info['name'];

            $this->data['text_refine'] = $this->language->get('text_refine');
            $this->data['text_empty'] = $this->language->get('text_empty');
            $this->data['text_quantity'] = $this->language->get('text_quantity');
            $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $this->data['text_model'] = $this->language->get('text_model');
            $this->data['text_price'] = $this->language->get('text_price');
            $this->data['text_tax'] = $this->language->get('text_tax');
            $this->data['text_points'] = $this->language->get('text_points');
            $this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $this->data['text_display'] = $this->language->get('text_display');
            $this->data['text_list'] = $this->language->get('text_list');
            $this->data['text_grid'] = $this->language->get('text_grid');
            $this->data['text_sort'] = $this->language->get('text_sort');
            $this->data['text_limit'] = $this->language->get('text_limit');
            $this->data['shop_by_category'] = $this->language->get('shop_by_category');
            $this->data['text_as_low'] = $this->language->get('text_as_low');
            $this->data['text_description'] = $this->language->get('text_description');
            $this->data['text_product_detail'] = $this->language->get('text_product_detail');
            $this->data['text_more'] = $this->language->get('text_more');
            $this->data['text_less'] = $this->language->get('text_less');
            $this->data['text_view_all'] = $this->language->get('text_view_all');
            $this->data['text_hide'] = $this->language->get('text_hide');

            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_wishlist'] = $this->language->get('button_wishlist');
            $this->data['button_compare'] = $this->language->get('button_compare');
            $this->data['button_continue'] = $this->language->get('button_continue');
            //$this->mylog($this->data);
            // Set the last category breadcrumb		
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
            $this->data['no_filter_url'] = $this->url->link('product/category', 'path=' . $this->request->get['path'].$url);
            $this->data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                //'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
                'href' => false,
                'child' => false,
                'separator' => false
            );
            if ($category_info['seo_image']) {
                $this->data['seo_thumb'] = $this->model_tool_image->resize($category_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT);
            } else {
                $this->data['seo_thumb'] = '';
            }

            if ($category_info['small_image']) {
                $this->data['small_thumb'] = $this->model_tool_image->resize($category_info['small_image'], CATALOG_SMALL_IMG_WIDTH, CATALOG_SMALL_IMG_HEIGHT);
            } else {
                $this->data['small_thumb'] = '';
            }

            $this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            $this->data['compare'] = $this->url->link('product/compare');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
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
            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            /*
            $menu_catlog = array(256, 253, 254);
            if (in_array($category_id, $menu_catlog)) {
                $this->data['is_menu_catlog'] = true;
                //边栏special 专题banner
                $this->data['text_hot'] = $this->language->get('text_hot');
                $this->load->model('design/banner');
                $special_lists = $this->model_design_banner->getBannerByCode('special_history_baner');
                if ($special_lists) {
                    foreach ($special_lists as $side_banner) {
                        if ($side_banner['image']) {
                            $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                        } else {
                            $image = false;
                        }
                        $this->data['special_lists'][] = array(
                            'link' => $side_banner['link'],
                            'image' => $image,
                            'title' => $side_banner['title']
                        );
                    }
                } else {
                    $this->data['special_lists'] = array();
                }
            } else {
                $this->data['is_menu_catlog'] = false;
            }
            */

            //top sellers
            /*
            $top_sellers = $this->model_catalog_product->getBestSellerProductsByCatgory(5,$parent_category_id);
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

                if ((float) $result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }
                $best_product_url = '';
                if ($result['url_path']) {
                    $best_product_url = $result['url_path'];
                } else {
                    $best_product_url = $this->url->link('product/product', 'product_id=' . $result['product_id']);
                }
                $this->data['top_selles'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'price' => $price,
                    'special' => $special,
                    'rating' => $rating,
                    'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
                    'href' => $best_product_url,
                );
            }
            */

            //商品头部banner
            /*
            $this->load->model('design/banner');
            $top_banner_info = $this->model_design_banner->getBannerByCodeByCategory('catagory_top_banner',$category_id);
            if ($top_banner_info) {
                foreach ($top_banner_info as $side_banner) {
                    if ($side_banner['image']) {
                        $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                    } else {
                        $image = false;
                    }
                    $this->data['top_banner_info'][] = array(
                        'link' => $side_banner['link'],
                        'image' => $image,
                        'title' => $side_banner['title']
                    );
                }
            } else {
                $this->data['top_banner_info'] = array();
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
            */
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

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
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
            //$this->data['AttrbuteGroup'] = $AttrbuteGroup;

            /*
             *  得到左侧分类商品分布信息
             */
            // Set the last category breadcrumb		
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
            if (isset($this->request->get['attr_id'])) {
                $url .= '&attr_id=' . $this->request->get['attr_id'];
            }
            if (isset($this->request->get['option_id'])) {
                $url .= '&option_id=' . $this->request->get['option_id'];
            }
            if (isset($this->request->get['price_range'])) {
                $url .= '&price_range=' . $this->request->get['price_range'];
            }
            $this->data['categories'] = array();
            if ($category_info['level'] == 1) {
                $filter_data = array(
                    'filter_category_id' => $category_info['category_id'],
                    'filter_sub_category' => false,
                    'attr_id' => $attr_id,
                    'option_id' => $option_id,
                    'filter_group_id' => $filter_group_id,
                    'price_range' => $filter_price_range,
                );
                 if(ELASTICSEARCH_CATRGORY_ENABLE){
                     $product_total = $this->model_catalog_product->getTotalProductsByElasticsearch($filter_data);
                     if(isset($product_total[$category_info['category_id']])){
                         $current_category_total = $product_total[$category_info['category_id']];
                     }else{
                         $current_category_total = 0;
                     }
                     
                     $this->data['categories']['self'] = array(
                        'name' => $category_info['name'],
                        'level' => $category_info['level'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $current_category_total . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($category_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                     
                    $results = $this->model_catalog_category->getCategories($category_info['category_id']);
                    foreach ($results as $result) {
                        $data = array(
                            'filter_category_id' => $result['category_id'],
                            'filter_sub_category' => false,
                            'attr_id' => $attr_id,
                            'option_id' => $option_id
                        );
                        if(isset($product_total[$result['category_id']])){
                            $_category_product_number = $product_total[$result['category_id']];
                        }else{
                            $_category_product_number = 0;
                        }
                        
                        $this->data['categories']['child'][] = array(
                            'name' => $result['name'],
                            'pro_total' => $this->config->get('config_product_count') ? ' (' . $_category_product_number . ')' : '',
                            'small_image' => $this->model_tool_image->resize($result['small_image'], 210, 150),
                            'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'] . "_" . $result['category_id'] . $url, 'SSL')
                        );
                    }
                 }else{
                    $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    $this->data['categories']['self'] = array(
                        'name' => $category_info['name'],
                        'level' => $category_info['level'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $product_total . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($category_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                    $results = $this->model_catalog_category->getCategories($category_info['category_id']);
                    foreach ($results as $result) {
                        $data = array(
                            'filter_category_id' => $result['category_id'],
                            'filter_sub_category' => false,
                            'attr_id' => $attr_id,
                            'option_id' => $option_id
                        );
                        $product_total = $this->model_catalog_product->getTotalProducts($data);
                        $this->data['categories']['child'][] = array(
                            'name' => $result['name'],
                            'pro_total' => $this->config->get('config_product_count') ? ' (' . $product_total . ')' : '',
                            'small_image' => $this->model_tool_image->resize($result['small_image'], 210, 150),
                            'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'] . "_" . $result['category_id'] . $url, 'SSL')
                        );
                    }
                 }
                $this->data['categories']['parent'] = array();
            } elseif ($category_info['level'] == 2) {
                $filter_data = array(
                    'filter_category_id' => $category_info['category_id'],
                    'filter_sub_category' => false,
                    'attr_id' => $attr_id,
                    'option_id' => $option_id,
                    'filter_group_id' => $filter_group_id,
                    'price_range' => $filter_price_range,
                );
                if(ELASTICSEARCH_CATRGORY_ENABLE){
                    $product_total = $this->model_catalog_product->getTotalProductsByElasticsearch($filter_data);
                    if(isset($product_total[$category_info['category_id']])){
                        $_category_product_number = $product_total[$category_info['category_id']];
                    }else{
                        $_category_product_number = 0;
                    }
                    
                    $this->data['categories']['self'] = array(
                        'name' => $category_info['name'],
                        'level' => $category_info['level'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $_category_product_number . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $category_info['parent_id'] . '_' . $category_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($category_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                    
                    $parent_info = $this->model_catalog_category->getCategory($category_info['parent_id']);
                    if(isset($product_total[$parent_info['category_id']])){
                        $_category_product_number = $product_total[$parent_info['category_id']];
                    }else{
                        $_category_product_number = 0;
                    }
                    
                    $this->data['categories']['parent'] = array(
                        'name' => $parent_info['name'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $_category_product_number . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $parent_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($parent_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                } else {
                    $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    $this->data['categories']['self'] = array(
                        'name' => $category_info['name'],
                        'level' => $category_info['level'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $product_total . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $category_info['parent_id'] . '_' . $category_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($category_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                    $filter_data = array(
                        'filter_category_id' => $category_info['parent_id'],
                        'filter_sub_category' => false,
                        'attr_id' => $attr_id,
                        'option_id' => $option_id
                    );
                    $parent_info = $this->model_catalog_category->getCategory($category_info['parent_id']);
                    $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    $this->data['categories']['parent'] = array(
                        'name' => $parent_info['name'],
                        'pro_total' => $this->config->get('config_product_count') ? ' (' . $product_total . ')' : '',
                        'href' => $this->url->link('product/category', 'path=' . $parent_info['category_id'] . $url, 'SSL'),
                        'seo_img' => $this->model_tool_image->resize($parent_info['seo_image'], CATALOG_SEO_IMG_WIDTH, CATALOG_SEO_IMG_HEIGHT)
                    );
                }
                $this->data['categories']['child'] = array();
            }
            //分类商品信息
            $this->data['products'] = array();
            $sort_arr = array(
                'popularity' => 'sales_num',
                'price' => 'p.price',
                'reviews' => 'toal_review',
                'new_arrivals' => 'p.date_added'
            );
            $data = array(
                'filter_category_id' => $category_id,
                'filter_filter' => $filter,
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
            if($results && is_array($results)) {
                foreach ($results as $result) {
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

                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }
                    if ($special) {
                        $discount_rate = $this->model_catalog_product->getDiscountPercent($result['special'],$result['price']);
                    } else {
                        $discount_rate = false;
                    }

                    $discounts = $this->model_catalog_product->getProductDiscounts($result['product_id']);
                    if ($discounts) {
                        $count = count($discounts);
                        if($result['special']&&($result['special']<$discounts[$count - 1]['price'])){
                            $as_low_as_price = false;
                        }
                        else{
                           $as_low_as_price = $this->currency->format($this->tax->calculate($discounts[$count - 1]['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                        }
                    } else {
                        $as_low_as_price = false;
                    }
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = (int) $result['rating'];
                    } else {
                        $rating = false;
                    }
                    $tmp_product_url = '';

                    if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                        $server = $this->config->get('config_ssl');
                    } else {
                        $server = $this->config->get('config_url');
                    }
                    if ($result['url_path']) {
                        $tmp_product_url = $result['url_path'];
                        $tmp_js_href=$server.$tmp_product_url ;
                    } else {
                        $tmp_product_url = $this->url->link('product/product', 'product_id=' . $result['product_id']);
                        $tmp_js_href=$server.$tmp_product_url;
                    }
                    
                    $battery_type = $this->config->get('battery_type');
                    $_is_battery = 0;
                    if(in_array($result['battery_type'],$battery_type)){    
                        $_is_battery  = 1;
                    }

                    //product hot label
                    $is_product_hot_label = $this->model_catalog_product->is_product_hot_label($result['model']);

                    $this->data['products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
                        'name' => $result['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 180) . '...',
                        'meta_keyword' => $result['meta_keyword'],
                        'price' => $price,
                        'model' => $result['model'],
                        'special' => $special,
                        'discount_rate' => $discount_rate,
                        'as_low_as_price' => $as_low_as_price,
                        'tax' => $tax,
                        'rating' => $result['rating'],
                        'reviews' => (int) $result['reviews'],
                        'is_new' => (int) $result['is_new'],
                        'href' => $tmp_product_url,
                        'js_href' =>$tmp_js_href,
                        'is_battery' => $_is_battery,
                        'is_product_hot_label' => $is_product_hot_label,
                    );
                }
            }
            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }
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
            if (isset($this->request->get['order'])) {
                if ($order && $order == 'ASC') {
                    $desc_order = 'DESC';
                } else {
                    $desc_order = 'ASC';
                }
            }else{
                $desc_order = 'DESC';
            }

            $sort_arr = array(
                'popularity' => 'p.salesnum',
                'price' => 'p.price',
                'reviews' => 'toal_review',
                'new_arrivals' => 'p.date_added'
            );
            if(isset($this->request->get['sort']) && $this->request->get['sort'] == 'popularity'){
                if($this->request->get['order'] == 'ASC'){
                    $popularity_desc_order = 'DESC';
                }else{
                    $popularity_desc_order = 'ASC';
                }
            }else{
                if(!isset($this->request->get['sort'])){
                    $popularity_desc_order = 'ASC';
                }else{
                    $popularity_desc_order = 'DESC';
                }

            }
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_popularity'),
                'value' => 'p.salesnum',
                'code' => 'popularity',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=popularity&order=' . $popularity_desc_order . $url)
            );

            if(isset($this->request->get['sort']) && $this->request->get['sort'] == 'price'){
                if($this->request->get['order'] == 'ASC'){
                    $price_desc_order = 'DESC';
                }else{
                    $price_desc_order = 'ASC';
                }
            }else{
                $price_desc_order = 'ASC';
            }
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price'),
                'value' => 'p.price',
                'code' => 'price',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=price&order=' . $price_desc_order . $url)
            );
            if(isset($this->request->get['sort']) && $this->request->get['sort'] == 'reviews'){
                if($this->request->get['order'] == 'ASC'){
                    $reviews_desc_order = 'DESC';
                }else{
                    $reviews_desc_order = 'ASC';
                }
            }else{
                $reviews_desc_order = 'DESC';
            }
            if ($this->config->get('config_review_status')) {
                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_rating'),
                    'value' => 'reviews',
                    'code' => 'reviews',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=reviews&order=' . $reviews_desc_order . $url)
                );
            }
            if(isset($this->request->get['sort']) && $this->request->get['sort'] == 'new_arrivals'){
                if($this->request->get['order'] == 'ASC'){
                    $new_arrivals_desc_order = 'DESC';
                }else{
                    $new_arrivals_desc_order = 'ASC';
                }
            }else{
                $new_arrivals_desc_order = 'DESC';
            }
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_new_arrivals'),
                'value' => 'p.date_added',
                'code' => 'new_arrivals',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=new_arrivals&order=' . $new_arrivals_desc_order . $url)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }
            
             if (isset($this->request->get['attr_id'])) {
                $url .= '&attr_id=' . $this->request->get['attr_id'];
            }
            if (isset($this->request->get['option_id'])) {
                $url .= '&option_id=' . $this->request->get['option_id'];
            }
            if (isset($this->request->get['price_range'])) {
                $url .= '&price_range=' . $this->request->get['price_range'];
            }
            $this->data['limits'] = array();

            $limits = array_unique(array($this->config->get('config_catalog_limit'),30,50,80));
            //$limits = array(48, 96, 144);

            sort($limits);

            foreach ($limits as $value) {
                $this->data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }
            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
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
             if (isset($this->request->get['attr_id'])) {
                $url .= '&attr_id=' . $this->request->get['attr_id'];
            }
            if (isset($this->request->get['option_id'])) {
                $url .= '&option_id=' . $this->request->get['option_id'];
            }
            if (isset($this->request->get['price_range'])) {
                $url .= '&price_range=' . $this->request->get['price_range'];
            }
            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
            $this->data['pagination'] = $pagination->render();
            $this->data['sort'] = $sort;
            $this->data['order'] = $order;
            $this->data['limit'] = $limit;
            $this->data['page'] = $page;

            $this->data['continue'] = $this->url->link('common/home');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/product/category.tpl';
            } else {
                $this->template = 'default/template/product/category.tpl';
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

?>