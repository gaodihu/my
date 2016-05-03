<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/account');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account_dashboard'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => false
		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->load->model('account/customer');
        
		$customer_info = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
		$this->data['customer_info'] =$customer_info;
		$this->data['text_edit'] =$this->language->get('text_edit');
		$this->data['text_delete'] =$this->language->get('text_delete');

		$this->data['text_welcome'] =sprintf($this->language->get('text_welcome'),$customer_info['nickname']?$customer_info['nickname']:$customer_info['firstname']);
		$this->data['text_account_dashboard'] =$this->language->get('text_account_dashboard');
		$this->data['text_account_introducing'] =$this->language->get('text_account_introducing');
		$this->data['text_dashboard_view'] =$this->language->get('text_dashboard_view');
		
		//points
		$this->data['text_my_points'] =$this->language->get('text_my_points');
		$this->data['text_available_points'] =$this->language->get('text_available_points');
		$this->data['text_accumulated_points'] =$this->language->get('text_accumulated_points');
		$this->data['text_points_spent'] =$this->language->get('text_points_spent');
		$this->data['text_validation_points'] =$this->language->get('text_validation_points');
        
        $this->data['available_points'] =$this->customer->getRewardPoints();
        $this->data['accumulated_points'] =$this->customer->getAvailablePoints();
        $this->data['points_spent'] =$this->customer->getSpentPoint();
        $this->data['validation_points'] =$this->customer->getValidationPoint();
        //
		//coupon
		$this->data['text_my_coupon'] =$this->language->get('text_my_coupon');
		$this->data['text_my_coupon_code'] =sprintf($this->language->get('text_my_coupon_code'),5);
		
		//addres and account info
		$this->data['text_my_account_information'] =$this->language->get('text_my_account_information');
		$this->data['text_contact_information'] =$this->language->get('text_contact_information');
		$this->data['text_change_password'] =$this->language->get('text_change_password');

		$this->data['text_address_book'] =$this->language->get('text_address_book');
		$this->data['text_default_biiling'] =$this->language->get('text_default_biiling');
		$this->data['text_default_shipping'] =$this->language->get('text_default_shipping');
		$this->data['text_Manage_Addresses'] =$this->language->get('text_Manage_Addresses');
		$this->data['text_default_biiling_empty'] =$this->language->get('text_default_biiling_empty');
		$this->data['text_default_shipping_empty'] =$this->language->get('text_default_shipping_empty');
		//Newsletters
		$this->data['text_newsletters'] =$this->language->get('text_newsletters');
        $this->load->model('newsletter/newsletter');
        $newslette_info =$this->model_newsletter_newsletter->getNewsletterByCustomer($customer_info['customer_id']);
		$this->data['text_newsletters_to'] =isset($newslette_info['email'])? sprintf($this->language->get('text_newsletters_to'),$newslette_info['email']):$this->language->get('text_newsletters_empty');

		//points
		$this->data['text_vip_level'] = $this->language->get("text_vip_level");
		$this->data['text_vip_grades'] = $this->language->get("text_vip_grades");
		$this->data['text_vip_reward_points_required'] = $this->language->get("text_vip_reward_points_required");
		$this->data['text_vip_bronze'] = $this->language->get("text_vip_bronze");
		$this->data['text_vip_silver'] = $this->language->get("text_vip_silver");
		$this->data['text_vip_gold'] = $this->language->get("text_vip_gold");
		$this->data['text_vip_platinum'] = $this->language->get("text_vip_platinum");
		$this->data['text_vip_diamond'] = $this->language->get("text_vip_diamond");
		$this->data['text_vip_discounts'] = $this->language->get("text_vip_discounts");



		//得到用户的默认地址信息
		$this->load->model('account/address');
		$default_shipping_address_id =$this->model_account_address->getDefaultAddress($this->session->data['customer_id']);
		$default_billing_address_id =$this->model_account_address->getDefaultBillingAddress($this->session->data['customer_id']);
		if($default_shipping_address_id){
			$default_shipping_address=$this->model_account_address->getAddress($default_shipping_address_id);
			$this->data['default_shipping_address'] =$default_shipping_address['firstname'].' '.$default_shipping_address['lastname'].' '.$default_shipping_address['address_1']." ".$default_shipping_address['city']." ".$default_shipping_address['zone']." ".$default_shipping_address['country']."(".$default_shipping_address['postcode'].")";
		}
		else{
			$this->data['default_shipping_address'] =array();
		}
		if($default_billing_address_id){
			 $default_billing_address=$this->model_account_address->getAddress($default_billing_address_id);
			 $this->data['default_billing_address'] =$default_billing_address['firstname'].' '.$default_billing_address['lastname'].' '.$default_billing_address['address_1']." ".$default_billing_address['city']." ".$default_billing_address['zone']." ".$default_billing_address['country']."(".$default_billing_address['postcode'].")";
		}
		else{
			$this->data['default_billing_address'] =array();
		}
         $this->load->model('account/customer_group');
         $this->data['customer_groups'] =$this->model_account_customer_group->getCustomerGroups();   
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
		}
		$this->children = array(
			'account/menu',
			'account/right_top',
			'account/right_bottom',
			'common/footer',
			'common/header'		
		);
		$this->response->setOutput($this->render());
	}
}
?>