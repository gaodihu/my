<?php 
class ModelPaymentGlobebillSafety extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/globebill_safety');
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
            'BR','CR','MX','NI','PA','PE','AT','ES','DE','CA','US'
        );
        if (!in_array(strtoupper($address['iso_code_2']), $countries)) {
			$status = false;
		}			
        
		$method_data = array();

		if ($status) {  
			$method_data = array(
				'code'       => 'globebill_safety',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('globebill_safety_sort_order'),
                'desc'       => $this->language->get('text_description'),
			);
            return $method_data;
		} else {
            return false;
        }

		
	}
}
?>