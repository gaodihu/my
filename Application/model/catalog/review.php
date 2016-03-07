<?php
class ModelCatalogReview extends Model {		
	public function addReview($product_id, $data) {
        $sql ="INSERT INTO " . DB_PREFIX . "review SET store_id ='".$this->config->get('config_store_id')."',product_id=".$product_id.",customer_id=".$this->session->data['customer_id'].",author='".$data['nickname']."',title='".$data['title']."',text='".$this->db->escape($data['content'])."',rating='".$data['rating']. "',order_number='".$data['order_number']."',status=0,is_publish=0,date_added=NOW(),date_modified=NOW()";
        $this->db->query($sql);
        $review_id =$this->db->getLastId();
        if(isset($data['image'])){
            foreach($data['image'] as $image){
                $this->db->query("INSERT INTO ".DB_PREFIX."review_images set review_id='".(int)$review_id."',product_id='".(int)$product_id."',image_path='".$this->db->escape($image)."',video_path=NULL");
            }
        }
        return $review_id;
	}
    
    public function getReviewsImage($review_id){
        $query=$this->db->query("SELECT image_path FROM " . DB_PREFIX . "review_images where review_id=".$review_id);
        return $query->rows;
    }
	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
        $data =array();
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
        
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
		$query = $this->db->query("SELECT r.review_id, r.author,r.title, r.rating, r.text,r.against,r.support, p.product_id,r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id)  WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND r.is_publish = '1' ".$store_where." ORDER BY r.support DESC,r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
        
        foreach($query->rows as $row){
            $review_image =$this->getReviewsImage($row['review_id']);
            $image_data =array();
            if($review_image){
                foreach($review_image as $image){
                    $image_data[] =$image['image_path'];
                }
            }
            $row['image'] =$image_data;
            $data[] =$row;
        }
		return $data;
	}

	public function getTotalReviewsByProductId($product_id) {
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND r.is_publish = '1' ".$store_where);
		return $query->row['total'];
	}

    public function getTotalReviewsByRating($product_id,$rating){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id)  WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1'  AND r.is_publish='1' ".$store_where." and r.rating=".$rating);
		
