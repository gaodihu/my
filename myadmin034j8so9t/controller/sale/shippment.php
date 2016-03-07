<?php
class ControllerSaleShippment extends Controller {
    private $error = array();
    public function add(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validateForm()) {
            $this->load->model('sale/shippment');
            $this->load->model('sale/order');
            $store_id = $this->model_sale_order->getValue($this->request->get['order_id'],'store_id');
            $this->request->post['store_id'] =$store_id;
			$shippment_id=$this->model_sale_shippment->addShippment($this->request->get['order_id'],$this->request->post);
            if($this->sendEmail($this->request->get['order_id'],$shippment_id)){
                $this->model_sale_shippment->updateEmailSend($shippment_id);
            }
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'], 'SSL'));
		}
       $this->data['add_invoice'] =1;
       $this->data['form_action'] =$this->url->link('sale/shippment/add', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
       $this->getForm();     

    }

    public function delete(){
        $this->load->model('sale/shippment');
        $order_id =$this->request->get['order_id'];
        $shipment_id =$this->request->get['shippment_id'];
        $this->model_sale_shippment->deleteOrderShipment($shipment_id);
        $this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'], 'SSL'));
    }
    public function info() {
        $this->getFormInfo();
        $this->load->model('sale/shippment');
        $order_id =$this->request->get['order_id'];
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
               $order_id =$this->request->post['order_id'];
               $data =array();
               foreach($this->request->post['carrier_code'] as $key=>$item){
                    $data[] =array(
                        'track_id'        =>$key,
                        'carrier_code'  =>$item,
                        'title'        =>$this->request->post['title'][$key],
                        'track_number'        =>$this->request->post['track_number'][$key]
                    );
               }
               $this->model_sale_shippment->updateShippmentTrack($data);
               $this->session->data['success'] ="更新成功";
               $this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$order_id, 'SSL'));
         }
       $this->data['action'] = $this->url->link('sale/shippment/info', 'token=' . $this->session->data['token'], 'SSL');
       $this->data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'] , 'SSL');

        $this->data['order_id'] = $this->request->get['order_id'];
        $this->load->model('sale/order');
        $products = $this->model_sale_order->getOrderShippmentItem((int)$this->request->get['shippment_id']);
        $order_info= $this->getOrderInfo($this->request->get['order_id']);
        foreach($order_info['product'] as $key=>$pro){
            foreach($products as $pro_shippment){
                if($pro['product_id']==$pro_shippment['product_id']){
                    $pro['qty_shiped'] =$pro_shippment['qty'];
                }
                
            }
            $order_info['product'][$key] =$pro;
        }
        $this->load->model('sale/shippment');
        $this->data['tracks']=$this->model_sale_shippment->getShippmentTrack($this->request->get['shippment_id']);
        $this->data['title'] = sprintf($this->language->get('heading'),$order_info['order_number']);    
     

        $this->data['order'] =$order_info;
       $this->template = 'sale/order_shippment_info.tpl';
       $this->children = array(
                'common/header',
                'common/footer'
         );
       $this->response->setOutput($this->render());
        
    }

    public function getForm(){
        
        $this->getFormInfo();
        $order_id =$this->request->get['order_id'];

        
        //$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
        $this->data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token']. '&order_id=' . (int)$this->request->get['order_id'] , 'SSL');

        $this->data['order_id'] = $this->request->get['order_id'];
        $orderInfo = $this->getOrderInfo($order_id);
        $this->data['title'] = sprintf($this->language->get('heading'),$orderInfo['order_number']);    
     
        $this->data['error_warning'] =$this->error;
        $this->data['order'] =$orderInfo;
       $this->template = 'sale/order_shippment.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
         );
       $this->response->setOutput($this->render());
    }
    protected function getFormInfo(){
        $this->language->load('sale/shippment');

       
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
            'href'      => $this->url->link('sale/shippment', 'token=' . $this->session->data['token'], 'SSL'),               
            'separator' => ' :: '
        ); 
    }
    

    //发送shippment 邮件
    public function sendEmail($order_id,$shippment_id){
        $this->load->model('tool/email');
        $this->load->model('sale/shippment');
        $order_info= $this->getOrderInfo($order_id);
        $language =$this->model_sale_order->load_language($order_info['store_id']);
        $language->load('mail/order_shippment');
        $order_info['payment_information']='';
        if ($order_info['payment_code'] == 'bank_transfer') {
            $language->load('payment/bank_transfer');
            $order_info['payment_information'] = $language->get('text_description');
        } elseif ($order_info['payment_code'] == 'westernunion') {
            $language->load('payment/westernunion');
            $order_info['payment_information'] = $language->get('text_description');
        }
        $shippment_info =$this->model_sale_shippment->getShippmentInfo($shippment_id);
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
        $template->data['col_product'] = $language->get('col_product');
        $template->data['col_price'] = $language->get('col_price');
        $template->data['col_qty'] =$language->get('col_qty');
        $template->data['col_total'] =$language->get('col_total');
        $template->data['text_shipped_by'] =$language->get('text_shipped_by');
        $template->data['text_stracking_number'] =$language->get('text_stracking_number');
        $template->data['text_tarcking'] =$language->get('text_tarcking');
        $template->data['text_no_reply'] = $language->get('text_no_reply');
        $template->data['text_footer'] = $language->get('text_edm_foot');
        $template->data['title'] =sprintf($language->get('text_title'),$order_info['order_number']); 
        $shippment_time =gmdate('M d ,Y A',strtotime($shippment_info['created_at']))." GMT";
        $template->data['text_main_content'] = sprintf($language->get('text_main_content'),$this->config->get('config_name'),$order_info['order_number'],$shippment_time);
        $email_data['email_subject'] =sprintf($language->get('text_title'),$order_info['order_number']);
        $products = $this->model_sale_order->getOrderShippmentItem($shippment_id);
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
            foreach($products as $pro_shippment){
                if($product['product_id']==$pro_shippment['product_id']){
                    $product['qty_shiped'] =$pro_shippment['qty'];
                }
            }
            $order_info['product'][$key]['qty_shiped'] =$product['qty_shiped'];
            $order_info['product'][$key]['image'] =$image;
        }
        $template->data['tracks'] =array();
        $tracks_info=$this->model_sale_shippment->getShippmentTrack($shippment_id);
        foreach($tracks_info as $key=>$track){
            $title =strtolower($track['title']);
            if(strpos($title,'dhl')!==false){
                $track['track_url'] =DHL_URL;
            }
            if(strpos($title,'global')!==false){
                $track['track_url'] =GLOBALMAIL_URL;
            }
            if(strpos($title,'ems')!==false||strpos($title,'eub')!==false){
                $track['track_url'] =EMS_URL;
            }
            if(strpos($title,'ups')!==false){
               $track['track_url'] =UPS_URL;
            }
            if(strpos($title,'sg')!==false){
                $track['track_url']=SG_URL;
            }
            if(strpos($title,'au')!==false){
                $track['track_url']=AU_URL;
            }
            if(strpos($title,'usps')!==false){
                $track['track_url'] = USPS_URL;
            }

            if(strpos($title,'sf express')!==false){
                $track['track_url'] = SFEXPRESS_URL;
            }
            
             $template->data['tracks'][$key] =$track;
        }
        $template->data['order'] =$order_info;
        
        $template->data['main_order_tips'] = '';
        if($order_info['main_order_number']){
            $main_order_tips = $language->get('main_order_tips');
            $main_order_tips = sprintf($main_order_tips,$order_info['order_number'],$order_info['main_order_number'],$shippment_time);
            $template->data['main_order_tips'] = $main_order_tips;
        }
        
        $html = $template->fetch('mail/order_shippment.tpl');
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

    function validateForm(){
        if($this->request->post['shipment_items']){
            foreach($this->request->post['shipment_items'] as $key=>$value){
                    if($value['qty']<$value['qty_shipped']){
                        $this->error='product:'.$value['name']." qty for shippment is out to qty for order";
                    }
            }
        }
        if($this->error){
            return false;
        }
        else{
            return true;
        }
    }

   function getOrderInfo($order_id='',$order_no=''){
     $this->load->model('sale/order');

     $this->load->model('setting/setting');
     if($order_id){
        $order_info = $this->model_sale_order->getOrder($order_id);
     }else{
        $order_info = $this->model_sale_order->getOrderByNo($order_no);
        $order_id = $order_info['order_id'];
     }
        
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

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
                'firstname' => $order_info['shipping_firstname'],
                'lastname'  => $order_info['shipping_lastname'],
                'company'   => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city'      => $order_info['shipping_city'],
                'postcode'  => $order_info['shipping_postcode'],
                'zone'      => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country'   => $order_info['shipping_country']
            );

            $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'href'=>$this->url->link('product/product','product_id='.$product['product_id'],'SSL')
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
            
            $parent_id = $order_info['parent_id'];
            $parent_order_number = '';
            if($parent_id){
              $parent_order = $this->model_sale_order->getOrder($parent_id);  
              $parent_order_number = $parent_order['order_number'];
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
                'store_telephone'    => $store_telephone,
                'store_fax'          => $store_fax,
                'email'              => $order_info['email'],
                'firstname'              => $order_info['firstname'],
                'lastname'              => $order_info['lastname'],
                'telephone'          => $order_info['telephone'],
                'customer_id'              => $order_info['customer_id'],
                'customer_group_id'              => $order_info['customer_group_id'],
                'customer_group'              => $customer_group['name'],
                'shipping_address'   => $shipping_address,
                'shipping_method'    => $order_info['shipping_method'],
                'payment_address'    => $payment_address,
                'payment_company_id' => $order_info['payment_company_id'],
                'payment_tax_id'     => $order_info['payment_tax_id'],
                'payment_method'     => $order_info['payment_method'],
                'payment_code'     => $order_info['payment_code'],
                'product'            => $product_data,
                'voucher'            => $voucher_data,
                'total'              => $total_data,
                'comment'            => nl2br($order_info['comment']),
                'main_order_number'  => $parent_order_number,
            );
            return $orderInfo;
        }
        else{
            return array();
        }
   }

   
   function upload(){
        $this->language->load('sale/shippment');
        $this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['flag']) &&  $this->request->get['flag'] == 0) {
			$this->data['error_warning'] = '上传失败';
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->get['flag']) &&  $this->request->get['flag'] == 1) {
			$this->data['success'] = '上传成功';
		} else {
			$this->data['success'] = '';
		}
        
        
        
        $url = '';

		

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
        $this->data['action'] = $this->url->link('sale/shippment/batch', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['heading_title'] = 'upload shippemnt';
        $this->template = 'sale/upload_shippment.tpl';
        $this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
       
   }
   
     public function batch(){
        if($_POST && $_FILES["shippment"]){
            if ($_FILES["shippment"]["error"] > 0)
            {
               $this->redirect( $this->url->link('sale/shippment/upload', 'flag=0&token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            else
            {
                $file = $_FILES['shippment']['tmp_name'];
                $data = file($file);
                foreach ($data as $item){
                    $item = trim($item);
                    $item_data = explode(',',$item);
                    $order_no = $item_data[0];
                    $order_no = trim($order_no);
                    $orderInfo = $this->getOrderInfo('',$order_no);
                    $this->load->model('sale/order');
                    $this->load->model('sale/shippment');
                    $this->load->model('sale/order');
                    $store_id = $orderInfo['store_id'];

                    $post_data = array();
                    
                    foreach($orderInfo['product'] as $product){
                        $post_data['shipment_items'] = array();
                        $post_data['shipment_items'][$product['product_id']] =  array(
                            'qty_shipped' =>$product['quantity'] ,
                            'price' => $product['orginal_price'],
                            'qty' => $product['quantity'],
                            'name' => $product['name'],
                            'sku' => $product['model'],
                        );
                    }
                    $post_data['comment'] = '';
                    $post_data['order_number'] =$orderInfo['order_number'];
                    $post_data['customer_id'] = $orderInfo['customer_id'];
                    $post_data['store_id'] = $store_id;
                    if($item_data[1] != '' &&  $item_data[2]!=''){
                        $post_data['tarck'] = array();
                        $post_data['tarck'][] = array(
                            'carrier' => $item_data[2],
                            'title' => $item_data[1],
                            'namber' => $item_data[3]
                      );
                    }
                    if(trim($item_data[3])&&$this->model_sale_shippment->haveTrack($item_data[3])){
                        $track_data[] =array(
                            'track_id'        =>$track_info['track_id'],
                            'carrier_code'  =>$item_data[2],
                            'title'        =>$item_data[1],
                            'track_number'        =>$item_data[3]  
                        );
                       $this->model_sale_shippment->updateShippmentTrack($track_data);
                       $shippment_id =$track_info['shippment_id'];
                    }
                    else{
                        $shippment_id=$this->model_sale_shippment->addShippment($orderInfo['order_id'],$post_data);
                    }
                    
                    $history_data =array(
                        'order_status_id' =>5,
                        'comment'  =>'upload shippment'
                    );
                    $this->model_sale_order->addOrderHistory($orderInfo['order_id'],$history_data);
                    if($orderInfo['is_parent'] == 0 && $orderInfo['parent_id'] > 0){
                        $order_list = $this->model_sale_order->getOrders(array('is_parent'=>0,'parent_id'=>$orderInfo['parent_id']));
                        if($order_list){
                            $is_all_complete = 1;
                            foreach($order_list as $item){
                                if($item['order_status_id'] != 5){
                                    $is_all_complete = 0;
                                }
                            }
                            if($is_all_complete){
                                $this->model_sale_order->addOrderHistory($orderInfo['parent_id'],$history_data);
                            }
                        }
                    }
                    if($this->sendEmail($orderInfo['order_id'],$shippment_id)){
                        $this->model_sale_shippment->updateEmailSend($shippment_id);
                    }
                    
                    
                }
                
                  $this->redirect( $this->url->link('sale/shippment/upload', 'flag=1&token=' . $this->session->data['token'] . $url, 'SSL'));
            }
        }
    }
   
}
?>
