<?php 
class ModelPaymentGlobebillSofortbanking extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/globebill_sofortbanking');
        $status = true;
		if ($total>5000) {
			$status = false;
		} 
		$currencies = array(
			'EUR'
		);

		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			

        $countries = array(
            'DE','BE','NL','AT'
        );
        if (!in_array(strtoupper($address['iso_code_2']), $countries)) {
			$status = false;
		}			
        
		$method_data = array();

		if ($status) {  
			$method_data = array(
				'code'       => 'globebill_sofortbanking',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('globebill_sofortbanking_sort_order'),
                'desc'       => $this->language->get('text_description'),
			);
            return $method_data;
		} else {
            return false;
        }

		
	}
}
?>