		return $query->row['total'];
    }

    public function UpdateReviewSupport($review_id,$condition,$num){
        $this->db->query("UPDATE " . DB_PREFIX ."review SET ".$condition." = ".$num." where review_id=".$review_id);
    }
      //得到分类下的所有评论总数
    public function getCountReviesByCatagory($catagory_id_in){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        if($catagory_id_in){
            $sql =" select count(distinct r.review_id) as total from ".DB_PREFIX."product_to_category as p2c left join ".DB_PREFIX."review as r on p2c.product_id =r.product_id where p2c.category_id in ".$catagory_id_in." and r.status=1 and r.is_publish=1 ".$store_where;
        }else{
            $sql =" select count(distinct r.review_id) as total from ".DB_PREFIX."review where status=1 and is_publish=1 ".$store_where;
        }
        $query =$this->db->query($sql);
        return $query->row['total'];
        
    }
    //得到分类下的所有评论
    public function getReviesProductByCatagory($data){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        if(isset($data['filter_category_id'])&&$data['view']==1){
             if(isset($data['filter_category_id'])&&$data['filter_category_id']){
                $sql =" select distinct r.* from ".DB_PREFIX."product_to_category as p2c left join ".DB_PREFIX."review_images as rm on p2c.product_id =rm.product_id 
                left join ".DB_PREFIX."review as r on rm.review_id=r.review_id 
                where p2c.category_id in ".$data['filter_category_id']." and r.status=1 and r.is_publish=1 ".$store_where; 
            }
            else{
                $sql =" select  distinct r.* from ".DB_PREFIX."review_images as rm
                left join ".DB_PREFIX."review as r on rm.review_id=r.review_id 
                where  r.status=1 and r.is_publish=1 ".$store_where; 
            }
        }else{
            if(isset($data['filter_category_id'])&&$data['filter_category_id']){
                $sql =" select distinct r.* from ".DB_PREFIX."product_to_category as p2c left join ".DB_PREFIX."review as r on p2c.product_id =r.product_id where p2c.category_id in ".$data['filter_category_id']." and r.status=1 and r.is_publish=1 ".$store_where; 
            }
            else{
                $sql =" select  distinct r.* from ".DB_PREFIX."review as r  where  r.status=1 and r.is_publish=1 ".$store_where; 
            }
        }
        if(isset($data['sort'])){
           $sql .=" order by ".$data['sort'];
            
        }
        if(isset($data['sort'])&&isset($data['order'])){
            $sql .=' '.$data['order'];
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
        $query =$this->db->query($sql);
        return $query->rows;
    }
    
    public function getTotalRevies($data){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        if(isset($data['filter_category_id'])&&$data['view']==1){
             if(isset($data['filter_category_id'])&&$data['filter_category_id']){
                $sql =" select count(distinct r.review_id) as total from ".DB_PREFIX."product_to_category as p2c left join ".DB_PREFIX."review_images as rm on p2c.product_id =rm.product_id 
                left join ".DB_PREFIX."review as r on rm.review_id=r.review_id 
                where p2c.category_id in ".$data['filter_category_id']." and r.status=1 and r.is_publish=1 ".$store_where; 
            }
            else{
                $sql =" select  count(distinct r.review_id) as total from ".DB_PREFIX."review_images as rm
                left join ".DB_PREFIX."review as r on rm.review_id=r.review_id 
                where  r.status=1 and r.is_publish=1 ".$store_where; 
            }
        }else{
           if(isset($data['filter_category_id'])&&$data['filter_category_id']){
                $sql =" select count(distinct r.review_id) as total from ".DB_PREFIX."product_to_category as p2c left join ".DB_PREFIX."review as r on p2c.product_id =r.product_id where p2c.category_id in ".$data['filter_category_id']." and r.status=1 and r.is_publish=1 ".$store_where; 
            }
            else{
                $sql =" select  count(distinct r.review_id) as total from ".DB_PREFIX."review as r  where  r.status=1 and r.is_publish=1 ".$store_where; 
            }
        }
        $query =$this->db->query($sql);
        return $query->row['total'];
    }
    
    //得到商品下的所有评论
    public function getReviesByProduct($product_id,$data){
        if(isset($data['view'])&&$data['view']==1){
            $sql ="select distinct r.* from ".DB_PREFIX."review_images as rm left join ".DB_PREFIX."review as r  on rm.review_id=r.review_id where rm.product_id=".$product_id;
           
        }else{
            $sql ="select r.* from ".DB_PREFIX."review as r   where r.product_id=".$product_id;
        }
        $sql .=" and r.status=1 and r.is_publish=1 ";
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        $sql .=$store_where;
        if(isset($data['sort'])){
           $sql .=" order by r.".$data['sort'];
            
        }
        if(isset($data['sort'])&&isset($data['order'])){
            $sql .=' '.$data['order'];
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
        $query =$this->db->query($sql);
        return $query->rows;
    }
    public function getTotalReviesByProduct($product_id,$data){
         if(isset($data['view'])&&$data['view']==1){
            $sql ="select count(*) as total from ".DB_PREFIX."review_images as rm left join ".DB_PREFIX."review as r  on rm.review_id=r.review_id where rm.product_id=".$product_id;
           
        }else{
            $sql ="select count(*) as total from ".DB_PREFIX."review as r   where r.product_id=".$product_id;
        }
        $sql .=" and r.status=1 and r.is_publish=1 ";
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        $sql .=$store_where;
        $query =$this->db->query($sql);
        return $query->row['total'];
    }
    //得到商品最近一条评论
    public function get_laster_review($product_id,$sort='date_added'){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        $sql =" select r.* from ".DB_PREFIX."review as r  where r.product_id=".$product_id." and r.status=1 and r.is_publish=1 ".$store_where ." order by ".$sort." DESC limit 1";
        $query =$this->db->query($sql);
        return $query->row;
    }
    //得到商品最近一条带有图片的评论
    public function get_laster_review_image($product_id,$sort='date_added'){
        $store_where ='';
        if((int)$this->config->get('config_store_id')!=0){
            $store_where =" and r.store_id=".(int)$this->config->get('config_store_id');
        }
        $data =array();
        $sql =" select r.* from ".DB_PREFIX."review as r  where r.product_id=".$product_id." and r.status=1 and r.is_publish=1 ".$store_where ."  order by ".$sort." DESC";
        $query =$this->db->query($sql);
        foreach($query->rows as $row){
            $image_info =$this->getReviewsImage($row['review_id']);
            if($image_info){
                foreach($image_info as $img){
                    $row['review_image'][] =$img['image_path'];
                }
                
                return $row;
            }
        }
        return  $data;
    }
    

    
    public function getMaxCountProduct($limit){
        $sql ="SELECT count(*) as total,product_id  FROM ".DB_PREFIX."review WHERE status=1 and is_publish=1 group by product_id order by total desc limit ".$limit;
        $query =$this->db->query($sql);
        return $query->rows;
    }

    public function  getReviewInfo($product_id,$review_id){
        $sql ="SELECT *  FROM ".DB_PREFIX."review WHERE review_id=".$review_id." and product_id=".$product_id;
        $query =$this->db->query($sql);
        return $query->row;
    }
    
    public function addReviewReply($data){
        $this->db->query("INSERT INTO  ".DB_PREFIX."review_reply set review_id=".$data['review_id'].",customer_id='".$data['customer_id']."',text='".$this->db->escape($data['text'])."',created_at=NOW(),updated_at=NOW()");
    }
    public function getReplyByReview($review_id){
        $query =$this->db->query("SELECT rr.*,c.avatar,c.firstname FROM ".DB_PREFIX."review_reply as rr  left join oc_customer as c  on rr.customer_id =c.customer_id where rr.review_id=".$review_id." and rr.status=1 order by rr.created_at desc");
        if($query->num_rows){
            return $query->rows;
        }
        else{
            return false;
        }
    }
    public function getCountReplyByReview($review_id){
        $query =$this->db->query("SELECT count(*) as total  FROM ".DB_PREFIX."review_reply where review_id=".$review_id." and status=1 ");
        if($query->num_rows){
            return $query->row['total'];
        }
        else{
            return 0;
        }
    }
    
    public function getReviewOrders($customer_id,$product_id,$order_number=""){
        $sql = "select order_number from ".DB_PREFIX."review where customer_id = '{$customer_id}' and  product_id = '{$product_id}' ";
        if($order_number != ''){
            $sql .= " and  order_number= '{$order_number}'";
        }
        $query = $this->db->query($sql);
        if($query->num_rows){
            return $query->rows;
        }else{
            return false;
        }
    }
    
    public function haveReviews($product_id,$order_number){
        $query = $this->db->query("select review_id from ".DB_PREFIX."review where order_number = '{$order_number}' and  product_id = '{$product_id}'");
        if($query->num_rows){
            return true;
        }else{
            return false;
        }
    }
    
    public function autoCheck($review_id,$points){
        $query = $this->db->query("select * from ".DB_PREFIX."review where review_id = '{$review_id}'");
        $row   = $query->row;
        $text  = $row['text'];
        $title = $row['title'];
        $customer_id = $row['customer_id'];
        $product_id = $row['product_id'];
        $date_time = $row['date_added'];
        $time = strtotime(date('Y-m-d',  strtotime($date_time)).' 00:00:00');
        $start = date('Y-m-d H:i:s',$time);
        $end   = date('Y-m-d H:i:s',$time+24*3600);
        

        if($customer_id){
            $sql_all = "select * from ".DB_PREFIX."review where customer_id='{$customer_id}' and  product_id = '{$product_id}' and  status = 1 and date_added >='{$start}' and date_added <'{$end}' order by date_added asc limit 1";
            $query_all = $this->db->query($sql_all);
            if($query_all->num_rows){
                
            }else{
                if($row['status'] == 0 && $row['point_send'] == 0){
                    if(utf8_strlen($text)>=20){
                        $this->sendPoints($customer_id,$points);
                        $this->db->query("update ".DB_PREFIX."review set point_send=1,status=1 where review_id=".$review_id);
                    }
                }
            }
        }
    }
    public function sendPoints($customer_id,$points){
       if(isset($this->session->data['2nd_anniversary']) &&$this->session->data['2nd_anniversary']){
           $sql ="insert into ".DB_PREFIX."customer_reward set customer_id=".$customer_id.",description='Double points for reviews, only in 2nd Anniversary',points='".$points."',points_spent=0,status=1,date_added=NOW(),date_confirm=NOW()";
       }
       else{
           $sql ="insert into ".DB_PREFIX."customer_reward set customer_id=".$customer_id.",description='product reviews',points='".$points."',points_spent=0,status=1,date_added=NOW(),date_confirm=NOW()";
       }
        
        $this->db->query($sql);
    }
}
?>