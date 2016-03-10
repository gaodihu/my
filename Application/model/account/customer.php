<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		
		//用户密码加密方式使用magento 原有方式
		
		/*
		注册时 得到随机2个字符，添加到password 后面
       如 密码：123456 随机字符ad  则 password 为 md5('ad123456'):ad

		验证:
		$hashArr = explode(':', $password);
		return $this->hash($hashArr[1] . $from_password) === $hashArr[0];
		*/
        $customer_validate_code =uniqid();
        if(!isset($data['third_from'])){
            $data['third_from'] = '';
        }
        if(!isset($data['third_uid'])){
            $data['third_uid'] = '';
        }
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "',nickname='".$this->db->escape($data['nickname'])."', email = '" . $this->db->escape($data['email']) . "',salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 2)) . "', password = '" . $this->db->escape(md5($salt.$data['password']).":".$salt) . "', newsletter = '" . (isset($data['newsletter']) ? 1 : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "',validate_code='".$customer_validate_code."',is_active=0, date_added = NOW(),third_from='".$data['third_from'] . "',third_uid='".$data['third_uid'] . "'");

		$customer_id = $this->db->getLastId();

		if($customer_id){
			if($data['third_uid'] && $data['third_from']){
				$this->db->query("INSERT INTO " .DB_PREFIX."third_customer(customer_id	,	email,third_from,third_uid) values ('".$customer_id."','".$this->db->escape($data['email'])."','".$this->db->escape($data['third_from'])."','".$this->db->escape($data['third_uid'])."')");
			}

		}

        //插入订阅
        if(isset($data['newsletter'])){
            $this->load->model('newsletter/newsletter');
            $newsletter_info = $this->model_newsletter_newsletter->getNewsletter($this->db->escape($data['email']));
            if(!$newsletter_info){
                $this->load->model('newsletter/newsletter');
                $data_newsletter =array();
                $validate_code =$customer_validate_code;
                $data_newsletter['customer_id'] =$customer_id;
                $data_newsletter['email'] =$data['email'];
                $data_newsletter['validate_code'] =$validate_code;
                $this->model_newsletter_newsletter->addNewsletter($data_newsletter);
                //$this->model_newsletter_newsletter->sendEmail($validate_code,$data_newsletter['email']);
            }
        }


        //发送用户确认邮件
        $this->load->model('tool/email');
        $this->language->load('mail/customer');
        $validate_link =$this->url->link('account/login/validate','customer_id='.$customer_id.'&validate_code='.$customer_validate_code."&email=".$data['email']);
        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] =$this->config->get('config_name');
        $email_data['email_to'] =$data['email'];
        $template = new Template();
        $template->data['title'] =$this->language->get('text_subject_confim');
        $template->data['subject'] =$this->language->get('text_subject_confim');  
        $template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');		
        $template->data['store_id'] = $this->config->get('config_store_id');
        $template->data['store_name'] = $this->config->get('config_name');
        if ($this->config->get('config_store_id')) {
            $template->data['store_url'] = $this->config->get('config_url');		
        } else {
            $template->data['store_url'] = HTTP_SERVER;	
        }
        $template->data['text_home'] =$this->language->get('text_home');
        $template->data['text_menu_new_arrivals'] =$this->language->get('text_menu_new_arrivals');
        $template->data['text_menu_top_sellers'] =$this->language->get('text_menu_top_sellers');
        $template->data['text_menu_deals'] =$this->language->get('text_menu_deals');
        $template->data['text_menu_clearance'] =$this->language->get('text_menu_clearance');

        $template->data['text_footer'] = $this->language->get('text_edm_foot');
        $template->data['text_main_content'] = sprintf($this->language->get('text_main_content_confim'),$validate_link);
        $email_data['email_subject'] =$this->language->get('text_subject_confim');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/new_customer_confim.tpl')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/mail/new_customer_confim.tpl');
        } else {
            $html = $template->fetch('default/template/mail/new_customer_confim.tpl');
        }
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
	}
	public function editCustomer($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "',gender='".$data['gender']."',birthday='".(isset($data['birthday'])?$data['birthday']:NULL)."',country_id='".$data['country_id']."',avatar='".$data['avatar']."',telephone = '" . $this->db->escape($data['telephone']) . "'  WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 2)) . "', password = '" . $this->db->escape(md5($salt.$password).":".$salt) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . $this->db->escape($newsletter) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	

		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}	

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	public function validatePassword($customer_id,$password) {
		$query = $this->db->query("SELECT password, salt FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$customer_id . "'");
		$password_info =$query->row;
		if(md5($password_info['salt'].$password).":".$password_info['salt'] ==$password_info['password']){
			return true;
		}
		else{
			return false;
		}
	}

    public function hasActive($customer_id,$code){
        $query =$this->db->query("SELECT customer_id FROM ".DB_PREFIX."customer where customer_id = ".$customer_id." and is_active=1 and validate_code='".$code."'");
         if($query->num_rows){
            return true;
         }
         else{
             return false;
         }
    }

    public function validateCustomer($customer_id,$code){
        $query =$this->db->query("SELECT customer_id,email FROM ".DB_PREFIX."customer where customer_id=".$customer_id." and is_active=0 and validate_code='".$code."'");
        if($query->num_rows){
            $res =$query->row;
            $this->db->query("UPDATE ".DB_PREFIX."customer SET is_active=1 where customer_id=".$res['customer_id']);
            //修改邮件订阅状态
            //$this->db->query("UPDATE ".DB_PREFIX."newsletter SET is_active=1 where customer_id=".$res['customer_id']." and email='".$res['email']."' ");
            return $res;
        }
        else{
             return false;
        }
    }
    /*
    * 判断用户是否购买过某商品，订单状态为processing或者complete
    *
    */
    public function getBuyProductByCustomer($customer_id,$product_id){
        $sql ="select o.order_id from ".DB_PREFIX."order_product as op left join oc_order as o on op.order_id = o.order_id where op.product_id=".(int)$product_id." and o.customer_id=".(int)$customer_id." and o.order_status_id in (2,5)";
        $query =$this->db->query($sql);
        if($query->num_rows){
            return true;
        }
        else{
            return false;
        }
    }
    
    /*
    *取得用户是否购买过某商品，订单状态为processing或者complete
    *
    */
    public function getCustomerOrdersByProduct($customer_id,$product_id){
        $sql ="select o.order_number from ".DB_PREFIX."order_product as op left join oc_order as o on op.order_id = o.order_id where op.product_id=".(int)$product_id." and o.customer_id=".(int)$customer_id." and o.order_status_id in (2,5)";
        $query =$this->db->query($sql);
        if($query->num_rows){
            return $query->rows;
        }
        else{
            return false;
        }
    }
    
    
    public function bingding($customer_id,$third_from,$third_uid,$third_email){
        $sql = "INSERT INTO " . DB_PREFIX ."third_customer(customer_id,email,third_from,third_uid) value('{$customer_id}','".$this->db->escape(utf8_strtolower($third_email))."','".$this->db->escape(utf8_strtolower($third_from))."','".$this->db->escape(utf8_strtolower($third_uid))."')";
        $this->db->query($sql);
    }

	public function getThirdCustomerByEmail($email){
		$email = strtolower($email);
		$sql = "SELECT * FROM `" .DB_PREFIX."third_customer` WHERE email = '".$this->db->escape(utf8_strtolower($email))."'";

		$query = $this->db->query($sql);
		if($query->num_rows){
			return $query->rows;
		}
		return false;
	}
}
?>
