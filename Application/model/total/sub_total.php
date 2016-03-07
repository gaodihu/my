<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/sub_total');
		
		//$sub_total = $this->cart->getSubTotal();
		$currency_sub_total = $this->cart->getCurrencySubTotal();
        $sub_total =  $this->currency->convert($currency_sub_total,$this->currency->getCode(),'USD');

		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->onlyformat($currency_sub_total),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
		$total += $sub_total;
        
        
        if(isset($this->session->data['shipping_packages'])){
            $shipping_packages = $this->session->data['shipping_packages'];
            foreach($shipping_packages as $_pk => $_package){
                $_products = $_package['package'];
                $_currency_product_sub_total = 0;
                foreach($_products as $_product){
                    $_currency_product_sub_total += floatval($_product['price']) * floatval($_product['quantity']);
                }
                $_currency_product_sub_total = round($_currency_product_sub_total,2);
                $_total_data = array( 
                    'code'       => 'sub_total',
                    'title'      => $this->language->get('text_sub_total'),
                    'text'       => $this->currency->format($_currency_product_sub_total),
                    'value'      => $_currency_product_sub_total,
                    'sort_order' => $this->config->get('sub_total_sort_order')
                );

                $this->session->data['package_total'][$_pk] +=  $_currency_product_sub_total;
                $this->session->data['package_total_data'][$_pk][]   =  $_total_data;
            }
        }
    }

}
?>