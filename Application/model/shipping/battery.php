<?php

class ModelShippingBattery extends Model {
    private $products;
    function getQuote($address,$products) {
        $this->products = $products;
        $this->language->load('shipping/myled');
        $country_code = $address['iso_code_2'];
        $country_code = strtoupper($country_code);

        $total_weigth = $this->getWeight();
        $total_weigth = ceil($total_weigth);
        $volume = $this->getVolume();

        $method_data = array();

        $airmail = $this->getMethodByCode('airmailwb', $country_code, $total_weigth);
        if ($airmail) {
            $method_data[] = $airmail;
        }

        //澳洲专线
        $standardwb = $total_weigth;
        if($country_code == 'AU'){
            $volume_weight = $volume /6;
            if($volume_weight > $total_weigth){
                $standardwb = $volume_weight;
            }
        }
        
        $standard = $this->getMethodByCode('standardwb', $country_code, $standardwb);
        if ($standard) {
            $method_data[] = $standard;
        }
        
        $product_cal_weigth = $total_weigth;
        if (ceil($volume / 5) > $total_weigth) {
            $product_cal_weigth = ceil($volume / 5);
        }
        $expedited = $this->getMethodByCode('expeditedwb', $country_code, $product_cal_weigth);
        if ($expedited) {
            //DHL的燃油附加费
            $_surcharge_1 = 0;
            $_surcharge_2 = 0;
            
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
            
            
            $method_data[] = $expedited;
        }
        
        return $method_data;
    }

    public function getMethodByCode($method_code, $country_code, $total_weigth) {
        if (empty($method_code) || empty($country_code)) {
            return false;
        }
        if (!$this->volumeLimit($method_code)) {
            return false;
        }
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_matrixrate_battery WHERE dest_country_id = '" . $country_code . "' AND delivery_method='" . $method_code . "'  AND  condition_from_value <=  '" . $total_weigth . "' AND  condition_to_value >= '" . $total_weigth . "' limit 1");
        if ($query->num_rows) {
            $status = true;
            return $query->rows[0];
        } else {
            $status = false;
            return false;
        }
    }

    public function volumeLimit($method_code) {
        $method_code = strtolower($method_code);
        $products = $this->products;
        if(!$products || count($products)<=0){
            return true;
        }
        switch ($method_code) {
            case 'airmailwb':
            case 'standardwb': {
                    if(is_array($products)) {
                        foreach ($products as $product) {
                            $_volume = array($product['length'], $product['height'], $product['width']);
                            sort($_volume, SORT_NUMERIC);
                            $_h = $_volume[0];
                            $_w = $_volume[1];
                            $_l = $_volume[2];
                            if ($_l > 58 || $_h > 58 || $_w > 58) {
                                return false;
                            }
                            if ($_l + $_h + $_w > 88) {
                                return false;
                            }
                        }
                    } else {
                        return true;
                    }
            }
            break;
           
            case 'expeditedwb': {
                foreach ($products as $product) {
                    $_volume = array($product['length'], $product['height'], $product['width']);
                    sort($_volume, SORT_NUMERIC);
                    $_h = $_volume[0];
                    $_w = $_volume[1];
                    $_l = $_volume[2];
                    $weight = $product['weight'];
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

    public function getVolume() {
        $products = $this->products;
        $volume = 0;
        if(count($products)>0) {
            foreach ($products as $product) {
                $_volume = array($product['length'], $product['height'], $product['width']);
                sort($_volume, SORT_NUMERIC);
                $_h = $_volume[0];
                $_w = $_volume[1];
                $_l = $_volume[2];

                $volume += $_h * $_w * $_l * $product['quantity'];
            }
        }
        return $volume;
    }
    
    public function getWeight($country_code){
        $total_weight = 0;
        $products = $this->products;
        foreach($products as $_product){
            $total_weight += $_product['weight'] * $_product['quantity'];
        }
        return $total_weight;
    }


}

?>