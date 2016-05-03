<?php
class ModelCheckoutCheckout extends Model {
    //得到购物车total价格信息
	 public function getCartTotal(){
		// Totals
			$this->load->model('setting/extension');

			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array(); 

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}
				
				array_multisort($sort_order, SORT_ASC, $results);
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);
						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}

					$sort_order = array(); 
					
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
				  
					array_multisort($sort_order, SORT_ASC, $total_data);
					
				}
			}
			return $total_data; 
	 }

      //得到shipping 方式
	 public function getShippingMethod($shipping_address){
		$shipping_methods = array();
        $total_weigth = $this->cart->getWeight();
        
        $order_packages = $this->splitOrder($shipping_address);
        
        //print_r($order_packages);
  
        $order_no_battery_package = $order_packages['no_battery'];
        $order_battery_packages = $order_packages['battery'];
        $can_not_ship = $order_packages['can_not_ship'];
        $_order_pk = 1;
        if($order_no_battery_package){
            //no battey packages cal
            $this->load->model('shipping/myled');
            $shipping_data = $this->model_shipping_myled->getQuote($shipping_address,$order_no_battery_package);
            $_methods = array();

            foreach($shipping_data as $info){
                $info['shipping_method'] = $info['delivery_type'];
                $info['format_price'] = $this->currency->format($info['price']);
                $_methods[$info['delivery_method']] =  $info;
            }
            $_order_pk_no = 'p' . $_order_pk;
            $shipping_methods[$_order_pk_no] = array(
                'package' => $order_no_battery_package,
                'methods' => $_methods,
                'battery' => 0,
            );
            $_order_pk ++ ;
        }
        if($order_battery_packages){
            $this->load->model('shipping/battery');
            $i = 1;
            foreach($order_battery_packages as $_package){
                $shipping_data = $this->model_shipping_battery->getQuote($shipping_address,$_package);
                $_methods = array();
                foreach($shipping_data as $info){
                    $info['shipping_method'] = $info['delivery_type'];
                    $info['format_price'] = $this->currency->format($info['price']);
                    $_methods[$info['delivery_method']] =  $info;
                }
                $_order_pk_no = 'p' . $_order_pk;
                $shipping_methods[$_order_pk_no] = array(
                    'package' => $_package,
                    'methods' => $_methods,
                    'battery' => 1,
                );
                $_order_pk ++ ;
            }
        }

         /*
        foreach($shipping_methods as $_pk => $_item){
            if(empty($_item['methods'])){
                $can_not_ship = array_merge($can_not_ship,$_item['package']);
            }
        }
         */

        return array('can_shipping'=>$shipping_methods,'no_shipping'=>$can_not_ship);
	 }

     //得到所有的payment 支付方式
     public function getPaymentMethod($shipping_address){
        // Totals
        $total_data = array();					
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $this->load->model('setting/extension');

        $sort_order = array(); 

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        // Payment Methods
        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('payment');

        
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($shipping_address, $total);
                
                if($method){
                    $method_data[$result['code']] = $method;
                }
                
            }
        }

        $sort_order = array(); 

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);	
        return $method_data;
     }

     
     public function getCartTotalMoney(){
         // Totals
			$this->load->model('setting/extension');

			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);
                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }
			
			return $total; 
     }
   
    
    public function splitOrder($shipping_address){


        $shipping_address_country = $shipping_address['iso_code_2'];
        $battery_package_limit_weight_country = $this->config->get('battery_package_limit_weight_country');
        if($battery_package_limit_weight_country[$shipping_address_country]){
            $battery_package_limit_weight = $battery_package_limit_weight_country[$shipping_address_country];
        }else{
            $battery_package_limit_weight = $this->config->get('battery_package_limit_weight');
        }

        $battery_type = $this->config->get('battery_type');
        $total_weigth = $this->cart->getWeight();
        $products = $this->cart->getProducts();
        $_no_battery_products = array();
        $_battery_products = array();
        $_can_not_ship_battery_products = array();
        $_battery_weight = 0;
        foreach($products as $product ){
            $_product_battery_type = $product['battery_type'];
            if(in_array($_product_battery_type,$battery_type)){
                if($product['weight'] > $battery_package_limit_weight){
                    $_can_not_ship_battery_products[] = $product;
                }else{
                    $_battery_products[] = $product;
                    $_battery_weight += $product['weight'] *  $product['quantity'];
                }
            }else{
                $_no_battery_products[] = $product;
            }
        }
        //没有电池,固定一个包裹
        $_order_no_battery_products = array();
        //多个包裹，一个数组一个包裹信息
        $_order_battery_products    = array();
        $this->session->data['battery_can_split'] = 0;
        if(count($_battery_products) == 0){
            $_order_no_battery_products = $_no_battery_products;
            $_order_battery_products = array();
        }else{
            if($total_weigth <= $battery_package_limit_weight){
                //电池和不带电池的产品总重要小于电池一个包裹最高限重，默认使用电池包裹寄送，但是用户可以选择拆包
                if(count($_no_battery_products)> 0){
                    $this->session->data['battery_can_split'] = 1;
                }else{
                    $this->session->data['battery_can_split'] = 0;
                }
                
                if(isset($this->session->data['customer_split_package']) && $this->session->data['customer_split_package']==1 ){
                     $_order_no_battery_products = $_no_battery_products ;
                     $_order_battery_products[]  = $_battery_products;
                } else {
                    $_battery_products = array_merge($_battery_products,$_no_battery_products);
                    $_no_battery_products = array();
                    $_order_no_battery_products = array();
                    $_order_battery_products[] = $_battery_products;
                }
            } else {
                if($_battery_weight <= $battery_package_limit_weight){
                    $_order_no_battery_products = $_no_battery_products;
                    $_order_battery_products[] = $_battery_products;
                }else{
                    $x = array();
                    foreach($_battery_products as $_item){
                        $_product_weight       = $_item['weight'];
                        $_product_quantity     = $_item['quantity'];
                        $_sku                  = $_item['model'];
                        $_product_total_weight = $_product_weight * $_product_quantity;
                        for($i=0;$i<$_product_quantity;$i++){
                            $_item['quantity'] = 1;
                            $x[] = $_item;
                        }
                    }
                    $order_package = array();
                    
                    while (count($x)) {
                        $this->tsort($x);
                        $_res = $this->tanxin($x, $battery_package_limit_weight);
                        $order_package[] = $_res[0];
                        $_used_arr = $_res[1];
                        $t = array();
                        foreach ($x as $_k => $_item) {
                            if (!in_array($_k, $_used_arr)) {
                                $t[] = $_item;
                            }
                        }
                        unset($x);
                        $x = $t;
                    }
                    $_order_no_battery_products = $_no_battery_products;
                    
                    //合并同一个包裹的相同产品
                    
                    $_merge_order_packages = array();
                    foreach($order_package as $_package){
                        $_merge_package = array();
                        foreach($_package as $_p){
                            $model = $_p['model'];
                            if(isset($_merge_package[$model])){
                                $_merge_package[$model]['quantity'] += 1;
                            }else{
                                $_merge_package[$model] = $_p;
                            }
                            $_merge_package[$model]['currency_total'] = round($_merge_package[$model]['currency_price'] * $_merge_package[$model]['quantity'],2);
                        }
                        
                        $_merge_order_packages[] = $_merge_package;
                        
                    }
                    $_order_battery_products = $_merge_order_packages;
                }
            }
        }
        return array(
            'no_battery' => $_order_no_battery_products,
            'battery'    => $_order_battery_products,
            'can_not_ship' => $_can_not_ship_battery_products,
        );
        
    }
    //按照价格和重量比排序
    function tsort(&$x) {
        $len = count($x);
        for ($i = 0; $i < $len; $i++) {
            for ($j = 0; $j <= $len - $i; $j++) {
                $temp = $x[$j];
                $res = $x[$j + 1]['weight'];
                $temres = $temp['weight'];
                if ($res > $temres) {
                    $x[$j] = $x[$j + 1];
                    $x[$j + 1] = $temp;
                }
            }
        }
    }

    //贪心算法
    function tanxin($x, $totalweight) {
        $len = count($x);
        $allprice = 0;
        $package = array();
        $_used_arr = array();
        for ($i = 0; $i < $len; $i++) {
            if ($x[$i]['weight'] > $totalweight) {
                //break;
            } else {
                $allprice += $x[$i]['price'];
                $totalweight = $totalweight - $x[$i]['weight'];
                $package[] = $x[$i];
                $_used_arr[] = $i;
            }
        }
        return array($package, $_used_arr);
    }
}
?>
