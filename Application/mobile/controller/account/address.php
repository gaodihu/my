<?php 
class ControllerAccountAddress extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
            $this->data['logged']=0;
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        else{
            $this->data['logged']=1;
        }
		$lang =$this->language->load('account/address');
        $this->data =array_merge($this->data,$lang);
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		$this->getList();
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->load->model('account/address');

		if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);	

			// Default Shipping Address
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
				unset($this->session->data['shipping_address_id']);
				unset($this->session->data['shipping_country_id']);
				unset($this->session->data['shipping_zone_id']);
				unset($this->session->data['shipping_postcode']);				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
				unset($this->session->data['payment_address_id']);
				unset($this->session->data['payment_country_id']);
				unset($this->session->data['payment_zone_id']);				
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_delete_success');
		}
        if(isset($this->session->data['redirect'])){
            $this->redirect($this->session->data['redirect']);
        }else{
            $this->redirect($this->url->link('account/address', '', 'SSL'));
        }
	}
	protected function getList() {
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		//shipping address list
		$this->data['shipping_addresses'] = array();

		$this->data['shipping_addresses'] =$this->getAddressList('shipping');
			//Billing address list
		$this->data['billing_addresses'] = array();
		$this->data['billing_addresses'] =$this->getAddressList('billing');
		
		

		$this->data['insert'] = $this->url->link('account/address/insert', '', 'SSL');
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		$this->data['account_address'] = true;
		
		$this->load->model('localisation/country');
	
		$this->data['countries'] = $this->model_localisation_country->getCountries();

        $this->data['add_address'] = $this->url->link('account/address/add', '', 'SSL');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/address_list.tpl';
		} else {
			$this->template = 'default/template/account/address_list.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'		
		);

		$this->response->setOutput($this->render());		
	}
    
	protected function validateDelete() {
        $this->language->load('account/address');
		if ($this->model_account_address->getTotalAddresses() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getAddressId() == $this->request->get['address_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		if (!$this->error) {
			return true;
		} else {
            $this->session->data['success'] =$this->error['warning'];
			return false;
		}
	}
     //deafault地址信息
    public function defaultAddress() {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $address_id = $this->request->get['address_id'];
        $address_info = $this->model_account_address->setDefaultAddress($address_id);
        $this->session->data['shipping_address'] = null;
        if(isset($this->session->data['redirect'])){
            $this->redirect($this->session->data['redirect']);
        }else{
            $this->redirect($this->url->link('account/address', '', 'SSL'));
        }
    }

    public function update(){
        $this->load->model('account/address');
        $lang =$this->language->load('account/address');
        $this->data =array_merge($this->data,$lang);
        $this->document->setTitle($this->language->get('text_edit_address'));
        $this->data['action'] =$this->url->link('account/address/update', '', 'SSL');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $address_id =$this->request->post['address_id'];
            $this->load->model('localisation/zone');
            $zone_info =$this->model_localisation_zone->getZone($this->request->post['zone_id']);
            if($zone_info){
                $this->request->post['zone'] =$zone_info['name'];
            }
             if (!$this->customer->isLogged()&&!$address_id) {
                 $this->session->data['redirect'] =$this->url->link('checkout/checkout', '', 'SSL');
                 $country_id = $this->request->post['country_id'];
                 $this->load->model('localisation/country');
                 $country = $this->model_localisation_country->getCountry($country_id);
                 $this->session->data['shipping_address'] = $this->request->post;
                 $this->session->data['shipping_address']['iso_code_2'] = $country['iso_code_2'];
                 $this->session->data['shipping_address']['country'] = $country['name'];

                 
            }else{
                 $this->model_account_address->editAddress($address_id,$this->request->post);
            }
            $this->session->data['success'] = $this->language->get('text_update_success');
            if(isset($this->session->data['redirect'])){
                $this->redirect($this->session->data['redirect']);
            }else{
                $this->redirect($this->url->link('account/address', '', 'SSL'));
            }
            
        }
        $this->getForm();
        
    }

    public function add(){
        $this->load->model('account/address');
        $lang =$this->language->load('account/address');
        $this->data =array_merge($this->data,$lang);
        $this->document->setTitle($this->language->get('text_add_address'));
        $this->data['action'] =$this->url->link('account/address/add', '', 'SSL');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('localisation/zone');
            $zone_info =$this->model_localisation_zone->getZone($this->request->post['zone_id']);
            if($zone_info){
                $this->request->post['zone'] =$zone_info['name'];
            }
            if (!$this->customer->isLogged()) {
                 $this->session->data['redirect'] =$this->url->link('checkout/checkout', '', 'SSL');
                 $country_id = $this->request->post['country_id'];
                 $this->load->model('localisation/country');
                 $country = $this->model_localisation_country->getCountry($country_id);
                 $this->session->data['shipping_address'] = $this->request->post;
                 $this->session->data['shipping_address']['iso_code_2'] = $country['iso_code_2'];
                $this->session->data['shipping_address']['country'] = $country['name'];
            }else{
                $this->model_account_address->addAddress($this->request->post);
            }
            $this->session->data['success'] = $this->language->get('text_insert_success');
            if(isset($this->session->data['redirect'])){
                $this->redirect($this->session->data['redirect']);
            }else{
                $this->redirect($this->url->link('account/address', '', 'SSL'));
            }
        }
        $this->getForm();
    }
	public function getAddressList($type){
		$address_list =array();
		$results = $this->model_account_address->getAddresses($type);
		if($results){
			foreach ($results as $address) {
				if ($address['address_format']) {
					$format = $address['address_format'];
				} else {
					$format =  '{address_1}' . " ".'{company}' ." ". '{city} ' . " " . '{zone}' . " " . '{country}({postcode})';
				}
				$customer_id =$this->session->data['customer_id'];
				if($type =='shipping'){
					$default_address_id =$this->model_account_address->getDefaultAddress($customer_id);
				}
				else{
					$default_address_id =$this->model_account_address->getDefaultBillingAddress($customer_id);
				}
				
				if($default_address_id&&$address['address_id']==$default_address_id){
					$default =true;
				}
				else{
					$default =false;
				}
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $address['firstname'],
					'lastname'  => $address['lastname'],
					'company'   => $address['company'],
					'address_1' => $address['address_1'],
					'address_2' => $address['address_2'],
					'city'      => $address['city'],
					'postcode'  => $address['postcode'],
					'zone'      => $address['zone'],
					'zone_code' => $address['zone_code'],
					'country'   => $address['country']
				);

				$address_list[] = array(
					'address_name' =>$address['firstname']." ".$address['lastname'],
					'default' =>$default,
					'address_id' => $address['address_id'],
					//'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
					'address'    => trim(str_replace($find, $replace, $format)),
					'phone'    => $address['phone'],
                    'tax_id'    => $address['tax_id'],
					'update'     => $this->url->link('account/address/update', 'address_id=' . $address['address_id'], 'SSL'),
                    'default_href'     => $this->url->link('account/address/defaultAddress', 'address_id=' . $address['address_id'], 'SSL'),
					'delete'     => $this->url->link('account/address/delete', 'address_id=' . $address['address_id'], 'SSL')
				);
			}
		}
		return $address_list;
	}
    
    public function getForm(){
        $this->load->model('account/address');
        if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			//$this->redirect($this->url->link('account/login', '', 'SSL'));
            $this->data['guest'] = 1;
		}else{
            $this->data['guest'] = 0;
        }
        $this->data['logged'] = $this->customer->isLogged();
        require_once  DIR_SYSTEM .'library/ip.php';
        $ip_class = new Ip();
        $ip = $ip_class->getIp();
        $country_code = $ip_class->getCountryCode($ip);
        if($country_code){
            $this->data['default_country_code'] = $country_code;
        }else{
            $this->data['default_country_code'] = 'US';
        }
        //$this->data['default_country_code'] = 'US';
        /*
        if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');
            //$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        */
        //$this->document->addScript('mobile/view/js/Address.js');
        $this->document->addScript('mobile/view/js/validform.js');
        if ($this->request->get['address_id']) {
            $address_info = $this->model_account_address->getAddress($this->request->get['address_id'] );
            $this->data['address_info'] =$address_info;
        } else {
            if (!$this->customer->isLogged() && $this->session->data['shipping_address'] ) {
                 $address_info = $this->session->data['shipping_address'];
                 $this->data['address_info'] = $this->session->data['shipping_address'];
            }
        }
        
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        if (isset($address_info['country_id'])) {
            $this->load->model('localisation/zone');
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($address_info['country_id']);   
        }else if($this->data['default_country_code']){
            foreach( $this->data['countries'] as $item){
                if(strtolower($item['iso_code_2']) == strtolower($this->data['default_country_code'])){
                    $this->load->model('localisation/zone');
                    $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($item['country_id']);
                }
            }

        }
       
        if(isset($this->request->post['firstname'])){
            $this->data['firstname'] =$this->request->post['firstname'];
        }else{
            $this->data['firstname'] ='';
        }
        if(isset($this->request->post['lastname'])){
            $this->data['lastname'] =$this->request->post['lastname'];
        }else{
            $this->data['lastname'] ='';
        }
        if(isset($this->request->post['email'])){
            $this->data['email'] =$this->request->post['email'];
        }else{
            $this->data['email'] ='';
        }
        if(isset($this->request->post['address_1'])){
            $this->data['address_1'] =$this->request->post['address_1'];
        }else{
            $this->data['address_1'] ='';
        }
        if(isset($this->request->post['address_2'])){
            $this->data['address_2'] =$this->request->post['address_2'];
        }else{
            $this->data['address_2'] ='';
        }
        if(isset($this->request->post['city'])){
            $this->data['city'] =$this->request->post['city'];
        }else{
            $this->data['city'] ='';
        }
        if(isset($this->request->post['postcode'])){
            $this->data['postcode'] =$this->request->post['postcode'];
        }else{
            $this->data['postcode'] ='';
        }
        if(isset($this->request->post['phone'])){
            $this->data['phone'] =$this->request->post['phone'];
        }else{
            $this->data['phone'] ='';
        }

        if(isset($this->error['firstname'])){
            $this->data['error_firstname'] =$this->error['firstname'];
        }else{
            $this->data['error_firstname'] ='';
        }
        if(isset($this->error['lastname'])){
            $this->data['error_lastname'] =$this->error['lastname'];
        }else{
            $this->data['error_lastname'] ='';
        }
         if(isset($this->error['email'])){
            $this->data['error_email'] =$this->error['email'];
        }else{
            $this->data['error_email'] ='';
        }
        if(isset($this->error['address_1'])){
            $this->data['error_address_1'] =$this->error['address_1'];
        }else{
            $this->data['error_address_1'] ='';
        }
        if(isset($this->error['address_2'])){
            $this->data['error_address_2'] =$this->error['address_2'];
        }else{
            $this->data['error_address_2'] ='';
        }
        if(isset($this->error['city'])){
            $this->data['error_city'] =$this->error['city'];
        }else{
            $this->data['error_city'] ='';
        }
        if(isset($this->error['postcode'])){
            $this->data['error_postcode'] =$this->error['postcode'];
        }else{
            $this->data['error_postcode'] ='';
        }
        if(isset($this->error['country'])){
            $this->data['error_country'] =$this->error['country'];
        }else{
            $this->data['error_country'] ='';
        }
        if(isset($this->error['zone'])){
            $this->data['error_zone'] =$this->error['zone'];
        }else{
            $this->data['error_zone'] ='';
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/address_form.tpl';
		} else {
			$this->template = 'default/template/account/address_form.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'		
		);

		$this->response->setOutput($this->render());		
    }

    public function validateForm(){
        $this->language->load('account/address');
        $this->load->model('account/address');
        
        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }
        if(!$this->customer->isLogged()){
            if(!isset($this->request->post['email'])){
                $this->error['error_email'] = $this->language->get('error_email');
            }
            if($this->request->post['email']&&!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])){
                $this->error['error_email'] = $this->language->get('error_email');
            }
        }
        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
           $this->error['address_1'] = $this->language->get('error_address');
        }
        /*
         if ((utf8_strlen($this->request->post['address_2']) < 3) || (utf8_strlen($this->request->post['address_2']) > 128)) {
            $this->error['address_2'] = $this->language->get('error_address');
        }
        */
       

        if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
            $this->error['city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
            $this->error['postcode'] = $this->language->get('error_postcode');
        }

        if ($this->request->post['country_id'] == '') {
            $this->error['country'] = $this->language->get('error_country');
        }
        /*
        if ((isset($this->request->post['zone_id'])&&$this->request->post['zone_id'] == '')) {
            $this->error['zone'] = $this->language->get('error_zone');
        }
        */
        if($this->error){
            return false;
        }else{
            return true;
        }
    }
}
?>