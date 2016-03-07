<?php 
class ModelPaymentGlobebillPoli extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/globebill_poli');
        $status = true;
		if ($total > 5000) {
			$status = false;
		} 
		$currencies = array(
			'AUD','NZD'
		);

		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			

        $countries = array(
            'AU','NZ' 
        );
        if (!in_array(strtoupper($address['iso_code_2']), $countries)) {
			$status = false;
		}
        if( !((strtoupper($address['iso_code_2']) == 'AU' && strtoupper($this->currency->getCode()) == 'AUD')
            || (strtoupper($address['iso_code_2']) == 'NZ' && strtoupper($this->currency->getCode()) == 'NZD'))
          ){
            $status = false;
        }
		$method_data = array();

		if ($status) {  
			$method_data = array(
				'code'       => 'globebill_poli',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('globebill_poli_sort_order'),
                'desc'       => $this->language->get('text_description'),
			);
            return $method_data;
		}else{
            
            return false;
        }

		
	}
}
?>