<?php
class ControllerAccountOrder extends Controller {
    private $error = array();
    private $limit =6;
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $lang =$this->language->load('account/order');
        $this->data =array_merge($this->data,$lang);
        $this->load->model('account/order');
        $this->document->setTitle($this->language->get('heading_title'));
        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_account_order->getOrder($this->request->get['order_id']);

            if ($order_info) {
                $order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

                foreach ($order_products as $order_product) {
                    $option_data = array();

                 
                    $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['order_id']);

                    $this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
                }

                $this->redirect($this->url->link('checkout/cart'));
            }
        }
        $this->data['action'] =$this->url->link('account/order','', 'SSL');
        $data =array();

        $page = 1;
        $limit =$this->limit;
        $data['page'] =$page;
        $data['limit'] =$limit;
        $url ='';
        if (isset($this->request->request['order_number'])) {
            $data['order_number'] = $this->request->request['order_number'];
            $this->data['order_number'] =$this->request->request['order_number'];
            $url .="&order_number=".$data['order_number'];
        } else {
            $data['order_number'] ='';
            $this->data['order_number'] ='';
        }
        if (isset($this->request->request['date_from'])&&$this->request->request['date_from']) {
            $data['date_from'] = $this->request->request['date_from']." 00:00:00";
            $this->data['date_from'] =$this->request->request['date_from'];
            $url .="&date_from=".$data['date_from'];
        } else {
            $data['date_from'] = 1;
            $this->data['date_from'] ='';
        }
        if (isset($this->request->request['date_to'])&&$this->request->request['date_to']) {
            $data['date_to'] = $this->request->request['date_to']." 24:00:00" ;
            $this->data['date_to'] =$this->request->request['date_to'];
            $url .="&date_to=".$data['date_to'];
        } else {
            $data['date_to'] =1;
            $this->data['date_to'] ='';
        }
        $this->data['orders'] = array();
        $order_total = $this->model_account_order->getTotalOrders($data);
        $this->data['orders'] =$this->getOrderList($data);
        if($order_total >$limit){
            $this->data['show_ajax_list'] =1;
        }else{
            $this->data['show_ajax_list'] =0;
        }
        $this->data['json_list_url'] =$this->url->link('account/order/PageList',$url, 'SSL');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
        } else {
            $this->template = 'default/template/account/order_list.tpl';
        }

        $this->children = array(
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    public function info() {
        $lang =$this->language->load('account/order');
        $this->data =array_merge($this->data,$lang);
        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
        $this->load->model('checkout/order');
        $this->load->model('account/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);
        if ($order_info) {
            $this->document->setTitle($this->language->get('text_order'));
            $this->data['order_id'] = $this->request->get['order_id'];
            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $this->data['order_status'] = $order_info['order_status'];

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
                $this->load->model('catalog/product');
                $pro_info = $this->model_catalog_product->getValue(array('image'),$product['product_id']);
                $this->load->model('tool/image');
                $this->data['products'][] = array(
                    'name'     => $product['name'],
                    'image'     => $this->model_tool_image->resize($pro_info['image'],74,74),
                    'model'    => $product['model'],
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'href'   => $this->url->link('product/product',  'product_id=' . $product['product_id'], 'SSL')
                );
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
                    $track_url = SFEXPRESS_URL;
                }

                $this->data['tracks_info'][] = array(
                    'title' => $tracks['title'],
                    'track_number'     => $tracks['track_number'],
                    'track_url'    => $track_url,
                    'created_at'    => date('Y-m-d',strtotime($tracks['created_at']))
                );
            }
            $this->data['order'] = $order_info;
            $this->data['copy'] = $this->url->link('account/order', '&order_id='.$order_id, 'SSL');
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/order_info.tpl';
            } else {
                $this->template = 'default/template/account/order_info.tpl';
            }

            $this->children = array(
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
    public function PageList(){
        $page =$this->request->get['page']?$this->request->get['page']:0;
        $limit =$this->limit;
        $order_number =$this->request->get['order_number']?$this->request->get['order_number']:'';
        $date_from =$this->request->get['date_from']?$this->request->get['date_from']:1;
        $date_to =$this->request->get['date_to']?$this->request->get['date_to']:1;
        $data =array(
            'page'  =>    $page,
            'limit'  =>    $limit,
            'order_number'  =>    $order_number,
            'date_from'  =>    $date_from,
            'date_to'  =>    $date_to,

        );
        if($page){
            $res =$this->getOrderList($data);
            $json['error']=0;
            $json['data'] =$res;
        }else{
            $json['error']=1;
            $json['message']='load fialed! please try again';
        }
        $this->response->setOutput(json_encode($json));

    }
    public function getOrderList($data){
        $this->language->load('account/order');
        $this->load->model('account/order');
        $page =$data['page']?$data['page']:1;
        $limit =$data['limit']?$data['limit']:6;
        $list =array();
        $results = $this->model_account_order->getOrders($data,($page - 1) * $limit, $limit);
        foreach ($results as $result) {
            $list[] = array(
                'order_id'   => $result['order_id'],
                'order_number'   => $result['order_number'],
                'name'       => $result['firstname'] . ' ' . $result['lastname'],
                'status'     => $result['status'],
                'date_added' => date('M d,Y', strtotime($result['date_added'])),
                'shipping_method' => $result['shipping_method'],
                'tracking_number' => $result['track_number']?$result['track_number']:'N/A',
                'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL')
            );
        }
        return $list;
    }
}
?>