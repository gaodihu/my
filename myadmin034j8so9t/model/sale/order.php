<?php
class ModelSaleOrder extends Model {
    public function addOrder($data) {
        $this->load->model('setting/store');
        $order_number = $this->SetOrderNumber($data['store_id']);
        $ii = 0;
        $flag = 0;
        while ($ii < 10) {
            $sql = "SELECT COUNT(*) AS cnt FROM `" . DB_PREFIX . "order` where order_number = '{$order_number}'";
            $rs = $this->db->query($sql);
            $cnt = $rs->row['cnt'];
            if ($cnt > 0) {
                $order_number = $this->SetOrderNumber($data['store_id']);
            } else {
                $flag = 1;
                break;
            }
            $ii ++;
        }
        if (!$flag) {
            return false;
        }
        $store_info = $this->model_setting_store->getStore($data['store_id']);

        if ($store_info) {
            $store_name = $store_info['name'];
            $store_url = $store_info['url'];
        } else {
            $store_name = $this->config->get('config_name');
            $store_url = HTTP_CATALOG;
        }

        $this->load->model('setting/setting');

        $setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);

        if (isset($setting_info['invoice_prefix'])) {
            $invoice_prefix = $setting_info['invoice_prefix'];
        } else {
            $invoice_prefix = $this->config->get('config_invoice_prefix');
        }

        $this->load->model('localisation/country');

        $this->load->model('localisation/zone');

        $country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

        if ($country_info) {
            $shipping_country = $country_info['name'];
            $shipping_address_format = $country_info['address_format'];
            $shipping_country_code = $country_info['iso_code_2'];
        } else {
            $shipping_country = ''; 
            $shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            $shipping_country_code='';
        }   

        $zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

        if ($zone_info) {
            $shipping_zone = $zone_info['name'];
            $shipping_zone_code = $zone_info['code'];
        } else {
            $shipping_zone = '';
            $shipping_zone_code='';
        }   
        
        if(isset($data['payment_country_id'])){
            $country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
        }else{
            $country_info =false;
        }
        if ($country_info) {
            $payment_country = $country_info['name'];
            $payment_address_format = $country_info['address_format'];          
        } else {
            $payment_country = '';  
            $payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';                  
        }
        
        if(isset($data['payment_zone_id'])){
            $zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
        }
        else{
            $zone_info=false;
        }
        

        if ($zone_info) {
            $payment_zone = $zone_info['name'];
        } else {
            $payment_zone = '';         
        }   

        $this->load->model('localisation/currency');

        $currency_info = $this->model_localisation_currency->getCurrencyByCode($data['currency_code']);

