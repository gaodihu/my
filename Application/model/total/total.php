<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/total');
		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'text'       => $this->currency->format(max(0, $total)),
			'value'      => max(0, $total),
			'sort_order' => $this->config->get('total_sort_order')
		);
        
        if(isset($this->session->data['shipping_packages'])){
            $shipping_packages = $this->session->data['shipping_packages'];
            foreach($shipping_packages as $_pk => $_package){
                $_total_data = array(
                    'code'       => 'total',
                    'title'      => $this->language->get('text_total'),
                    'text'       => $this->currency->format(max(0, $this->session->data['package_total'][$_pk])),
                    'value'      => max(0, $this->session->data['package_total'][$_pk]),
                    'sort_order' => $this->config->get('total_sort_order')
                );
                 
                $this->session->data['package_total_data'][$_pk][]   =  $_total_data;
            }
        }
	}
}
?>