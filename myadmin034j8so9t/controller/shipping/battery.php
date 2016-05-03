<?php

class ControllerShippingBattery extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('shipping/battery');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $data = array();
            $data['battery_status'] = $this->request->post['battery_status'];
            $data['battery_type'] = $this->request->post['battery_type'];
            $data['battery_package_limit_weight'] = $this->request->post['battery_package_limit_weight'];


            foreach($this->request->post['battery_package_limit_weight_country'] as $_k => $_v){
                $data['battery_package_limit_weight_country'][$_v] = $this->request->post['battery_package_limit_weight_country_limit'][$_k];
            }


            $data['battery_sort_order'] = $this->request->post['battery_sort_order'];

            $this->model_setting_setting->editSetting('battery', $data);

            $this->session->data['success'] = $this->language->get('text_success');

            //$this->redirect($this->url->link('shipping/battery', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');
        $this->data['text_none'] = $this->language->get('text_none');

        $this->data['entry_total'] = $this->language->get('entry_total');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_shipping'),
            'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('shipping/battery', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('shipping/battery', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');


        if (isset($this->request->post['battery_status'])) {
            $this->data['battery_status'] = $this->request->post['battery_status'];
        } else {
            $this->data['battery_status'] = $this->config->get('battery_status');
        }
        
        
        if (isset($this->request->post['battery_type'])) {
            
            $this->data['battery_type'] = $this->request->post['battery_type'];
        } else {
            $battery_type = $this->config->get('battery_type');
            $this->data['battery_type'] = $battery_type;
        }
        
        
        if (isset($this->request->post['battery_package_limit_weight'])) {
            
            $this->data['battery_package_limit_weight'] = floatval($this->request->post['battery_package_limit_weight']);
        } else {
            $battery_package_limit_weight = $this->config->get('battery_package_limit_weight');
            $this->data['battery_package_limit_weight'] = $battery_package_limit_weight;
        }

        if (isset($this->request->post['battery_sort_order'])) {
            $this->data['battery_sort_order'] = $this->request->post['battery_sort_order'];
        } else {
            $this->data['battery_sort_order'] = $this->config->get('battery_sort_order');
        }


        if(isset($this->request->post['battery_package_limit_weight_country'])){
            foreach($this->request->post['battery_package_limit_weight_country'] as $_k => $_v){
                $this->data['battery_package_limit_weight_country'][$_v] = $this->request->post['battery_package_limit_weight_country_limit'][$_k];
            }

        }else{
            $this->data['battery_package_limit_weight_country'] = $this->config->get('battery_package_limit_weight_country');
        }

        //添加国家
        $this->load->model('localisation/country');
        $countries = $this->model_localisation_country->getCountries();
        $this->data['countries'] = $countries;

        

        $this->template = 'shipping/battery.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/free')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!isset($this->request->post['battery_package_limit_weight']) || floatval($this->request->post['battery_package_limit_weight']) <= 0) {
            $this->error['warning'] = "请填写重量限制";
        }

        if (!isset($this->request->post['battery_type']) || !is_array($this->request->post['battery_type'])) {
            $this->error['warning'] = "请选择电池类型";
        }
        foreach ($this->request->post['battery_type'] as $_item) {
            if (!in_array($_item, array(1, 2, 3, 4))) {
                $this->error['warning'] = "请选择电池类型";
            }
        }



        if ($this->request->post['battery_package_limit_weight_country']) {
            foreach ($this->request->post['battery_package_limit_weight_country'] as $_country) {
                if (!$_country) {
                    $this->error['warning'] = "请检查国家重量限制";
                }
            }
            $battery_package_limit_weight_country = $this->request->post['battery_package_limit_weight_country'];
            $battery_package_limit_weight_country_unique = array_unique($battery_package_limit_weight_country);
            if(count($battery_package_limit_weight_country)!= count($battery_package_limit_weight_country_unique)){
                $this->error['warning'] = "请检查国家重量限制，重复了国家设置";
            }
        }
        if($this->request->post['battery_package_limit_weight_country_limit']) {
            foreach ($this->request->post['battery_package_limit_weight_country_limit'] as $_limit) {
                if (!$_limit) {
                    $this->error['warning'] = "请检查国家重量限制";
                }
            }
        }
        
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>