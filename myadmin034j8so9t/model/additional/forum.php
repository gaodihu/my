<?php
class ModelAdditionalForum extends Model {
     /*

    *  论坛账户信息
    *
    *
    */
    public function getForumProgram($data){
        $sql ="select * from ".DB_PREFIX."forum_program_info where 1";
         if(isset($data['filter_forum_name'])){
            $sql .=" and forum_name='".trim($data['filter_forum_name'])."'";
        }
        if(isset($data['filter_forum_url'])){
            $sql .=" and forum_url like '%".trim($data['filter_forum_url'])."%'";
        }
        if(isset($data['filter_user_name'])){
            $sql .=" and user_name='".trim($data['filter_user_name'])."'";
        }
        if(isset($data['filter_contact_email'])){
            $sql .=" and contact_email='".trim($data['filter_contact_email'])."'";
        }
        if(isset($data['filter_contact_name'])){
            $sql .=" and contact_name='".trim($data['filter_contact_name'])."'";
        }

       
	    $sql .= " ORDER BY forum_program_id DESC ";	
		

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
     
    public function getForumProgramInfo($id){
        $query =$this->db->query("select * from ".DB_PREFIX."forum_program_info where forum_program_id =".(int)$id);
        return $query->row;
    }
     public function deleteForumProgramInfo($id){
        $query =$this->db->query("delete from ".DB_PREFIX."forum_program_info where forum_program_id =".(int)$id);
        return $query->row;
    }
    public function getTotalForumProgram($data){
        $sql = "select count(*) as total from ".DB_PREFIX."forum_program_info where 1";
        if(isset($data['filter_forum_name'])){
            $sql .=" and forum_name='".trim($data['filter_forum_name'])."'";
        }
        if(isset($data['filter_forum_url'])){
            $sql .=" and forum_url='".trim($data['filter_forum_url'])."'";
        }
        if(isset($data['filter_user_name'])){
            $sql .=" and user_name='".trim($data['filter_user_name'])."'";
        }
        if(isset($data['filter_contact_email'])){
            $sql .=" and contact_email='".trim($data['filter_contact_email'])."'";
        }
        if(isset($data['filter_contact_name'])){
            $sql .=" and contact_name='".trim($data['filter_contact_name'])."'";
        }
        $query =$this->db->query($sql);
        return $query->row['total'];
    }

    /*

    *  用户发表信息
    *
    *
    */
    public function getForumUserList($data){
        $sql ="select * from ".DB_PREFIX."forum_user where 1";
        if(isset($data['filter_fourm_ga_id'])){
            $sql .=" and fourm_ga_id='".trim($data['filter_fourm_ga_id'])."'";
        }
        if(isset($data['filter_user_email'])){
            $sql .=" and user_id='".trim($data['filter_user_id'])."'";
        }
        if(isset($data['filter_status'])){
            $sql .=" and status='".trim($data['filter_status'])."'";
        }
        if(isset($data['filter_email_send'])){
            $sql .=" and email_send='".trim($data['filter_email_send'])."'";
        }
	    $sql .= " ORDER BY created_at DESC ";	
		

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
    public function getTotalForumUserList($data){
        $sql = "select count(*) as total from ".DB_PREFIX."forum_user where 1";
        if(isset($data['filter_fourm_ga_id'])){
            $sql .=" and fourm_ga_id='".trim($data['filter_fourm_ga_id'])."'";
        }
        if(isset($data['filter_user_email'])){
            $sql .=" and user_id='".trim($data['filter_user_id'])."'";
        }
        if(isset($data['filter_status'])){
            $sql .=" and status='".trim($data['filter_status'])."'";
        }
        if(isset($data['filter_email_send'])){
            $sql .=" and email_send='".trim($data['filter_email_send'])."'";
        }
        $query =$this->db->query($sql);
        return $query->row['total'];
    }

    public function getForumUserPost($id){
        $query =$this->db->query("select * from ".DB_PREFIX."forum_user where forum_user_id =".(int)$id);
        return $query->row;
    }
    public function deleteForumUserPost($id){
        $query =$this->db->query("delete from ".DB_PREFIX."forum_user where forum_user_id =".(int)$id);
        return $query->row;
    }
     public function editForumUserPost($id,$data){
        if($data['image_path']){
            $query =$this->db->query("update  ".DB_PREFIX."forum_user set ga_click='".(int)$data['ga_click']."',ga_click_screenshot 	='".$this->db->escape($data['image_path'])."',forum_money='".(float)$data['money']."',forum_get_points='".(int)$data['points']."',status='".(int)$data['status']."',updated_at=NOW() where forum_user_id =".(int)$id);
        }
        else{
            $query =$this->db->query("update  ".DB_PREFIX."forum_user set ga_click='".(int)$data['ga_click']."',forum_money='".(float)$data['money']."',forum_get_points='".(int)$data['points']."',status='".(int)$data['status']."',updated_at=NOW() where forum_user_id =".(int)$id);
        }
        
     }
}
?>