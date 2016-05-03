<?php
class ControllerSaleInvoice extends Controller {
    private $error = array();
    public function add(){
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('sale/invoice');
            $data =array();
            $data['order_id'] =(int)$this->request->get['order_id'];
            $data['comment'] =$this->request->post['comment'];
			$invoice_id=$this->model_sale_invoice->addInvoice($data);
             if($this->sendEmail($this->request->get['order_id'])){
                $this->model_sale_invoice->updateEmailSend($invoice_id);
            }    
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'], 'SSL'));
		}
       $this->data['add_invoice'] =1;
       $this->data['form_action'] =$this->url->link('sale/invoice/add', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
       $html =  $this->getForm();
        $this->response->setOutput($html);

    }
    public function info() {
        if (isset($this->request->get['print'])) {
            $this->data['print']=1;
        }
        else{
             $this->data['print']=0;
        }
        $html = $this->getForm();
        $this->response->setOutput($html);

    }

    public function getForm(){
        
        $this->language->load('sale/invoice');

        $this->data['title'] = $this->language->get('heading_title');
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $this->data['base'] = HTTP_SERVER;
        }

        $this->data['direction'] = $this->language->get('direction');
        $this->data['language'] = $this->language->get('code');

        $this->data['text_invoice'] = $this->language->get('text_invoice');

