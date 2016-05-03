<?php

class ModelPaymentGlobebillCredit extends Model {

    public function getMethod($address, $total) {
        $this->load->language('payment/globebill_credit');
        $status = true;
        if($total>5000){
            return false;
        }
        /*
        if ($this->config->get('globebill_credit_total') != '' && floatval($this->config->get('globebill_credit_total')) > $total) {
            $status = false;
        }
         * */
        
        $start_time = '2015-03-15 22:50:00';
        $end_time = '2015-03-16 00:10:00';
        $t = date('Y-m-d H:i:s');
        if($t > $start_time && $t < $end_time){
            return false;
        }
        
        $currencies = array(
           'KRW', 'RUB','TRY','BAM','ZAR','BGN','AED','HRK','MXN','HUF','CLP','LTL',
           'BYR','LVL','MYR','RON','MKD','RSD','CSD','BRL','PKR',''
        );

        if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
            //$status = false;
        }
        $globebill_credit_allow_contries_enable = $this->config->get('globebill_credit_allow_contries_enable');
        $globebill_credit_allow_countries = $this->config->get('globebill_credit_allow_countries');
        if ($globebill_credit_allow_contries_enable != 1) {
            $globebill_credit_allow_countries = strtoupper($globebill_credit_allow_countries);
            $globebill_credit_allow_countries = explode(',', $globebill_credit_allow_countries);
            $countries = array_unique($globebill_credit_allow_countries);

            if (!in_array(strtoupper($address['iso_code_2']), $countries)) {
                $status = false;
            }
        }

        $method_data = array();
        
        $sort = $this->config->get('globebill_credit_sort_order');
        if($this->currency->getCode() != 'USD' && $this->currency->getCode() != 'HKD'){
            $sort = 0;
        }
        if ($status) {
            $method_data = array(
                'code' => 'globebill_credit',
                'title' => $this->language->get('text_title'),
                'sort_order' => $sort,
                'desc' => $this->language->get('text_description'),
            );
            return $method_data;
        } else {
            return false;
        }
    }

}

?>