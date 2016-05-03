<?php

class ModelCheckoutOrder extends Model {

    public function addOrder($data,$is_suborder = 0) {
        $coupon_code = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';
        $user_points = isset($this->session->data['points']) ? $this->session->data['points'] : 0;
        $order_number = $this->SetOrderNumber(11);
        $ii = 0;
        $flag = 0;
        while ($ii < 10) {
            $sql = "SELECT COUNT(*) AS cnt FROM `" . DB_PREFIX . "order` where order_number = '{$order_number}'";
            $rs = $this->db->query($sql);
            $cnt = $rs->row['cnt'];
            if ($cnt > 0) {
                $order_number = $this->SetOrderNumber(11);
            } else {
                $flag = 1;
                break;
            }
            $ii ++;
        }
        if (!$flag) {
            return false;
        }
        //积分计算
        /*
        $get_point = 0;
        foreach ($data['products'] as $product) {
            //特价商品不赠送积分
            if ($product['special_price']) {
                //$get_point -=floor($product['total']);
            }else{
                $get_point += $product['total'];
            }
        }
        $get_point = floor($get_point);
        */
        $get_point = floor($data['base_subtotal']);
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET order_number='" . $order_number
            . "', invoice_prefix = '" . $this->db->escape($data['invoice_prefix'])
            . "', store_id = '" . (int) $data['store_id']
            . "', store_name = '" . $this->db->escape($data['store_name'])
            . "', store_url = '" . $this->db->escape($data['store_url'])
            . "', coupon_code ='" . $coupon_code
            . "',customer_id = '" . (int) $data['customer_id']
            . "', customer_group_id = '" . (int) $data['customer_group_id']
            . "', firstname = '" . $this->db->escape($data['firstname'])
            . "', lastname = '" . $this->db->escape($data['lastname'])
            . "', email = '" . $this->db->escape($data['email'])
            . "', telephone = '" . $this->db->escape($data['telephone'])
            . "', fax = '" . $this->db->escape($data['fax'])
            . "', payment_firstname = '" . $this->db->escape($data['payment_firstname'])
            . "', payment_lastname = '" . $this->db->escape($data['payment_lastname'])
            . "', payment_phone = '" . $this->db->escape($data['payment_phone'])
            . "', payment_company = '" . $this->db->escape($data['payment_company'])
            . "', payment_company_id = '" . $this->db->escape($data['payment_company_id'])
            . "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id'])
            . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1'])
            . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2'])
            . "', payment_city = '" . $this->db->escape($data['payment_city'])
            . "', payment_postcode = '" . $this->db->escape($data['payment_postcode'])
            . "', payment_country = '" . $this->db->escape($data['payment_country'])
            . "', payment_country_id = '" . (int) $data['payment_country_id']
            . "', payment_country_code = '" . $data['payment_country_code']
            . "', payment_zone = '" . $this->db->escape($data['payment_zone'])
            . "', payment_zone_id = '" . (int) $data['payment_zone_id']
            . "', payment_zone_code = '" . $data['payment_zone_code']
            . "', payment_address_format = '" . $this->db->escape($data['payment_address_format'])
            . "', payment_method = '" . $this->db->escape($data['payment_method'])
            . "', payment_code = '" . $this->db->escape($data['payment_code'])
            . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname'])
            . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname'])
            . "', shipping_phone = '" . $this->db->escape($data['shipping_phone'])
            . "', shipping_company = '" . $this->db->escape($data['shipping_company'])
            . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1'])
            . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2'])
            . "', shipping_city = '" . $this->db->escape($data['shipping_city'])
            . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode'])
            . "', shipping_country = '" . $this->db->escape($data['shipping_country'])
            . "', shipping_country_id = '" . (int) $data['shipping_country_id']
            . "', shipping_country_code = '" . $data['shipping_country_code']
            . "', shipping_zone = '" . $this->db->escape($data['shipping_zone'])
            . "', shipping_zone_id = '" . (int) $data['shipping_zone_id']
            . "', shipping_zone_code = '" . $data['shipping_zone_code']
            . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format'])
            . "', shipping_method = '" . $this->db->escape($data['shipping_method'])
            . "', shipping_code = '" . $this->db->escape($data['shipping_code'])
            . "', comment = '" . $this->db->escape($data['comment'])
            . "', total = '" . (float) $data['total']
            . "',  affiliate_id = '" . (int) $data['affiliate_id']
            . "', commission = '" . (float) $data['commission']
            . "', language_id = '" . (int) $data['language_id']
            . "', currency_id = '" . (int) $data['currency_id']
            . "', currency_code = '" . $this->db->escape($data['currency_code'])
            . "', currency_value = '" . (float) $data['currency_value']
            . "', ip = '" . $this->db->escape($data['ip'])
            . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip'])
            . "', user_agent = '" . $this->db->escape($data['user_agent'])
            . "', accept_language = '" . $this->db->escape($data['accept_language'])
            
             . "', base_discount_amount = '" . $data['base_discount_amount']
             . "', base_shipping_amount = '" . $data['base_shipping_amount']
             . "', base_subtotal = '" . $data['base_subtotal']
             . "', base_grand_total = '" . $data['base_grand_total']
             . "', discount_amount = '" . $data['discount_amount']
             . "', shipping_amount = '" . $data['shipping_amount']
             . "', subtotal = '" . $data['subtotal']
             . "', grand_total = '" . $data['grand_total']
             . "', points = '" . $get_point
             . "', order_tax_id = '" . $data['order_tax_id']
             . "', is_remote = '" . $data['is_remote']
             . "', parent_id = '" . $data['parent_id']
             . "', is_parent = '" . $data['is_parent']
            . "', date_added = NOW(), date_modified = NOW(),order_status_id = 1");