        $this->data['text_order_id'] = $this->language->get('text_order_id');
        $this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $this->data['text_date_added'] = $this->language->get('text_date_added');
        $this->data['text_telephone'] = $this->language->get('text_telephone');
        $this->data['text_fax'] = $this->language->get('text_fax');
        $this->data['text_to'] = $this->language->get('text_to');
        $this->data['text_company_id'] = $this->language->get('text_company_id');
        $this->data['text_tax_id'] = $this->language->get('text_tax_id');       
        $this->data['text_ship_to'] = $this->language->get('text_ship_to');
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $this->data['column_product'] = $this->language->get('column_product');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['column_comment'] = $this->language->get('column_comment');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'], 'SSL'),               
            'separator' => ' :: '
        );
        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $order_id =$this->request->get['order_id'];
        //$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
        $this->data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'] , 'SSL');

        $this->data['order_id'] = $this->request->get['order_id'];
        $this->data['order'] =$this->getOrderInfo($order_id);



        $this->template = 'sale/order_invoice.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
         );
       return $this->render();
    }


    function sendEmail($order_id){
        $this->load->model('tool/email');
        $this->load->model('sale/invoice');
        
        $order_info= $this->getOrderInfo($order_id);
        $language =$this->model_sale_order->load_language($order_info['store_id']);
        $language->load('mail/order_invoice');	
        $order_info['payment_information']='';
        if ($order_info['payment_code'] == 'bank_transfer') {
            $language->load('payment/bank_transfer');
            $order_info['payment_information'] =$language->get('text_description');
        } elseif ($order_info['payment_code'] == 'westernunion') {
           $language->load('payment/westernunion');
            $order_info['payment_information'] = $language->get('text_description');
        }
        $email_data =array();
        $email_data['store_id'] =$order_info['store_id'];
        $email_data['email_from'] ='MyLED';
        $email_data['email_to'] =$order_info['email'];
        $template = new Template();	
        $template->data['store_id'] = $order_info['store_id'];
        $template->data['store_name'] = $order_info['store_name'];
        $template->data['store_url'] = $order_info['store_url']."/";	
        $template->data['text_home'] =$language->get('text_home');
        $template->data['text_menu_new_arrivals'] =$language->get('text_menu_new_arrivals');
        $template->data['text_menu_top_sellers'] =$language->get('text_menu_top_sellers');
        $template->data['text_menu_deals'] =$language->get('text_menu_deals');
        $template->data['text_menu_clearance'] =$language->get('text_menu_clearance');        
        $template->data['text_payment_method'] = $language->get('text_payment_method');
        $template->data['text_payment_information'] = $language->get('text_payment_information');
        $template->data['text_shipping_information'] = $language->get('text_shipping_information');
        $template->data['text_shipping_method'] = $language->get('text_shipping_method');
        $template->data['text_shipping_address'] = $language->get('text_shipping_address');
        $template->data['text_payment_address'] = $language->get('text_payment_address');
        //order
        $template->data['text_order_info'] =$language->get('text_order_info');
        $template->data['text_invoice'] = sprintf($language->get('text_invoice'),$order_info['invoice_no']);
        $template->data['text_order'] = sprintf($language->get('text_order'),$order_info['order_number']);
        $template->data['text_date_add'] = $language->get('text_date_add');
        $template->data['text_order_status'] = $language->get('text_order_status');
        $template->data['text_purchased_from'] = $language->get('text_purchased_from');
        $template->data['text_guest'] = $language->get('text_guest');
        //account
        $template->data['text_account_info'] = $language->get('text_account_info');
        $template->data['text_customer_name'] = $language->get('text_customer_name');
        $template->data['text_email'] = $language->get('text_email');
        $template->data['text_customer_group'] = $language->get('text_customer_group');

        $template->data['col_product'] = $language->get('col_product');
        $template->data['col_price'] = $language->get('col_price');
        $template->data['col_qty'] =$language->get('col_qty');
        $template->data['col_total'] =$language->get('col_total');
        $template->data['text_shipped_by'] =$language->get('text_shipped_by');
        $template->data['text_tracking_number'] =$language->get('text_tracking_number');
        $template->data['text_no_reply'] = $language->get('text_no_reply');
        $template->data['text_footer'] = $language->get('text_edm_foot');
        $template->data['title'] =sprintf($language->get('text_title'),$order_info['invoice_no'],$order_info['order_number']); 
        $email_data['email_subject'] =sprintf($language->get('text_title'),$order_info['invoice_no'],$order_info['order_number']);
        foreach($order_info['product'] as $key=>$product){
            $this->load->model('catalog/product');
            $product_info =$this->model_catalog_product->getProductImage($product['product_id']);
            $this->load->model('tool/image');
            if($product_info['image']){
                $image =$this->model_tool_image->resize($product_info['image'],60,60);
            }
            else{
                 $image=false;
            }
            $order_info['product'][$key]['image'] =$image;
        }
        $template->data['order'] =$order_info;
        $template->data['tracks']=$this->model_sale_order->getOrderTrack($order_id);
        $html = $template->fetch('mail/order_invoice.tpl');
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ="";
        $email_id =$this->model_tool_email->addEmailList($email_data);
        if($email_id){
            return true;
        }
        else{
            return false;
        }

    }


    function getOrderInfo($order_id){
     $this->load->model('sale/order');
        $this->load->model('sale/shippment');


     $this->load->model('setting/setting');
      $order_info = $this->model_sale_order->getOrder($order_id);
        
        $order_invoice =$this->model_sale_order->getOrderInvoice($order_id);
        if ($order_info) {
            $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

            if ($store_info) {
                $store_address = $store_info['config_address'];
                $store_email = $store_info['config_email'];
                $store_telephone = $store_info['config_telephone'];
                $store_fax = $store_info['config_fax'];
            } else {
                $store_address = $this->config->get('config_address');
                $store_email = $this->config->get('config_email');
                $store_telephone = $this->config->get('config_telephone');
                $store_fax = $this->config->get('config_fax');
            }
            
            if ($order_invoice) {
                $invoice_no =$order_invoice['invoice_no'];
            } else {
                $invoice_no = '';
            }

           
           $format = '{firstname} {lastname}' . "\n" . '{company}' ."\n" . '{tax_id}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{tax_id}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname'  => $order_info['shipping_lastname'],
                'company'   => $order_info['shipping_company'],
                'tax_id'   => $order_info['order_tax_id'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city'      => $order_info['shipping_city'],
                'postcode'  => $order_info['shipping_postcode'],
                'zone'      => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country'   => $order_info['shipping_country']
            );

            $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

           
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            

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
                'firstname' => $order_info['payment_firstname'],
                'lastname'  => $order_info['payment_lastname'],
                'company'   => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city'      => $order_info['payment_city'],
                'postcode'  => $order_info['payment_postcode'],
                'zone'      => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country'   => $order_info['payment_country']
            );

            $payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $product_data = array();

            $products = $this->model_sale_order->getOrderProducts($order_id);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => $value
                    );                              
                }

                $product_data[] = array(
                    'product_id'     => $product['product_id'],
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'orginal_price' =>$product['price'],
                    'base_price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $this->config->get('config_currency')),
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                     'base_total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),$this->config->get('config_currency')),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $voucher_data = array();

            $vouchers = $this->model_sale_order->getOrderVouchers($order_id);

            foreach ($vouchers as $voucher) {
                $voucher_data[] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])           
                );
            }

            $total_data = $this->model_sale_order->getOrderTotals($order_id);
            $order_status_text =$this->model_sale_order->getOrderStatus($order_info['order_status_id']);
            $this->load->model('sale/customer_group');
		    $customer_group = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
            $order_info['payment_information'] = '';
            if ($order_info['payment_code'] == 'bank_transfer') {
                $this->language->load('payment/bank_transfer');
                $order_info['payment_information'] = $this->language->get('text_description');
            } elseif ($order_info['payment_code'] == 'westernunion') {
                $this->language->load('payment/westernunion');
                $order_info['payment_information'] = $this->language->get('text_description');
            }
            $order_comment_history =array();
            $order_history =$this->model_sale_order->getOrderHistories($order_id);
            foreach($order_history as $key=>$item){
                if($item['comment']){
                    $order_comment_history[$key] =$item;
                }
            }
            $tracks = array();


            if($order_info['is_parent'] == '1'){
               $sub_orders =  $this->model_sale_order->getOrders(array('parent_id'=> $order_id ));
                foreach($sub_orders as $s){
                    $_tracks = $this->model_sale_shippment->getShippmentTrackByOrderId($s['order_id']);
                    $tracks = array_merge($tracks,$_tracks);
                }

            }else{
                $tracks = $this->model_sale_shippment->getShippmentTrackByOrderId($order_id);

            }





            $orderInfo = array(
                'order_id'           => $order_id,
                'order_number'           => $order_info['order_number'],
                'invoice_no'         => $invoice_no,
                'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                'store_name'         => $order_info['store_name'],
                'store_id'         => $order_info['store_id'],
                'store_url'          => rtrim($order_info['store_url'], '/'),
                'store_address'      => nl2br($store_address),
                'store_email'        => $store_email,
                'status' =>$order_status_text,
                'ip' => $order_info['ip'],
                'currency_code' => $order_info['currency_code'],
                'currency_value' => $order_info['currency_value'],
                'store_telephone'    => $store_telephone,
                'store_fax'          => $store_fax,
                'order_tax_id'          =>  $order_info['order_tax_id'],
                'email'              => $order_info['email'],
                'firstname'              => $order_info['firstname'],
                'lastname'              => $order_info['lastname'],
                'telephone'          => $order_info['telephone'],
                'customer_id'              => $order_info['customer_id'],
                'customer_group_id'              => $order_info['customer_group_id'],
                'customer_group'              => $customer_group['name'],
                'shipping_address_id'   => $shipping_address,
                'shipping_address'   => $shipping_address,
                'shipping_method'    => $order_info['shipping_method'],
                'payment_address'    => $payment_address,
                'payment_company_id' => $order_info['payment_company_id'],
                'payment_tax_id'     => $order_info['payment_tax_id'],
                'payment_method'     => $order_info['payment_method'],
                'payment_code'     => $order_info['payment_code'],
                'payment_information'  =>$order_info['payment_information'],
                'product'            => $product_data,
                'voucher'            => $voucher_data,
                'total'              => $total_data,
                'comment'            => nl2br($order_info['comment']),
                'order_comment_history' =>$order_comment_history,
                'tracks'            => $tracks,
            );
            return $orderInfo;
        }
        else{
            return array();
        }
   }
}
?>
