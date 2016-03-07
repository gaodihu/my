<?php
class Cart {
	private $config;
	private $db;
	private $data = array();
	private $data_recurring = array();
    private $session_id;

	public function __construct($registry) {
        $this->registry =$registry;
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
        $this->currency = $registry->get('currency');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
        $this->session_id =$this->session->data['session_id'];
        $this->load = $registry->get('load');
	}

	public function getProducts() {
        $this->load->model('catalog/product');
        //if(isset($this->session->data['coupon'])){
            $this->data=array();
        //}
        
		//if (!$this->data) {
            if(isset($this->session->data['customer_id']) && $this->session->data['customer_id']){
                //更新session_id
                $this->db->query("UPDATE ".DB_PREFIX."cart set session_id='".$this->session_id."' where user_id =".$this->session->data['customer_id']);
                $sql="select * from ".DB_PREFIX."cart where user_id='".$this->session->data['customer_id']."'";
            }
            else{
                $sql="select * from ".DB_PREFIX."cart where session_id='".$this->session_id."'";
            }
            $query=$this->db->query($sql);
			foreach ( $query->rows as $product) {
				$product_id = $product['product_id'];
				$stock = true;

				// Options

				$options = array();

				// Profile

				$profile_id = 0;

				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");
				if ($product_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					foreach ($options as $product_option_id => $option_value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $option_value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],									
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);								
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
								foreach ($option_value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'option_value'            => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
										);								
									}
								}						
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',								
									'weight'                  => '',
									'weight_prefix'           => ''
								);						
							}
						}
					} 

					if ($this->customer->isLogged()) {
						$customer_group_id = $this->customer->getCustomerGroupId();
                       
					} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
                        
					}
                    $original_price =  $product_query->row['price'];
					$price = $product_query->row['price'];
					// Product Discounts
                    
                     
                    if(!isset($this->session->data['coupon'])){
                        $discount_quantity = $product['goods_number'];

                        $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND (customer_group_id = '" . (int)$customer_group_id . "' or customer_group_id =0) AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
               
                        if ($product_discount_query->num_rows) {
                            $trie_price = $product_discount_query->row['price'];
                            if($trie_price<$price){
                                $price = $trie_price;
                            }
                        }
                        else{
                            $trie_price =NULL;
                        }
                    }
                    else{
                        $trie_price =NULL;
                    }
				    
                    
					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND (customer_group_id = '" . (int)$customer_group_id . "' or customer_group_id =0 )  AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                    
					if ($product_special_query->num_rows) {
						$special_price = $product_special_query->row['price'];
                        if($special_price<$price){
                            $price = $special_price;
                        }
					}
                    else{
                        $special_price=NULL;
                    }
                    
                    //专属price
                    $exclusive_price_info =array();
                    $exclusive_price_info =$this->load->model_catalog_product->realy_exclusive_price($product_id);
                    if($exclusive_price_info){
                        $exclusive_price =$exclusive_price_info['price'];
                        if($exclusive_price<$price){
                            $price = $exclusive_price;
                        } 
                    }else{
                        $exclusive_price =null;
                    }
                   
					// Reward Points
                    /*
					$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

					if ($product_reward_query->num_rows) {	
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}
                    */
                    $reward =ceil($price);
					// Downloads		
					$download_data = array();     		

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask'],
							'remaining'   => $download['remaining']
						);
					}
					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $product['goods_number'])) {
						$stock = false;
					}

					$recurring = false;
					$recurring_frequency = 0;
					$recurring_price = 0;
					$recurring_cycle = 0;
					$recurring_duration = 0;
					$recurring_trial_status = 0;
					$recurring_trial_price = 0;
					$recurring_trial_cycle = 0;
					$recurring_trial_duration = 0;
					$recurring_trial_frequency = 0;
					$profile_name = '';

					if ($profile_id) {
						$profile_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`profile_id` = `p`.`profile_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;

						if ($profile_info) {
							$profile_name = $profile_info['name'];

							$recurring = true;
							$recurring_frequency = $profile_info['frequency'];
							$recurring_price = $profile_info['price'];
							$recurring_cycle = $profile_info['cycle'];
							$recurring_duration = $profile_info['duration'];
							$recurring_trial_frequency = $profile_info['trial_frequency'];
							$recurring_trial_status = $profile_info['trial_status'];
							$recurring_trial_price = $profile_info['trial_price'];
							$recurring_trial_cycle = $profile_info['trial_cycle'];
							$recurring_trial_duration = $profile_info['trial_duration'];
						}
					}
                    $currency_price = $this->currency->convert($price + $option_price,'USD',$this->currency->getCode());
                    $currency_total = $currency_price * $product['goods_number'];
                    
                    $usd_total = $this->currency->convert($currency_total,$this->currency->getCode(),'USD');
                    
                    $battery_type = $product_query->row['battery_type'];
                    
					$this->data[] = array(
                        'rec_id'                    => $product['rec_id'],
						'product_id'                => $product['product_id'],
						'name'                      => $product_query->row['name'],
						'model'                     => $product['model'],
						'shipping'                  => $product_query->row['shipping'],
						'image'                     => $product_query->row['image'],
						'option'                    => $option_data,
						'download'                  => $download_data,
						'quantity'                  => $product['goods_number'],
						'minimum'                   => $product_query->row['minimum'],
						'subtract'                  => $product_query->row['subtract'],
						'stock'                     => $stock,
						'price'                     => ($price + $option_price),
						'total'                     => $usd_total,
						'reward'                    => $reward * $product['goods_number'],
						'points'                    => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $product['goods_number'] : 0),
						'tax_class_id'              => $product_query->row['tax_class_id'],
						'total_weight'              => ($product_query->row['weight'] + $option_weight) * $product['goods_number'],
						'weight_class_id'           => $product_query->row['weight_class_id'],
						'length'                    => $product_query->row['length'],
						'width'                     => $product_query->row['width'],
						'height'                    => $product_query->row['height'],
						'length_class_id'           => $product_query->row['length_class_id'],
						'profile_id'                => $profile_id,
						'profile_name'              => $profile_name,
						'recurring'                 => $recurring,
						'recurring_frequency'       => $recurring_frequency,
						'recurring_price'           => $recurring_price,
						'recurring_cycle'           => $recurring_cycle,
						'recurring_duration'        => $recurring_duration,
						'recurring_trial'           => $recurring_trial_status,
						'recurring_trial_frequency' => $recurring_trial_frequency,
						'recurring_trial_price'     => $recurring_trial_price,
						'recurring_trial_cycle'     => $recurring_trial_cycle,
						'recurring_trial_duration'  => $recurring_trial_duration,
                        'original_price'            => $original_price,
                        'trie_price'                => $trie_price,
                        'special_price'             => $special_price,
                        'exclusive_price'           => $exclusive_price,
                        'currency_total'            => $currency_total,
                        'currency_price'           => $currency_price,
                        'battery_type'              => $battery_type,
                        'weight'                    => ($product_query->row['weight'] + $option_weight),
					);
                    
				} else {
					$this->remove($key);
				}
			}
		//} 
		return $this->data;
	}

	public function getRecurringProducts(){
		$recurring_products = array();

		foreach ($this->getProducts() as $key => $value) {
			if ($value['recurring']) {
				$recurring_products[$key] = $value;
			}
		}

		return $recurring_products;
	}

	public function add($product_id, $qty = 1, $option, $profile_id = '') {

        //把购物车数据保存在oc_cart中
        $curreny_code =$this->currency->getCode();
        //得到购物车所需商品信息
        $sql ="select model from ".DB_PREFIX."product where product_id=".$product_id;
        $query =$this->db->query($sql);
        $model =$query->row['model'];
        $time=date("Y-m-d H:i:s",time());
        //判断物品是否存在于购物车，如果存在，更新
       
        if(isset($this->session->data['customer_id']) && $this->session->data['customer_id']){
            $customer_id =$this->session->data['customer_id'];
        }
        else{
            $customer_id=0;
        }
        if($this->is_in_cart($product_id)){
            $this->db->query("update ".DB_PREFIX."cart set goods_number =goods_number +".$qty.",update_time='".$time."' where product_id=".$product_id." and session_id='".$this->session_id."'");
        } 
        else{
            $remote_ip = $_SERVER['REMOTE_ADDR'];	
            $sql_insert ="INSERT INTO ".DB_PREFIX."cart values(NULL,'".$this->session_id."','".$customer_id."','$product_id','$model','$qty','$remote_ip','$time','$time')";
            $this->db->query($sql_insert);
        }
	}

	public function update($product_id, $qty) {
        $time=date("Y-m-d H:i:s",time());
		 if($this->is_in_cart($product_id)){
             $this->db->query("update ".DB_PREFIX."cart set goods_number =".$qty." ,update_time='".$time."'  where product_id=".$product_id." and session_id='".$this->session_id."'");
        }
        else{
           $this->remove($product_id);
        }
	}
    
	public function remove($product_id) {
         $this->db->query("delete from ".DB_PREFIX."cart  where product_id='".$product_id."' and session_id='".$this->session_id."'");
	}

	public function clear() {
		$this->db->query("delete from ".DB_PREFIX."cart  where session_id='".$this->session_id."'");
	}
    
     //判断物品是否存在于购物车
    public function is_in_cart($product_id){
        $sql ="select rec_id,goods_number from ".DB_PREFIX."cart where product_id=".$product_id." and session_id='".$this->session_id."'";
        $query =$this->db->query($sql);
        if($query->row&&$query->row['goods_number']>0){
            return true;
        }
        else{
            return false;
        }
    }

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['total_weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['currency_total'];
		}
        $total = $this->currency->convert($total,$this->currency->getCode(),'USD');
		return $total;
	}
    public function getCurrencySubTotal(){
        $total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['currency_total'];
		}

		return $total;
    }

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}		

		return $product_total;
	}

	public function hasProducts() {
        $query =$this->db->query("select count(*) as count from ".DB_PREFIX."cart  where session_id='".$this->session_id."'");
		return $query->row['count'];
	}

	public function hasRecurringProducts(){
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		$stock = true;

		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				$stock = false;
			}
		}

		return $stock;
	}

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				$download = true;

				break;
			}
		}

		return $download;
	}
    
}
?>