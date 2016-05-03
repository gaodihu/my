<?php
class ModelServiceProductPost extends Model {
	public function addNewProductPost($data) {
        $count =count($data['product_name']);
        for($i=0;$i<$count;$i++){
            $this->db->query("insert into " . DB_PREFIX . "new_product_post set language_code='".$this->db->escape($data['language_code'])."',user_name='".$this->db->escape($data['user_name'])."', email='".$this->db->escape($data['user_email'])."',product_name='".$this->db->escape($data['product_name'][$i])."',product_color='".$this->db->escape($data['product_color'][$i])."',product_img='".$this->db->escape($data['product_img'][$i])."',price='".(float)$data['product_price'][$i]."',currency_code='".$data['currency'][$i]."',url_link='".$this->db->escape($data['product_link'][$i])."',shipment='".$this->db->escape($data['shipment'][$i])."',product_description='".$this->db->escape($data['product_description'][$i])."',comment='".$this->db->escape($data['comment'][$i])."',status=0,email_send=0,created_at=NOW()");
        }
	}
}
?>