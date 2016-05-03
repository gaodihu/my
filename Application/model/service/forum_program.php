<?php
class ModelServiceForumProgram extends Model {
	public function addForum($data) {
        $forum_url =implode(',',$data['forum_url']);
        $paypal_account =isset($data['paypal_account'])?$data['paypal_account']:NULL;
        $this->db->query("insert into " . DB_PREFIX . "forum_program_info  set  forum_name='".$this->db->escape($data['forum_name'])."', forum_url='".$this->db->escape($forum_url)."',profile_link='".$this->db->escape($data['profile_link'])."',user_name='".$this->db->escape($data['user_name'])."',account_id='".(int)$data['account_id']."',paypal_account='".$this->db->escape($paypal_account)."',contact_email='".$this->db->escape($data['contact_email'])."', 	contact_name='".$this->db->escape($data['contact_name'])."',created_at=NOW()");
    }

    public function addForumUser($data){
        if($user_ga_id =$this->getGaIdByCustomer($data['user_id'])){
            $fourm_ga_id=$user_ga_id;
        }
        else{
             $fourm_ga_id =$data['link_id'];
        }
        $this->db->query("insert into " . DB_PREFIX ."forum_user  set  fourm_ga_id='".$this->db->escape($fourm_ga_id)."', lang_code='".$this->db->escape($data['lang_code'])."',user_id='".intval($data['user_id'])."',forum_link='".$this->db->escape($data['post_link'])."',forum_content='".$this->db->escape($data['post_text'])."',ga_click=0,ga_click_screenshot='',forum_money=0, 	forum_get_points=0,status=1,email_send=0,created_at=NOW(),updated_at=NOW()");
    }

    public function getForumUserInfo($user_id,$data){
        $sql ="select * from ".DB_PREFIX."forum_user where user_id=".$user_id;
        if(isset($data['sort'])){
            $sql .=' order by '.$data['sort']." desc ";
        }else{
            $sql .=' order by created_at desc ';
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

    public function getTotalForumUserInfo($user_id){
         $sql ="select count(*) as total  from ".DB_PREFIX."forum_user where user_id=".$user_id;
         $query = $this->db->query($sql);

		return $query->row['total'];	

    }
    public function getUserGaId($user_id){
     $md_str ='abcdefghijklmnopqrstuvwxyz1234567890';
     $password ='';  
     $length =strlen($user_id);
     for ( $i = 0; $i < $length; $i++)  
     {  
        $password .= $md_str[$user_id[$i]];  
     }  
     return $password.$user_id[0];  
    }
    
    //判断ID是否已经存在
    public function is_have_ga_id($ga_id){
        $query =$this->db->query("select forum_user_id from ".DB_PREFIX."forum_user where fourm_ga_id='".$ga_id."' ");
        if($query->num_rows){
            return true;
        }
        else{
            return false;
        }
    }

    //得到用户的GA_ID
    public function getGaIdByCustomer($user_id){
        $query =$this->db->query("select fourm_ga_id from ".DB_PREFIX."forum_user where user_id='".$user_id."' ");
        if($query->num_rows){
            return $query->row['fourm_ga_id'];
        }
        else{
            return false;
        }
    }
}
?>