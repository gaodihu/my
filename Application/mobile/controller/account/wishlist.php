<?php 
class ControllerAccountWishList extends Controller {
    private $limit =4;
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $this->document->addScript('mobile/view/js/cart.js');
		$lang =$this->language->load('account/wishlist');
        $this->data =array_merge($this->data,$lang);
		$this->load->model('account/wishlist');
		$customer_id =$this->session->data['customer_id'];
	
		if (isset($this->request->get['remove'])) {
			$remove_id=$this->request->get['remove'];
			
			$this->model_account_wishlist->deleteWishlist($remove_id,$customer_id);
			$this->session->data['success'] = $this->language->get('text_remove');

			$this->redirect($this->url->link('account/wishlist'));
		}
	
		$this->document->setTitle($this->language->get('heading_title'));	

		

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
	    $filter =array(
            'page' =>1,
            'limit' =>$this->limit
        );
		$this->data['wish_lists'] =$this->GetWishList($filter);
	    $total_wishlist =$this->model_account_wishlist->getTotalWishlists($customer_id);
        if($total_wishlist['total']>$filter['limit']){
            $this->data['show_ajax_list'] =1;
        }else{
            $this->data['show_ajax_list'] =0;
        }
        $this->data['json_list_url'] =$this->url->link('account/wishlist/PageList');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/wishlist.tpl';
		} else {
			$this->template = 'default/template/account/wishlist.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());		
	}

	public function add() {
		if($this->customer->isLogged()){
			$this->language->load('account/wishlist');

			$json = array();

			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else {
				$product_id = 0;
			}
			$customer_id =$this->session->data['customer_id'];
			$this->load->model('account/wishlist');
			$data =array(
				'customer_id'=>	$customer_id,
				'product_id'=>	$product_id,
			);
			$wish_info = $this->model_account_wishlist->getWishlist($customer_id,$product_id);
			if($wish_info){
                
				$this->model_account_wishlist->delWishlist($data);
                $json['error'] =0;
                //$json['message'] =sprintf($this->language->get('text_success'),$this->url->link('account/wishlist', 'SSL'));
			}
			else{
				$this->model_account_wishlist->addWishlist($data);
				$json['error'] =0;
				//$json['message'] =sprintf($this->language->get('text_success'),$this->url->link('account/wishlist', 'SSL'));
			}
			
				
		}
		else{
			$json['error'] =3;
			$json['link'] =$this->url->link('account/login');
		}
		$this->response->setOutput(json_encode($json));
	}
    
    public function GetWishList($filter){
        $this->language->load('account/wishlist');
        $this->load->model('account/wishlist');
		$this->load->model('catalog/product');
        $this->load->model('tool/image');
        $page=$filter['page']?$filter['page']:1;
        $limit =$filter['limit']?$filter['limit']:6;
		$start =($page-1)*$limit;
		$customer_id =$this->session->data['customer_id'];
		$re_data = array();
		$wishlists = $this->model_account_wishlist->getWishlists($customer_id,$start,$limit);
		foreach ($wishlists as  $product) {
			$product_info = $this->model_catalog_product->getProduct($product['product_id']);

			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], 207, 160);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = 0;
				}
				$re_data[] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'price'      => $price,		
					'special'    => $special,
					'rating'     => $rating,
					'reviews'    => (int)$product_info['reviews'],
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $product['wish_id'])
				);
			}
		}
        return $re_data;
    }

    public function PageList(){
       $page =$this->request->get['page']?$this->request->get['page']:0;
       $limit =$this->limit;
        $data =array(
            'page'  =>    $page,
            'limit'  =>    $limit
        );
        if($page){
            $res =$this->GetWishList($data);
            $json['error']=0;
            $json['data'] =$res;
        }else{
            $json['error']=1;
            $json['message']='load fialed! please try again';
        }
        $this->response->setOutput(json_encode($json));
    }
}
?>