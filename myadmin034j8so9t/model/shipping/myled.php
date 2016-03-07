<?php
class ModelShippingMyled extends Model {
    private $products;
	function getQuote($address,$products) {
        $this->products = $products;
        $country_code = $address['iso_code_2'];
        $country_code = strtoupper($country_code);
        $total_weigth =ceil($this->getWeight());
        
        $volume = $this->getVolume($products);
        $method_data = array();
          
        $airmail = $this->getMethodByCode('airmail',$country_code,$total_weigth,$products);
        if($airmail){
           $method_data[] = $airmail;
        }
        
        $standard = $this->getMethodByCode('standard',$country_code,$total_weigth,$products);
        if($standard){
           $method_data[] = $standard;
        }
       
        $product_cal_weigth = $total_weigth;
        
        if(ceil($volume/5) > $total_weigth){
            $product_cal_weigth =  ceil($volume/5);
        }
        
        $expedited = $this->getMethodByCode('expedited',$country_code,$product_cal_weigth,$products);
        if($expedited){
            //DHL的燃油附加费
            $_surcharge_1 = 0;
            $_surcharge_2 = 0;
            //$products = $this->cart->getProducts();
            foreach ($products as $product) {
                $_volume = array($product['length'], $product['height'], $product['width']);
                sort($_volume, SORT_NUMERIC);
                $_h = $_volume[0];
                $_w = $_volume[1];
                $_l = $_volume[2];
                $weight = $product['weight'];
               
                if ($_l > 118 || $_w > 118 || $_h > 118) {
                    $_surcharge_1 = 1;
                }
                if($weight > 68000){
                    $_surcharge_2 = 1;
                }
            }
            $expedited['price'] = $expedited['price'] +  39 * $_surcharge_1 + 39 * $_surcharge_2;
            //是否是偏远地区
            $is_remote =$this->isRemoteArea($address);
            //偏远地区加收偏远地区附加费
            if($is_remote){
                $remote_fee =$this->getRemoteFree($total_weigth);
                $expedited['price'] += $remote_fee;
            }
            $method_data[] = $expedited;
        }
        $product_cal_weigth = $total_weigth;
        if(ceil($volume/8) > $total_weigth){
            $product_cal_weigth =  ceil($volume/8);
        }
        $ems = $this->getMethodByCode('ems',$country_code,$product_cal_weigth,$products);
        if($ems){
             $method_data[] = $ems;
        }
        
		return $method_data;
	}
    public function getMethodByCode($method_code,$country_code,$total_weigth,$products){
        if( empty($method_code) || empty($country_code) ){
            return false;
        }
        if(!$this->volumeLimit($method_code,$products)){
            return false;
        }
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_matrixrate WHERE dest_country_id = '" . $country_code  . "' AND delivery_method='". $method_code ."'  AND  condition_from_value <=  '" .$total_weigth. "' AND  condition_to_value >= '".$total_weigth."' limit 1");
        if ($query->num_rows) {
			$status = true;
            return $query->rows[0];
		} else {
			$status = false;
            return false;
	   }
    }
    
    public function getMethodTypeByCode($method_code){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_matrixrate WHERE delivery_method='". $method_code ."'  limit 1");
        if ($query->num_rows) {
			$status = true;
            return $query->row['delivery_type'];
		} else {
			$status = false;
            return false;
	   }
    }
    public function volumeLimit($method_code,$products){
        $this->load->model('catalog/product');
        $method_code = strtolower($method_code);
        switch($method_code){
            case 'airmail':
            case 'standard':{
                    foreach($products as $product){
                        $product_info =$this->model_catalog_product->getProduct($product['product_id']);
                        $_volume = array($product_info['length'],$product_info['height'],$product_info['width']);
                        sort($_volume,SORT_NUMERIC );
                        $_h = $_volume[0];
                        $_w = $_volume[1];
                        $_l = $_volume[2];
                        if($_l > 58 || $_h > 58 || $_w > 58){
                            return false;
                        }
                        if($_l + $_h + $_w > 88){
                            return false;
                        }
                    }
                }
                break;
            case 'ems' : {
                 foreach($products as $product){
                    $product_info =$this->model_catalog_product->getProduct($product['product_id']);
                    $_volume = array($product_info['length'],$product_info['height'],$product_info['width']);
                    sort($_volume,SORT_NUMERIC );
                    $_h = $_volume[0];
                    $_w = $_volume[1];
                    $_l = $_volume[2];
                    if($_l > 148 || $_h > 148 || $_w > 148){
                        return false;
                    }
                    if($_l + 2 * $_h + 2 * $_w > 298){
                        return false;
                    }
                  }
                }
                break;
            case  'expedited':{
                  foreach($products as $product){
                    $product_info =$this->model_catalog_product->getProduct($product['product_id']);
                    $_volume = array($product_info['length'],$product_info['height'],$product_info['width']);
                    sort($_volume,SORT_NUMERIC);
                    $_h = $_volume[0];
                    $_w = $_volume[1];
                    $_l = $_volume[2];
                    $weight = $product_info['weight'];
                    if ($weight < 68000) {
                        if ($_l > 298 || $_w > 298 || $_h > 298) {
                            return false;
                        }
                    } else {
                        if ($_l > 158 || $_w > 118 || $_h > 100) {
                            return false;
                        }
                    }
                  }
            }
            break;
        }
        return true;
    }