        if ($currency_info) {
            $currency_id = $currency_info['currency_id'];
            $currency_code = $currency_info['code'];
            $currency_value = $currency_info['value'];
        } else {
            $currency_id = 0;
            $currency_code = $this->config->get('config_currency');
            $currency_value = 1.00000;          
        }
        if($data['customer_id']){
            $this->load->model('sale/customer');
            $customer_info =$this->model_sale_customer->getCustomer($data['customer_id']);
            $customer_firstname = $customer_info['firstname'];
            $customer_lastname = $customer_info['lastname'];
            $telephone =$customer_info['telephone'];
            $fax =$customer_info['fax'];
        }
        else{
            $customer_firstname = '';
            $customer_lastname = '';
            $fax ='';
        }
       if (isset($data['order_total'])) {
            foreach ($data['order_total'] as $order_total) {
                if($order_total['code']=='sub_total'){
                    $data['base_subtotal'] =$order_total['value'];
                }
                elseif($order_total['code']=='shipping'){
                    $data['base_shipping_amount'] =$order_total['value'];
                }
                elseif($order_total['code']=='total'){
                    $data['base_grand_total'] =$order_total['value'];
                
                }
            }
        }
        $data['shipping_amount'] = $this->currency->format($data['base_shipping_amount'], $currency_code, false, false);
        $data['subtotal'] = $this->currency->format($data['base_subtotal'], $currency_code, false, false);
        $data['grand_total'] = $this->currency->format($data['base_grand_total'],$currency_code, false, false);
        $address ['city'] =$this->request->post['shipping_city'];
        $address ['postcode'] =$this->request->post['shipping_postcode'];
        $address ['iso_code_2'] =$shipping_country_code;
        $this->load->model('shipping/myled');
        $is_remote =$this->model_shipping_myled->isRemoteArea($address);
        if($is_remote){
            $data['is_remote'] =1;
        }else{
            $data['is_remote'] =0;
        }
        
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET order_number ='".$order_number."', invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($customer_firstname) . "', lastname = '" . $this->db->escape($customer_lastname) . "', email = '" . $this->db->escape($data['customer_email']) . "',telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "',shipping_country_code='".$shipping_country_code."', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "',shipping_zone_code='".$shipping_zone_code."', shipping_phone='".$this->db->escape($data['shipping_phone'])."', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "',order_tax_id='".$this->db->escape($data['order_tax_id'])."', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW(),base_shipping_amount='".$data['base_shipping_amount']."',base_subtotal='".$data['base_subtotal']."',base_grand_total='".$data['base_grand_total']."',shipping_amount='".$data['shipping_amount']."',subtotal='".$data['subtotal']."',grand_total='".$data['grand_total']."',is_remote='".$data['is_remote']."',parent_id='".$data['parent_id']."',is_parent='".$data['is_parent']."' ");

        $order_id = $this->db->getLastId();

        if (isset($data['order_product'])) {
           
            foreach ($data['order_product'] as $order_product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', original_price='".(float)$order_product['original_price']."',price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
            }
        }

        // Get the total
        $total = 0;

        if (isset($data['order_total'])) {
            
            foreach ($data['order_total'] as $order_total) {
                if(isset($order_total['code'])){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
                }
            }

            $total += $order_total['value'];
        }


        // Update order total            
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");  
        return $order_id;
    }

    public function editOrder($order_id, $data) {
        $this->load->model('localisation/country');

        $this->load->model('localisation/zone');

        $country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

        if ($country_info) {
            $shipping_country = $country_info['name'];
            $shipping_address_format = $country_info['address_format'];
        } else {
            $shipping_country = ''; 
            $shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }   

        $zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

        if ($zone_info) {
            $shipping_zone = $zone_info['name'];
        } else {
            $shipping_zone = '';            
        }   
        if(isset($data['payment_country_id'])){
            $country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
        }else{
            $country_info=false;
        }
        

        if ($country_info) {
            $payment_country = $country_info['name'];
            $payment_address_format = $country_info['address_format'];          
        } else {
            $payment_country = '';  
            $payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';                  
        }
        if(isset($data['payment_zone_id'])){
            $zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
        }else{
            $zone_info =false;
        }
        

        if ($zone_info) {
            $payment_zone = $zone_info['name'];
        } else {
            $payment_zone = '';         
        }       
        if($data['customer_id']){
            $this->load->model('sale/customer');
            $customer_info =$this->model_sale_customer->getCustomer($data['customer_id']);
            $customer_firstname = $customer_info['firstname'];
            $customer_lastname = $customer_info['lastname'];
            $telephone =$customer_info['telephone'];
            $fax =$customer_info['fax'];
        }
        else{
            $customer_firstname = '';
            $customer_lastname = '';
            $telephone ='';
            $fax ='';
        }

        // Restock products before subtracting the stock later on
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id >= '0' AND order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

            foreach($product_query->rows as $product) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

                $option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                foreach ($option_query->rows as $option) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                }
            }
        }
        $sql ="UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($customer_firstname) . "', lastname = '" . $this->db->escape($customer_lastname) . "', email = '" . $this->db->escape($data['customer_email']) . "', telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "',shipping_phone='".$this->db->escape($data['shipping_phone'])."', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "',order_tax_id='".$this->db->escape($data['order_tax_id'])."', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";
        $this->db->query($sql);

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
        if (isset($data['order_product'])) {
            foreach ($data['order_product'] as $order_product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_product_id = '" . (int)$order_product['order_product_id'] . "', order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "',original_price='".(float)$order_product['original_price']."'");

                $order_product_id = $this->db->getLastId();

                $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

                if (isset($order_product['order_option'])) {
                    foreach ($order_product['order_option'] as $order_option) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_option_id = '" . (int)$order_option['order_option_id'] . "', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");


                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                }

                if (isset($order_product['order_download'])) {
                    foreach ($order_product['order_download'] as $order_download) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_download_id = '" . (int)$order_download['order_download_id'] . "', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($order_download['name']) . "', filename = '" . $this->db->escape($order_download['filename']) . "', mask = '" . $this->db->escape($order_download['mask']) . "', remaining = '" . (int)$order_download['remaining'] . "'");
                    }
                }
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 

        if (isset($data['order_voucher'])) {    
            foreach ($data['order_voucher'] as $order_voucher) {    
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . "', order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");

                $this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
            }
        }

        // Get the total
        $total = 0;

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

        if (isset($data['order_total'])) {      
            foreach ($data['order_total'] as $order_total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_total_id = '" . (int)$order_total['order_total_id'] . "', order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
            }

            $total += $order_total['value'];
        }

        // Affiliate
        $affiliate_id = 0;
        $commission = 0;

        if (!empty($this->request->post['affiliate_id'])) {
            $this->load->model('sale/affiliate');

            $affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);

            if ($affiliate_info) {
                $affiliate_id = $affiliate_info['affiliate_id']; 
                $commission = ($total / 100) * $affiliate_info['commission']; 
            }
        }

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 
    }

    public function deleteOrder($order_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id >= '0' AND order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

            foreach($product_query->rows as $product) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

                $option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                foreach ($option_query->rows as $option) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                }
            }
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_fraud WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_invoice WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE `or`, ort FROM " . DB_PREFIX . "order_recurring `or`, " . DB_PREFIX . "order_recurring_transaction ort WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
        $this->db->query("DELETE `os`, ost FROM " . DB_PREFIX . "order_shipment `os`, " . DB_PREFIX . "order_shipment_item ost WHERE order_id = '" . (int)$order_id . "' AND ost.shippment_id = `os`.shippment_id");
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $reward = 0;

            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

            
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            if ($order_query->row['affiliate_id']) {
                $affiliate_id = $order_query->row['affiliate_id'];
            } else {
                $affiliate_id = 0;
            }               

            $this->load->model('sale/affiliate');

            $affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

            if ($affiliate_info) {
                $affiliate_firstname = $affiliate_info['firstname'];
                $affiliate_lastname = $affiliate_info['lastname'];
            } else {
                $affiliate_firstname = '';
                $affiliate_lastname = '';               
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_filename = $language_info['filename'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_filename = '';
                $language_directory = '';
            }

            $amazonOrderId = '';

            if ($this->config->get('amazon_status') == 1) {
                $amazon_query = $this->db->query("
                    SELECT `amazon_order_id`
                    FROM `" . DB_PREFIX . "amazon_order`
                    WHERE `order_id` = " . (int)$order_query->row['order_id'] . "
                    LIMIT 1")->row;

                if (isset($amazon_query['amazon_order_id']) && !empty($amazon_query['amazon_order_id'])) {
                    $amazonOrderId = $amazon_query['amazon_order_id'];
                }
            }

            if ($this->config->get('amazonus_status') == 1) {
                $amazon_query = $this->db->query("
                        SELECT `amazonus_order_id`
                        FROM `" . DB_PREFIX . "amazonus_order`
                        WHERE `order_id` = " . (int)$order_query->row['order_id'] . "
                        LIMIT 1")->row;

                if (isset($amazon_query['amazonus_order_id']) && !empty($amazon_query['amazonus_order_id'])) {
                    $amazonOrderId = $amazon_query['amazonus_order_id'];
                }
            }
            $invoice_info =$this->getOrderInvoice($order_id);
            return array(
                'amazon_order_id'         => $amazonOrderId,
                'order_id'                => $order_query->row['order_id'],
                'order_number'                => $order_query->row['order_number'],
                'invoice_id'              => isset($invoice_info['invoice_id'])?$invoice_info['invoice_id']:'',
                'invoice_no'              => isset($invoice_info['invoice_no'])?$invoice_info['invoice_no']:'',
                'store_id'                => $order_query->row['store_id'],
                'store_name'              => $order_query->row['store_name'],
                'store_url'               => $order_query->row['store_url'],
                'customer_id'             => $order_query->row['customer_id'],
                'customer'                => $order_query->row['customer'],
                'customer_group_id'       => $order_query->row['customer_group_id'],
                'firstname'               => $order_query->row['firstname'],
                'lastname'                => $order_query->row['lastname'],
                'telephone'               => $order_query->row['telephone'],
                'fax'                     => $order_query->row['fax'],
                'email'                   => $order_query->row['email'],
                'email_send'                   => $order_query->row['email_send'],
                'credit_validate_email_send'                   => $order_query->row['credit_validate_email_send'],
                'pending_email_send'                   => $order_query->row['pending_email_send'],
                'payment_firstname'       => $order_query->row['payment_firstname'],
                'payment_lastname'        => $order_query->row['payment_lastname'],
                'payment_company'         => $order_query->row['payment_company'],
                'payment_company_id'      => $order_query->row['payment_company_id'],
                'payment_tax_id'          => $order_query->row['payment_tax_id'],
                'payment_address_1'       => $order_query->row['payment_address_1'],
                'payment_address_2'       => $order_query->row['payment_address_2'],
                'payment_postcode'        => $order_query->row['payment_postcode'],
                'payment_city'            => $order_query->row['payment_city'],
                'payment_zone_id'         => $order_query->row['payment_zone_id'],
                'payment_zone'            => $order_query->row['payment_zone'],
                'payment_zone_code'       => $payment_zone_code,
                'payment_country_id'      => $order_query->row['payment_country_id'],
                'payment_country'         => $order_query->row['payment_country'],
                'payment_iso_code_2'      => $payment_iso_code_2,
                'payment_iso_code_3'      => $payment_iso_code_3,
                'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_method'          => $order_query->row['payment_method'],
                'payment_code'            => $order_query->row['payment_code'],             
                'shipping_firstname'      => $order_query->row['shipping_firstname'],
                'shipping_lastname'       => $order_query->row['shipping_lastname'],
                'shipping_company'        => $order_query->row['shipping_company'],
                'shipping_address_1'      => $order_query->row['shipping_address_1'],
                'shipping_address_2'      => $order_query->row['shipping_address_2'],
                'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
                'shipping_zone'           => $order_query->row['shipping_zone'],
                'shipping_zone_code'      => $shipping_zone_code,
                'shipping_country_id'     => $order_query->row['shipping_country_id'],
                'shipping_country'        => $order_query->row['shipping_country'],
                'shipping_iso_code_2'     => $shipping_iso_code_2,
                'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method'         => $order_query->row['shipping_method'],
                'shipping_code'           => $order_query->row['shipping_code'],
                'shipping_phone'           => $order_query->row['shipping_phone'],
                'comment'                 => $order_query->row['comment'],
                'total'                   => $order_query->row['total'],
                'reward'                  => $order_query->row['points'],
                'order_status_id'         => $order_query->row['order_status_id'],
                'order_tax_id'         => $order_query->row['order_tax_id'],
                'affiliate_id'            => $order_query->row['affiliate_id'],
                'affiliate_firstname'     => $affiliate_firstname,
                'affiliate_lastname'      => $affiliate_lastname,
                'commission'              => $order_query->row['commission'],
                'language_id'             => $order_query->row['language_id'],
                'language_code'           => $language_code,
                'language_filename'       => $language_filename,
                'language_directory'      => $language_directory,               
                'currency_id'             => $order_query->row['currency_id'],
                'currency_code'           => $order_query->row['currency_code'],
                'currency_value'          => $order_query->row['currency_value'],
                'ip'                      => $order_query->row['ip'],
                'forwarded_ip'            => $order_query->row['forwarded_ip'], 
                'user_agent'              => $order_query->row['user_agent'],   
                'accept_language'         => $order_query->row['accept_language'],                  
                'date_added'              => $order_query->row['date_added'],
                'date_modified'           => $order_query->row['date_modified'],
                'grand_total'           => $order_query->row['grand_total'],
                'base_grand_total'           => $order_query->row['base_grand_total'],
                'pending_email_send_time'                   => $order_query->row['pending_email_send_time'],
                'credit_validate_email_send_time'                   => $order_query->row['credit_validate_email_send_time'],
                'paypal_onestep_auth'           => $order_query->row['paypal_onestep_auth'],
                'is_remote'           => $order_query->row['is_remote'],
                'parent_id'              => $order_query->row['parent_id'],
                'is_parent'              => $order_query->row['is_parent'],
             );
        } else {
            return false;
        }
    }

    public function getOrders($data = array()) {
        
        $sql = "SELECT o.order_id,o.store_id,o.order_number,o.send_forecast_status, CONCAT(o.shipping_firstname, ' ', o.shipping_lastname) AS customer,o.email, o.payment_method, o.payment_code,o.grand_total,(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.parent_id,o.is_parent FROM `" . DB_PREFIX . "order` o ";
        
        
       

        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id >= '0'";
        }

        if (isset($data['filter_send_forecast_status']) && !is_null($data['filter_send_forecast_status'])) {
            $sql .= " AND o.send_forecast_status = '" . (int)$data['filter_send_forecast_status'] . "'";
        } 
        
        if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])&&$data['filter_store_id']>=0) {
            $sql .= " AND o.store_id = '" . (int)$data['filter_store_id'] . "'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }
        if (!empty($data['filter_order_number'])) {
            $sql .= " AND o.order_number = '" . $data['filter_order_number'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.shipping_firstname, ' ', o.shipping_lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }
        if (!empty($data['filter_email'])) {
            $sql .= " AND o.email  ='" . $this->db->escape($data['filter_email']) . "' ";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total_from'])) {
            $sql .= " AND o.total >='" . (float)$data['filter_total_from'] . "'";
        }
        if (!empty($data['filter_total_to'])) {
            $sql .= " AND o.total <='" . (float)$data['filter_total_to'] . "'";
        }
        if (!empty($data['filter_payment_code'])) {
            $sql .= " AND o.payment_code  in  (" . $data['filter_payment_code'] . ")";
        }
        if (isset($data['parent_id'])) {
            $sql .= " AND o.parent_id   = '" . intval($data['parent_id']) . "' ";
        }
        if (isset($data['is_parent'])) {
            $sql .= " AND o.is_parent   = '" . intval($data['is_parent']) . "' ";
        }
            
        $sort_data = array(
            'o.order_id',
            'o.order_number',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total'
        );
    
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }

    public function getOrderOption($order_id, $order_option_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

        return $query->row;
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT oo.* FROM " . DB_PREFIX . "order_option AS oo LEFT JOIN " . DB_PREFIX . "product_option po USING(product_option_id) LEFT JOIN `" . DB_PREFIX . "option` o USING(option_id) WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "' ORDER BY o.sort_order");

        return $query->rows;
    }

    public function getOrderDownloads($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }

    public function getOrderVoucherByVoucherId($voucher_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

        return $query->row;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE order_status_id >= '0'";
        }

        if (isset($data['filter_send_forecast_status']) && !is_null($data['filter_send_forecast_status'])) {
            $sql .= " AND send_forecast_status = '" . (int)$data['filter_send_forecast_status'] . "'";
        }
        if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])&&$data['filter_store_id']>=0) {
            $sql .= " AND store_id = '" . (int)$data['filter_store_id'] . "'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_number'])) {
            $sql .= " AND order_number = '" . $data['filter_order_number'] . "'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }
        if (!empty($data['filter_email'])) {
            $sql .= " AND email = '" . $this->db->escape($data['filter_email']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total_from'])) {
            $sql .= " AND total >='" . (float)$data['filter_total_from'] . "'";
        }
        if (!empty($data['filter_total_to'])) {
            $sql .= " AND total <='" . (float)$data['filter_total_to'] . "'";
        }
        
        if (!empty($data['filter_payment_code'])) {
            $sql .= " AND payment_code  in  (" . $data['filter_payment_code'] . ")";
        }

        if (isset($data['parent_id'])) {
            $sql .= " AND parent_id   = '" . $data['parent_id'] . "' ";
        }
        if (isset($data['is_parent'])) {
            $sql .= " AND is_parent   = '" . $data['is_parent'] . "' ";
        }
     
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByStoreId($store_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrdersByOrderStatusId($order_status_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id >= '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByLanguageId($language_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id >= '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByCurrencyId($currency_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id >= '0'");

        return $query->row['total'];
    }

    public function getTotalSales() {
        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id in(2,5) AND  parent_id = 0");

        return $query->row['total'];
    }

    public function getTotalSalesByYear($year) {
        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id in(2,5) AND YEAR(date_added) = '" . (int)$year . "'  AND  parent_id = 0");

        return $query->row['total'];
    }
    

    public function createInvoiceNo($order_id) {
        $order_info = $this->getOrder($order_id);

        if ($order_info && !$order_info['invoice_no']) {
            $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

            if ($query->row['invoice_no']) {
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

            return $order_info['invoice_prefix'] . $invoice_no;
        }
    }

    public function addOrderHistory($order_id, $data) {
        $order_info = $this->getOrder($order_id);
        $old_order_status_id = $order_info['order_status_id'];
        
        $flag = 0;
        
        //从pending变成processing要扣除库存
        if($old_order_status_id == 1 && $data['order_status_id'] == 2){
            $order_products = $this->getOrderProducts($order_id);
            
            
            //库存检查,如果订单库存不足，就报库存不足
            foreach($order_products as $item){
                $product_number = $item['quantity'];
                $product_id = $item['product_id'];
                $order_product_id = $item['order_product_id'];
                $sql_unlock = "select * from oc_order_product_stock_unlock where order_product_id = '{$order_product_id}'";
                $row_unlock = $this->db->query($sql_unlock);
                if($row_unlock->num_rows){
                    $status = $row_unlock->row['status'];
                    if($status){
                        $sql_product_stock = "select quantity,stock_status_id FROM oc_product where product_id = '{$product_id}'";
                        $product_stock = $this->db->query($sql_product_stock);
                        if($product_stock->num_rows){
                           $product_stock_quantity = $product_stock->row['quantity'];
                           $stock_status_id =  $product_stock->row['stock_status_id'];
                           if($stock_status_id == 7 &&   $product_stock_quantity >= $product_number){
                               
                           }else{
                               return -2;//库存不足
                           }
                        }
                    }
                }
            }
            //扣减库存
            foreach($order_products as $item){
                $product_number = $item['quantity'];
                $product_id = $item['product_id'];
                $order_product_id = $item['order_product_id'];
                $sql_unlock = "select * from oc_order_product_stock_unlock where order_product_id = '{$order_product_id}'";
                $row_unlock = $this->db->query($sql_unlock);
                if($row_unlock->num_rows){
                    $status = $row_unlock->row['status'];
                    if($status){
                        $sql_product_stock = "select quantity,stock_status_id FROM oc_product where product_id = '{$product_id}'";
                        $product_stock = $this->db->query($sql_product_stock);
                        if($product_stock->num_rows){
                           $product_stock_quantity = $product_stock->row['quantity'];
                           $stock_status_id =  $product_stock->row['stock_status_id'];
                           if($stock_status_id == 7 &&   $product_stock_quantity >= $product_number){
                                $flag = 1;
                                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
                                $sql_qty = "UPDATE `" . DB_PREFIX . "product` SET quantity = quantity - " . (int)$product_number . " WHERE product_id = '" . (int)$product_id . "'";
                                $this->db->query($sql_qty);
                                $sql_unlock_status = "UPDATE `" . DB_PREFIX . "order_product_stock_unlock` SET status = 0  WHERE order_product_id = '" . (int)$order_product_id . "'";
                                $this->db->query($sql_unlock_status);
                           }else{
                               return -2;//库存不足
                           }
                        }
                    }else{
                        $flag = 1;
                    }
                }else{
                    $flag = 1;
                }
            }
        }else{
           $flag = 1;
        }
        if($flag){
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
            $this->load->model('user/user');
            $user_id =$this->session->data['user_id'];
            $user_info =$this->model_user_user->getUser($user_id);
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '[user:" .$user_info['username'].']'.$this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");


            //后台更新订单状态，用于线下付款
            //status complete 更新积分和coupon
             if($order_info['order_status_id']!=$data['order_status_id']&&$data['order_status_id']==5){             
                //确认积分使用
                $this->db->query("UPDATE " . DB_PREFIX . "customer_reward SET status=1, date_confirm = NOW() where customer_id =".$order_info['customer_id']." and order_id=".$order_id);
                if($order_info['customer_id']){
                //更新用户组
                    $this->load->model('sale/customer');
                    $total_point =$this->model_sale_customer->getRewardTotal($order_info['customer_id']);
                    $customer_group_id =$this->model_sale_customer->getCustomerGroupInfoByPoints($total_point);
                    $this->db->query("UPDATE ".DB_PREFIX."customer set customer_group_id=".$customer_group_id." where customer_id='".$order_info['customer_id']."' ");
                }
                //确认coupon 使用记录
               $this->db->query("UPDATE " . DB_PREFIX ."coupon_history SET status=1,date_confirm=NOW() WHERE customer_id=".(int)$order_info['customer_id']." AND order_id=".$order_id);

               //增加商品销量值
               $order_products =$this->getOrderProducts($order_id);
               foreach($order_products as $product){
                    $query_salesnum=$this->db->query("select  sales_num from  " . DB_PREFIX ."product_to_store WHERE product_id=".(int)$product['product_id']." AND store_id=".$order_info['store_id']);
                    if($query_salesnum->num_rows){
                        $this->db->query("UPDATE " . DB_PREFIX ."product_to_store set  sales_num=sales_num+".$product['quantity']." WHERE product_id=".(int)$product['product_id']." AND store_id=".$order_info['store_id']);
                    }
               }

             }

             //如果cancle，返回积分
             if($order_info['order_status_id']!=$data['order_status_id']&&($data['order_status_id']==7||$data['order_status_id']==18)){
                    //返还积分
                    $this->db->query("UPDATE " . DB_PREFIX . "customer_reward SET points_spent=0 where customer_id =".$order_info['customer_id']." and order_id=".$order_id);
             }
            // Send out any gift voucher mails
            /*
            if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
                $this->load->model('sale/voucher');

                $results = $this->getOrderVouchers($order_id);

                foreach ($results as $result) {
                    $this->model_sale_voucher->sendVoucher($result['voucher_id']);
                }
            }
            */
            //后台修改订单状态为processing并且勾选通知，发送付款确认邮件
            if ($data['order_status_id']==2&&$data['notify']) {
                //发送收款确认邮件
                $this->load->model('tool/email');
                $language =$this->model_sale_order->load_language($order_info['store_id']);
                $language->load('mail/order_confim');
                $email_data =array();
                $email_data['store_id'] =$order_info['store_id'];

                $email_data['email_from'] ='MyLED';
                $email_data['email_to'] =$order_info['email'];
                $template = new Template();
                $template->data['store_name'] = $order_info['store_name'];
                $template->data['store_url'] = $order_info['store_url'];    

                $template->data['text_home'] =$language->get('text_home');
                $template->data['text_menu_new_arrivals'] =$language->get('text_menu_new_arrivals');
                $template->data['text_menu_top_sellers'] =$language->get('text_menu_top_sellers');
                $template->data['text_menu_deals'] =$language->get('text_menu_deals');
                $template->data['text_menu_clearance'] =$language->get('text_menu_clearance');
                $template->data['text_payment_method'] = $language->get('text_payment_method');
                $template->data['text_payment_information'] = $language->get('text_payment_information');
                $template->data['text_shipping_information'] = $language->get('text_shipping_information');
                $template->data['text_shipping_method'] = $language->get('text_shipping_method');
                $template->data['col_product'] = $language->get('col_product');
                $template->data['col_price'] = $language->get('col_price');
                $template->data['col_qty'] =$language->get('col_qty');
                $template->data['col_total'] =$language->get('col_total');
                $template->data['text_shipped_by'] =$language->get('text_shipped_by');
                $template->data['text_stracking_number'] =$language->get('text_stracking_number');
                $template->data['text_no_reply'] = $language->get('text_no_reply');
                $template->data['text_footer'] = $language->get('text_edm_foot');

              if($order_info['payment_code']=='bank_transfer'){
                   $language->load('payment/bank_transfer');
                   $order_info['payment_information'] = $language->get('text_description');
                   $template->data['title'] =sprintf($language->get('text_bank_title'),$order_info['order_number']); 
                   $template->data['text_main_content'] = sprintf($language->get('text_bank_main_content'),$this->config->get('config_name'),$order_info['order_number']);
                   $email_data['email_subject'] =sprintf($language->get('text_bank_title'),$order_info['order_number']);
                }
               elseif($order_info['payment_code']=='westernunion'){
                   $language->load('payment/westernunion');
                   $order_info['payment_information'] = $language->get('text_description');
                   $template->data['title'] =sprintf($language->get('text_wu_title'),$order_info['order_number']); 
                   $template->data['text_main_content'] = sprintf($language->get('text_wu_main_content'),$this->config->get('config_name'),$order_info['order_number']);
                   $email_data['email_subject'] =sprintf($language->get('text_wu_title'),$order_info['order_number']);
                }

                $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
                foreach ($order_product_query->rows as $order_product) {
                    $this->load->model('catalog/product');
                    $product_info =$this->model_catalog_product->getProductImage($order_product['product_id']);
                    $this->load->model('tool/image');
                    if($product_info['image']){
                        $image =$this->model_tool_image->resize($product_info['image'],60,60);
                    }
                    else{
                         $image=false;
                    }
                    $order_info['products'][] = array(
                        'name'     => $order_product['name'],
                        'model'    => $order_product['model'],
                        'quantity' => $order_product['quantity'],
                        'image'    =>$image,
                        'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'href'=>$this->url->link('product/product','product_id='.$order_product['product_id'],'SSL')
                    );
                }

                    $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{country}'
                 );
                $replace = array(
                    'firstname' => $order_info['shipping_firstname'],
                    'lastname'  => $order_info['shipping_lastname'],
                    'company'   => $order_info['shipping_company'],
                    'address_1' => $order_info['shipping_address_1'],
                    'address_2' => $order_info['shipping_address_2'],
                    'city'      => $order_info['shipping_city'],
                    'postcode'  => $order_info['shipping_postcode'],
                    'zone'      => $order_info['shipping_zone'],
                    'country'   => $order_info['shipping_country']  
                );
                $order_info['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $order_info['shipping_address_format']))));
                $total_data = $this->model_sale_order->getOrderTotals($order_id);
                $order_info['totals']=$total_data;
                $template->data['order'] =$order_info;
                $html = $template->fetch('mail/order_confim.tpl');
                $email_data['email_content'] =addslashes($html);
                $email_data['is_html'] =1;
                $email_data['attachments'] ="";

                $email_id =$this->model_tool_email->addEmailList($email_data);      
            }

            //订单状态为Refunded(退款),发送退款通知邮件
            if($data['order_status_id']==11&&$data['notify']){
                 //发送退款通知邮件
                $this->load->model('tool/email');
                $language =$this->model_sale_order->load_language($order_info['store_id']);
                $language->load('mail/order_refunded');
                $order_info['payment_information'] ='';
                $email_data =array();
                $email_data['email_from'] ='MyLED';
                $email_data['email_to'] =$order_info['email'];
                $template = new Template(); 
                $email_data['store_id'] = $order_info['store_id'];
                $template->data['store_name'] = $order_info['store_name'];
                $template->data['store_url'] = $order_info['store_url'];    
                $template->data['text_home'] =$language->get('text_home');
                $template->data['text_menu_new_arrivals'] =$language->get('text_menu_new_arrivals');
                $template->data['text_menu_top_sellers'] =$language->get('text_menu_top_sellers');
                $template->data['text_menu_deals'] =$language->get('text_menu_deals');
                $template->data['text_menu_clearance'] =$language->get('text_menu_clearance');        
                $template->data['text_payment_method'] = $language->get('text_payment_method');
                $template->data['text_payment_information'] = $language->get('text_payment_information');
                $template->data['text_shipping_information'] = $language->get('text_shipping_information');
                $template->data['text_shipping_method'] = $language->get('text_shipping_method');
                $template->data['col_product'] = $language->get('col_product');
                $template->data['col_price'] = $language->get('col_price');
                $template->data['col_qty'] =$language->get('col_qty');
                $template->data['col_total'] =$language->get('col_total');
                $template->data['text_shipped_by'] =$language->get('text_shipped_by');
                $template->data['text_stracking_number'] =$language->get('text_stracking_number');
                $template->data['text_no_reply'] = $language->get('text_no_reply');
                $template->data['text_footer'] = $language->get('text_edm_foot');

               $template->data['title'] =sprintf($language->get('text_title'),$order_info['order_number']); 
               $refunded_time =gmdate('M d ,Y g:i:s A',time())." GMT";
               $template->data['text_main_content'] = sprintf($language->get('text_main_content'),$this->config->get('config_name'),$order_info['order_number'],$refunded_time);
               $email_data['email_subject'] =sprintf($language->get('text_title'),$order_info['order_number']);

                $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
                foreach ($order_product_query->rows as $order_product) {
                    $this->load->model('catalog/product');
                    $product_info =$this->model_catalog_product->getProductImage($order_product['product_id']);
                    $this->load->model('tool/image');
                    if($product_info['image']){
                        $image =$this->model_tool_image->resize($product_info['image'],60,60);
                    }
                    else{
                         $image=false;
                    }
                    $order_info['products'][] = array(
                        'name'     => $order_product['name'],
                        'model'    => $order_product['model'],
                        'quantity' => $order_product['quantity'],
                        'image'    =>$image,
                        'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total'    => $this->currency->format($order_product['grand_total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'href'=>$this->url->link('product/product','product_id='.$order_product['product_id'],'SSL')
                    );
                }

                    $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{country}'
                 );
                $replace = array(
                    'firstname' => $order_info['shipping_firstname'],
                    'lastname'  => $order_info['shipping_lastname'],
                    'company'   => $order_info['shipping_company'],
                    'address_1' => $order_info['shipping_address_1'],
                    'address_2' => $order_info['shipping_address_2'],
                    'city'      => $order_info['shipping_city'],
                    'postcode'  => $order_info['shipping_postcode'],
                    'zone'      => $order_info['shipping_zone'],
                    'country'   => $order_info['shipping_country']  
                );
                $order_info['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $order_info['shipping_address_format']))));
                $total_data = $this->model_sale_order->getOrderTotals($order_id);
                $order_info['totals']=$total_data;
                $template->data['order'] =$order_info;
                $html = $template->fetch('mail/order_refunded.tpl');
                $email_data['email_content'] =addslashes($html);
                $email_data['is_html'] =1;
                $email_data['attachments'] ="";
                $email_id =$this->model_tool_email->addEmailList($email_data); 
            }
             return  1;
        }
        /*
        $this->load->model('payment/amazon_checkout');
        $this->model_payment_amazon_checkout->orderStatusChange($order_id, $data);
        */
        return  -1;
    }

    public function getOrderHistories($order_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }   

        $query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalOrderHistories($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

        return $query->row['total'];
    }   

    public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

        return $query->row['total'];
    }   

    public function getEmailsByProductsOrdered($products, $start, $end) {
        $implode = array();

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . $product_id . "'";
        }

        $query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

        return $query->rows;
    }

    public function getTotalEmailsByProductsOrdered($products) {
        $implode = array();

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . $product_id . "'";
        }

        $query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . $start . "," . $end);   

        return $query->row['total'];
    }
    
    public function getOrderInvoice($order_id){
        $query = $this->db->query("SELECT *  FROM ".DB_PREFIX."order_invoice where order_id=".$order_id);
        if($query->num_rows){
            return $query->row;
        }
        else{
             return false;
        }
    }

    public function getOrderShippment($order_id){
        $query = $this->db->query("SELECT *  FROM ".DB_PREFIX."order_shipment where order_id=".$order_id);
        if($query->num_rows){
            return $query->rows;
        }
        else{
            return false;    
        }
    }

    public function getOrderTrack($order_id){
        $query = $this->db->query("SELECT st.*  FROM ".DB_PREFIX."order_shipment as s left join ".DB_PREFIX."order_shipment_track as st on s.shippment_id=st.shippment_id where s.order_id=".$order_id);
        if($query->num_rows){
            if($query->row['track_id']){
                 return $query->rows;
            }
           
        }
        else{
            return false;    
        }
    }

    public function getOrderShippmentItem($shipment_id){
        $query = $this->db->query("SELECT *  FROM ".DB_PREFIX."order_shipment_item where shippment_id=".$shipment_id);
        if($query->num_rows){
            return $query->rows;
        }
        else{
            return false;    
        }
    }

    public function getOrderStatus($order_status_id){
        $query = $this->db->query("SELECT name  FROM ".DB_PREFIX."order_status where order_status_id=".$order_status_id." and language_id=".(int)$this->config->get('config_language_id'));
        return $query->row['name'];
    }

    public function getValue($order_id,$filed){
         $query = $this->db->query("SELECT ".$filed."  FROM ".DB_PREFIX."order where order_id=".$order_id);
         return $query->row[$filed];
    }

   /*
    public function addOrderProduct($order_id,$product_info,$qty){
        $query_excit =$this->db->query("select order_product_id,quantity,total,base_total,currency_total from ".DB_PREFIX."order_product where order_id= $order_id and product_id=".$product_info['product_id']);
        if($query_excit->num_rows){
            $row =$query_excit->row;
            $qty =$row['quantity']+$qty;
            $total =$row['total']+$product_info['total'];
            $base_total =$row['base_total']+$product_info['base_total'];
            $currency_total =$row['currency_total']+$product_info['currency_total'];
            
            $this->db->query("UPDATE  ".DB_PREFIX."order_product set quantity='".$qty."',total=$total , base_total=$base_total,currency_total=$currency_total  where order_product_id=".$row['order_product_id']);
        }
        else{
            $query =$this->db->query("INSERT INTO ".DB_PREFIX."order_product set order_id='".$order_id."',product_id='".$product_info['product_id']."',name='".$this->db->escape($product_info['name'])."',model='".$product_info['model']."',quantity='".$qty."',original_price='".$product_info['original_price']."',price='".$product_info['base_price']."',total='".$product_info['total']."',tax='0',reward='0',base_price='".$product_info['base_price']."',base_total='".$product_info['base_total']."',currency_price='".$product_info['currency_price']."',currency_total='".$product_info['currency_total']."' ");
        }
        
    }

    public function ClearAddOrderProducts(){
        $order_id =$this->session->data['user_id']."9999999";
        $this->db->query("DELETE FROM ".DB_PREFIX."order_product where order_id=".$order_id);
    }

    public function DeleteAddOrderProducts($order_product_id){
        $this->db->query("DELETE FROM ".DB_PREFIX."order_product where order_product_id=".$order_product_id);
        
    }
    public function ClearAddOrderTotals(){
        $order_id =$this->session->data['user_id']."9999999";
        $this->db->query("DELETE FROM ".DB_PREFIX."order_total where order_id=".$order_id);
    }
  
    public function addOrderTotal($order_id,$data){
        $query_excit =$this->db->query("select order_total_id,code,text,value from ".DB_PREFIX."order_total where order_id= $order_id and code='".$data['code']."'");
        if($query_excit->num_rows){
            $row =$query_excit->row;
            $query =$this->db->query("UPDATE ".DB_PREFIX."order_total set title='".$data['title']."',text='".$data['text']."',value='".$data['value']."' where order_total_id=".$row['order_total_id']);
        }
        else{
            $query =$this->db->query("INSERT INTO ".DB_PREFIX."order_total set order_id='".$order_id."',code='".$data['code']."',title='".$data['title']."',text='".$data['text']."',value='".$data['value']."',sort_order=".$data['sort_order']." ");
        }
        
    }
   */
    public function updateOrderTotal($order_id,$total_array){
        foreach($total_array as $total){
            $query =$this->db->query("UPDATE ".DB_PREFIX."order_total set value='".$total['value']."',text='".$total['text']."' where order_id =".$order_id." and code='".$total['code']."'");
        }
        
    }

   public function UpdateSendStatus($order_id){
        $this->db->query("UPDATE ".DB_PREFIX."order set send_forecast_status=1 where order_id=".$order_id);
   }
   
   public function getPaypalDetail($order_id){
       $sql = "SELECT t.debug_data FROM " .DB_PREFIX. "paypal_order p," . DB_PREFIX . "paypal_order_transaction t where p.order_id = '{$order_id}' and  p.paypal_order_id = t.paypal_order_id";
       $query = $this->db->query($sql);
       if($query->num_rows){
           $data = $query->row['debug_data'];
           return json_decode($data,true);
       }else{
           return false;
       }
   }


     //订单号生成规则
    public function SetOrderNumber($store_id) {
        //require_once(DIR_SYSTEM . 'helper/fun.inc.php');
        /*
        11 - myled英文站     0
        15 - myled德语站     52
        16 - myled法语站     54
        17 - myled意大利语站  55
        18 - myled西语种     53
        19 - myled葡语站     56
         * *
         */
        $pre_store = '';
        $store_id = intval($store_id);
        switch($store_id){
            case 0  : $pre_store = '11';break;
            case 52 : $pre_store = '15';break;
            case 53 : $pre_store = '18';break;
            case 54 : $pre_store = '16';break;
            case 55 : $pre_store = '17';break;
            case 56 : $pre_store = '19';break;
            default :
                $pre_store  = '11';
        }
        $date = date('ymd');
        $number = mt_rand(0, 99999);
        $number = sprintf('%05d', $number);
        $chars =  $pre_store . $date . $number . '1';
        return $chars;
    }

   
    public function getOrderByNo($order_no) {
        $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_number = '" . $order_no . "'");
        if ($order_query->num_rows) {
            $reward = 0;
            $order_id = $order_query->row['order_id'];
            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

            foreach ($order_product_query->rows as $product) {
                $reward += $product['reward'];
            }           

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            if ($order_query->row['affiliate_id']) {
                $affiliate_id = $order_query->row['affiliate_id'];
            } else {
                $affiliate_id = 0;
            }               

            $this->load->model('sale/affiliate');

            $affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

            if ($affiliate_info) {
                $affiliate_firstname = $affiliate_info['firstname'];
                $affiliate_lastname = $affiliate_info['lastname'];
            } else {
                $affiliate_firstname = '';
                $affiliate_lastname = '';               
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_filename = $language_info['filename'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_filename = '';
                $language_directory = '';
            }

            $amazonOrderId = '';

            if ($this->config->get('amazon_status') == 1) {
                $amazon_query = $this->db->query("
                    SELECT `amazon_order_id`
                    FROM `" . DB_PREFIX . "amazon_order`
                    WHERE `order_id` = " . (int)$order_query->row['order_id'] . "
                    LIMIT 1")->row;

                if (isset($amazon_query['amazon_order_id']) && !empty($amazon_query['amazon_order_id'])) {
                    $amazonOrderId = $amazon_query['amazon_order_id'];
                }
            }

            if ($this->config->get('amazonus_status') == 1) {
                $amazon_query = $this->db->query("
                        SELECT `amazonus_order_id`
                        FROM `" . DB_PREFIX . "amazonus_order`
                        WHERE `order_id` = " . (int)$order_query->row['order_id'] . "
                        LIMIT 1")->row;

                if (isset($amazon_query['amazonus_order_id']) && !empty($amazon_query['amazonus_order_id'])) {
                    $amazonOrderId = $amazon_query['amazonus_order_id'];
                }
            }
            $invoice_info =$this->getOrderInvoice($order_id);
            return array(
                'amazon_order_id'         => $amazonOrderId,
                'order_id'                => $order_query->row['order_id'],
                'order_number'                => $order_query->row['order_number'],
                'invoice_id'              => isset($invoice_info['invoice_id'])?$invoice_info['invoice_id']:'',
                'invoice_no'              => isset($invoice_info['invoice_no'])?$invoice_info['invoice_no']:'',
                'store_id'                => $order_query->row['store_id'],
                'store_name'              => $order_query->row['store_name'],
                'store_url'               => $order_query->row['store_url'],
                'customer_id'             => $order_query->row['customer_id'],
                'customer'                => $order_query->row['customer'],
                'customer_group_id'       => $order_query->row['customer_group_id'],
                'firstname'               => $order_query->row['firstname'],
                'lastname'                => $order_query->row['lastname'],
                'telephone'               => $order_query->row['telephone'],
                'fax'                     => $order_query->row['fax'],
                'email'                   => $order_query->row['email'],
                'email_send'                   => $order_query->row['email_send'],
                'payment_firstname'       => $order_query->row['payment_firstname'],
                'payment_lastname'        => $order_query->row['payment_lastname'],
                'payment_company'         => $order_query->row['payment_company'],
                'payment_company_id'      => $order_query->row['payment_company_id'],
                'payment_tax_id'          => $order_query->row['payment_tax_id'],
                'payment_address_1'       => $order_query->row['payment_address_1'],
                'payment_address_2'       => $order_query->row['payment_address_2'],
                'payment_postcode'        => $order_query->row['payment_postcode'],
                'payment_city'            => $order_query->row['payment_city'],
                'payment_zone_id'         => $order_query->row['payment_zone_id'],
                'payment_zone'            => $order_query->row['payment_zone'],
                'payment_zone_code'       => $payment_zone_code,
                'payment_country_id'      => $order_query->row['payment_country_id'],
                'payment_country'         => $order_query->row['payment_country'],
                'payment_iso_code_2'      => $payment_iso_code_2,
                'payment_iso_code_3'      => $payment_iso_code_3,
                'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_method'          => $order_query->row['payment_method'],
                'payment_code'            => $order_query->row['payment_code'],             
                'shipping_firstname'      => $order_query->row['shipping_firstname'],
                'shipping_lastname'       => $order_query->row['shipping_lastname'],
                'shipping_company'        => $order_query->row['shipping_company'],
                'shipping_address_1'      => $order_query->row['shipping_address_1'],
                'shipping_address_2'      => $order_query->row['shipping_address_2'],
                'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
                'shipping_zone'           => $order_query->row['shipping_zone'],
                'shipping_zone_code'      => $shipping_zone_code,
                'shipping_country_id'     => $order_query->row['shipping_country_id'],
                'shipping_country'        => $order_query->row['shipping_country'],
                'shipping_iso_code_2'     => $shipping_iso_code_2,
                'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method'         => $order_query->row['shipping_method'],
                'shipping_code'           => $order_query->row['shipping_code'],
                'shipping_phone'           => $order_query->row['shipping_phone'],
                
                'comment'                 => $order_query->row['comment'],
                'total'                   => $order_query->row['total'],
                'reward'                  => $reward,
                'order_status_id'         => $order_query->row['order_status_id'],
                'affiliate_id'            => $order_query->row['affiliate_id'],
                'affiliate_firstname'     => $affiliate_firstname,
                'affiliate_lastname'      => $affiliate_lastname,
                'commission'              => $order_query->row['commission'],
                'language_id'             => $order_query->row['language_id'],
                'language_code'           => $language_code,
                'language_filename'       => $language_filename,
                'language_directory'      => $language_directory,               
                'currency_id'             => $order_query->row['currency_id'],
                'currency_code'           => $order_query->row['currency_code'],
                'currency_value'          => $order_query->row['currency_value'],
                'ip'                      => $order_query->row['ip'],
                'forwarded_ip'            => $order_query->row['forwarded_ip'], 
                'user_agent'              => $order_query->row['user_agent'],   
                'accept_language'         => $order_query->row['accept_language'],                  
                'date_added'              => $order_query->row['date_added'],
                'date_modified'           => $order_query->row['date_modified'],
                'grand_total'             => $order_query->row['grand_total'],
                'parent_id'               => $order_query->row['parent_id'],
                'is_parent'               => $order_query->row['is_parent'],
            );
        } else {
            return false;
        }
    }
    
   public function updateOrderStatus($order_id,$order_status_id){
       $sql = "update " . DB_PREFIX . "order set order_status_id = '{$order_status_id}' where order_id = '{$order_id}'" ;
       $this->db->query($sql);
   }


   public function load_language($store_id){
         switch($store_id){
            case '0':
                $lang_directory ='english';
                break;
            case '52':
                $lang_directory ='de';
                break;
            case '53':
                $lang_directory ='es';
                break;
            case '54':
                $lang_directory ='fr';
                break;
            case '55':
                $lang_directory ='it';
                break;
            case '56':
                $lang_directory ='pt';
                break;
            case '57':
                $lang_directory ='english';
                break;
            default:
                $lang_directory ='fr';
                break;
        }
        $language = new Language($lang_directory);
        $language->load($lang_directory);
        return $language;
   }

   public function joinOrder($data){
        $query_customer =$this->db->query("select customer_id,email,firstname,lastname,customer_group_id from ".DB_PREFIX."customer where customer_id ='".intval($data['customer_id'])."' ");
        $customer_info =$query_customer->row;
        if(isset($data['points'])){
            $data['points'] =abs(intval($data['points']));
            $query_order =$this->db->query("select order_id,order_status_id from ".DB_PREFIX."order where order_number ='".$this->db->escape($data['order_number'])."' ");
            $order_id= $query_order->row['order_id'];
            //更新用户积分，判断用户等级
            if($query_order->row['order_status_id']==5){
                $status =1;
            }else{
                $status=0;
            }
            $query_exixt =$this->db->query("select customer_reward_id from ".DB_PREFIX."customer_reward where order_id=".$order_id);
            if($query_exixt->num_rows){
                $this->db->query("update ".DB_PREFIX."customer_reward set customer_id=".$customer_info['customer_id'].",points=".$data['points'].",status=".$status.",date_confirm=NOW() where order_id=".$order_id);
            }
            else{
                $this->db->query("insert into ".DB_PREFIX."customer_reward set customer_id=".$customer_info['customer_id'].",order_id=".$order_id.",points=".$data['points'].",points_spent=0,status=".$status.",date_added=NOW(),date_confirm=NOW() ");
            }
            
            $this->load->model('sale/customer');
            $total_point =$this->model_sale_customer->getRewardTotal($customer_info['customer_id']);
            $customer_group_id =$this->model_sale_customer->getCustomerGroupInfoByPoints($total_point);
            if($customer_info['customer_group_id']!=$customer_group_id){
                $customer_info['customer_group_id']=$customer_group_id;
                $this->db->query("UPDATE ".DB_PREFIX."customer set customer_group_id=".$customer_group_id." where email='".$this->db->escape($data['email'])."' ");
            }

        }
        $this->db->query("UPDATE ".DB_PREFIX."order set customer_id=".$customer_info['customer_id'].",customer_group_id=".$customer_info['customer_group_id'].",firstname='".$customer_info['firstname']."',lastname='".$customer_info['lastname']."',email='".$customer_info['email']."' where order_number='".$this->db->escape($data['order_number'])."' ");
 
   }
}
?>