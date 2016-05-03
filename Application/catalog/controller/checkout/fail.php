<?php
class ControllerCheckoutFail extends Controller { 
	public function index() { 	
		if (isset($this->session->data['order_id'])) {

            $this->load->model('checkout/order');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $order_info =$this->model_checkout_order->getOrder($this->session->data['order_id']);
            $order_product =array();
            $order_product_info =$this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
            foreach($order_product_info as $key=>$product){
                $product_image =$this->model_catalog_product->getValue(array('image'),$product['product_id']);
                $product['price_format'] =$this->currency->format($product['price']);
                $product['total_format'] =$this->currency->format($product['total']);
                $product['image_email'] =$this->model_tool_image->resize($product_image['image'], 60, 60);
                $order_product[$key] =$product;
            }
            $order_total_info =$this->model_checkout_order->getOrderTotal($this->session->data['order_id']);
         
			$this->cart->clear();
            
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}
        else{
            $redirect = $this->url->link('checkout/cart');
            $this->redirect($redirect);
        }
		$this->language->load('checkout/fail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array(); 

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' =>$this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);	

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/success'),
			'text'      => $this->language->get('text_success'),
			'separator' => false
		);
		$this->data['heading_title'] = $this->language->get('heading_title');
        
       
		if ($this->customer->isLogged()) {
			$this->data['text_message'] = sprintf($this->language->get('text_customer'),$order_info['order_number'], $this->url->link('account/order', '', 'SSL'));
		} else {
			$this->data['text_message'] = sprintf($this->language->get('text_guest'),$order_info['order_number'], $this->url->link('information/contact'));
		}
        
        $this->data['order_info'] =  $order_info;
        $this->data['order_product_info'] =  $order_product_info;
        $this->data['order_total_info'] =  $order_total_info;
		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = $this->url->link('common/home');
        

        //发送邮件
        $order_data =array();
        $order_data =$order_info;
        $order_data['products'] =$order_product;
        $order_data['totals'] =$order_total_info;
        
         //发送订单确认邮件
        

        if (((stristr($order_data['payment_code'], 'pp') !== false)||(stristr($order_data['payment_code'], 'globebill') !== false))&&($order_data['order_status_id']==2)) {
            $payment_info =$this->model_checkout_order->getPaypalDetail($order_data['order_id']);
            if($payment_info){
                $payment_email =$payment_info['payer_email'];
            }
            else{
                $payment_email='';
            }
            $order_data['payment_information'] ="Email:<br>".$payment_email;
            $this->send_order_confim_email($order_data, 1);
        } elseif ($order_data['payment_code'] == 'bank_transfer') {
            $this->language->load('payment/bank_transfer');
            $order_data['payment_information'] = $this->language->get('text_description');
            $this->send_order_confim_email($order_data, 2);
        } elseif ($order_data['payment_code'] == 'westernunion') {
            $this->language->load('payment/westernunion');
            $order_data['payment_information'] = $this->language->get('text_description');
            $this->send_order_confim_email($order_data, 3);
        }
        
        $this->data['text_error_tips'] = $this->language->get('text_error_tips');
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/fail.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/fail.tpl';
		} else {
			$this->template = 'default/template/checkout/fail.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'			
		);

		$this->response->setOutput($this->render());
	}
    // 订单确认邮件
    /*
     *  $data 订单信息
     *  $type 支付方式。$type=1 PayPal ,$type=2,银行转账;$type=3,西联汇款
     *
     */
    public function send_order_confim_email($data, $type) {
        $this->load->model('tool/email');
        $this->language->load('mail/order');
        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{country}'
        );
        $replace = array(
            'firstname' => $data['shipping_firstname'],
            'lastname' => $data['shipping_lastname'],
            'company' => $data['shipping_company'],
            'address_1' => $data['shipping_address_1'],
            'address_2' => $data['shipping_address_2'],
            'city' => $data['shipping_city'],
            'postcode' => $data['shipping_postcode'],
            'zone' => $data['shipping_zone'],
            'country' => $data['shipping_country']
        );
        if(!$data['shipping_address_format']){
           $data['shipping_address_format']="
           {company}
           {firstname}  {lastname}
           {address_1}
           {address_2}
           {postcode} {city}
           {country}";
        }
        $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $data['shipping_address_format']))));
        $email_data = array();
        $email_data['store_id'] = $this->config->get('config_store_id');
        $email_data['email_from'] = $this->config->get('config_name');
        $email_data['email_to'] = $data['email'];
        $template = new Template();
        $template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
        $template->data['store_id'] = $this->config->get('config_store_id');
        $template->data['store_name'] = $this->config->get('config_name');
        if ($this->config->get('config_store_id')) {
            $template->data['store_url'] = $this->config->get('config_url');
        } else {
            $template->data['store_url'] = HTTP_SERVER;
        }
        $template->data['text_home'] = $this->language->get('text_home');
        $template->data['text_menu_new_arrivals'] = $this->language->get('text_menu_new_arrivals');
        $template->data['text_menu_top_sellers'] = $this->language->get('text_menu_top_sellers');
        $template->data['text_menu_deals'] = $this->language->get('text_menu_deals');
        $template->data['text_menu_clearance'] = $this->language->get('text_menu_clearance');

        $template->data['text_payment_method'] = $this->language->get('text_payment_method');
        $template->data['text_payment_information'] = $this->language->get('text_payment_information');
        $template->data['text_shipping_information'] = $this->language->get('text_shipping_information');
        $template->data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $template->data['col_product'] = $this->language->get('col_product');
        $template->data['col_price'] = $this->language->get('col_price');
        $template->data['col_qty'] = $this->language->get('col_qty');
        $template->data['col_total'] = $this->language->get('col_total');
        $template->data['text_order_product_info_text'] = $this->language->get('text_order_product_info_text');
        $template->data['text_no_reply'] = $this->language->get('text_no_reply');
        $template->data['text_footer'] = $this->language->get('text_edm_foot');
        if ($type == 1) {
            $template->data['title'] = sprintf($this->language->get('text_paypal_new_order_confirmation'), $data['order_number']);
            $template->data['text_order_welcome'] = sprintf($this->language->get('text_paypal_order_welcome'), $this->config->get('config_name'), $data['order_number']);
            $email_data['email_subject'] = sprintf($this->language->get('text_paypal_new_order_confirmation'), $data['order_number']);
        } elseif ($type == 2) {
            $template->data['title'] = sprintf($this->language->get('text_bank_new_order_confirmation'), $data['order_number']);
            $template->data['text_order_welcome'] = sprintf($this->language->get('text_bank_order_welcome'), $this->config->get('config_name'), $data['order_number']);
            $email_data['email_subject'] = sprintf($this->language->get('text_bank_new_order_confirmation'), $data['order_number']);
        } elseif ($type == 3) {
            $template->data['title'] = sprintf($this->language->get('text_western_union_new_order_confirmation'), $data['order_number']);
            $template->data['text_order_welcome'] = sprintf($this->language->get('text_western_union_order_welcome'), $this->config->get('config_name'), $data['order_number']);
            $email_data['email_subject'] = sprintf($this->language->get('text_western_union_new_order_confirmation'), $data['order_number']);
        }

        //订单信息
        $template->data['order'] = $data;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/new_order.tpl')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/new_order.tpl');
        } else {
            $html = $template->fetch('default/template/mail/new_order.tpl');
        }
        $email_data['email_content'] = addslashes($html);
        $email_data['is_html'] = 1;
        $email_data['attachments'] = '';
        $this->model_tool_email->addEmailList($email_data);
    }
}
?>