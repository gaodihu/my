<?php 
class ControllerAccountReviews extends Controller {
    private $limit =4;
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $this->document->addScript('mobile/view/js/pagescroll.js');
		$lang =$this->language->load('account/reviews');
        $this->data =array_merge($this->data,$lang);
		$this->load->model('account/reviews');
		
		$customer_id =$this->session->data['customer_id'];

		$this->document->setTitle($this->language->get('heading_title'));	

	

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$filter =array(
            'page'   =>$page,
            'limit'      =>$this->limit
        );
        $this->data['reviews_list'] =$this->GetReviewsList($filter);
		
		$total =$this->model_account_reviews->getTotalReviews($customer_id);
        if($total >$filter['limit']){
            $this->data['show_ajax_list'] =1;
        }else{
            $this->data['show_ajax_list'] =0;
        }
        $this->data['json_list_url'] =$this->url->link('account/reviews/PageList');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/reviews.tpl';
		} else {
			$this->template = 'default/template/account/reviews.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());		
	}

    public function GetReviewsList($filter){
        $this->language->load('account/reviews');
        $this->load->model('account/reviews');
        $this->load->model('catalog/product');
		$this->load->model('tool/image');
        $page =$filter['page']?$filter['page']:1;
        $limit =$filter['limit']?$filter['limit']:$this->limit;
		$start =($page-1)*$limit;
		$res_data = array();
		$data =array(
			'sort'=>'date_added',
			'start'=>$start,
			'limit'=>$limit
		);
        $customer_id =$this->session->data['customer_id'];
		$reviews = $this->model_account_reviews->getReviews($customer_id,$data);
		foreach ($reviews as  $review) {
			$product_info = $this->model_catalog_product->getProduct($review['product_id']);

			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], 85, 85);
				} else {
					$image = false;
				}
				$res_data[] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'text'			=>$review['text'],
  					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}
        return $res_data;
    }
    
    public function PageList(){
       $page =$this->request->get['page']?$this->request->get['page']:0;
       $limit =$this->limit;
        $filter =array(
            'page'  =>    $page,
            'limit'  =>    $limit
        );
        if($page){
            $res =$this->GetReviewsList($filter);
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