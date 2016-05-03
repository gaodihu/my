<?php 
class ControllerCheckoutCart extends Controller {
	private $error = array();

	public function index() {
		$lang =$this->language->load('checkout/cart');
        $this->data =array_merge($this->data,$lang);
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $this->document->addScript('mobile/view/js/Cart.js');
		$this->document->setTitle($this->language->get('heading_title'));
        /*
         * 数据清理
         */
        unset($this->session->data['paypal']);
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['paypal_ec_setting']);
        unset($this->session->data['customer_split_package']);
        unset($this->session->data['battery_can_split']);
        unset($this->session->data['delivery_method']);
        
        if($this->customer->isLogged()){
            $this->data['logged'] =1;
        }else{
            $this->data['logged'] =0;
        }
		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			$points = $this->customer->getRewardPoints();

			//购物车检查
            $this->load->model('catalog/product');
            $this->load->model('checkout/checkout');
            $error_info = array();
			foreach ($this->cart->getProducts() as $product) {
                $product_id = $product['product_id'];
                $qty = $product['quantity'];
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if($qty<=0){
                        $this->cart->remove($product_id);
                    }
                    //检查商品库存

                    $quantity = $qty;

                    if ($product_info['stock_status_id'] != 7) {
                            $error_info['error'][] = sprintf($this->language->get('error_product_stock'),$product['name']);
                            $this->cart->remove($product_id);
                    }else{
                        if($quantity > $product_info['quantity']){
                            $error_info['error'][] = $product['name'] . ',' . sprintf($this->language->get('error_stock'),$product_info['quantity']);
                            $this->cart->update($product_id, $product_info['quantity']); 
                            $qty = $product_info['quantity'];
                            $product['quantity'] = $product_info['quantity'];
                            $quantity = $product['quantity'];
                         }
                    }
                    //检查是否是特价商品，最近24小时的购买数是否超过最大购买数,如果没有登录以IP作为判断同一用户基准
                    $exclusive_price_info =$this->model_catalog_product->realy_exclusive_price($product_id);
                    if(!empty($exclusive_price_info)&&$exclusive_price_info['limit_number']>0){
                        //得到一天内购买数，以付款订单作为条件结算
                        $date_end =date('Y-m-d H:i:s',time());
                        $data_start =date('Y-m-d H:i:s',time()-24*3600);
                        if(!$this->customer->isLogged()){
                            require_once(DIR_SYSTEM . 'library/ip.php');
                            $IP =new IP();
                            $customer_ip =$IP->getIp();
                            $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id in(2,5) and op.product_id=".$product_id." and o.ip='".$customer_ip."' and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
                        }
                        else{
                            $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id in(2,5) and op.product_id=".$product_id." and o.customer_id=".$this->session->data['customer_id']." and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
                        }
                        $query =$this->db->query($sql);
                        $buy_num =intval($query->row['maxNum']);
                        //购物车已有数量
                        $alrealy_buy =0;
                        
                        $alrealy_buy += $product['quantity'];
                        if(($buy_num+$alrealy_buy) > $exclusive_price_info['limit_number']){
                            if($buy_num>0){
                                $error_info['error'][] = sprintf($this->language->get('error_deals_salesnum'),$product_info['model'],$exclusive_price_info['limit_number'],$buy_num);
                            }else{
                                $error_info['error'][] = sprintf($this->language->get('error_deals_buy'),$product_info['model'],$exclusive_price_info['limit_number']);
                            }
                            
                            $this->cart->update($product_id, $exclusive_price_info['limit_number']-$buy_num); 
                        }
                    }
                }
                else{
                   $this->cart->remove($product_id);
                }
			}

            $this->data['shipping_status'] = $this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping();
            //取得默认配送国家
			if (isset($this->session->data['shipping_address'])) {
                $shipping_address = $this->session->data['shipping_address'];
				$ship_to_countey_code = $shipping_address['iso_code_2'];			  	
			}else if(isset($_COOKIE['battery_ship_to']) && $_COOKIE['battery_ship_to']){
                $ship_to_countey_code = $_COOKIE['battery_ship_to'];
            } else {
                require_once  DIR_SYSTEM .'library/ip.php';
                $ip_class = new Ip();
                $ip = $ip_class->getIp();
               
                $country_code = $ip_class->getCountryCode($ip);
                if($country_code){
                    $ship_to_countey_code = $country_code;
                }
                
			}
            $this->session->data['ship_to_country_code'] =$ship_to_countey_code;
            $this->data['ship_to_countey_code'] =$ship_to_countey_code;
            $this->data['can_estimated_shipping'] = 0;
            $this->load->model('checkout/checkout');
            if($ship_to_countey_code){
                $address['iso_code_2'] = $ship_to_countey_code;
                $_all_packages = $this->model_checkout_checkout->getShippingMethod($address);
                $shipping_methods = $_all_packages['can_shipping'];
                $no_shipping = $_all_packages['no_shipping'];
                if($no_shipping){
                    foreach($no_shipping as $_item){
                        $_product_id = $_item['product_id'];
                        if($this->cart->is_in_cart($_product_id)){
                            $error_info['error'][] = $_item['name'] . $this->language->get("text_product_not_delivered");
                            //$this->cart->remove($product_id);
                        }
                    }
                }
                if(count($shipping_methods) == 1){
                    reset($shipping_methods);
                    $_current_shipping_methods = current($shipping_methods);
                    if(count($_current_shipping_methods['methods']) == 0){
                        $this->data['ship_cost_error'] = 1;
                        $this->data['ship_cost_msg'] = $this->language->get('can_not_ship_to');
                    } else {
                        $this->data['ship_cost']   = $_current_shipping_methods['methods'];
                    }
                }else if(count($shipping_methods) == 0){
                    $this->data['ship_cost_error'] = 1;
                    $this->data['ship_cost_msg'] = $this->language->get('can_not_ship_to');
                }else{
                    $this->data['ship_cost_error'] = 1;
                    $this->data['ship_cost_msg'] = $this->language->get('text_can_not_cal_shipping_fee');
                }
            }
            //$this->cart->clear();
            $this->data['count_qty'] =(int)$this->cart->countProducts();
            $this->data['error_info'] = $error_info['error'];
            //积分计算
            $points_total = 0;
            foreach ($this->cart->getProducts() as $product) {
                if ($product['points']) {
					$points_total += $product['points'];
				}
            }
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} elseif (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$this->data['error_warning'] = $this->language->get('error_stock');
			} else {
				$this->data['error_warning'] = '';
			}

			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$this->data['attention'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$this->data['action'] = $this->url->link('checkout/cart');   

			if ($this->config->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$this->data['weight'] = '';
			}

			$this->load->model('tool/image');

			$this->data['products'] = array();

			$products = $this->cart->getProducts();
			$this->data['products'] =$this->FormatCartProducts($products);
				
			$this->data['products_recurring'] = array();
			
			//货币代码
			$this->data['currency_code'] = $this->currency->getCode();

			if (isset($this->request->post['next'])) {
				$this->data['next'] = $this->request->post['next'];
			} else {
				$this->data['next'] = '';
			}

			$this->data['shipping_status'] = $this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping();
            //取得默认配送国家
			if (isset($this->session->data['ship_to_country_code'])) {
				$ship_to_countey_code = $this->session->data['ship_to_country_code'];			  	
			} else {
                $ship_to_countey_code='';
			}
            $this->session->data['ship_to_country_code'] =$ship_to_countey_code;
            $this->data['ship_to_countey_code'] =$ship_to_countey_code;
            $this->load->model('checkout/checkout');
            $address['iso_code_2'] = $ship_to_countey_code;
            $shipping_data =$this->model_checkout_checkout->getShippingMethod($address);
            $this->data['ship_cost'] =$shipping_data;

			$this->load->model('localisation/country');

			$this->data['countries'] = $this->model_localisation_country->getCountries();

			if (isset($this->request->post['zone_id'])) {
				$this->data['zone_id'] = $this->request->post['zone_id'];				
			} elseif (isset($this->session->data['shipping_zone_id'])) {
				$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
			} else {
				$this->data['zone_id'] = '';
			}

			if (isset($this->request->post['postcode'])) {
				$this->data['postcode'] = $this->request->post['postcode'];				
			} elseif (isset($this->session->data['shipping_postcode'])) {
				$this->data['postcode'] = $this->session->data['shipping_postcode'];					
			} else {
				$this->data['postcode'] = '';
			}
            
            //用户组
            $this->load->model('account/customer_group');
            $this->data['customer_groups'] =$this->model_account_customer_group->getCustomerGroups();
            $this->load->model('checkout/checkout');
			// Totals
			$total_data =$this->model_checkout_checkout->getCartTotal();

			$this->data['totals'] = $total_data;
            $show_paypal = 1;
            foreach($total_data as $total){ 
                if($total['code']=='total'){
                    if($total['value'] > 5000 ){
                        $show_paypal = 0;
                    }
                }
            }
            $this->data['show_paypal'] = $show_paypal;
            //coupon code
            if(isset($this->session->data['coupon'])){
                 $this->data['coupon_code'] =$this->session->data['coupon'] ;
            }
           
			$this->data['continue'] = $this->url->link('common/home');
			$this->data['clear'] = $this->url->link('checkout/cart/clear');
            $this->session->data['redirect'] = $this->url->link('checkout/checkout');
			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
            $this->data['checkout_guest'] = $this->url->link('checkout/checkout', 'guest=1', 'SSL');
            $this->data['paypal_express'] = $this->url->link('payment/pp_express/start', '', 'SSL');
			$this->load->model('setting/extension');

			$this->data['checkout_buttons'] = array();
			
            $this->data['text_apply'] = $this->language->get('text_apply');
            
            
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}

			$this->children = array(
				'common/footer',
				'common/header'	
			);
			$this->response->setOutput($this->render());					
		} else {
            $this->data['products'] =array();
            $this->data['count_qty'] =0;
            
		    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}

			$this->children = array(
				'common/footer',
				'common/header'	
			);
			$this->response->setOutput($this->render());
		}
	}

	//coupon 取消
	public function cancelCoupon() {
		$json = array();
		$this->load->model('checkout/coupon');
        $this->load->model('checkout/checkout');
        $lang =$this->language->load('checkout/cart');
        $this->data =array_merge($this->data,$lang);
        $coupon =$this->session->data['coupon'];
		unset($this->session->data['coupon']);
        $this->data['products'] = array();
        $json['error'] =0;
        $json['message'] ='';
        $json['content'] ='';
        $json['subtotal'] =0;
        $products = $this->cart->getProducts();
        $this->data['products'] =$this->FormatCartProducts($products);
        $total_data =$this->model_checkout_checkout->getCartTotal();
       
        $this->data['totals'] =$total_data;
        $this->data['currency_code'] =$this->session->data['currency'];
        foreach($total_data as $total){
            if($total['code']=='total'){
                $subtoal =$total['text'];
            }
        }
        $this->template = 'default/template/checkout/include/cart_product.tpl';
        $content = $this->render();
        $json['content'] =$content;
        $json['message'] =sprintf($this->language->get('coupon_cancelled_sucess'),$coupon);
        $json['subtotal'] =$subtoal;
		$this->response->setOutput(json_encode($json));
	}

    public function validateCoupon() {
		$json = array();
		$this->load->model('checkout/coupon');
        $this->load->model('checkout/checkout');
        $lang =$this->language->load('checkout/cart');
        $this->data =array_merge($this->data,$lang);
		$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);
        
		if (!$coupon_info) {			
			//$this->error['warning'] = $this->language->get('error_coupon');
            $json['error'] =1;
            $json['message'] =$this->language->get('error_coupon');
            //$json['return'] =$this->url->link('checkout/cart','','SSL');
		}
		else{
			$this->session->data['coupon'] = $this->request->post['coupon'];
            $this->data['products'] = array();
            $json['error'] =0;
            $json['message'] ='';
            $json['content'] ='';
            $json['subtotal'] =0;
            $products = $this->cart->getProducts();
			$this->data['products'] =$this->FormatCartProducts($products);
			$total_data =$this->model_checkout_checkout->getCartTotal();
           
			$this->data['totals'] =$total_data;
			$this->data['currency_code'] =$this->session->data['currency'];
            $this->data['coupon_code'] =$this->request->post['coupon'];
			foreach($total_data as $total){
				if($total['code']=='total'){
					$subtoal =$total['text'];
				}
			}
			$this->template = 'default/template/checkout/include/cart_product.tpl';
			$content = $this->render();
			$json['content'] =$content;
			$json['message'] =sprintf($this->language->get('coupon_sucess'),$this->request->post['coupon']);
			$json['subtotal'] =$subtoal;
		}
		$this->response->setOutput(json_encode($json));
	}

	protected function validateVoucher() {
		$this->load->model('checkout/voucher');

		$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			

		if (!$voucher_info) {			
			$this->error['warning'] = $this->language->get('error_voucher');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}

	protected function validateReward() {
		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}	

		if (empty($this->request->post['reward'])) {
			$this->error['warning'] = $this->language->get('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$this->error['warning'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$this->error['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}

	protected function validateShipping() {
		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
				$this->error['warning'] = $this->language->get('error_shipping');
			}
		} else {
			$this->error['warning'] = $this->language->get('error_shipping');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}
    
    public function getShipCost(){
        $json =array();
        $json['error'] =0;
        $json['message'] ='';
        $country_code =isset($this->request->post['country_code'])?addslashes(trim($this->request->post['country_code'])):'';
        $this->session->data['ship_to_country_code'] =$country_code;
        if($country_code){
            $this->load->model('checkout/checkout');
            $address['iso_code_2'] = $country_code;
            $shipping_data =$this->model_checkout_checkout->getShippingMethod($address);
            $json['data'] =$shipping_data;
        }
        else{
            $json['error'] =1;
        }
        $this->response->setOutput(json_encode($json));
    }
	public function add() {
		$this->language->load('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
        $product_id = intval($product_id);
		$this->load->model('catalog/product');
        $this->load->model('checkout/checkout');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {
            $ship_to = $this->request->post['ship_to'];
            $ship_to = strtoupper($ship_to);
            $battery_type = $this->config->get('battery_type');
            $_is_battery = 0;
            if(in_array($product_info['battery_type'],$battery_type)){ 
                $can_ship = $this->model_catalog_product->canBatteryShipTo($ship_to);
                if(!$can_ship){
                    $json['error'] = $this->language->get('text_can_not_shipping');
                }
            }
            //检查商品库存
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
               
			} else {
				$quantity = 1;
			}
            $quantity = intval($quantity);
             
			
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}

			if (isset($this->request->post['profile_id'])) {
				$profile_id = $this->request->post['profile_id'];
			} else {
				$profile_id = 0;
			}

            if ($product_info['stock_status_id'] != 7) {
					$json['error'] = $this->language->get('error_stock');
			}else{
                if($quantity>$product_info['quantity']){
                   
                    $json['error'] = sprintf($this->language->get('error_stock'),$product_info['quantity']);
                }
            }
            //检查是否是专属特价商品，最近24小时的购买数是否超过最大购买数,如果没有登录以IP作为判断同一用户基准
            $exclusive_price_info =$this->model_catalog_product->realy_exclusive_price($product_id);
            if(!empty($exclusive_price_info)&&$exclusive_price_info['limit_number']>0){
                $limit_number =$exclusive_price_info['limit_number'];
                //得到一天内购买数，以付款订单作为条件结算
                $date_end =date('Y-m-d H:i:s',time());
                $data_start =date('Y-m-d H:i:s',time()-24*3600);
                if(!$this->customer->isLogged()){
                    require_once(DIR_SYSTEM . 'library/ip.php');
                    $IP =new IP();
			        $customer_ip =$IP->getIp();
                    $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id in(2,5) and op.product_id=".$product_id." and o.ip='".$customer_ip."' and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
		        }
                else{
                    $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id in(2,5) and op.product_id=".$product_id." and o.customer_id=".$this->session->data['customer_id']." and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
                }
                $query =$this->db->query($sql);
                $buy_num =intval($query->row['maxNum']);
                //购物车已有数量
                $alrealy_buy =0;
                $cart_product =$this->cart->getProducts();
                foreach($cart_product as $cat_pro){
                    if($cat_pro['product_id']==$product_id){
                        $alrealy_buy+=$cat_pro['quantity'];
                    }
                }
                if(($buy_num+$quantity+$alrealy_buy) > $limit_number){
                    if($buy_num>0&&$alrealy_buy>0){
                        $json['error'] = sprintf($this->language->get('error_deals_buy_and_add'),$product_info['model'],$limit_number,$buy_num,$alrealy_buy);
                    }elseif($buy_num==0&&$alrealy_buy>0){
                        $json['error'] = sprintf($this->language->get('error_deals_add'),$product_info['model'],$limit_number,$alrealy_buy);
                    }
                    elseif($buy_num>0&&$alrealy_buy==0){
                        $json['error'] = sprintf($this->language->get('error_deals_salesnum'),$product_info['model'],$limit_number,$buy_num);
                    }
                    else{
                        $json['error'] = sprintf($this->language->get('error_deals_buy'),$product_info['model'],$limit_number);
                    }
                }
            }
			

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $profile_id);
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$total_data = $this->model_checkout_checkout->getCartTotal();

				
                foreach($total_data as $total){
                    if($total['code']=='total'){
                        $subtoal =$total['text'];
                    }
                }
				//$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
                $json['add_qty'] =$quantity;
				$json['total_num'] =$this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0);
				$json['total_price'] =$subtoal;
                
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}
        else{
            $json['error'] =$this->language->get('error_product');
        }
		$this->response->setOutput(json_encode($json));		
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}

		$this->response->setOutput(json_encode($json));
	}

    public function  update(){
		$lang =$this->language->load('checkout/cart');
        $this->data =array_merge($this->data,$lang);
        $this->load->model('checkout/checkout');
        $product_id = $this->request->post['key'];
        $product_id = intval($product_id);
        $qty = $this->request->post['qty'];
        $qty = intval($qty);
        
        $error_info = array();
        
		$this->load->model('catalog/product');
        $this->load->model('checkout/checkout');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {
            if($qty<=0){
                $this->cart->remove($product_id);
            }
            //检查商品库存

            $quantity = $qty;
            
            if ($product_info['stock_status_id'] != 7) {
					$error_info['error'] = sprintf($this->language->get('error_product_stock'),$product_info['name']);
                     $this->cart->remove($product_id);
			}else{
                if($quantity > $product_info['quantity']){
                    $error_info['error'] = $product_info['name'].','.sprintf($this->language->get('error_stock'),$product_info['quantity']);
                    $qty = $product_info['quantity'];
                    $quantity = $product_info['quantity'];
                }
            }
            //检查是否是特价商品，最近24小时的购买数是否超过最大购买数,如果没有登录以IP作为判断同一用户基准
            $exclusive_price_info =$this->model_catalog_product->realy_exclusive_price($product_id);
            if(!empty($exclusive_price_info)&&$exclusive_price_info['limit_number']>0){
                //得到一天内购买数，以付款订单作为条件结算
                $date_end =date('Y-m-d H:i:s',time());
                $data_start =date('Y-m-d H:i:s',time()-24*3600);
                if(!$this->customer->isLogged()){
                    require_once(DIR_SYSTEM . 'library/ip.php');
                    $IP =new IP();
			        $customer_ip =$IP->getIp();
                    $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id=5 and op.product_id=".$product_id." and o.ip='".$customer_ip."' and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
		        }
                else{
                    $sql = "select sum(op.quantity) as maxNum from ".DB_PREFIX."order o left join ".DB_PREFIX."order_product op on o.order_id=op.order_id where  o.order_status_id=5 and op.product_id=".$product_id." and o.customer_id=".$this->session->data['customer_id']." and o.date_added>='".$data_start."' and o.date_added<='".$date_end."'";
                }
                $query =$this->db->query($sql);
                $buy_num =intval($query->row['maxNum']);
                //购物车已有数量
                $alrealy_buy =$quantity;
        
                if(($buy_num+$alrealy_buy) > $exclusive_price_info['limit_number']){
                    if($buy_num>0){
                        $error_info['error'] = sprintf($this->language->get('error_deals_salesnum'),$product_info['model'],$exclusive_price_info['limit_number'],$buy_num);
                    }
                    else{
                        $error_info['error'] = sprintf($this->language->get('error_deals_buy'),$product_info['model'],$exclusive_price_info['limit_number']);
                    }
                    $qty = $exclusive_price_info['limit_number']-$buy_num;
                }
            }
			
			//if (!$error_info) {
                $this->cart->update($product_id, $qty);  
			//} 
		}
        else{
            $error_info['error'] =$this->language->get('error_product');
        }
 
        $this->data['products'] = array();
        $json =array();
        $json['error'] =0;
        $json['message'] ='';
        $json['content'] ='';
        $json['subtotal'] =0;
        $json['sub_qty'] =0;
        $products = $this->cart->getProducts();
        if($products){
            $this->data['products'] =$this->FormatCartProducts($products);
            $total_data =$this->model_checkout_checkout->getCartTotal();
            $this->data['totals'] =$total_data;
            $this->data['currency_code'] =$this->session->data['currency'];
            foreach($total_data as $total){
                if($total['code']=='total'){
                    $subtoal =$total['text'];
                }
            }

            $this->load->model('checkout/checkout');
            $address['iso_code_2'] = $this->session->data['ship_to_country_code'];
            $shipping_data =$this->model_checkout_checkout->getShippingMethod($address);
            $json['ship_cost'] =$shipping_data;
            $this->template = 'default/template/checkout/include/cart_product.tpl';
            $content = $this->render();
            $json['content'] =$content;
            $json['message'] =$this->language->get('update_sucess');
            $json['subtotal'] =$subtoal;
            $json['sub_qty'] = $this->cart->countProducts();
           $show_paypal = 1;
            foreach($total_data as $total){ 
                if($total['code']=='total'){
                    if($total['value'] > 5000 ){
                        $show_paypal = 0;
                    }
                }
            }
            $json['show_paypal'] = $show_paypal;
        }
        else{
            $json['message'] =$this->language->get('update_error');
        }
        if($error_info['error']){
            $json['error'] = 1; 
            $json['message'] = $error_info['error'];
        }
		$this->response->setOutput(json_encode($json));
    }
    public function remove(){
		$lang =$this->language->load('checkout/cart');
        $this->data =array_merge($this->data,$lang);
        $this->load->model('checkout/checkout');
		
        $key = $this->request->post['key'];
		$key_arr = explode(',',$key);
		$count =count($key_arr);
		if($count>1){
			unset($key_arr[$count-1]);
		}
		foreach($key_arr as $key){
			$this->cart->remove($key);
		}

		
		$this->data['products'] = array();
		$json =array();
		$json['error'] =0;
		$json['message'] ='';
		$json['content'] ='';
		$json['subtotal'] =0;
        $json['sub_qty'] =0;
		$products = $this->cart->getProducts();
		if($products){
			$this->data['products'] =$this->FormatCartProducts($products);
			$total_data =$this->model_checkout_checkout->getCartTotal();
			$this->data['totals'] =$total_data;
			$this->data['currency_code'] =$this->session->data['currency'];
			foreach($total_data as $total){
				if($total['code']=='total'){
					$subtoal =$total['text'];
				}
			}
            $this->load->model('checkout/checkout');
            $address['iso_code_2'] = $this->session->data['ship_to_country_code'];
            $shipping_data =$this->model_checkout_checkout->getShippingMethod($address);
            $json['ship_cost'] =$shipping_data;
			$this->template = 'default/template/checkout/include/cart_product.tpl';
			$content = $this->render();
			$json['content'] =$content;
			$json['message'] =$this->language->get('update_sucess');
			$json['subtotal'] =$subtoal;
            $json['sub_qty'] = $this->cart->countProducts();
		}
		else{
			$json['error'] =1;
			$json['return'] =$this->url->link('checkout/cart');
		}
		$this->response->setOutput(json_encode($json));

    }

	public function clear(){
	   $this->cart->clear();
       $this->redirect($this->url->link('checkout/cart'));

    }
    public function FormatCartProducts($products){
		$format_products =array();
		$subtoal ='';
        $this->load->model('catalog/product');
		$this->load->model('tool/image');
		foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$this->data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 207, 160);
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);

						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
                
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $save_price = $product['original_price'] - $product['price'];
                    $save_price_percent = $this->model_catalog_product->getDiscountPercent($product['price'],$product['original_price'],2);
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                    $original_price = $this->currency->format($this->tax->calculate($product['original_price'], $product['tax_class_id'], $this->config->get('config_tax')));
                    $save_price_text = $this->currency->format($this->tax->calculate($save_price, $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),'USD',$this->currency->getCode());
                       
                        $_total = $price * $product['quantity'];
                        $total_format = $this->currency->onlyFormat($_total,$this->currency->getCode());
				} else {
					$total_format = false;
				}
                
				//$total =$product['price']*$product['quantity'];
				$profile_description = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day' => $this->language->get('text_day'),
						'week' => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month' => $this->language->get('text_month'),
						'year' => $this->language->get('text_year'),
					);

					if ($product['recurring_trial']) {
						$recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
						$profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
					}

					$recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));

					if ($product['recurring_duration']) {
						$profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
					} else {
						$profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
					}
				}
               
                $is_wishlist =$this->model_catalog_product->isWishlist($product['product_id']);
                if($this->currency->getCode() =='EUR'){
                    $price  =number_format($price,2,',','.');
                }
				$format_products[] = array(
                    'rec_id'                 => $product['rec_id'],
                    'product_id'          => $product['product_id'],
					'thumb'               => $image,
					'name'                => $product['name'],
					'model'               => $product['model'],
					'option'              => $option_data,
                    'is_wishlist'              => $is_wishlist,
					'quantity'            => $product['quantity'],
					'stock'               => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'              => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'               => $price,
					'total_format'               => $total_format,
					'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'remove'              => $this->url->link('checkout/cart', 'remove=' . $product['product_id']),
					'recurring'           => $product['recurring'],
					'profile_name'        => $product['profile_name'],
					'profile_description' => $profile_description,
                    'original_price'      => $original_price,
                    'save_price'          => $save_price,
                    'save_price_text'     => $save_price_text,
                    'save_price_percent'  => $save_price_percent,
				);
				//$subtoal +=$total;
		}
		//$subtoal_format = $this->currency->format($subtoal);
		//$format_products['total']['subtotal'] =$subtoal;
		//$format_products['total']['subtoal_format'] =$subtoal_format;
		return $format_products;
	 }
 }
?>
