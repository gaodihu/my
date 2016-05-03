<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($code) {
		$status = array();
        //基准条件
        $base_status = true;
		$coupon_query = $this->db->query("SELECT c.* ,cd .front_name FROM `" . DB_PREFIX . "coupon` as c left join ".DB_PREFIX."coupon_description as cd on c.coupon_id=cd.coupon_id  WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1' and cd.language_id='".(int)$this->config->get('config_language_id')."'");
		if ($coupon_query->num_rows) {
            //条件符合的情况
            $combine_condition =$coupon_query->row['combine_condition'];
            //选择的coupon筛选条件
            $combine_condition_value =$coupon_query->row['combine_condition_value'];
            //购物车总额条件
			if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
				$status['total'] = 0;
			}
            else{
                $status['total'] = 1;
            }
            //购物车总数量条件
            if ($coupon_query->row['total_qty'] > $this->cart->countProducts()) {
				$status['total_qty'] = 0;
			}
            else{
                $status['total_qty'] = 1;
            }
            $check =true;
            //折扣商品不能使用coupon(coupon只能用于普通商品)
            $nomal_product =array();
            foreach($this->cart->getProducts() as $cart_product){
                if(!$cart_product['special_price']&&!$cart_product['exclusive_price']){
                    $nomal_product[] =$cart_product['product_id'];
                }
                if ($coupon_query->row['row_item_qty'] > $cart_product['quantity']) {
                    $check =false;
			    }
            }
            if($nomal_product){
                $status['nomal_product'] = 1;
            }
            else{
                $status['nomal_product'] = 0;
            }
           //购物车单个商品数量的条件
            if($check){
                $status['row_item_qty'] = 1;
            }
            else{
                $status['row_item_qty'] = 0;
            }
            
            //判断coupon是否使用，或者是否超过使用次数
			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
				//$status = false;
                $base_status = false;
			}

            //是否需要登录才能使用
			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				//$status = false;
                $status['logged'] = 0;
			}
            else{
                 $status['logged'] = 1;
            }
            
            //是否超出每个客户的使用次数
			if ($this->customer->getId()) {
				$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$base_status = false;
				}
			}
			// Products
			$coupon_product_data = array();

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}
			// Categories
			$coupon_category_data = array();
            $coupon_category_product_data =array(); 
            
            //得到符合分类及其子分类的商品
			$coupon_category_query = $this->db->query("SELECT path FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category` cp ON (cc.category_id = cp.category_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
			foreach ($coupon_category_query->rows as $category) {
                $path_query=$this->db->query("SELECT category_id FROM " . DB_PREFIX . "category  WHERE path  like '".$category['path']."%' ");
                foreach($path_query->rows as $cat){
                    $coupon_category_data[] = $cat['category_id'];
                }
				
			}	
            //去除重复值
			$coupon_category_data =array_flip(array_flip($coupon_category_data));
			$product_data = array();
            $special_product =array(); 
            $status['products']=1;
            $status['category']=1;
            foreach ($this->cart->getProducts() as $product) {
               if ($coupon_product_data) {
                    //sku is one of
                   if($coupon_query->row['sku_condition']==1){
                        $status['products']=0;
                        if (in_array($product['product_id'], $coupon_product_data)) {
                            $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                            $product_data[$product['product_id']]['total'] =$product['total'];
                            $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                        }
                        if (in_array($product['product_id'], $coupon_product_data)) {
                            $status['products']=1;
                            break;
                        }
                        
                   }
                   else if($coupon_query->row['sku_condition']==2){
                        if (!in_array($product['product_id'], $coupon_product_data)) {
                            $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                            $product_data[$product['product_id']]['total'] =$product['total'];
                            $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                        }
                       //sku is not one off
                        if (in_array($product['product_id'], $coupon_product_data)) {
                            $status['products']=0;
                            break;
                        }
                   } 
                   else if($coupon_query->row['sku_condition']==3){    
                        //sku is all
                        foreach($coupon_product_data as $coupon_product){
                            if($coupon_product==$product['product_id']){
                                $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                $product_data[$product['product_id']]['total'] =$product['total'];
                                $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                            }
                            if($coupon_product!=$product['product_id']){
                                $status['products']=0;
                                break;
                            }
                        }
                   }
                   else if($coupon_query->row['sku_condition']==4){
                       //sku is not
                        foreach($coupon_product_data as $coupon_product){
                            if($coupon_product!=$product['product_id']){
                                $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                $product_data[$product['product_id']]['total'] =$product['total'];
                                $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                              }
                             if($coupon_product==$product['product_id']){
                                        $status['products']=0;
                                        break;
                              }
                         }
                   }
              }
                  //判断是否符合分类条件
                  if($coupon_category_data){
                      $in_coupon_category_data =implode(",",$coupon_category_data);
                      if($coupon_query->row['category_condition']==1){
                             $status['category']=0;
                             $coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id in (".$in_coupon_category_data.")");
                             if ($coupon_category_query->row['total']) {
                                $status['category']=1;
                             }						
                              if($status['category']){
                                 $coupon_category_product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                 $coupon_category_product_data[$product['product_id']]['total'] =$product['total'];
                                 $coupon_category_product_data[$product['product_id']]['quantity'] =$product['quantity'];
                              }
                        
                      }
                      elseif($coupon_query->row['category_condition']==2){
                                $status['category']=0;
                                $coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id in(".$in_coupon_category_data.")");
                                if ($coupon_category_query->row['total']==0) {
                                   $status['category']=1;
                                }						
                              if($status['category']){
                                $coupon_category_product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                $coupon_category_product_data[$product['product_id']]['total'] =$product['total'];
                                $coupon_category_product_data[$product['product_id']]['quantity'] =$product['quantity'];
                              }
                      }
                    
                      if($coupon_category_product_data){
                          $status['category']=1;
                      }
                      else{
                        $status['category']=0;
                      }
                 }
                    
               if($product['special_price']||$product['exclusive_price']){
                    $special_product [] =$product['product_id'];
                } 
            } 
            //得到购物车符合条件的coupon商品
            foreach ($this->cart->getProducts() as $product) {
               if ($coupon_product_data) {
                    //sku is one of
                   if($coupon_query->row['sku_condition']==1){
                        if (in_array($product['product_id'], $coupon_product_data)) {
                            $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                            $product_data[$product['product_id']]['total'] =$product['total'];
                            $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                        }
                   }
                   else if($coupon_query->row['sku_condition']==2){
                        if (!in_array($product['product_id'], $coupon_product_data)) {
                            $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                            $product_data[$product['product_id']]['total'] =$product['total'];
                            $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                        }
                   } 
                   else if($coupon_query->row['sku_condition']==3){    
                        //sku is all
                        foreach($coupon_product_data as $coupon_product){
                            if($coupon_product==$product['product_id']){
                                $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                $product_data[$product['product_id']]['total'] =$product['total'];
                                $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                            }
                        }
                   }
                   else if($coupon_query->row['sku_condition']==4){
                       //sku is not
                        foreach($coupon_product_data as $coupon_product){
                            if($coupon_product!=$product['product_id']){
                                $product_data[$product['product_id']]['product_id'] =$product['product_id'];
                                $product_data[$product['product_id']]['total'] =$product['total'];
                                $product_data[$product['product_id']]['quantity'] =$product['quantity'];
                              }
                         }
                   }
                }
            }
            $status['condition_total'] =1;
            $total_condition_value =0;
            $total_condition_qty=0;
            if($coupon_category_product_data){
               foreach($coupon_category_product_data as $conditions){
                   //把符合条件的商品放入折扣商品中
                   if(isset($product_data[$conditions['product_id']])){
                        $product_data[$conditions['product_id']]['total']+=$conditions['total'];
                        $product_data[$conditions['product_id']]['quantity']+=$conditions['quantity'];
                   }
                   else{
                        $product_data[$conditions['product_id']]['product_id']=$conditions['product_id'];
                        $product_data[$conditions['product_id']]['total']=$conditions['total'];
                        $product_data[$conditions['product_id']]['quantity']=$conditions['quantity'];
                   }
                   if(in_array($conditions['product_id'],$coupon_product_data)){
                        $coupon_product_data[] =$conditions['product_id'];
                   }
               }
            }
           $condition_product_id =array();
           if($product_data){
                foreach($product_data as $condition_product){
                    $condition_product_id[] =$condition_product['product_id'];
                    $total_condition_value+=$condition_product['total'];
                    $total_condition_qty+=$condition_product['quantity'];
                }
               if($total_condition_value>=$coupon_query->row['condition_total']){
                    $status['condition_total'] =1;
               }else{
                    $status['condition_total'] =0;
               }
               if($total_condition_qty>=$coupon_query->row['condition_total_qty']){
                    $status['condition_total_qty'] =1;
               }else{
                    $status['condition_total_qty'] =0;
               }
           }
               
               
		} else {
            $combine_condition_value =null;
			$base_status = false;
		}
        $vailte_status =true;
        //条件选择，如果为空，这是全部满足
        if($combine_condition_value){
            $combine_condition_value_array =explode(',',$combine_condition_value);
            if($combine_condition=='1'){   //满足所有选择条件
                foreach($combine_condition_value_array as $condition){
                    if($status[$condition]==0){
                        $vailte_status =false;
                    }
                }
            }
            elseif($combine_condition=='0'){  //满足任意其中一个条件
                 $vailte_status=false;
                foreach($combine_condition_value_array as $condition){
                    if($status[$condition]==1){
                        $vailte_status =true;
                    }
                }
            }
            
        }
        else{
            foreach($status as $value){
                //有一项条件不满足，则coupon不能使用
                if($value==0){
                    $vailte_status =false;
                }
            }
        }
		if ($base_status&&$vailte_status) {
			return array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
                'front_name'          => $coupon_query->row['front_name'],
				'type'          => $coupon_query->row['type'],
                'buy_x'          => $coupon_query->row['buy_x'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
                'condition_total'         => $coupon_query->row['condition_total'],
                'combine_condition_value'         => $coupon_query->row['combine_condition_value'],
				'product'       => $condition_product_id,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added'],
                'special_product'    =>$special_product
			);
		}
	}

	public function redeem($coupon_id, $order_id, $customer_id, $amount) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_id . "', order_id = '" . (int)$order_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (float)$amount . "', date_added = NOW()");
	}
}
?>
