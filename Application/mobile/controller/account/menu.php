<?php 
class ControllerAccountMenu extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$lang =$this->language->load('account/menu');
        $this->data =array_merge($this->data,$lang);
		//左边account 菜单
		$account_menus =array();
		$route =$this->request->get['route'];
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
			'text' =>$this->language->get('text_password'),
			'link'  =>$this->url->link('account/password', '', 'SSL'),
			'is_active'	=>$route =='account/password' ? 1:0
		);
		$account_menus[] =array(
			'text' =>$this->language->get('text_profile'),
			'link'  =>$this->url->link('account/profile', '', 'SSL'),
			'is_active'	=>$route =='account/profile' ? 1:0
		);

		$this->data['account_menus'] =$account_menus;

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/menu.tpl';
		} else {
			$this->template = 'default/template/account/menu.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>