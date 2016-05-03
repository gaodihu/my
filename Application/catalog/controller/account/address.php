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

		$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('js/Address.js');
		$this->load->model('account/address');

		$this->getList();
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

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

			$this->redirect($this->url->link('account/address', '', 'SSL'));
		}

		$this->getList();	
	}
	protected function getList() {
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/address', '', 'SSL'),
			'separator' => false
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_addres_used'] = $this->language->get('text_addres_used');
		$this->data['text_shipping_address_book'] = $this->language->get('text_shipping_address_book');
		$this->data['text_billing_address_book'] = $this->language->get('text_billing_address_book');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_street'] = $this->language->get('entry_street');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
        $this->data['entry_company'] = $this->language->get('entry_company');
        $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_phone'] = $this->language->get('entry_phone');
		$this->data['entry_set_default'] = $this->language->get('entry_set_default');


		$this->data['button_new_shipping_address'] = $this->language->get('button_new_shipping_address');
		$this->data['button_new_billing_address'] = $this->language->get('button_new_billing_address');
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/address_list.tpl';
		} else {
			$this->template = 'default/template/account/address_list.tpl';
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

	protected function validateDelete() {
		if ($this->model_account_address->getTotalAddresses() == 1) {
			//$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getAddressId() == $this->request->get['address_id']) {
			//$this->error['warning'] = $this->language->get('error_default');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
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
					'delete'     => $this->url->link('account/address/delete', 'address_id=' . $address['address_id'], 'SSL')
				);
			}
		}
		return $address_list;
	}
}
?>