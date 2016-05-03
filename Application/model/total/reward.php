<?php
class ModelTotalReward extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['points'])) {
			$this->language->load('total/reward');

			$config_point_reword =$this->config->get('config_point_reward');
            $discount_total =($this->session->data['points']/$config_point_reword);
            if($discount_total!=0){
                $total_data[] = array(
                    'code'       => 'points',
                    'title'      => sprintf($this->language->get('text_reward'), $this->session->data['points']),
                    'text'       => $this->currency->format(-$discount_total),
                    'value'      => -$discount_total,
                    'sort_order' => $this->config->get('reward_sort_order')
                );

                $total -= $discount_total;
            }
		}
        
        if(isset($this->session->data['shipping_packages'])){
            $shipping_packages = $this->session->data['shipping_packages'];
            $_total_use_discount = 0;
            $_total_use_points = 0;
            $_i = 1;
            foreach($shipping_packages as $_pk => $_package){
                $_products = $_package['package'];
                $_product_total = 0;
                foreach($_products as $_product){
                     $_product_total += $_product['price'] * $_product['quantity'];;
                }
                $_sub_total = $this->cart->getSubTotal();
               
                if($_sub_total>0){
                    if($_i >= count($shipping_packages)){
                        $_product_discount_total = $discount_total - $_total_use_discount;
                        $_product_discount_total = round($_product_discount_total,2);
                        $_points = $this->session->data['points'] - $_total_use_points;
                    }else{
                        $_percentage = round($_product_total/$_sub_total,2);
                        $_product_discount_total = $discount_total * $_percentage;
                        $_product_discount_total = round($_product_discount_total,2);
                        $_points = round($this->session->data['points'] * $_percentage);
                        $_total_use_discount += $_product_discount_total;
                        $_total_use_points += $_points;
                    }
                    if($_product_discount_total!=0) {
                        $_total_data = array(
                            'code'       => 'points',
                            'title'      => sprintf($this->language->get('text_reward'), $_points),
                            'text'       => $this->currency->format(-$_product_discount_total),
                            'value'      => -$_product_discount_total,
                            'sort_order' => $this->config->get('reward_sort_order')
                         );

                        $this->session->data['package_total'][$_pk] -=  $_product_discount_total;
                        $this->session->data['package_total_data'][$_pk][]   =  $_total_data;
                    }
                }
                $_i ++;
            }
        }
        
        
	}

	public function confirm($order_info, $order_total) {
		$this->language->load('total/reward');

		$points = 0;

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {  
			$points = substr($order_total['title'], $start, $end - $start);
		}	

		if ($points) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$order_info['customer_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', points = '" . (float)-$points . "', date_added = NOW()");				
		}
	}		
}
?>