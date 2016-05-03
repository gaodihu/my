<?php
class ModelTotalVip extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->customer->isLogged()) {
			$this->language->load('total/vip');
            $this->load->model('account/customer');
            $customer = $this->model_account_customer->getCustomer($this->customer->getId());
            $this->load->model('account/customer_group');
            $group = $this->model_account_customer_group->getCustomerGroup($customer['customer_group_id']);
            if(!$group){
                return ;
            }
         
            $group_name = $group['name'];
            $group_id  = $group['customer_group_id'];
            $vip_key = 'vip_group_' . $group_id; 
			$config_vip =$this->config->get($vip_key );
            
            $products = $this->cart->getProducts();
            $currency_product_discount_total = 0;
            foreach($products as $product){
                 if ($product['special_price']) {
                     
                }else{
                    $currency_product_discount_total += $product['currency_total'];
                }
            }
            
            $config_vip = floatval($config_vip);
            
           
            
            if($config_vip > 0 ){
                $currency_discount = round($currency_product_discount_total * $config_vip / 100,2);
                $discount = $this->currency->convert($currency_discount,$this->currency->getCode(),$this->currency->getWebDefaultCurrency());

                $total_data[] = array(
                  'code'       => 'vip',
                  'title'      => sprintf($this->language->get('text_reward'), $group_name .' ' . $config_vip . '%'),
                  'text'       => '-' . $this->currency->onlyFormat($currency_discount),
                  'value'      => -$discount,
                  'sort_order' => $this->config->get('vip_sort_order')
              );
              $total -= $discount;
            }
            
            if(isset($this->session->data['shipping_packages'])){
                $shipping_packages = $this->session->data['shipping_packages'];
                foreach($shipping_packages as $_pk => $_package){
                    $_products = $_package['package'];
                    $_currency_product_discount_total = 0;
                    foreach($_products as $_product){
                         if ($_product['special_price']) {

                        }else{
                            $_currency_product_discount_total += $_product['price'] * $_product['quantity'];
                        }
                    }
                    
                    if($config_vip > 0 ){
                        $_currency_discount = round($_currency_product_discount_total * $config_vip / 100,2);
                        $_discount = $_currency_discount;
                        if($_currency_discount>0){
                            $_total_data = array(
                              'code'       => 'vip',
                              'title'      => sprintf($this->language->get('text_reward'), $group_name .' ' . $config_vip . '%'),
                              'text'       => '-' . $this->currency->onlyFormat($_currency_discount),
                              'value'      => -$_currency_discount,
                              'sort_order' => $this->config->get('vip_sort_order')
                          );
                      
                           $this->session->data['package_total'][$_pk] -=  $_discount;
                           $this->session->data['package_total_data'][$_pk][]   =  $_total_data;
                      }
                    }
                    
                   
                }
            }
            
		} 
	}

	public function confirm($order_info, $order_total) {
    }
}
?>