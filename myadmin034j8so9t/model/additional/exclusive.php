<?php
class ModelAdditionalExclusive extends Model {
    public function getExclusiveUrls($data){
        $sql ="select * from ".DB_PREFIX."product_exclusive_source";
        $sort_data = array(
			's_id'
		);
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY s_id";	
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
     
    public function getExclusiveUrl($id){
        $query =$this->db->query("select * from ".DB_PREFIX."product_exclusive_source where s_id =".(int)$id);
        return $query->row;
    }
    public function getTotalExclusiveUrl(){
        $query = $this->db->query("select count(*) as total from ".DB_PREFIX."product_exclusive_source");	
        return $query->row['total'];
    }
    
    public function deleteExclusiveUrl($s_id){
        $this->db->query("delete from ".DB_PREFIX."product_exclusive_source where s_id =".$s_id);	
    }
    public function addExclusiveUrl($data){
        $this->db->query("insert into ".DB_PREFIX."product_exclusive_source set url='".$this->db->escape($data['url'])."' ");	
    }

    public function editExclusiveUrl($s_id,$data){
        $this->db->query("update ".DB_PREFIX."product_exclusive_source set url='".$this->db->escape($data['url'])."' where s_id=".$s_id);	
    }

    public function getExclusiveProducts($data){
        $sql = "SELECT * FROM " . DB_PREFIX . "product_exclusive_price
        where 1";																																					  

		$sort_data = array(
            'pep_id',
			'product_id',
			'start_time',
            'end_time'
		);
		if(!empty($data['filter_product_id'])){
			$sql .=" and product_id= '".$data['filter_product_id']."'";
		}
		if(!empty($data['filter_from_url'])){
			$sql .=" and from_url like '%".$data['filter_from_url']."%' ";
		}
        if(!empty($data['filter_start_time'])){
			$sql .=" and start_time<= '".$data['filter_start_time']."'";
		}
		if(!empty($data['filter_end_time'])){
			$sql .=" and end_time >= '".$data['filter_end_time']."'";
		}
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pep_id";	
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

    public function getExclusiveProduct($id){
        $query =$this->db->query("select * from ".DB_PREFIX."product_exclusive_price where pep_id=".$id);
        return $query->row;
    }
    public function getTotalExclusiveProducts(){
        $sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_exclusive_price";
        $sql .=" where 1";
        if(!empty($data['filter_product_id'])){
			$sql .=" and product_id= '".$data['filter_product_id']."'";
		}
		if(!empty($data['filter_from_url'])){
			$sql .=" and from_url like '%".$data['filter_from_url_id']."%' ";
		}
        if(!empty($data['filter_start_time'])){
			$sql .=" and start_time<= '".$data['filter_start_time']."'";
		}
		if(!empty($data['filter_end_time'])){
			$sql .=" and end_time >= '".$data['filter_end_time']."'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
    }

    public function addExclusiveProduct($data){
         $this->db->query("insert into ".DB_PREFIX."product_exclusive_price set  product_id ='".(int)$data['product_id']."',price='".(float)$data['price']."',from_url='".$this->db->escape($data['from_url'])."',limit_number='".(int)$data['limit_number']."',start_time='".$data['start_time']."',end_time='".$data['end_time']."' ");	
    }
    public function deleteExclusiveProduct($id){
        $this->db->query("delete from ".DB_PREFIX."product_exclusive_price where pep_id =".$id);
    }
    public function editExclusiveProduct($id,$data){
        $this->db->query("update ".DB_PREFIX."product_exclusive_price set  product_id ='".(int)$data['product_id']."',price='".(float)$data['price']."',from_url='".$this->db->escape($data['from_url'])."',limit_number='".(int)$data['limit_number']."',start_time='".$data['start_time']."',end_time='".$data['end_time']."'  where pep_id=".$id);	
    }
}
?>