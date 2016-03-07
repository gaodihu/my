<?php
class ModelSaleShipping extends Model {
    private $products;
	function getQuote($address,$total_weigth,$products) {
        $this->products = $products;
        $country_code = $address['iso_code_2'];
        $country_code = strtoupper($country_code);

        $total_weigth =ceil($total_weigth);
        
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
}
?>