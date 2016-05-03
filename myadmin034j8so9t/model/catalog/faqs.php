<?php
class ModelCatalogFaqs extends Model {
	public function addFaq($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "faqs SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "',faq_title='".$this->db->escape(strip_tags($data['title']))."', faq_text = '" . $this->db->escape(strip_tags($data['text'])) . "',is_pass =".$data['is_pass']." ,is_reply=0,store_id='".$data['store_id']."', add_time = NOW(),moditify_time=NOW()");
	}

	public function editFaq($faq_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "faqs SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "',faq_title='".$this->db->escape(strip_tags($data['title']))."', faq_text = '" . $this->db->escape(strip_tags($data['text'])) . "',is_pass =".$data['is_pass'].",store_id='".$data['store_id']."',moditify_time=NOW() WHERE faq_id = '" . (int)$faq_id . "'");
	}

	public function deleteFaq($faq_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqs WHERE faq_id = '" . (int)$faq_id . "'");
	}

	public function getFaq($faq_id) {
		$query = $this->db->query("SELECT f.*,fr.reply_text,c.email FROM " . DB_PREFIX . "faqs as f 
        left join " . DB_PREFIX . "faqs_reply as fr on f.faq_id=fr.faq_id  
        left join " . DB_PREFIX . "customer as c on f.customer_id=c.customer_id
        WHERE f.faq_id = '" . (int)$faq_id . "'");

		return $query->row;
	}


	public function editReply($faq_id, $data) {
		//判断是否存在，存在更新，不存在插入
		$query = $this->db->query("select reply_id from ". DB_PREFIX ."faqs_reply where faq_id=". $faq_id);
		if($query->row){
			$this->db->query("UPDATE " . DB_PREFIX . "faqs_reply SET reply_text = '" . $this->db->escape(strip_tags($data['reply_text'])) . "' WHERE faq_id = '" . (int)$faq_id . "'");
		}
		else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "faqs_reply SET faq_id ='".$faq_id."' ,reply_text = '" . $this->db->escape(strip_tags($data['reply_text'])) . "',user_id ='".$this->session->data['user_id']."',reply_time=NOW() ");
			$this->db->query("UPDATE " . DB_PREFIX . "faqs SET is_reply =1 where faq_id=".$faq_id);

		}
		
	}

	public function deleteReply($faq_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqs_reply WHERE faq_id = '" . (int)$faq_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "faqs SET is_reply =0 where faq_id=".$faq_id);
	}

	public function getReply($faq_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqs_reply WHERE faq_id = '" . (int)$faq_id . "'");

		return $query->row;
	} 

	public function getFaqs($data = array()) {
		$sql = "SELECT f.*,p.model,c.email FROM " . DB_PREFIX . "faqs f 
        left join ".DB_PREFIX."product p on f.product_id=p.product_id
        left join ".DB_PREFIX."customer c on f.customer_id=c.customer_id
        where 1";																																					  

		$sort_data = array(
			'f.faq_id',
			'f.add_time',
			'f.is_pass',
			'f.store_id',
			'f.is_reply'
		);
		if(!empty($data['filter_id'])){
			$sql .=" and f.faq_id= ".$data['filter_id'];
		}
		if(!empty($data['filter_sku'])){
			$sql .=" and p.model= '".$data['filter_sku']."'";
		}
        if(!empty($data['filter_email'])){
			$sql .=" and c.email= '".$data['filter_email']."'";
		}
		if(!empty($data['filter_author'])){
			$sql .=" and f.author= '".$data['filter_author']."'";
		}
		if(!empty($data['filter_store_id'])){
			$sql .=" and f.store_id= ".$data['filter_store_id'];
		}
		if(!empty($data['filter_pass'])){
			$sql .=" and f.is_pass= ".$data['filter_pass'];
		}
		if(!empty($data['filter_reply'])){
			$sql .=" and f.is_reply= ".$data['filter_reply'];
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY f.faq_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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

	public function getTotalFaqs($data) {
        $sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faqs as f ";
        if(!empty($data['filter_sku'])){
            $sql .="left join ".DB_PREFIX."product p on f.product_id=p.product_id ";
		}
        if(!empty($data['filter_email'])){
			$sql .=" left join ".DB_PREFIX."customer c on f.customer_id=c.customer_id";
		}
        $sql .=" where 1";
        if(!empty($data['filter_id'])){
			$sql .=" and f.faq_id= ".$data['filter_id'];
		}
		if(!empty($data['filter_sku'])){
			$sql .=" and p.model= '".$data['filter_sku']."'";
		}
        if(!empty($data['filter_email'])){
			$sql .=" and c.email= '".$data['filter_email']."'";
		}
		if(!empty($data['filter_author'])){
			$sql .=" and f.author= '".$data['filter_author']."'";
		}
		if(!empty($data['filter_store_id'])){
			$sql .=" and f.store_id= ".$data['filter_store_id'];
		}
		if(!empty($data['filter_pass'])){
			$sql .=" and f.is_pass= ".$data['filter_pass'];
		}
		if(!empty($data['filter_reply'])){
			$sql .=" and f.is_reply= ".$data['filter_reply'];
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalFaqssAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faqs WHERE status = '0'");

		return $query->row['total'];
	}	
	
}
?>