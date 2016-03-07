<?php

class ControllerPaymentGlobebillGiropay extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('payment/globebill_giropay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('globebill_giropay', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_authorization'] = $this->language->get('text_authorization');
        $this->data['text_sale'] = $this->language->get('text_sale');

        $this->data['entry_merchant_no'] = $this->language->get('entry_merchant_no');
        $this->data['entry_payment_gateway'] = $this->language->get('entry_payment_gateway');
        $this->data['entry_signkey_code'] = $this->language->get('entry_signkey_code');
        $this->data['entry_transport_url'] = $this->language->get('entry_transport_url');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_test'] = $this->language->get('entry_test');
        $this->data['entry_transaction'] = $this->language->get('entry_transaction');
        $this->data['entry_debug'] = $this->language->get('entry_debug');
        $this->data['entry_total'] = $this->language->get('entry_total');
        $this->data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
        $this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
        $this->data['entry_denied_status'] = $this->language->get('entry_denied_status');
        $this->data['entry_expired_status'] = $this->language->get('entry_expired_status');
        $this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
        $this->data['entry_pending_status'] = $this->language->get('entry_pending_status');
        $this->data['entry_processed_status'] = $this->language->get('entry_processed_status');
        $this->data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
        $this->data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
        $this->data['entry_voided_status'] = $this->language->get('entry_voided_status');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

         $this->data['entry_payment_review_status'] = $this->language->get('entry_payment_review_status');
        
        
        
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

     
        if (isset($this->error['merchant_no'])) {
            $this->data['error_merchant_no'] = $this->language->get('error_merchant_no');
        } else {
            $this->data['error_merchant_no'] = '';
        }
        if (isset($this->error['payment_gateway'])) {
            $this->data['error_payment_gateway'] = $this->language->get('error_payment_gateway');
        } else {
            $this->data['error_payment_gateway'] = '';
        }
        if (isset($this->error['signkey_code'])) {
            $this->data['error_signkey_code'] = $this->language->get('error_signkey_code');
        } else {
            $this->data['error_signkey_code'] = '';
        }
        if (isset($this->error['transport_url'])) {
            $this->data['error_transport_url'] = $this->language->get('error_transport_url');
        } else {
            $this->data['error_transport_url'] = '';
        }



        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/globebill_giropay', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/globebill_giropay', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['globebill_giropay_email'])) {
            $this->data['globebill_giropay_email'] = $this->request->post['globebill_giropay_email'];
        } else {
            $this->data['globebill_giropay_email'] = $this->config->get('globebill_giropay_email');
        }

        if (isset($this->request->post['globebill_giropay_merchant_no'])) {
            $this->data['globebill_giropay_merchant_no'] = $this->request->post['globebill_giropay_merchant_no'];
        } else {
            $this->data['globebill_giropay_merchant_no'] = $this->config->get('globebill_giropay_merchant_no');
        }
        if (isset($this->request->post['globebill_giropay_payment_gateway'])) {
            $this->data['globebill_giropay_payment_gateway'] = $this->request->post['globebill_giropay_payment_gateway'];
        } else {
            $this->data['globebill_giropay_payment_gateway'] = $this->config->get('globebill_giropay_payment_gateway');
        }
        if (isset($this->request->post['globebill_giropay_signkey_code'])) {
            $this->data['globebill_giropay_signkey_code'] = $this->request->post['globebill_giropay_signkey_code'];
        } else {
            $this->data['globebill_giropay_signkey_code'] = $this->config->get('globebill_giropay_signkey_code');
        }
        if (isset($this->request->post['globebill_giropay_transport_url'])) {
            $this->data['globebill_giropay_transport_url'] = $this->request->post['globebill_giropay_transport_url'];
        } else {
            $this->data['globebill_giropay_transport_url'] = $this->config->get('globebill_giropay_transport_url');
        }


        if (isset($this->request->post['globebill_giropay_total'])) {
            $this->data['globebill_giropay_total'] = $this->request->post['globebill_giropay_total'];
        } else {
            $this->data['globebill_giropay_total'] = $this->config->get('globebill_giropay_total');
        }

        if (isset($this->request->post['globebill_giropay_canceled_reversal_status_id'])) {
            $this->data['globebill_giropay_canceled_reversal_status_id'] = $this->request->post['globebill_giropay_canceled_reversal_status_id'];
        } else {
            $this->data['globebill_giropay_canceled_reversal_status_id'] = $this->config->get('globebill_giropay_canceled_reversal_status_id');
        }

        if (isset($this->request->post['globebill_giropay_completed_status_id'])) {
            $this->data['globebill_giropay_completed_status_id'] = $this->request->post['globebill_giropay_completed_status_id'];
        } else {
            $this->data['globebill_giropay_completed_status_id'] = $this->config->get('globebill_giropay_completed_status_id');
        }

        if (isset($this->request->post['globebill_giropay_denied_status_id'])) {
            $this->data['globebill_giropay_denied_status_id'] = $this->request->post['globebill_giropay_denied_status_id'];
        } else {
            $this->data['globebill_giropay_denied_status_id'] = $this->config->get('globebill_giropay_denied_status_id');
        }

        if (isset($this->request->post['globebill_giropay_expired_status_id'])) {
            $this->data['globebill_giropay_expired_status_id'] = $this->request->post['globebill_giropay_expired_status_id'];
        } else {
            $this->data['globebill_giropay_expired_status_id'] = $this->config->get('globebill_giropay_expired_status_id');
        }

        if (isset($this->request->post['globebill_giropay_failed_status_id'])) {
            $this->data['globebill_giropay_failed_status_id'] = $this->request->post['globebill_giropay_failed_status_id'];
        } else {
            $this->data['globebill_giropay_failed_status_id'] = $this->config->get('globebill_giropay_failed_status_id');
        }

        if (isset($this->request->post['globebill_giropay_pending_status_id'])) {
            $this->data['globebill_giropay_pending_status_id'] = $this->request->post['globebill_giropay_pending_status_id'];
        } else {
            $this->data['globebill_giropay_pending_status_id'] = $this->config->get('globebill_giropay_pending_status_id');
        }

        if (isset($this->request->post['globebill_giropay_processed_status_id'])) {
            $this->data['globebill_giropay_processed_status_id'] = $this->request->post['globebill_giropay_processed_status_id'];
        } else {
            $this->data['globebill_giropay_processed_status_id'] = $this->config->get('globebill_giropay_processed_status_id');
        }

        if (isset($this->request->post['globebill_giropay_refunded_status_id'])) {
            $this->data['globebill_giropay_refunded_status_id'] = $this->request->post['globebill_giropay_refunded_status_id'];
        } else {
            $this->data['globebill_giropay_refunded_status_id'] = $this->config->get('globebill_giropay_refunded_status_id');
        }

        if (isset($this->request->post['globebill_giropay_reversed_status_id'])) {
            $this->data['globebill_giropay_reversed_status_id'] = $this->request->post['globebill_giropay_reversed_status_id'];
        } else {
            $this->data['globebill_giropay_reversed_status_id'] = $this->config->get('globebill_giropay_reversed_status_id');
        }

        if (isset($this->request->post['globebill_giropay_voided_status_id'])) {
            $this->data['globebill_giropay_voided_status_id'] = $this->request->post['globebill_giropay_voided_status_id'];
        } else {
            $this->data['globebill_giropay_voided_status_id'] = $this->config->get('globebill_giropay_voided_status_id');
        }
        
        if (isset($this->request->post['globebill_giropay_payment_review_status_id'])) {
            $this->data['globebill_giropay_payment_review_status_id'] = $this->request->post['globebill_giropay_payment_review_status_id'];
        } else {
            $this->data['globebill_giropay_payment_review_status_id'] = $this->config->get('globebill_giropay_payment_review_status_id');
        }

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();



        $this->load->model('localisation/geo_zone');

        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['globebill_giropay_status'])) {
            $this->data['globebill_giropay_status'] = $this->request->post['globebill_giropay_status'];
        } else {
            $this->data['globebill_giropay_status'] = $this->config->get('globebill_giropay_status');
        }

        if (isset($this->request->post['globebill_giropay_sort_order'])) {
            $this->data['globebill_giropay_sort_order'] = $this->request->post['globebill_giropay_sort_order'];
        } else {
            $this->data['globebill_giropay_sort_order'] = $this->config->get('globebill_giropay_sort_order');
        }

        $this->template = 'payment/globebill_giropay.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/globebill_giropay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['globebill_giropay_merchant_no']) {
            $this->data['error_merchant_no'] = $this->language->get('error_merchant_no');
        }
        if (!$this->request->post['globebill_giropay_payment_gateway']) {
            $this->data['error_payment_gateway'] = $this->language->get('error_payment_gateway');
        }
        if (!$this->request->post['globebill_giropay_signkey_code']) {
            $this->data['error_signkey_code'] = $this->language->get('error_signkey_code');
        }
        if (!$this->request->post['globebill_giropay_transport_url']) {
            $this->data['error_transport_url'] = $this->language->get('error_transport_url');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>