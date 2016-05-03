<?php
class ModelCatalogReview extends Model {
	public function addReview($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET store_id='".$data['store_id']."',author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "', title='".$this->db->escape(strip_tags($data['title'])) ."',text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', is_publish = '" . (int)$data['is_publish'] . "',support=".(int)$data['support'].",against=".(int)$data['against'].", date_added = NOW(),date_modified = NOW()");

		$this->cache->delete('product');
	}

	public function editReview($review_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "review SET title='".$this->db->escape(strip_tags($data['title'])) ."',text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', is_publish = '" . (int)$data['is_publish'] . "',support=".(int)$data['support'].",against=".(int)$data['against']." ,date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');
	}

	public function deleteReview($review_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');
	}

	public function getReview($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT p.model FROM " . DB_PREFIX . "product p WHERE p.product_id = r.product_id ) AS sku FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");
        foreach($this->getReviewImage($review_id) as $image){
            $query->row['images'][] =$image['image_path'];
        }
		return $query->row;
	}

    public function getReviewImage($review_id){
        $query = $this->db->query("SELECT  image_path FROM " . DB_PREFIX . "review_images WHERE review_id = '" . (int)$review_id . "'");

		return $query->rows;
    }
	public function getReviews($data = array()) {
		$sql = "SELECT r.review_id,r.store_id, p.model, r.author, r.rating, r.status,r.is_publish, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON r.product_id = p.product_id where 1 ";																																					  
		$sort_data = array(
            'r.review_id',
            'r.store_id',
			'p.sku',
			'r.author',
			'r.rating',
			'r.status',
            'r.is_publish',
			'r.date_added'
		);	
        if(!empty($data['filter_id'])){
			$sql .=" and r.review_id= ".$data['filter_id'];
		}
        if(!is_null($data['filter_store_id'])){
			$sql .=" and r.store_id= ".$data['filter_store_id'];
		}

		if(!empty($data['filter_sku'])){
			$sql .=" and p.model='".$data['filter_sku']."'";
		}
		if(!empty($data['filter_author'])){
			$sql .=" and r.author like '%".$data['filter_author']."%'";
		}
		if(!empty($data['filter_rating'])){
			$sql .=" and r.rating= ".$data['filter_rating'];
		}
		if(!is_null($data['filter_status'])){
			$sql .=" and r.status= ".$data['filter_status'];
		}
        
        if(!is_null($data['filter_is_publish'])){
			$sql .=" and r.is_publish= ".$data['filter_is_publish'];
		}
        
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.review_id";	
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

	public function getTotalReviews($data = array()) {
		$sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review as r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) where 1";
         if(!empty($data['filter_id'])){
			$sql .=" and r.review_id= ".$data['filter_id'];
		}
        if(!is_null($data['filter_store_id'])){
			$sql .=" and r.store_id= ".$data['filter_store_id'];
		}
		if(!empty($data['filter_sku'])){
			$sql .=" and p.model= '".$data['filter_sku']."'";
		}
		if(!empty($data['filter_author'])){
			$sql .=" and r.author like '%".$data['filter_author']."%'";
		}
		if(!empty($data['filter_rating'])){
			$sql .=" and r.rating= ".$data['filter_rating'];
		}
		if(!is_null($data['filter_status'])){
			$sql .=" and r.status = ".$data['filter_status'];
		}

		if(!is_null($data['filter_is_publish'])){
			$sql .=" and r.is_publish = ".$data['filter_is_publish'];
		}

        $query =$this->db->query($sql);
		return $query->row['total'];
	}

	public function getTotalReviewsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");

		return $query->row['total'];
	}
    
    public function if_send_point($review_id){
        $query =$this->db->query("select  point_send from ".DB_PREFIX."review where review_id=".$review_id);
        if($query->row['point_send']){
            return true;
        }else{
            return false;
        }
    }
    public function update_send_point($review_id){
        $this->db->query("update ".DB_PREFIX."review set point_send=1 where review_id=".$review_id);
    }

}
?>