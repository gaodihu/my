<?php
class ModelNewsletterNewsletter extends Model {
	public function addNewsletter($data) {
        require_once(DIR_SYSTEM . 'library/ip.php');
        $IP =new Ip();
        $remote_ip =$IP->getIP();
        $country =$IP->getCountryName($remote_ip);
        $this->db->query("INSERT INTO ".DB_PREFIX."newsletter SET store_id='".$this->config->get('config_store_id')."',customer_id='".$data['customer_id']."',email='".$data['email']."',status=1,ip_address='".$this->db->escape($remote_ip)."',country='".$this->db->escape($country)."',validate_code='".$this->db->escape($data['validate_code'])."',created_time=NOW()");
        return $this->db->getLastId();
    }
    
    public function editNewsletter($customer,$email,$validate_code,$status){
        $query =$this->db->query("update  ".DB_PREFIX."newsletter set email='".$email."',status=".$status.",validate_code='".$validate_code."' where customer_id=".$customer);
    }
    public function getNewsletter($email){
        $query =$this->db->query("SELECT * FROM ".DB_PREFIX."newsletter where email='".$email."'");
         if($query->num_rows){
            return $query->row;
         }
         else{
            return array();
         }

    }

    public function getNewsletterByCustomer($customer_id){
        $query =$this->db->query("SELECT * FROM ".DB_PREFIX."newsletter where customer_id='".$customer_id."'");
         if($query->num_rows){
            return $query->row;
         }
         else{
            return array();
         }

    }
    
    public function getActiveNewsletterByCustomer($customer_id){
        $query =$this->db->query("SELECT * FROM ".DB_PREFIX."newsletter where customer_id='".$customer_id."' and status=2");
         if($query->num_rows){
            return $query->row;
         }
         else{
            return array();
         }

    }

    public function removeNewsletter($email){
         $this->db->query("update ".DB_PREFIX."newsletter set status=3 where email='".$email."'");
    }

    public function validateNewsletter($code){
        $query =$this->db->query("SELECT rec_id,email FROM ".DB_PREFIX."newsletter where status=1 and validate_code='".$code."'");
        if($query->num_rows){
            $res =$query->row;
            $this->db->query("UPDATE ".DB_PREFIX."newsletter SET status=2 where rec_id=".$res['rec_id']);
            return $res;
        }
        else{
             return false;
        }
    }

    public function hasActive($code){
        $query =$this->db->query("SELECT rec_id FROM ".DB_PREFIX."newsletter where status=2 and validate_code='".$code."'");
         if($query->num_rows){
            return true;
         }
         else{
             return false;
         }
    }
}
?>