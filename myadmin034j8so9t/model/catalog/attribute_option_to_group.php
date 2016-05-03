<?php 
class ModelCatalogAttributeOptionToGroup extends Model {
	public function addAttribute($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute SET attribute_code ='".$data['attribute_code']."', sort_order = '" . (int)$data['sort_order'] . "',is_show_catalog='".$data['is_show_catalog']."' ");
		$attribute_id = $this->db->getLastId();
		
		foreach ($data['attribute_group_id'] as $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_to_group SET attribute_id = '" . (int)$attribute_id . "', attribute_group_id = $value");
		}

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		if(!empty($data['attribute_option'])){
			foreach($data['attribute_option']['value'] as $order =>$attribute_option){
				$order = $data['attribute_option']['order'][$order];
                $is_show = $data['attribute_option']['show'][$order];
				$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option values(NULL,$attribute_id,$is_show,$order)");
				$option_id = $this->db->getLastId();
				foreach($attribute_option as $key =>$value){
					$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option_value values(NULL,$option_id,$key,'$value')");
				}
				
			}
		}
	}

	public function editOptionAttributeGroup($option_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "attribute_option_value_to_group SET status = 0 WHERE option_id = '" . (int)$option_id . "'");
        foreach($data['option_group_id'] as $value_id){
            $this->db->query("UPDATE " . DB_PREFIX . "attribute_option_value_to_group SET status =1 WHERE value_id = '" . (int)$value_id . "'");
        }
	}
	public function getOptionAttributeGroup($option_id) {
		$query = $this->db->query("SELECT aovg.*,ag.attribute_group_code FROM " . DB_PREFIX . "attribute_option_value_to_group aovg left join ".DB_PREFIX . "attribute_group as ag on aovg.attribute_group_id=ag.attribute_group_id  WHERE option_id = " . (int)$option_id );
		return $query->rows;
	}

	public function getOptionAttributeGroups($data = array()) {
        $data_array =array();
		$sql = "SELECT distinct aovg.option_id,aov.option_value FROM " . DB_PREFIX . "attribute_option_value_to_group aovg left join ".DB_PREFIX . "new_attribute_option_value as aov on aovg.option_id=aov.option_id where aov.language_id=".(int)$this->config->get('config_language_id');
		if (!empty($data['filter_name'])) {
			$sql .= " AND aov.option_value LIKE '" . $this->db->escape($data['filter_name']) . "%'";
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
        foreach($query->rows as $key=>$options){
            $sql_group ="SELECT aovg.attribute_group_id,aovg.status,ag.attribute_group_code from ".DB_PREFIX . "attribute_option_value_to_group aovg left join ".DB_PREFIX . "attribute_group ag on aovg.attribute_group_id=ag.attribute_group_id where aovg.option_id=".$options['option_id'];
            $query_group =$this->db->query($sql_group);
            $res_group =$query_group->rows;
            $data_array[$key]['option']=$options;
            $data_array[$key]['attribute_group']=$res_group;
        }
		return $data_array;
	}

	public function getTotalOptionAttributesGroups() {
		$query = $this->db->query("SELECT COUNT(distinct option_id) AS total FROM " . DB_PREFIX . "attribute_option_value_to_group");

		return $query->row['total'];
	}
    
    public function getOptionAttributeCode($option_id){
        $query=$this->db->query("select a.attribute_code from ".DB_PREFIX . "new_attribute_option as ao left join ".DB_PREFIX . "new_attribute as a on ao.attribute_id=a.attribute_id where ao.option_id=".$option_id);
        return $query->row;
    }

    public function getOptionValue($option_id){
        $query=$this->db->query("select option_value from ".DB_PREFIX . "new_attribute_option_value where option_id=".$option_id." and language_id=".(int)$this->config->get('config_language_id'));
        return $query->row;
    }
}
?>