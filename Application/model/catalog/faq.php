<?php
class ModelCatalogFaq extends Model {		
	public function addFaq($product_id, $data) {
        $sql ="INSERT INTO " . DB_PREFIX . "faqs SET  store_id ='".$this->config->get('config_store_id')."',product_id=".$product_id.",customer_id=".$this->session->data['customer_id'].",author='".$data['name']."',faq_title='".$data['faq_title']."',faq_text='".$this->db->escape($data['faq_content'])."',is_pass=0,is_reply=0,add_time=NOW(),moditify_time=NOW()";
        $this->db->query($sql);
	}
		

    //得到通过审核的商品所有的FAQS
	public function getFaqsByProductId($product_id,$start=0,$limit=false){

        if((int)$this->config->get('config_store_id')!=0){
            $sql =" select * from ".DB_PREFIX."faqs as f left join ".DB_PREFIX."faqs_reply as fr on f.faq_id=fr.faq_id where product_id=".$product_id." and is_pass =1 and store_id='".(int)$this->config->get('config_store_id')."' order by add_time desc ";
        }else{
            $sql =" select * from ".DB_PREFIX."faqs as f left join ".DB_PREFIX."faqs_reply as fr on f.faq_id=fr.faq_id where product_id=".$product_id." and is_pass =1  order by add_time desc ";
        }
		
		if($limit){
			$sql .=" limit ".$start."," .$limit;
		}
		$query =$this->db->query($sql);
		return $query->rows;
	}
}
?>