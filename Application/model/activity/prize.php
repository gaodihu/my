<?php
class ModelActivityPrize extends Model {

    //得到活动的设置
    function get_action_info($prize_name_id){
        $query =$this->db->query("select *  from ".DB_PREFIX."prize_name  where id=".(int)$prize_name_id );
        return $query->row;
    }
    //得到抽奖的设置
	public function get_prize_set($prize_name_id ){
        $query =$this->db->query("select ps.*  from ".DB_PREFIX."prize_set as ps where prize_name_id =".(int)$prize_name_id." order by prize_id ASC" );
        return $query->rows;
    }
    
    //得到奖项中奖的人数
    public function get_prize_num($prize_name_id,$prize_id){
        $query =$this->db->query("select count(*) as total from ".DB_PREFIX."prize_get_detail where prize_name_id='".(int)$prize_name_id."' and prize_id =".(int)$prize_id);
        return $query->row['total'];
    }
    //插入抽奖数据
    public function add_prize_detail($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."prize_get_detail set prize_name_id='".(int)$data['prize_name_id']."',nickname='".$this->db->escape($data['nickname'])."', prize_token='".$this->db->escape($data['prize_token'])."',prize_id='".(int)$data['prize_id']."',add_time=NOW(),order_created_time='".$data['order_created_time']."',email='".$data['email']."'");
        return $this->db->getLastId();
    }
    //查看该用户是否已抽奖
    public function get_prize_detail($prize_name_id,$prize_token){
        $query =$this->db->query("select prize_id from ".DB_PREFIX."prize_get_detail where prize_name_id='".(int)$prize_name_id."' and prize_token ='".$this->db->escape($prize_token)."'");
        if($query->num_rows){
            return $query->row;
        }else{
            return false;
        }
    }

    //得到所有的抽奖数据
    public function getAllPrizeDetails($prize_name_id,$limit=''){
        $sq1 = "select pd.*,ps.prize_name from ".DB_PREFIX."prize_get_detail as pd left join ".DB_PREFIX."prize_set as ps on pd.prize_id =ps.prize_id  where pd.prize_name_id=".(int)$prize_name_id." and ps.prize_name_id=".(int)$prize_name_id." AND  nickname != '' order by pd.add_time desc";
        if(!$limit){
            $sq1 .= " limit {$limit}";
        }
        $query =$this->db->query($sq1);
        return $query->rows;
    }

    public function update_prize_detail_email_nickname($id,$email,$nickname){
        $sql = "update oc_prize_get_detail set email = '".$this->db->escape($email)."',nickname='".$this->db->escape($nickname)."' where id = '".(int)$id."'";
        $this->db->query($sql);
    }
}
?>