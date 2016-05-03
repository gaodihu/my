<?php 
class ControllerServiceOrderSearch extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('service/order_search');

		$this->load->model('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		}
        $this->document->addStyle('css/stylesheet/account.css');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' =>$this->language->get('text_separator')
		);
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('service/orderSearch', $url, 'SSL'),        	
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

	
        $this->data['text_order_number'] = $this->language->get('text_order_number');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_date_added'] = $this->language->get('text_date_added');
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');
        $this->data['text_payment_address'] = $this->language->get('text_payment_address');
        $this->data['text_items_ordered'] = $this->language->get('text_items_ordered');
        $this->data['text_order_detail'] = $this->language->get('text_order_detail');
		$this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['column_date_added'] = $this->language->get('column_date_added');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['text_emial_zip'] = $this->language->get('text_emial_zip');
        $this->data['empty_order_number'] = $this->language->get('empty_order_number');
        $this->data['empty_order_email_and_zip'] = $this->language->get('empty_order_email_and_zip');
        $this->data['text_war'] = $this->language->get('text_war');
        $this->data['text_shipped_by'] = $this->language->get('text_shipped_by');
        $this->data['text_stracking_number'] = $this->language->get('text_stracking_number');
        $this->data['text_tarcking'] = $this->language->get('text_tarcking');

		$this->data['action'] =$this->url->link('service/orderSearch', $url, 'SSL');
        
        $this->data['order_info'] =array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$order_info=$this->validateForm()) {
            $this->load->model('checkout/order');
            $this->load->model('account/order');
            
            
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

            $this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['payment_method'] = $order_info['payment_method'];

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

            $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['shipping_method'] = $order_info['shipping_method'];
            
              
            $this->data['products'] = array();

            $products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);

            foreach ($products as $product) {
               
                $option_data = array();
                $this->load->model('catalog/product');
                $pro_info = $this->model_catalog_product->getValue(array('image'),$product['product_id']);
                $this->load->model('tool/image');
                $this->data['products'][] = array(
                    'name'     => $product['name'],
                    'image'     => $this->model_tool_image->resize($pro_info['image'],100,100),
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'href'   => $this->url->link('product/product',  'product_id=' . $product['product_id'], 'SSL')
                );
            }
            $this->data['totals'] = $this->model_account_order->getOrderTotals($order_info['order_id']);
            $this->data['track'] =array();
            $tracks_info= $this->model_account_order->getOrderTrack($order_info['order_id']);
            foreach ($tracks_info as $tracks) {
                $title =strtolower($tracks['title']);
                if(strpos($title,'dhl')!==false){
                    $track_url =DHL_URL;
                }
                if(strpos($title,'global')!==false){
                    $track_url =GLOBALMAIL_URL;
                }
                if(strpos($title,'ems')!==false||strpos($title,'eub')!==false){
                    $track_url =EMS_URL;
                }
                if(strpos($title,'ups')!==false){
                    $track_url =UPS_URL;
                }
                if(strpos($title,'sg')!==false){
                    $track_url =SG_URL;
                }
                if(strpos($title,'au')!==false){
                    $track_url =AU_URL;
                }
                if(strpos($title,'usps')!==false){
                    $track_url =USPS_URL;
                }
                if(strpos($title,'sf express')!==false){
                    $track_url = SFEXPRESS_URL;
                }

                $this->data['tracks'][] = array(
                    'title' => $tracks['title'],
                    'track_number'     => $tracks['track_number'],
                    'track_url'    => $track_url ,
                    'created_at'    => date('Y-m-d',strtotime($tracks['created_at']))
                    
                );
            }
            $this->data['order_info'] =$order_info;
            $shipment_creat =$this->getShippmentTime($order_info['order_id']);
            if($shipment_creat){
                $this->data['shipment_creat'] =date('Y-m-d',strtotime($shipment_creat));
            }else{
                $this->data['shipment_creat'] =false;
            }

		}


		if (isset($this->request->post['order_number'])) {
			$this->data['order_number'] = $this->request->post['order_number'];
		} else {
			$this->data['order_number'] ='';
		}
        if (isset($this->request->post['order_email_zip'])) {
			$this->data['order_email_zip'] = $this->request->post['order_email_zip'];
		} else {
			$this->data['order_email_zip'] ='';
		}
         if ($this->error) {
			$this->data['error'] = $this->error;
		} else {
			$this->data['error'] ='';
		}
        
        if($order_info['is_parent'] == 1){
           $children = $this->model_account_order->getOrders(array('parent_id' => $order_info['order_id']));
           foreach($children as $_key => $_item){
                $_products = $this->model_checkout_order->getOrderProducts($_item['order_id']);
                $_children_product = array();
                foreach ($_products as $_product) {
                    $_option_data = array();
                    $this->load->model('catalog/product');
                    $_pro_info = $this->model_catalog_product->getValue(array('image'),$_product['product_id']);
                    $this->load->model('tool/image');
                    $_children_product[] = array(
                        'name'     => $_product['name'],
                        'image'     => $this->model_tool_image->resize($_pro_info['image'],100,100),
                        'model'    => $_product['model'],
                        'option'   => $option_data,
                        'quantity' => $_product['quantity'],
                        'price'    => $this->currency->format($_product['price'] + ($this->config->get('config_tax') ? $_product['tax'] : 0), $_item['currency_code'], $_item['currency_value']),
                        'total'    => $this->currency->format($_product['total'] + ($this->config->get('config_tax') ? ($_product['tax'] * $_product['quantity']) : 0), $_item['currency_code'], $_item['currency_value']),
                        'href'   => $this->url->link('product/product',  'product_id=' . $_product['product_id'], 'SSL')
                    );
                }
                $_item['product_list'] = $_children_product;


                $_tracks_info_list = array();

                $_tracks_info = $this->model_account_order->getOrderTrack($_item['order_id']);

                foreach ($_tracks_info as $_tracks) {
                    $_title =strtolower($_tracks['title']);
                    if(strpos($_title,'dhl')!==false){
                        $_track_url =DHL_URL;
                    }
                    if(strpos($_title,'global')!==false){
                        $_track_url =GLOBALMAIL_URL;
                    }
                    if(strpos($_title,'ems')!==false||strpos($_title,'eub')!==false){
                        $_track_url =EMS_URL;
                    }
                     if(strpos($_title,'ups')!==false){
                        $_track_url =UPS_URL;
                    }
                    if(strpos($_title,'sg')!==false){
                        $_track_url =SG_URL;
                    }
                    if(strpos($_title,'au')!==false){
                        $_track_url =AU_URL;
                    }

                    if(strpos($_title,'usps')!==false){
                        $_track_url =USPS_URL;
                    }

                    if(strpos($_title,'sf express')!==false){
                        $_track_url  = SFEXPRESS_URL;
                    }

                    $_tracks_info_list[] = array(
                        'title' => $_tracks['title'],
                        'track_number'     => $_tracks['track_number'],
                        'track_url'    => $_track_url,
                        'created_at'    => date('Y-m-d',strtotime($_tracks['created_at']))
                    );
                }
                $_item['tracks_info_list'] = $_tracks_info_list;
                $_item['href'] = $this->url->link('account/order/info', 'order_id=' . $_item['order_id'], 'SSL');
                $children[$_key] = $_item;
           }
           $this->data['children'] = $children;
        }
        

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/service/order_search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/service/order_search.tpl';
		} else {
			$this->template = 'default/template/service/order_search.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());				
	}

    private function validateForm(){
        $this->language->load('service/order_search');
        $order_number =isset($this->request->post['order_number'])?trim($this->request->post['order_number']):'';
        $order_email_zip =isset($this->request->post['order_email_zip'])?trim($this->request->post['order_email_zip']):'';
        if(!$order_number){
            $this->error['empty_order_number'] =$this->language->get('empty_order_number');
        }
        if(!$order_email_zip){
            $this->error['empty_order_email_and_zip'] =$this->language->get('empty_order_email_and_zip');
        }
        if($order_email_zip&&$order_number){
            $this->load->model('checkout/order');
            $order_info =$this->model_checkout_order->getOrderByNumber($order_number);
            if($order_email_zip!=$order_info['email']&&$order_email_zip!=$order_info['shipping_postcode']){
                $this->error['error_order_email_pipei'] =$this->language->get('error_order_email_pipei');
            }
        }
        
        if($this->error){
            return false;
        }
        else{
            return $order_info;
        }
    }
    public function getShippmentTime($order_id){
        $sql ="select created_at from ".DB_PREFIX."order_shipment where order_id=".$order_id;
        $query =$this->db->query($sql);
        if($query->num_rows){
            return $query->row['created_at'];
        }else{
            return false;
        }
    }
}
?>