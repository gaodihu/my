<?php
class ModelFestivalSkuActionSet extends Model {
	public function addSkuActionSet($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sku_action_set set all_sku='".$this->db->escape(trim($data['all_sku']))."',start_time='".$this->db->escape($data['start_time'])."',end_time='".$this->db->escape($data['end_time'])."' ");
        $set_id =$this->db->getLastId();
        if($data['text']){
            foreach($data['text'] as $lang_id=>$value){
                $this->db->query("INSERT INTO " . DB_PREFIX . "sku_action_set_descrition set set_id='".(int)$set_id ."',lang_id='".(int)$lang_id."',text='".$this->db->escape($value)."',link='".$this->db->escape($data['link'][$lang_id])."' ");
            }
        }
	}

	public function getSkuActionSet($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sku_action_set WHERE id = '" . (int)$id . "'");

		return $query->row;
	}
    public function getSkuActionSetDesc($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sku_action_set_descrition WHERE set_id = '" . (int)$id . "'");

		return $query->rows;
	}
    public function editSkuActionSet($set_id,$data){
       
        $this->db->query("UPDATE ".DB_PREFIX."sku_action_set set all_sku='".$this->db->escape(trim($data['all_sku']))."',start_time='".$this->db->escape($data['start_time'])."',end_time='".$this->db->escape($data['end_time'])."' where id=".(int)$set_id);
        if($data['text']){
            foreach($data['text'] as $lang_id=>$value){
                $this->db->query("update " . DB_PREFIX . "sku_action_set_descrition set text='".$this->db->escape($value)."',link='".$this->db->escape($data['link'][$lang_id])."'  where set_id ='".(int)$set_id."' and lang_id='".$lang_id."'");
            }
        }
    }
	public function getActionSetList() {
        $time =date("Y-m-d H:i:s",time());
		$sql = "SELECT * FROM " . DB_PREFIX . "sku_action_set   order by start_time desc";
		$query = $this->db->query($sql);																																				

		return $query->rows;	
	}
	
}
?>