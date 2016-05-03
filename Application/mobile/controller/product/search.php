<?php 
class ControllerProductSearch extends Controller { 	
	public function index() { 
		$lang =$this->language->load('product/search');
        $this->data =array_merge($this->data,$lang);
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
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = '';
		} 

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
        

		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

        $this->document->addScript('mobile/view/js/pagescroll.js');

		if (isset($this->request->get['search'])) {
			$this->data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}
        $page = 1;
        $limit =6;
		$this->data['products'] = array();
        $filte =array(
            'search'         => $search, 
            'sort'                => $sort,
            'order'               => $order,
            'page'               => $page,
            'limit'               => $limit 
        );
        $pro_list =$this->getProductList($filte);
        if(!$pro_list['product']){
            $this->data['no_res'] =sprintf($this->language->get('text_no_res'),$search);
        }else{
            $this->data['no_res'] ='';
        }
        $this->data['products'] =$pro_list['product'];
        $this->data['res_count'] =$pro_list['res_count'];
        if($pro_list['res_count']>$limit){
            $this->data['fanye_show'] =1;
        }else{
            $this->data['fanye_show'] =0;
        }
        $this->data['sorts'] = array();
        if ($order && strtolower($order) == 'asc') {
            $desc_order = 'DESC';
        } else {
            $desc_order = 'ASC';
        }
        $this->data['sorts']['popularity'] = array(
            'text' => $this->language->get('text_popularity'),
            'value' => 'p.salesnum',
            'code' => 'popularity',
            'href' => $this->url->link('product/search', 'search=' . $search . '&sort=popularity&order=' . $desc_order)
        );
        $this->data['sorts']['price'] = array(
            'text' => $this->language->get('text_price'),
            'value' => 'p.price',
            'code' => 'price',
            'href' => $this->url->link('product/search', 'search=' . $search. '&sort=price&order=' . $desc_order)
        );
        $this->data['sorts']['new_arrivals'] = array(
            'text' => $this->language->get('text_new_arrivals'),
            'value' => 'p.date_added',
            'code' => 'new_arrivals',
            'href' => $this->url->link('product/search', 'search=' . $search. '&sort=new_arrivals&order=' . $desc_order)
        );
        $this->data['current_sort_info'] =$this->data['sorts'][$sort];
        
        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $this->data['json_list_url'] =$this->url->link('product/search/ScrollList', 'search=' . $search. $url);
			
		$this->data['search'] = $search;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/product/search.tpl';
        } else{
            $this->template ='default/template/product/search.tpl';
        }
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
    
    public function getProductList($filter){
        $this->language->load('product/search');
        $this->load->model('catalog/product');
        $this->load->model('search/search');
        $this->load->model('tool/image');
        $return_data =array();
        $sort_arr = array(
				'popularity'=>'product.sales_num',
				'price'=>'product.price',	
				'reviews'=>'product.review_rating',	
				'new_arrivals'=>'product.date_added'
		);
        $page =$filter['page'];
        $limit =$filter['limit'];
        $sort =$filter['sort'];
        $order = $filter['order'];
        $keyword  = $filter['search'];
        $search_cat =array();
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
        $return_data['res_count'] = $product_total ;
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
            $return_data['product'][] = array(
                'product_id'  => $result['product_id'],
                'thumb'       => $image,
                'name'        => $product_name,
                'hightlight'  => $product_hightlight_name,
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                'price'       => $price,
                'model'       => $result['model'],
                'special'     => $special,
                'rating'      => $result['rating'],
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'status'      => $result['status'],
                'stock_status_id' => $result['stock_status_id'],
                'stock_status' => $result['stock_status'],
                'add_cart' => $this->language->get('button_cart'),
                );
        } 
        return  $return_data;
    }

    public function ScrollList(){
        $page =isset($this->request->get['page'])?$this->request->get['page']:0;
        $search =isset($this->request->get['search'])?$this->request->get['search']:'';
        $sort =isset($this->request->get['sort'])?$this->request->get['sort']:'popularity';
        $order =isset($this->request->get['order'])?$this->request->get['order']:'ASC';
        $limit =6;
        $filter =array(
            'search'         => $search, 
            'sort'                => $sort,
            'order'               => $order,
            'page'               => $page,
            'limit'               => $limit 
        );
        if($page&&$search){
            $data =$this->getProductList($filter);
            $json['error'] =0;
            $json['data'] =$data['product'];

        }else{
            $json['error'] =1;
            $json['message'] ='Load Failed! Please Try Again';
        }
        $this->response->setOutput(json_encode($json));
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