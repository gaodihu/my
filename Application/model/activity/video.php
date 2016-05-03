<?php
class ModelActivityVideo extends Model {
	public function addVideo($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "youtube_video SET customer_id = '" . (int)$this->customer->getId() . "', upload_name ='".$data['upload_name']."',order_number='".$data['order_number']."',youtube_link='".$data['youtube_link']."',status=0,add_date=NOW(),update_date=NOW()");
		$video_id = $this->db->getLastId();
		return $video_id;
	}
	
	public function getVideos($data) {
        $sql ="select * from ".DB_PREFIX."youtube_video ";
        if(isset($data['limit'])){
            $limit =$data['limit'];
        }
        else{
            $limit =16;
        }
        if(isset($data['sort'])){
            $sort =$data['sort'];
        }
        else{
            $sort ="add_date";
        }
        if(isset($data['order'])){
            $order =$data['order'];
        }
        else{
            $order ="DESC";
        }
        $sql .=" where status=1 order by ".$sort." ".$order." limit ".$data['start'].",".$limit;
		$query = $this->db->query($sql);
        return $query->rows;
	}

    public function getVideo($video_id) {
		$query = $this->db->query("select * from ".DB_PREFIX."youtube_video where video_id=".$video_id);
        return $query->row;
	}

    public function getTotalVideos() {
		$query = $this->db->query("select count(*) as total from ".DB_PREFIX."youtube_video where status=1");
        return $query->row['total'];
	}
	
	public function deleteVideo($video_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "youtube_video WHERE video_id = " . (int)$video_id);
	}	
	
}
?>