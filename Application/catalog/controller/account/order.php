<?php
class ControllerAccountOrder extends Controller {
    private $error = array();

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/order');

        $this->load->model('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_account_order->getOrder($this->request->get['order_id']);

            if ($order_info) {
                $order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

                foreach ($order_products as $order_product) {
                    $option_data = array();

                    $order_options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);

                    foreach ($order_options as $order_option) {
                        if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
                            $option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'checkbox') {
                            $option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
                            $option_data[$order_option['product_option_id']] = $order_option['value'];
                        } elseif ($order_option['type'] == 'file') {
                            $option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
                        }
                    }

                    $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['order_id']);

                    $this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
                }

                $this->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->document->setTitle($this->language->get('heading_title'));
        //$this->document->addScript('js/jquery/ui/jquery-ui.min.js');
        $this->document->addStyle('js/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' =>$this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/order', $url, 'SSL'),
            'separator' => false
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_order_id'] = $this->language->get('text_order_id');
        $this->data['text_status'] = $this->language->get('text_status');
        $this->data['text_date_added'] = $this->language->get('text_date_added');
        $this->data['text_customer'] = $this->language->get('text_customer');
        $this->data['text_products'] = $this->language->get('text_products');
        $this->data['text_total'] = $this->language->get('text_total');
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $this->data['text_tracking_no'] = $this->language->get('text_tracking_no');
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_action'] = $this->language->get('text_action');
        $this->data['text_order_number'] = $this->language->get('text_order_number');
        $this->data['text_order_dataed'] = $this->language->get('text_order_dataed');
        $this->data['text_sreach'] = $this->language->get('text_sreach');

        $this->data['button_view'] = $this->language->get('button_view');
        $this->data['button_reorder'] = $this->language->get('button_reorder');
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['action'] =$this->url->link('account/order', $url, 'SSL');
        $data =array();
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $_search_order = '';
         if (isset($this->request->post['order_number'])) {
            $_order_number = $this->request->post['order_number'];
            
            $_results = $this->model_account_order->getOrders(array('order_number'=>$_order_number));
            if($_results){
                if($_results[0]['parent_id'] > 0) {
                    $_parent_order = $this->model_account_order->getOrder($_results[0]['parent_id']);
                    if($_parent_order){
                        $_search_order = $_parent_order['order_number'];
                    }
                }else{
                    $_search_order = $_results[0]['order_number'];
                }
                if(!$_search_order){
                    $_search_order = $this->request->post['order_number'];
                }
            }
         }
        
        if (isset($this->request->post['order_number'])) {
            $data['order_number'] = $_search_order;
            $this->data['order_number'] =$this->request->post['order_number'];
        } else {
            $data['order_number'] ='';
            $this->data['order_number'] ='';
        }
        if (isset($this->request->post['date_from'])) {
            $data['date_from'] = $this->request->post['date_from']." 00:00:00";
            $this->data['date_from'] =$this->request->post['date_from'];
        } else {
            $data['date_from'] = 1;
            $this->data['date_from'] ='';
        }
        if (isset($this->request->post['date_to'])) {
            $data['date_to'] = $this->request->post['date_to']." 24:00:00" ;
            $this->data['date_to'] =$this->request->post['date_to'];
        } else {
            $data['date_to'] =1;
            $this->data['date_to'] ='';
        }
        $data['parent_id'] = 0;
        $this->data['orders'] = array();

        $order_total = $this->model_account_order->getTotalOrders($data);

        $results = $this->model_account_order->getOrders($data,($page - 1) * 10, 10);
        foreach ($results as $result) {
            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
            
           
            $_children_data = array();
            if($result['is_parent'] == 1){
                $_children = $this->model_account_order->getOrders(array('parent_id' => $result['order_id'],'is_parent' => 0 ),0,999);
                foreach($_children as $_item){
                    $_product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
                    $_voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
                    $_children_data[] = array(
                        'order_id'   => $_item['order_id'],
                        'order_number'   => $_item['order_number'],
                        'name'       => $_item['firstname'] . ' ' . $_item['lastname'],
                        'status'     => $_item['status'],
                        'date_added' => date('M d,Y', strtotime($_item['date_added'])),
                        'shipping_method' => $_item['shipping_method'],
                        'tracking_number' => $_item['track_number']?$_item['track_number']:'',
                        'products'   => ($_product_total + $_voucher_total),
                        'total'      => $this->currency->format($_item['total'], $_item['currency_code'], $_item['currency_value']),
                        'href'       => $this->url->link('account/order/info', 'order_id=' . $_item['order_id'], 'SSL'),
                        'reorder'    => $this->url->link('account/order', 'order_id=' . $_item['order_id'], 'SSL')
                    );
                }
            }
            
        
            
            $this->data['orders'][] = array(
                'order_id'   => $result['order_id'],
                'order_number'   => $result['order_number'],
                'name'       => $result['firstname'] . ' ' . $result['lastname'],
                'status'     => $result['status'],
                'date_added' => date('M d,Y', strtotime($result['date_added'])),
                'shipping_method' => $result['shipping_method'],
                'tracking_number' => $result['track_number']?$result['track_number']:'',
                'products'   => ($product_total + $voucher_total),
                'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'reorder'    => $this->url->link('account/order', 'order_id=' . $result['order_id'], 'SSL'),
                'children'   => $_children_data,
            );
        }
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['continue'] = $this->url->link('account/account', '', 'SSL');

        //取得最后下单的2个订单，
        $last_complete_orders = $this->model_account_order->getLastCustomerNoReviewOrders(2);
        if($last_complete_orders){
            $_order_text = '';
            foreach($last_complete_orders as $item){
                $_order_text .= "<a href='" . $this->url->link('account/order/info','order_id='.$item['order_id']). "'>".$item['order_number']."</a>,";
            }
            $_order_text = substr($_order_text,0,-1);
            $text_order_no_reviews = $this->language->get('text_order_no_reviews');
            $text_order_no_reviews = sprintf($text_order_no_reviews,$_order_text);
            $this->data['text_order_no_reviews'] = $text_order_no_reviews;
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
        } else {
            $this->template = 'default/template/account/order_list.tpl';
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

    public function info() {
        $this->language->load('account/order');
        $this->document->addStyle('css/stylesheet/account.css');
        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $customer_id = $this->session->data['customer_id'];
        $this->load->model('checkout/order');
        $this->load->model('account/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);
        
        if ($order_info && $order_info['customer_id'] == $customer_id) {

            $this->document->setTitle($this->language->get('text_order'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' =>$this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_account'),
                'href'      => $this->url->link('account/account', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('heading_title'),
                'href'      => $this->url->link('account/order', $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_order'),
                'href'      => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
                'separator' => false
            );

            $this->data['heading_title'] = $this->language->get('text_order');

            $this->data['text_order_detail'] = $this->language->get('text_order_detail');
            $this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $this->data['text_order_id'] = $this->language->get('text_order_id');
            $this->data['text_date_added'] = $this->language->get('text_date_added');
            $this->data['text_action'] = $this->language->get('text_action');
            $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
            $this->data['text_payment_method'] = $this->language->get('text_payment_method');
            $this->data['text_payment_address'] = $this->language->get('text_payment_address');
            $this->data['text_history'] = $this->language->get('text_history');
            $this->data['text_comment'] = $this->language->get('text_comment');
            $this->data['text_items_ordered'] = $this->language->get('text_items_ordered');
            $this->data['text_shipped_by'] = $this->language->get('text_shipped_by');
            $this->data['text_stracking_number'] = $this->language->get('text_stracking_number');
            $this->data['text_tarcking'] = $this->language->get('text_tarcking');


            $this->data['column_image'] = $this->language->get('column_image');
            $this->data['column_name'] = $this->language->get('column_name');
            $this->data['column_model'] = $this->language->get('column_model');
            $this->data['column_quantity'] = $this->language->get('column_quantity');
            $this->data['column_price'] = $this->language->get('column_price');
            $this->data['column_total'] = $this->language->get('column_total');
            $this->data['column_action'] = $this->language->get('column_action');
            $this->data['column_date_added'] = $this->language->get('column_date_added');
            $this->data['column_status'] = $this->language->get('column_status');
            $this->data['column_comment'] = $this->language->get('column_comment');
            $this->data['column_review']  = $this->language->get('column_review');
            

            $this->data['button_return'] = $this->language->get('button_return');
            $this->data['button_continue'] = $this->language->get('button_continue');



            $this->data['order_id'] = $this->request->get['order_id'];
            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

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

            $products = $this->model_checkout_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {

                $option_data = array();
                 /*
                $options = $this->model_checkout_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }
                */
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
                    'href'     => $this->url->link('product/product',  'product_id=' . $product['product_id'], 'SSL'),
                    'review_link'  => $this->get_product_reviews_link($product['product_id'],$product['model'],$order_info),
                );
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
                            'href'   => $this->url->link('product/product',  'product_id=' . $_product['product_id'], 'SSL'),
                            'review_link'  => $this->get_product_reviews_link($_product['product_id'],$_product['model'],$_item),
                        );
                    }
                    $_item['product_list'] = $_children_product;
                    
                    /*
                    $_results = $this->model_account_order->getOrderHistories($_item['order_id']);
                    $_histories = array();
                    foreach ($_results as $_result) {
                        $_histories[] = array(
                            'date_added' => date($this->language->get('date_format_short'), strtotime($_result['date_added'])),
                            'status'     => $_result['status'],
                            'comment'    => nl2br($_result['comment'])
                        );
                    }
                    $_item['histories'] = $_histories;
                    */
                    
                    $_tracks_info_list = array();

                    $_tracks_info = $this->model_account_order->getOrderTrack($_item['order_id']);

                    foreach ($_tracks_info as $_tracks) {
                        $_title =strtolower($_tracks['title']);
                        if(strpos($_title,'dhl')!==false){
                            $_track_url = DHL_URL;
                        }
                        if(strpos($_title,'global')!==false){
                            $_track_url =GLOBALMAIL_URL;
                        }
                        if(strpos($_title,'ems')!==false||strpos($_title,'eub')!==false){
                            $_track_url =EMS_URL;
                        }
                        if(strpos($_title,'ups')!==false){
                            $track_url =UPS_URL;
                        }
                        if(strpos($_title,'sg')!==false){
                            $track_url =SG_URL;
                        }
                        if(strpos($_title,'au')!==false){
                            $track_url =AU_URL;
                        }

                        if(strpos($_title,'usps')!==false){
                            $track_url =USPS_URL;
                        }
                        if(strpos($_title,'sf express')!==false){
                             $track_url  = SFEXPRESS_URL;
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
            
            
            
            
            $this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

            $this->data['comment'] = nl2br($order_info['comment']);

            $this->data['histories'] = array();

            
            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $this->data['histories'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status'     => $result['status'],
                    'comment'    => nl2br($result['comment'])
                );
            }

            $this->data['tracks_info'] = array();

            $tracks_info = $this->model_account_order->getOrderTrack($this->request->get['order_id']);

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
                    $track_url  = SFEXPRESS_URL;
                }
                $this->data['tracks_info'][] = array(
                    'title' => $tracks['title'],
                    'track_number'     => $tracks['track_number'],
                    'track_url'    => $track_url,
                    'created_at'    => date('Y-m-d',strtotime($tracks['created_at']))
                );
            }
            $this->data['order'] = $order_info;
            $this->data['continue'] = $this->url->link('account/order', '', 'SSL');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/order_info.tpl';
            } else {
                $this->template = 'default/template/account/order_info.tpl';
            }

            $this->children = array(
                'account/menu',
                'account/right_top',
                'account/right_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        } else {
            $url_404 =$this->url->link('error/not_found');
            header('HTTP/1.1 404 Not Found');
            header("status: 404 not found");
            header("location:".$url_404."");

            $this->response->setOutput($this->render());
        }
    }
    
        
    private function get_product_reviews_link($product_id,$product_sku,$order_info){
        $this->load->model('catalog/review');
        if($order_info){
            if($order_info['order_status_id'] != 2 && $order_info['order_status_id'] != 5 ){
                return '';
            }
        }
        $customer_id = $this->session->data['customer_id'];
        $order_number = $order_info['order_number'];
        $reviews = $this->model_catalog_review->getReviewOrders($customer_id,$product_id,$order_number);
        if( $reviews && count($reviews) > 1 && $order_info['order_status_id'] == 5 ){
            return '';
        }
        if( (!$reviews || count($reviews) == 0) && $order_info['order_status_id'] == 5 ){
            return '<a href="'.$this->url->link('product/review_write', '&id=' . $product_id).'">Write a Review</a>';
        }
        return '';
    }

}
?>