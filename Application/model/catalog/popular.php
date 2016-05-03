<?php
class ModelCatalogPopular extends Model {
	public function getAllPopular($tags_array,$limit=3) {
        $lang_code =strtolower($this->session->data['language']);
        $data =array();
        foreach($tags_array as $item){
             $query =$this->db->query("SELECT tags FROM " . DB_PREFIX . "hot_tags_".$lang_code." where tags_sign='".$item."' order by popularity desc limit ".$limit);
             foreach($query->rows as $res){
                 $data[$item][] =$res;
             } 
        }
       return $data;
	}
	
    public function getPopulars($data){
        $lang_code =strtolower($this->session->data['language']);
        $sql ="SELECT tags FROM " . DB_PREFIX . "hot_tags_".$lang_code." where 1";
        if(isset($data['tags'])){
            $sql .=" and tags_sign ='".$this->db->escape($data['tags'])."'";
        }
        $sql .=" order by popularity desc";
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 100;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
        $query =$this->db->query($sql);
        return $query->rows;
    }
    
    public function getTotalPopulars($data){
        $lang_code =strtolower($this->session->data['language']);
        $sql ="SELECT count(*) as total FROM " . DB_PREFIX . "hot_tags_".$lang_code." where 1";
        if(isset($data['tags'])){
            $sql .=" and tags_sign ='".$this->db->escape($data['tags'])."'";
        }
        $query =$this->db->query($sql);
        return $query->row['total'];
    }


    
}
?>