        $order_id = $this->db->getLastId();

                
        
        foreach ($data['products'] as $product) {
            $base_price = round($product['price'], 2);
            $default_currency = $this->currency->getWebDefaultCurrency();
            $currency_price = $this->currency->convert($product['price'], $default_currency, $data['currency_code']);
            $currency_total = round($currency_price * $product['quantity'], 2);
            $base_total = $this->currency->convert($currency_total, $data['currency_code'], $default_currency);

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "',original_price='" . $product['original_price'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "',base_price='{$base_price}',base_total='{$base_total}',currency_price='{$currency_price}',currency_total='{$currency_total}'");

            if ($is_suborder) {

            //扣减库存
            $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_id = " . (int)$product['product_id']);
            }
            
            $order_product_id = $this->db->getLastId();

            foreach ($product['option'] as $option) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $option['product_option_id'] . "', product_option_value_id = '" . (int) $option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
            }

            foreach ($product['download'] as $download) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int) ($download['remaining'] * $product['quantity']) . "'");
            }
        }
       
        //添加积分记录
        //$this->language->load('checkout/order');
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $data['customer_id'] . "',order_id=" . $order_id . " ,description = '', points = '" . $get_point . "',points_spent='" . $user_points . "',status=0, date_added = NOW()");


        foreach ($data['vouchers'] as $voucher) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int) $voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float) $voucher['amount'] . "'");
        }

        foreach ($data['totals'] as $total) {
            if($total['code']=='coupon'){
                $coupon_amount =abs($total['value']);
            }
            $default_currency = $this->currency->getWebDefaultCurrency();
            $currency_value = $this->currency->convert($total['value'],$default_currency,$data['currency_code']);
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "',currency_value='{$currency_value}'");
        }

        //添加coupon 使用记录
        //$this->db->query("UPDATE " . DB_PREFIX ."customer_coupon SET order_id=".(int)$order_id.",use_time=NOW() WHERE customer_id=".(int)$data['customer_id']." AND coupon_id=".$coupon_id;
        if ($coupon_code) {
            $query_coupon = $this->db->query("select coupon_id from " . DB_PREFIX . "coupon where code='" . $coupon_code . "'");
            $coupon_id = $query_coupon->row['coupon_id'];
            $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_history SET customer_id = '" . (int) $data['customer_id'] . "',order_id=" . $order_id . " ,coupon_id = '" . $coupon_id . "',amount='".$coupon_amount."',status='0',	date_added=NOW(),date_confirm=NOW()");
        }
        

        
        return $order_id;
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
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

            return array(
                'order_id' => $order_query->row['order_id'],
                'order_number' => $order_query->row['order_number'],
                'coupon_code' => $order_query->row['coupon_code'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'payment_firstname' => $order_query->row['payment_firstname'],
                'payment_lastname' => $order_query->row['payment_lastname'],
                'payment_company' => $order_query->row['payment_company'],
                'payment_company_id' => $order_query->row['payment_company_id'],
                'payment_tax_id' => $order_query->row['payment_tax_id'],
                'payment_address_1' => $order_query->row['payment_address_1'],
                'payment_address_2' => $order_query->row['payment_address_2'],
                'payment_postcode' => $order_query->row['payment_postcode'],
                'payment_city' => $order_query->row['payment_city'],
                'payment_zone_id' => $order_query->row['payment_zone_id'],
                'payment_zone' => $order_query->row['payment_zone'],
                'payment_zone_code' => $payment_zone_code,
                'payment_country_id' => $order_query->row['payment_country_id'],
                'payment_country' => $order_query->row['payment_country'],
                'payment_iso_code_2' => $payment_iso_code_2,
                'payment_iso_code_3' => $payment_iso_code_3,
                'payment_address_format' => $order_query->row['payment_address_format'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'payment_phone' => $order_query->row['payment_phone'],
                'shipping_firstname' => $order_query->row['shipping_firstname'],
                'shipping_lastname' => $order_query->row['shipping_lastname'],
                'shipping_company' => $order_query->row['shipping_company'],
                'shipping_address_1' => $order_query->row['shipping_address_1'],
                'shipping_address_2' => $order_query->row['shipping_address_2'],
                'shipping_postcode' => $order_query->row['shipping_postcode'],
                'shipping_city' => $order_query->row['shipping_city'],
                'shipping_zone_id' => $order_query->row['shipping_zone_id'],
                'shipping_zone' => $order_query->row['shipping_zone'],
                'shipping_zone_code' => $shipping_zone_code,
                'shipping_country_id' => $order_query->row['shipping_country_id'],
                'shipping_country' => $order_query->row['shipping_country'],
                'shipping_iso_code_2' => $shipping_iso_code_2,
                'shipping_iso_code_3' => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_code' => $order_query->row['shipping_code'],
                'shipping_phone' => $order_query->row['shipping_phone'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_tax_id' => $order_query->row['order_tax_id'],
                'order_status' => $order_query->row['order_status'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_filename' => $language_filename,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'base_discount_amount' => $order_query->row['base_discount_amount'],
                'base_shipping_amount' => $order_query->row['base_shipping_amount'],
                'base_subtotal' => $order_query->row['base_subtotal'],
                'base_grand_total' => $order_query->row['base_grand_total'],
                'discount_amount' => $order_query->row['discount_amount'],
                'shipping_amount' => $order_query->row['shipping_amount'],
                'subtotal' => $order_query->row['subtotal'],
                'grand_total' => $order_query->row['grand_total'],
                'parent_id'   => $order_query->row['parent_id'],
                'is_parent'   => $order_query->row['is_parent'],
            );
        } else {
            return false;
        }
    }

    public function confirm($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_info = $this->getOrder($order_id);

        if ($order_info && !$order_info['order_status_id']) {
            // Fraud Detection
            if ($this->config->get('config_fraud_detection')) {
                $this->load->model('checkout/fraud');

                $risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

                if ($risk_score > $this->config->get('config_fraud_score')) {
                    $order_status_id = $this->config->get('config_fraud_status_id');
                }
            }

            // Ban IP
            $status = false;

            $this->load->model('account/customer');

            if ($order_info['customer_id']) {
                $results = $this->model_account_customer->getIps($order_info['customer_id']);

                foreach ($results as $result) {
                    if ($this->model_account_customer->isBanIp($result['ip'])) {
                        $status = true;

                        break;
                    }
                }
            } else {
                $status = $this->model_account_customer->isBanIp($order_info['ip']);
            }

            if ($status) {
                $order_status_id = $this->config->get('config_order_status_id');
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '1', comment = '" . $this->db->escape(($comment && $notify) ? $comment : '') . "', date_added = NOW()");

            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

            foreach ($order_product_query->rows as $order_product) {
                //库存数量减少
                //$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_id = " . (int) $order_product['product_id']);

                //$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product['order_product_id'] . "'");

                //foreach ($order_option_query->rows as $option) {
                //    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $order_product['quantity'] . ") WHERE product_option_value_id = '" . (int) $option['product_option_value_id'] . "' AND subtract = '1'");
                //}
            }
            if (!isset($passArray) || empty($passArray)) {
                $passArray = null;
            }
            $this->openbay->orderNew((int) $order_id);

            $this->cache->delete('product');

            // Downloads
            $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "'");

            // Gift Voucher
            $this->load->model('checkout/voucher');

            $order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

            foreach ($order_voucher_query->rows as $order_voucher) {
                $voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $order_voucher);

                $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int) $voucher_id . "' WHERE order_voucher_id = '" . (int) $order_voucher['order_voucher_id'] . "'");
            }

            // Send out any gift voucher mails
            if ($this->config->get('config_complete_status_id') == $order_status_id) {
                $this->model_checkout_voucher->confirm($order_id);
            }

            // Order Totals			
            $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");

            foreach ($order_total_query->rows as $order_total) {
                $this->load->model('total/' . $order_total['code']);

                if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                    $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                }
            }

            // Send out order confirmation mail
            $language = new Language($order_info['language_directory']);
            $language->load($order_info['language_filename']);
            $language->load('mail/order');

            $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_info['language_id'] . "'");

            if ($order_status_query->num_rows) {
                $order_status = $order_status_query->row['name'];
            } else {
                $order_status = '';
            }

            $subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

            // HTML Mail
            $template = new Template();

            $template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

            $template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
            $template->data['text_link'] = $language->get('text_new_link');
            $template->data['text_download'] = $language->get('text_new_download');
            $template->data['text_order_detail'] = $language->get('text_new_order_detail');
            $template->data['text_instruction'] = $language->get('text_new_instruction');
            $template->data['text_order_id'] = $language->get('text_new_order_id');
            $template->data['text_date_added'] = $language->get('text_new_date_added');
            $template->data['text_payment_method'] = $language->get('text_new_payment_method');
            $template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
            $template->data['text_email'] = $language->get('text_new_email');
            $template->data['text_telephone'] = $language->get('text_new_telephone');
            $template->data['text_ip'] = $language->get('text_new_ip');
            $template->data['text_payment_address'] = $language->get('text_new_payment_address');
            $template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
            $template->data['text_product'] = $language->get('text_new_product');
            $template->data['text_model'] = $language->get('text_new_model');
            $template->data['text_quantity'] = $language->get('text_new_quantity');
            $template->data['text_price'] = $language->get('text_new_price');
            $template->data['text_total'] = $language->get('text_new_total');
            $template->data['text_footer'] = $language->get('text_new_footer');
            $template->data['text_powered'] = $language->get('text_new_powered');

            $template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
            $template->data['store_name'] = $order_info['store_name'];
            $template->data['store_url'] = $order_info['store_url'];
            $template->data['customer_id'] = $order_info['customer_id'];
            $template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

            if ($order_download_query->num_rows) {
                $template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
            } else {
                $template->data['download'] = '';
            }

            $template->data['order_id'] = $order_id;
            $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
            $template->data['payment_method'] = $order_info['payment_method'];
            $template->data['shipping_method'] = $order_info['shipping_method'];
            $template->data['email'] = $order_info['email'];
            $template->data['telephone'] = $order_info['telephone'];
            $template->data['ip'] = $order_info['ip'];

            if ($comment && $notify) {
                $template->data['comment'] = nl2br($comment);
            } else {
                $template->data['comment'] = '';
            }

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            // Products
            $template->data['products'] = array();

            foreach ($order_product_query->rows as $product) {
                $option_data = array();

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $template->data['products'][] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            // Vouchers
            $template->data['vouchers'] = array();

            foreach ($order_voucher_query->rows as $voucher) {
                $template->data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                );
            }

            $template->data['totals'] = $order_total_query->rows;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
                $html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
            } else {
                $html = $template->fetch('default/template/mail/order.tpl');
            }

            // Can not send confirmation emails for CBA orders as email is unknown
            $this->load->model('payment/amazon_checkout');
            if (!$this->model_payment_amazon_checkout->isAmazonOrder($order_info['order_id'])) {
                // Text Mail
                $text = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
                $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
                $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
                $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

                if ($comment && $notify) {
                    $text .= $language->get('text_new_instruction') . "\n\n";
                    $text .= $comment . "\n\n";
                }

                // Products
                $text .= $language->get('text_new_products') . "\n";

                foreach ($order_product_query->rows as $product) {
                    $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

                    $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

                    foreach ($order_option_query->rows as $option) {
                        $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
                    }
                }

                foreach ($order_voucher_query->rows as $voucher) {
                    $text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
                }

                $text .= "\n";

                $text .= $language->get('text_new_order_total') . "\n";

                foreach ($order_total_query->rows as $total) {
                    $text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
                }

                $text .= "\n";

                if ($order_info['customer_id']) {
                    $text .= $language->get('text_new_link') . "\n";
                    $text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
                }

                if ($order_download_query->num_rows) {
                    $text .= $language->get('text_new_download') . "\n";
                    $text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
                }

                // Comment
                if ($order_info['comment']) {
                    $text .= $language->get('text_new_comment') . "\n\n";
                    $text .= $order_info['comment'] . "\n\n";
                }

                $text .= $language->get('text_new_footer') . "\n\n";

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml($html);
                $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }


        }
    }

    public function update($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_info = $this->getOrder($order_id);

        if ($order_info && $order_info['order_status_id']) {
            // Fraud Detection
            if ($this->config->get('config_fraud_detection')) {
                $this->load->model('checkout/fraud');

                $risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

                if ($risk_score > $this->config->get('config_fraud_score')) {
                    $order_status_id = $this->config->get('config_fraud_status_id');
                }
            }

            // Ban IP
            $status = false;

            $this->load->model('account/customer');

            if ($order_info['customer_id']) {

                $results = $this->model_account_customer->getIps($order_info['customer_id']);

                foreach ($results as $result) {
                    if ($this->model_account_customer->isBanIp($result['ip'])) {
                        $status = true;

                        break;
                    }
                }
            } else {
                $status = $this->model_account_customer->isBanIp($order_info['ip']);
            }

            if ($status) {
                $order_status_id = $this->config->get('config_order_status_id');
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
            if ($order_status_id == 5) {
               
                //确认积分使用
                $this->db->query("UPDATE " . DB_PREFIX . "customer_reward SET status=1, date_confirm = NOW() where customer_id =" . $order_info['customer_id'] . " and order_id=" . $order_id);

                //确认coupon 使用记录
                $this->db->query("UPDATE " . DB_PREFIX . "coupon_history SET status=1,date_confirm=NOW() WHERE customer_id=" . (int) $data['customer_id'] . " AND order_id=" . $order_id);
            }
            // Send out any gift voucher mails
            if ($this->config->get('config_complete_status_id') == $order_status_id) {
                $this->load->model('checkout/voucher');

                $this->model_checkout_voucher->confirm($order_id);
            }

        }
    }

    public function getColume($order_id, $colume) {
        $query = $this->db->query("SELECT " . $colume . "  from " . DB_PREFIX . "order where order_id=" . $order_id);
        return $query->row[$colume];
    }

    //订单号生成规则
    public function SetOrderNumber() {
        //require_once(DIR_SYSTEM . 'helper/fun.inc.php');
        /*
        11 - 英文站     0
        15 - 德语站     52
        16 - 法语站     54
        17 - 意大利语站  55
        18 - 西语种     53
        19 - 葡语站     56
         * *
         */
        $pre_store = '';
        $store_id = $this->config->get('config_store_id');
        $store_id = intval($store_id);
        $lang_id = $this->config->get('config_language_id');
        $lang_id = intval($lang_id);
        if(strpos($_SERVER['HTTP_HOST'],'m.') === 0){
            switch($lang_id){
                case 1  : $pre_store = '11';break;
                case 4 : $pre_store = '15';break;
                case 6 : $pre_store = '18';break;
                case 5 : $pre_store = '16';break;
                case 7 : $pre_store = '17';break;
                case 8 : $pre_store = '19';break;
                default :
                    $pre_store  = '11';
            }
        }else{
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
        }
        $date = date('ymd');
        $number = mt_rand(0, 99999);
        $number = sprintf('%05d', $number);
        $chars =  $date . $pre_store . $number . '0';
        return $chars;
    }

    public function getOrderByNumber($order_number) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_number = '" . $order_number . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
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

            return array(
                'order_id' => $order_query->row['order_id'],
                'order_number' => $order_query->row['order_number'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'payment_firstname' => $order_query->row['payment_firstname'],
                'payment_lastname' => $order_query->row['payment_lastname'],
                'payment_company' => $order_query->row['payment_company'],
                'payment_company_id' => $order_query->row['payment_company_id'],
                'payment_tax_id' => $order_query->row['payment_tax_id'],
                'payment_address_1' => $order_query->row['payment_address_1'],
                'payment_address_2' => $order_query->row['payment_address_2'],
                'payment_postcode' => $order_query->row['payment_postcode'],
                'payment_city' => $order_query->row['payment_city'],
                'payment_zone_id' => $order_query->row['payment_zone_id'],
                'payment_zone' => $order_query->row['payment_zone'],
                'payment_zone_code' => $payment_zone_code,
                'payment_country_id' => $order_query->row['payment_country_id'],
                'payment_country' => $order_query->row['payment_country'],
                'payment_iso_code_2' => $payment_iso_code_2,
                'payment_iso_code_3' => $payment_iso_code_3,
                'payment_address_format' => $order_query->row['payment_address_format'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'shipping_firstname' => $order_query->row['shipping_firstname'],
                'shipping_lastname' => $order_query->row['shipping_lastname'],
                'shipping_company' => $order_query->row['shipping_company'],
                'shipping_address_1' => $order_query->row['shipping_address_1'],
                'shipping_address_2' => $order_query->row['shipping_address_2'],
                'shipping_postcode' => $order_query->row['shipping_postcode'],
                'shipping_city' => $order_query->row['shipping_city'],
                'shipping_zone_id' => $order_query->row['shipping_zone_id'],
                'shipping_zone' => $order_query->row['shipping_zone'],
                'shipping_zone_code' => $shipping_zone_code,
                'shipping_country_id' => $order_query->row['shipping_country_id'],
                'shipping_country' => $order_query->row['shipping_country'],
                'shipping_iso_code_2' => $shipping_iso_code_2,
                'shipping_iso_code_3' => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_code' => $order_query->row['shipping_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_tax_id' => $order_query->row['order_tax_id'],
                'order_status' => $order_query->row['order_status'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_filename' => $language_filename,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'base_discount_amount' => $order_query->row['base_discount_amount'],
                'base_shipping_amount' => $order_query->row['base_shipping_amount'],
                'base_subtotal' => $order_query->row['base_subtotal'],
                'base_grand_total' => $order_query->row['base_grand_total'],
                'discount_amount' => $order_query->row['discount_amount'],
                'shipping_amount' => $order_query->row['shipping_amount'],
                'subtotal' => $order_query->row['subtotal'],
                'grand_total' => $order_query->row['grand_total'],
                'parent_id'   => $order_query->row['parent_id'],
                'is_parent'   => $order_query->row['is_parent'],
            );
        } else {
            return false;
        }
    }

    public function savePaymentInfo($order_id, $payment_method, $pay_amount, $pay_authorized, $pay_trans_no, $trans_comment, $pay_currency, $pay_email, $pay_cart_no, $additional_data, $is_push) {
        if (is_array($additional_data)) {
            $additional_data = json_encode($additional_data);
        }
        $sql = "INSERT INTO " . DB_PREFIX . "order_payment(order_id,payment_method,pay_amount,pay_authorized,pay_trans_no,trans_comment,pay_currency,pay_email,pay_cart_no,additional_data,is_push) values ";
        $sql .= "('{$order_id}','{$payment_method}','{$pay_amount}','{$pay_authorized}','{$pay_trans_no}','{$trans_comment}','{$pay_currency}','{$pay_email}','{$pay_cart_no}','{$additional_data}','{$is_push}')";

        $this->db->query($sql);
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderTotal($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'");
        return $query->rows;
    }
    
    public function getPaypalDetail($order_id){
       $sql = "SELECT t.debug_data FROM " .DB_PREFIX. "paypal_order p," . DB_PREFIX . "paypal_order_transaction t where p.order_id = '{$order_id}' and  p.paypal_order_id = t.paypal_order_id";
       $query = $this->db->query($sql);
       $data = $query->row['debug_data'];
       if($data){
           return json_decode($data,true);
       }else{
           return false;
       }
   }
    //判断订单号是否正确
    /*
    * @order_number 订单号
    * @status  订单状态
    * @订单状态 =>processing 2,complete 5,Canceled,7,Payment Review 17,Pending Payment 19
    *
    */
   public function ComfimOrderNumber($order_number,$status=2){
        $query = $this->db->query("SELECT date_added FROM `" . DB_PREFIX . "order` WHERE  order_number = '" . $order_number . "' and order_status_id=".$status);
        if($query->num_rows){
            return $query->row['date_added'];
        }
        else{
            return false;
        }
   }

   public function haveProductForOrder($order_number,$product_id){
       $sql ="select op.order_product_id from ".DB_PREFIX."order_product as op left join oc_order as o on op.order_id = o.order_id where op.product_id=".(int)$product_id." and o.order_number='".$order_number."' and o.order_status_id in (2,5)";
        $query =$this->db->query($sql);
        if($query->num_rows){
            return true;
        }
        else{
            return false;
        }
   }
   
    public function paypal_onestep_auth_status($order_id){
        $order_id = intval($order_id);
        $sql = "update " . DB_PREFIX . "order set paypal_onestep_auth = 1 where order_id = '{$order_id}'";
        $this->db->query($sql);
    }
    
    
    public function getOrderChildren($parent_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.parent_id = '" . $parent_id . "'");
        if($order_query->num_rows){
            return $order_query->rows;
        }else{
            return false;
        }
       
    }

}

?>