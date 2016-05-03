<?php 
class ControllerAccountMenu extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		

		$this->document->addStyle('css/stylesheet/product.css');
		$this->document->addStyle('css/stylesheet/account.css');
		$this->document->addStyle('css/stylesheet/flexslider.css');
		$this->document->addScript('js/jquery/jquery.flexslider.js');
		$this->data['heading_title'] = $this->language->get('heading_title');
			
		//左边account 菜单
		$account_menus =array();
		$route =$this->request->get['route'];
		$account_menus[] =array(
			'text' =>$this->language->get('text_account_dashboard'),
			'link'  =>$this->url->link('account/account', '', 'SSL'),
			'is_active'	=> $route=='account/account' ? 1:0
		);
		$account_menus[] =array(
			'text' =>$this->language->get('text_my_orders'),
			'link'  =>$this->url->link('account/order', '', 'SSL'),
			'is_active'	=>($route =='account/order'||$route =='account/order/info')? 1:0
		);
        /*
        $account_menus[] =array(
			'text' =>$this->language->get('text_paypal_onestep'),
			'link'  =>$this->url->link('account/onestep', '', 'SSL'),
			'is_active'	=>$route =='account/onestep' ? 1:0
		);
        */
        
		$account_menus[] =array(
			'text' =>$this->language->get('text_wishlist'),
			'link'  =>$this->url->link('account/wishlist', '', 'SSL'),
			'is_active'	=>$route =='account/wishlist' ? 1:0
		);
       
		$account_menus[] =array(
			'text' =>$this->language->get('text_reward'),
			'link'  =>$this->url->link('account/points', '', 'SSL'),
			'is_active'	=>$route =='account/points' ? 1:0
		);
         /*
		$account_menus[] =array(
			'text' =>$this->language->get('text_coupon'),
			'link'  =>$this->url->link('account/coupon', '', 'SSL'),
			'is_active'	=>$route =='account/coupon' ? 1:0
		);
        */
		$account_menus[] =array(
			'text' =>$this->language->get('text_product_reviews'),
			'link'  =>$this->url->link('account/reviews', '', 'SSL'),
			'is_active'	=>$route =='account/reviews' ? 1:0
		);
		$account_menus[] =array(
			'text' =>$this->language->get('text_address'),
			'link'  =>$this->url->link('account/address', '', 'SSL'),
			'is_active'	=>$route =='account/address' ? 1:0
		);
		$account_menus[] =array(
			'text' =>$this->language->get('text_my_newsletter'),
			'link'  =>$this->url->link('account/newsletter', '', 'SSL'),
			'is_active'	=>$route =='account/newsletter' ? 1:0
		);
		$account_menus[] =array(
			'text' =>$this->language->get('text_password'),
			'link'  =>$this->url->link('account/password', '', 'SSL'),
			'is_active'	=>$route =='account/password' ? 1:0
		);
        

        
		$account_menus[] =array(
			'text' =>$this->language->get('text_profile'),
			'link'  =>$this->url->link('account/profile', '', 'SSL'),
			'is_active'	=>$route =='account/profile' ? 1:0
		);

		/*
        $account_menus[] =array(
			'text' =>$this->language->get('text_get_other_product'),
			'link'  =>$this->url->link('service/productPost', '', 'SSL'),
			'is_active'	=>$route =='service/productPost' ? 1:0
		);

        $account_menus[] =array(
			'text' =>$this->language->get('text_make_easy_money_program'),
			'link'  =>$this->url->link('account/program', '', 'SSL'),
			'is_active'	=>$route =='account/program' ? 1:0
		);
		*/

		$this->data['account_menus'] =$account_menus;

		$this->data['text_my_account'] =$this->language->get('text_my_account');

		/*
		if ($this->config->get('reward_status')) {
			$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
		} else {
			$this->data['reward'] = '';
		}
		*/
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
		$this->data['customer_info'] =$customer_info;
        $this->load->model('account/customer_group');
		$customer_group_info =$this->model_account_customer_group->getCustomerGroup($customer_info['customer_group_id']);
        $this->data['customer_group_name'] =$customer_group_info['name'];
         $this->data['points'] =$this->customer->getAvailablePoints()?$this->customer->getAvailablePoints():0;
		//得到 用户中心顶部banner
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$account_banner_info = $this->model_design_banner->getBannerByCode('accout_banner');
		if($account_banner_info){
			foreach($account_banner_info as $account_banner){
				if ($account_banner['image']) {
					$image = $this->model_tool_image->resize($account_banner['image'], $account_banner['banner_width'], $account_banner['banner_height']);
				} else {
					$image = false;
				}
				$this->data['account_banner'][] = array(
					'link' =>	$account_banner['link'],
					'image' =>	$image,
					'title' =>	$account_banner['title']
				);
			}
		}
		else{
			$this->data['account_banner'] = array();
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/menu.tpl';
		} else {
			$this->template = 'default/template/account/menu.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>