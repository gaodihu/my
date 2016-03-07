<?php
class Customer {
	private $customer_id;
	private $nickname;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) { 
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($customer_query->num_rows) {
                
				$this->customer_id = $customer_query->row['customer_id'];
				$this->nickname = $customer_query->row['nickname'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				//$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
                $this->update_customer_info();

				$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
                $this->db->query("UPDATE " . DB_PREFIX . "cart SET user_id = '" . (int)$this->customer_id . "' WHERE session_id = '" . $this->session->getId() . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {
		//$override 不需要验证密码
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1' AND approved = '1'");
		/*
		if ($override) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer where LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' status = '1' AND approved = '1'");
		} 
		*/
		if ($customer_query->num_rows) {
			if($override){
				$validate_password =1;
			}
			else{
				$cunstomer_info =$customer_query->row;
				$hashArr = explode(':', $cunstomer_info['password']);
				$count_hasharr = count($hashArr);
				if($count_hasharr==1){
					$validate_password = (md5($password) === $cunstomer_info['password']);
				}
				elseif($count_hasharr==2){
					$validate_password = (md5($hashArr[1] . $password) === $hashArr[0]);
				}
			}
		    if($validate_password){	
                
				$this->session->data['customer_id'] = $customer_query->row['customer_id'];
                $this->session->data['customer_email'] = $customer_query->row['email'];
                $this->session->data['shipping_address_id'] = $customer_query->row['address_id'];	
                $this->session->data['session_id'] = $this->session->getId().substr(md5($this->session->data['customer_id']),0,2);	
				$this->customer_id = $customer_query->row['customer_id'];
				$this->nickname = $customer_query->row['nickname'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				//$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
                $this->update_customer_info();
				
                
				return true;
			}
		} else {
			return false;
		}
	}

	public function logout() {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
        $this->db->query("UPDATE " . DB_PREFIX . "cart SET user_id=0 where session_id='".$this->session->data['session_id']."' " );
        $this->session->data['session_id'] = $this->session->getId();
		unset($this->session->data['customer_id']);
        unset($this->session->data['coupon']);
        unset($this->session->data['points']);
		$this->customer_id = '';
		$this->nickname = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

	public function getId() {
		return $this->customer_id;
	}
	public function getNickName() {
        if($this->nickname){
		    return $this->nickname;
        }
        else{
            return $this->firstname." ".$this->lastname;
        }
	}
	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getNewsletter() {
		return $this->newsletter;	
	}

	public function getCustomerGroupId() {
		return $this->customer_group_id;	
	}

	public function getAddressId() {
		return $this->address_id;	
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "' and status=1");
		return $query->row['total'];	
	}
    public function getSpentPoint(){
        $query = $this->db->query("SELECT SUM(points_spent) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");
		return $query->row['total'];	
    }
    public function getValidationPoint(){
        $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "' and status=0");
		return $query->row['total'];	
    }
    public function getAvailablePoints() {
		return $this->getRewardPoints()-$this->getSpentPoint();
	}

    public function update_customer_info(){
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
        //把以前购物车商品标记为用户
        $this->db->query("UPDATE " . DB_PREFIX . "cart SET user_id='".(int)$this->customer_id."' where session_id='".$this->session->data['session_id']."' " );
        //把现在的商品
        $this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id='".$this->session->data['session_id']."',user_id='".(int)$this->customer_id."' where session_id='".$this->session->getId()."' " );
        //如果存在相同商品,合并
        $sql ="select * from ".DB_PREFIX."cart where session_id='".$this->session->data['session_id']."' ";
        $query =$this->db->query($sql);
        $time=date("Y-m-d H:i:s",time());
        $goods_number_arr =array();
        foreach($query->rows as $cart_pro){
            if(array_key_exists($cart_pro['product_id'],$goods_number_arr)){
                $this->db->query("UPDATE ".DB_PREFIX."cart set goods_number=goods_number+".$cart_pro['goods_number'].",update_time='".$time."' where rec_id =".$goods_number_arr[$cart_pro['product_id']]);
                $this->db->query("delete from ".DB_PREFIX."cart where rec_id =".$cart_pro['rec_id']);
            }
            else{
                $goods_number_arr[$cart_pro['product_id']] =$cart_pro['rec_id'];
            }
        }
        
       //更新会员等级
       $enablePoint =$this->getRewardPoints();
       $query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group WHERE point <= '" . (int)$enablePoint ."' order by point desc limit 1");
       $customer_group_id =$query->row['customer_group_id'];
       
       if($this->customer_group_id != $customer_group_id){
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id='".$customer_group_id."' where customer_id=".(int)$this->customer_id );
       }
       $this->customer_group_id = $customer_group_id;
    }
    
    public function thirdlogin($email, $uid, $from) {
		//$override 不需要验证密码
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1' AND approved = '1'");

		if ($customer_query->num_rows) {
            $cunstomer_info =$customer_query->row;
			$customer_id = $cunstomer_info['customer_id'];
			$third_customer_sql = "SELECT * FROM " .DB_PREFIX."third_customer WHERE email='".$this->db->escape(utf8_strtolower($email))."' AND  	third_from='".strtolower($from)."'  AND  customer_id= '".$customer_id."'";

			$third_customer_query = $this->db->query($third_customer_sql);

			$validate_password = 0;
			if($third_customer_query->num_rows){
				$third_customer_row = $third_customer_query->row;

				if($from != 'paypal' && (strtolower($third_customer_row['third_uid']) == strtolower($uid))){
					$validate_password = 1;
				}else{
					if($from == 'paypal'){
						if(strtolower($third_customer_row['third_uid']) == strtolower($uid)){
							$validate_password = 1;
						}else{
							if($third_customer_row['created_at '] > '2016-01-22'){
								$validate_password = 0;
							}else{
								$validate_password = 1;
							}
						}
					}
				}


			}else{
				$validate_password = 0;
			}


		    if($validate_password){

				$this->session->data['customer_id'] = $customer_query->row['customer_id'];
                $this->session->data['shipping_address_id'] = $customer_query->row['address_id'];	
                $this->session->data['session_id'] = $this->session->getId().substr(md5($this->session->data['customer_id']),0,2);	
				$this->customer_id = $customer_query->row['customer_id'];
				$this->nickname = $customer_query->row['nickname'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				//$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
                $this->update_customer_info();
				
                
				return true;
			}
		}
		return false;

	}

}
?>