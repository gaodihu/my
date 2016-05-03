<?php 
class ModelPaymentGlobebillWebmoney extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/globebill_webmoney');
        $status = true;
		if ( $total > 5000) {
			$status = false;
		} 
		$currencies = array(
			'EUR','USD'
		);

		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			

        $countries = array(
            'RU'
        );
        if (!in_array(strtoupper($address['iso_code_2']), $countries)) {
			$status = false;
		}			
        
		$method_data = array();

		if ($status) {  
			$method_data = array(
				'code'       => 'globebill_webmoney',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('globebill_webmoney_sort_order'),
                'desc'       => $this->language->get('text_description'),
			);
            return $method_data;
		} else{
            return false;
        }

		
	}
}
?>