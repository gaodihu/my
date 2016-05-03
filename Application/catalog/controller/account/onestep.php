<?php
class ControllerAccountOnestep extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/onestep', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        
        $customer_id =$this->session->data['customer_id'];
		$this->language->load('account/onestep');
        $this->load->model('payment/pp_onestep');

		$this->document->setTitle($this->language->get('heading_title'));
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
			'text'      => $this->language->get('text_onestep'),
			'href'      => $this->url->link('account/onestep', '', 'SSL'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['onestep_desc'] = $this->language->get('onestep_desc');
        
        $this->data['text_turn_on'] = $this->language->get('text_turn_on');
        $this->data['text_turn_off'] = $this->language->get('text_turn_off');
        $this->data['text_turn_confirm_tips'] = $this->language->get('text_turn_confirm_tips');
        
		$onestep = $this->model_payment_pp_onestep->getOneStepByCustomer($customer_id);

        $this->data['onestep'] = $onestep;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/onestep.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/onestep.tpl';
		} else {
			$this->template = 'default/template/account/onestep.tpl';
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

    public function create(){
        if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/onestep', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $customer_id = $this->customer->getID();
        $this->load->model('payment/pp_onestep');
        
        $data = array(
            'METHOD' => 'CreateBillingAgreement',
            'TOKEN' => $this->session->data['paypal_onestep_add_token'],
        );
        $result = $this->model_payment_pp_onestep->call($data);
        if(strtolower($result['ACK']) == 'success'){
            $b_id = $result['BILLINGAGREEMENTID'];
            $this->model_payment_pp_onestep->addOneStep($customer_id,$this->config->get('pp_onestep_username'),$b_id);
        }
        $this->redirect($this->url->link('account/onestep', '', 'SSL'));
    }
    public function turnon(){
       if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/onestep', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $this->load->model('payment/pp_onestep'); 
        $data = array(
            'METHOD' => 'SetExpressCheckout',
            'RETURNURL' => $this->url->link('account/onestep/create', '', 'SSL'),
            'CANCELURL' => $this->url->link('account/onestep', '', 'SSL'),
            //'LANDINGPAGE' => 'Login',
            //'CHANNELTYPE' => 'Merchant',
            'L_BILLINGTYPE0'=>'MerchantInitiatedBilling',
            'BILLINGAGREEMENTDESCRIPTION0'=>'Sample'
            
        );
           
        $result = $this->model_payment_pp_onestep->call($data);

        
        /**
         * If a failed PayPal setup happens, handle it.
         */
        if (!isset($result['TOKEN'])) {
            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
            /**
             * Unable to add error message to user as the session errors/success are not
             * used on the cart or checkout pages - need to be added?
             * If PayPal debug log is off then still log error to normal error log.
             */
            if ($this->config->get('pp_onestep_debug')) {
                $this->log->write(serialize($result));
            }

            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }
        $this->session->data['paypal_onestep_add_token'] = $result['TOKEN'];
        
        $to_url = '';
        if ($this->config->get('pp_onestep_test') == 1) {
             $to_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'];
        } else {
            $to_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'];
        }
        $this->data['to_url'] = $to_url;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pp_onestep_to_paypal.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/pp_onestep_to_paypal.tpl';
        } else {
            $this->template = 'default/template/account/pp_onestep_to_paypal.tpl';
        }
        
	    $this->response->setOutput($this->render());
    }
    
    public function cancel(){
        if(!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/onestep', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
        $customer_id = $this->session->data['customer_id'];
        $this->load->model('payment/pp_onestep');
        $bing_id = $_REQUEST['id'];
        $bing_id = intval($bing_id);
        $rs = $this->model_payment_pp_onestep->cancelOnestep($customer_id,$bing_id);
        $data = array();
        $data['flag'] = $rs;
        $data['redirect'] = $this->url->link('account/onestep', '', 'SSL');
        echo json_encode($data);
    }
}
?>