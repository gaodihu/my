<?php

class ModelTotalCoupon extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
        if (isset($this->session->data['coupon'])) {
            $this->language->load('total/coupon');

            $this->load->model('checkout/coupon');
            $coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
            $coupon_discount_product = array();

            $_discount_product_number = array();
            if ($coupon_info) {
                $discount_total = 0;
                //只对符合条件的商品进行折扣
                if ((in_array('condition_total', explode(',', $coupon_info['combine_condition_value'])) || in_array('condition_total_qty', explode(',', $coupon_info['combine_condition_value']))) && $coupon_info['product']) {
                    $sub_total = 0;

                    foreach ($this->cart->getProducts() as $product) {
                        if (in_array($product['product_id'], $coupon_info['product'])) {
                            $sub_total += $product['total'];
                            $coupon_discount_product[] = $product;
                        }
                    }
                } else {
                    //对整个购物车打折
                    $sub_total = $this->cart->getSubTotal();
                    $coupon_discount_product = $this->cart->getProducts();
                }

                if ($coupon_info['type'] == 'F') {
                    $coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
                }
                //有效的总额
                $good_sub_total = 0;
                foreach ($coupon_discount_product as $product) {
                    $discount = 0;
                    //特价商品不能和coupon同时使用
                    if (!in_array($product['product_id'], $coupon_info['special_product'])) {
                        $good_sub_total+=$product['total'];
                    }
                    if ($coupon_info['type'] == 'P') {
                        $discount = $product['total'] / 100 * $coupon_info['discount'];
                    } elseif ($coupon_info['type'] == 'PF') {
                        if (in_array($product['product_id'], $coupon_info['product'])) {
                            $qty = $product['quantity'];
                            $discount = $qty * $coupon_info['discount'];
                        }
                    } elseif ($coupon_info['type'] == 'BGP') {
                        $buy_x = (int) $coupon_info['buy_x'];
                        $get_x = (int) $coupon_info['discount'];
                        if ($coupon_info['product']) {
                            if (in_array($product['product_id'], $coupon_info['product'])) {
                                $qty = $product['quantity'];
                                $discount = floor($qty / ($buy_x + $get_x)) * ($get_x * $product['price']);
                                $_discount_product_number[$product['model']] = floor($qty / ($buy_x + $get_x));
                            }
                        } else {
                            $qty = $product['quantity'];
                            $discount = floor($qty / ($buy_x + $get_x)) * ($get_x * $product['price']);
                            $_discount_product_number[$product['model']] = floor($qty / ($buy_x + $get_x));
                        }
                        
                    } elseif ($coupon_info['type'] == 'BGF') {
                        $buy_x = (int) $coupon_info['buy_x'];
                        $get_x = (int) $coupon_info['discount'];
                        if (in_array($product['product_id'], $coupon_info['product'])) {
                            $qty = $product['quantity'];
                            if ($qty >= ($buy_x + $get_x)) {
                                $discount = $get_x * $product['price'];
                                $_discount_product_number[$product['model']] = $get_x ;
                            }
                        }
                        
                    }elseif ($coupon_info['type'] == 'BGD') {
                        $buy_x = (int) $coupon_info['buy_x'];
                        $get_x =$coupon_info['discount']/100;
                        if ($coupon_info['product']) {
                            if (in_array($product['product_id'], $coupon_info['product'])) {
                                $qty = $product['quantity'];
                                $discount = floor($qty / ($buy_x + 1)) * ($get_x * $product['price']);
                                $_discount_product_number[$product['model']] = floor($qty / ($buy_x + 1));
                            }
                        } else {
                            $qty = $product['quantity'];
                            $discount = floor($qty / ($buy_x + 1)) * ($get_x * $product['price']);
                            $_discount_product_number[$product['model']] = floor($qty / ($buy_x + 1));
                        }
                        
                    }
                    if (in_array($product['product_id'], $coupon_info['special_product'])) {
                        $discount = 0;
                    }

                    $discount_total += $discount;
                }
                //固定减免金额
                if ($coupon_info['type'] == 'F') {
                    if ($good_sub_total >= $coupon_info['total']) {
                        $discount_total = $coupon_info['discount'];
                    } else {
                        $discount_total = $coupon_info['discount'] * ($good_sub_total / $coupon_info['total']);
                    }
                }
                if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
                    if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
                        $tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

                        foreach ($tax_rates as $tax_rate) {
                            if ($tax_rate['type'] == 'P') {
                                $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                            }
                        }
                    }

                    $discount_total += $this->session->data['shipping_method']['cost'];
                }
                if ($discount_total > 0) {
                    $total_data[] = array(
                        'code' => 'coupon',
                        'title' => sprintf($this->language->get('text_coupon'), $coupon_info['front_name']),
                        'text' => '-' . $this->currency->format($discount_total),
                        'value' => -$discount_total,
                        'sort_order' => $this->config->get('coupon_sort_order')
                    );

                    $total -= $discount_total;
                }

                //取得参加活动的产品
                $_all_can_discount_product_id_arr = array();
                foreach ($coupon_discount_product as $_product) {
                    if (!in_array($_product['product_id'], $coupon_info['special_product'])) {
                        $_all_can_discount_product_id_arr[] = $_product['product_id'];
                    }
                }
                if (isset($this->session->data['shipping_packages'])) {
          
                    $shipping_packages = $this->session->data['shipping_packages'];
                    $_total_use_discount = 0;
                    $_i = 1;
                    $_package_get_x_number = array();

                    foreach ($shipping_packages as $_pk => $_package) {
                                
                        $_products = $_package['package'];
                        
                        //固定金额，按照订单金额平均分配
                        if ($coupon_info['type'] == 'F' || $coupon_info['type'] == 'P' || $coupon_info['type'] == 'PF') {
                            $_product_total = 0;
                            foreach ($_products as $_product) {
                                if (in_array($_product['product_id'], $_all_can_discount_product_id_arr)) {
                                    $_product_total += $_product['price'] * $_product['quantity'];
                                }
                            }
                            $_product_discount_total = 0;
                            if ($_i >= count($shipping_packages)) {
                                $_product_discount_total = $discount_total - $_total_use_discount;
                            } else {
                                $_percentage = round($_product_total / $good_sub_total, 2);
                                $_product_discount_total = round($discount_total * $_percentage, 2);
                                $_total_use_discount += $_product_discount_total;
                            }


                            if ($_product_discount_total != 0) {
                                $_total_data = array(
                                    'code' => 'coupon',
                                    'title' => sprintf($this->language->get('text_coupon'), $coupon_info['front_name']),
                                    'text' => '-' . $this->currency->format($_product_discount_total),
                                    'value' => -$_product_discount_total,
                                    'sort_order' => $this->config->get('coupon_sort_order')
                                );

                                $this->session->data['package_total'][$_pk] -= $_product_discount_total;
                                $this->session->data['package_total_data'][$_pk][] = $_total_data;
                            }
                        }
                        
                        //第一轮分配，只分配满足全部条件的，第二轮对剩下的直接扣除
                        if ($coupon_info['type'] == 'BGP') {
                            $buy_x = (int) $coupon_info['buy_x'];
                            $get_x = (int) $coupon_info['discount'];
                            foreach ($_products as $_product) {
                                if(isset($_discount_product_number[$_product['model']]) && $_discount_product_number[$_product['model']]) {
                                    $_left_number = $_discount_product_number[$_product['model']];
                                    if ($_left_number) {
                                        $_hava = floor($_product['quantity'] / ($buy_x + $get_x));
                                        $_left_number = $_left_number - $_hava;
                                        $_discount_product_number[$_product['model']] = $_left_number;
                                        $_package_get_x_number[$_pk][$_product['model']] = $_hava;
                                    }
                                }else{
                                }
                            }
                        }
                        if ($coupon_info['type'] == 'BGF') {
                            $buy_x = (int) $coupon_info['buy_x'];
                            $get_x = (int) $coupon_info['discount'];
                            foreach ($_products as $_product) {
                                if(isset($_discount_product_number[$_product['model']]) && $_discount_product_number[$_product['model']]) {
                                    $_left_number = $_discount_product_number[$_product['model']];
                                    if ($_left_number) {
                                        if ($_product['quantity'] >= ($buy_x + $get_x)) {
                                            $_hava = $get_x;
                                            $_left_number = 0;
                                            $_discount_product_number[$_product['model']] = $_left_number;
                                            $_package_get_x_number[$_pk][$_product['model']] = $_hava;
                                        }
                                    }
                                }
                            }
                        }
                        
                        if ($coupon_info['type'] == 'BGD') {
                            $buy_x = (int) $coupon_info['buy_x'];
                            $get_x = (int) $coupon_info['discount'];
                            foreach ($_products as $_product) {
                                if(isset($_discount_product_number[$_product['model']]) && $_discount_product_number[$_product['model']]) {
                                    $_left_number = $_discount_product_number[$_product['model']];
                                    if ($_left_number) {
                                        $_hava = floor($_product['quantity'] / ($buy_x + 1));
                                        $_left_number = $_left_number - $_hava;
                                        $_discount_product_number[$_product['model']] = $_left_number;
                                        $_package_get_x_number[$_pk][$_product['model']] = $_hava;
                                    }
                                }else{
                                }
                            }
                        }
                        
                        
                        $_i ++;
                    }

                     //第一轮分配，只分配满足全部条件的，第二轮对剩下的直接扣除
                    if ($coupon_info['type'] == 'BGP' || $coupon_info['type'] == 'BGF' || $coupon_info['type'] == 'BGD') {
                        foreach ($shipping_packages as $_pk => $_package) {
                            $_products = $_package['package'];
                            $buy_x = (int) $coupon_info['buy_x'];
                            $get_x = (int) $coupon_info['discount'];
                            $_product_discount_total = 0;
                            foreach ($_products as $_product) {
                                $_left_number = $_discount_product_number[$_product['model']];
                                if (isset($_discount_product_number[$_product['model']])) {
                                    if (isset($_package_get_x_number[$_pk][$_product['model']]) && $_package_get_x_number[$_pk][$_product['model']]) {
                                        
                                    } else {
                                        if ($_left_number > $_product['quantity']) {
                                            $_package_get_x_number[$_pk][$_product['model']] = $_product['quantity'];
                                            $_left_number = $_left_number - $_product['quantity'];
                                            $_discount_product_number[$_product['model']] = $_left_number;
                                        } else {
                                            $_package_get_x_number[$_pk][$_product['model']] = $_left_number;
                                            $_left_number = 0;
                                            $_discount_product_number[$_product['model']] = $_left_number;
                                        }
                                    }
                                    if($coupon_info['type'] == 'BGD'){
                                        $_product_discount_total += round($_package_get_x_number[$_pk][$_product['model']] * $_product['price'] * $coupon_info['discount'] / 100,2);
                                    }else{
                                        $_product_discount_total += $_package_get_x_number[$_pk][$_product['model']] * $_product['price'] ;
                                    }
                                }
                               
                            }

                            if ($_product_discount_total != 0) {
                                $_total_data = array(
                                    'code' => 'coupon',
                                    'title' => sprintf($this->language->get('text_coupon'), $coupon_info['front_name']),
                                    'text' => '-' . $this->currency->format($_product_discount_total),
                                    'value' => -$_product_discount_total,
                                    'sort_order' => $this->config->get('coupon_sort_order')
                                );

                                $this->session->data['package_total'][$_pk] -= $_product_discount_total;
                                $this->session->data['package_total_data'][$_pk][] = $_total_data;
                            }
                        }
                    }
                }
            }
        }
      
    }

    public function confirm($order_info, $order_total) {
        $code = '';

        $start = strpos($order_total['title'], '(') + 1;
        $end = strrpos($order_total['title'], ')');

        if ($start && $end) {
            $code = substr($order_total['title'], $start, $end - $start);
        }

        $this->load->model('checkout/coupon');

        $coupon_info = $this->model_checkout_coupon->getCoupon($code);

        if ($coupon_info) {
            $this->model_checkout_coupon->redeem($coupon_info['coupon_id'], $order_info['order_id'], $order_info['customer_id'], $order_total['value']);
        }
    }

}

?>