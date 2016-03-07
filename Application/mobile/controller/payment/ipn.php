<?php

class ControllerPaymentIpn extends Controller {
    function index(){
        $this->log->write('gobal ipn :'.  var_export($this->request->post,true));
        $txn_type = $this->request->post['txn_type'];
        $txn_type = strtolower($txn_type);
        if($txn_type == 'mp_cancel'){
            $mp_id = $this->request->post['mp_id'];
            $this->load->model('payment/pp_onestep');
            $this->model_payment_pp_onestep->cancelOnestepByBillingAgreementId($mp_id);
        }
        
    }
}

