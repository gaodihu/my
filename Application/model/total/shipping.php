<?php
class ModelTotalShipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
        $this->language->load('total/shipping');
        
		if ($this->cart->hasShipping() && isset($this->session->data['delivery_method'])) {
			$delivery_method = $this->session->data['delivery_method'];
            $shipping_cost = 0;
            foreach($delivery_method as $_pk => $_package ){
                $shipping_cost += $_package['price'];
            }
            if(isset($this->session->data['delivery_method']) && count($this->session->data['delivery_method']) > 1){
                 $delivery_type = 'shipping fee';
            } else {
                $_current = current($this->session->data['delivery_method']);
                $delivery_type = $_current['delivery_type'];
            }
           
            
            $total_data[] = array(
					'code'       => 'shipping',
					'title'      => $delivery_type, 
					'text'       => $this->currency->format($shipping_cost),
					'value'      => $shipping_cost,
					'sort_order' => $this->config->get('shipping_sort_order')
			);
			$total += $shipping_cost;
		}
        
        if(isset($this->session->data['shipping_packages'])){
            if(isset($this->session->data['delivery_method'])){
                $delivery_method = $this->session->data['delivery_method'];
                foreach($delivery_method as $_pk => $_shipping){
                    $delivery_type = $_shipping['delivery_type']; 
                    $_total_data = array(
                        'code'       => 'shipping',
                        'title'      => $delivery_type, 
                        'text'       => $this->currency->format($_shipping['price']),
                        'value'      => $_shipping['price'],
                        'sort_order' => $this->config->get('shipping_sort_order')
                    );
                    $this->session->data['package_total'][$_pk] +=  $_shipping['price'];
                    $this->session->data['package_total_data'][$_pk][]   =  $_total_data;
                }
            }
        }
	}
}
?>