    public function getVolume($products){
        $this->load->model('catalog/product');
        $volume = 0;
        foreach($products as $product){
            $product_info =$this->model_catalog_product->getProduct($product['product_id']);
            $_volume = array($product_info['length'],$product_info['height'],$product_info['width']);
            sort($_volume,SORT_NUMERIC);
            $_h = $_volume[0];
            $_w = $_volume[1];
            $_l = $_volume[2];
            
            $volume += $_h * $_w * $_l * $product['quantity'];
         }
         return $volume;
    }
    public function isRemoteArea($address){
        $total_weigth = $this->getWeight();
        $total_weigth = ceil($total_weigth);
        $tatus =false;
        $query =$this->db->query("select type from ".DB_PREFIX."remote_districts_type where country_code='".$address['iso_code_2']."' and  weight>". $total_weigth);
        if($query->num_rows){
            $type = $query->row['type'];
            switch($type){
                case 0:
                    $tatus =false;
                    break;
                //邮编纯数字区间
                case 1:
                    $query_is =$this->db->query("select id from ".DB_PREFIX."remote_districts where country_code='".$address['iso_code_2']."' and low<='".trim($address['postcode'])."' and high>='".trim($address['postcode'])."' ");
                    if($query_is->num_rows){
                        $tatus =true;
                    }else{
                        $tatus =false;
                    }
                    break;
                 //邮编带分隔符数字区间
                 case 2:
                     $postcode=preg_replace('#([^0-9a-zA-Z])#',' ',$address['postcode']);
                     $postcode=preg_replace('#( +)#',' ',$address['postcode']);
                     $postcode =trim($postcode);
                     $query_is =$this->db->query("select id from ".DB_PREFIX."remote_districts where country_code='".$address['iso_code_2']."' and low<='".$postcode."' and high>='".$postcode."' ");
                    if($query_is->num_rows){
                        $tatus =true;
                    }else{
                        $tatus =false;
                    }
                    break;
                 //邮编数字字母空格混合全部列出
                case 3:
                     $postcode=preg_replace('#([^0-9a-zA-Z])#',' ',$address['postcode']);
                     $postcode=preg_replace('#( +)#',' ',$postcode);
                     $postcode =trim($postcode);
                     $query_is =$this->db->query("select id from ".DB_PREFIX."remote_districts where country_code='".$address['iso_code_2']."' and low='".$postcode."' and high='".$postcode."' ");
                    if($query_is->num_rows){
                        $tatus =true;
                    }else{
                        $tatus =false;
                    }
                    break;
                 //城市名称
                 case 4:
                     $query_is =$this->db->query("select id from ".DB_PREFIX."remote_districts where country_code='".$address['iso_code_2']."' and low ='".trim($address['city'])."' and high ='".trim($address['city'])."' ");
                    if($query_is->num_rows){
                        $tatus =true;
                    }else{
                        $tatus =false;
                    }
                    break;
                 default:
                    $tatus =false;
                    break; 
            }
        }
        return $tatus;
    }
    public function getRemoteFree($total_weigth){
        $remote_fee =0.0006*$total_weigth;
        $remote_fee = max(26,$remote_fee);
        return $remote_fee;
    }
    public function getWeight(){
        $total_weight = 0;
        $products = $this->products;
        foreach($products as $_product){
            $total_weight += $_product['weight'] * $_product['quantity'];
        }
        return $total_weight;
    }
        
    public function splitOrder($weight,$product){
        $battery_package_limit_weight = $this->config->get('battery_package_limit_weight');
        $battery_type = $this->config->get('battery_type');
        $total_weigth = $weight;
        $products = $product;
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
                            $_merge_package[$model]['base_total'] = round($_merge_package[$model]['base_price'] * $_merge_package[$model]['quantity'],2);
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


    //得到shipping 方式
	 public function getShippingMethod($shipping_address,$total_weigth,$product){
        $this->load->model('shipping/myled');
		$shipping_methods = array();
        $order_packages = $this->splitOrder($total_weigth,$product);
        
        //print_r($order_packages);
  
        $order_no_battery_package = $order_packages['no_battery'];
        $order_battery_packages = $order_packages['battery'];
        $can_not_ship = $order_packages['can_not_ship'];
        $_order_pk = 1;
        if($order_no_battery_package){
           
            $shipping_data = $this->getQuote($shipping_address,$order_no_battery_package);
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
        
        return array('can_shipping'=>$shipping_methods,'no_shipping'=>$can_not_ship);
	 }
    
}
?>