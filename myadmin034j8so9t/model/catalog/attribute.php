<?php 
class ModelCatalogAttribute extends Model {
	public function addAttribute($data) {
		$value_type = $data['value_type'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute SET attribute_code ='".$this->db->escape($data['attribute_code'])."', sort_order = '" . (int)$data['sort_order'] . "',value_type='".$this->db->escape($data['value_type'])."',`unit` = '".$this->db->escape($data['unit'])."' ");
		$attribute_id = $this->db->getLastId();
		


		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}


		if($value_type == 'radio' || $value_type == 'option'){
			if(!empty($data['attribute_option'])){
				foreach($data['attribute_option']['value'] as $order =>$attribute_option){
					$order = $data['attribute_option']['order'][$order];

					$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option(attribute_id,sort_order) values($attribute_id,$order)");
					$option_id = $this->db->getLastId();
					foreach($attribute_option as $key =>$value){
						$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option_value(option_id,language_id,option_value) values($option_id,$key,'$value')");
					}

				}
			}
		}
		return $attribute_id;

	}

	public function editAttribute($attribute_id, $data) {
		$value_type = $data['value_type'];
		$this->db->query("UPDATE " . DB_PREFIX . "new_attribute SET attribute_code = '" . $this->db->escape($data['attribute_code']) . "', sort_order = '" . (int)$data['sort_order'] . "',value_type='".$this->db->escape($value_type)."',unit = '".$this->db->escape($data['unit'])."' WHERE attribute_id = '" . (int)$attribute_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
		
		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		if($value_type == 'radio' || $value_type == 'option') {
			if (!empty($data['attribute_option'])) {
				foreach ($data['attribute_option']['value'] as $key => $attribute_option) {
					//索引不为数字，代表新增属性项
					if (!is_numeric($key)) {
						$order = $data['attribute_option']['order'][$key];
						$order = intval($order);

						$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option(attribute_id,sort_order) values($attribute_id,$order)");
						$option_id = $this->db->getLastId();
						foreach ($attribute_option as $key2 => $value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "new_attribute_option_value(option_id,language_id,option_value) values($option_id,$key2,'" . $this->db->escape($value) . "')");
						}


					} //索引为数字，代表更新属性项
					else if (is_numeric($key)) {
						$order = $data['attribute_option']['order'][$key];

						$this->db->query("UPDATE " . DB_PREFIX . "new_attribute_option set sort_order =$order  WHERE option_id = '" . (int)$key . "'");
						foreach ($attribute_option as $key2 => $value) {
							$this->db->query("UPDATE " . DB_PREFIX . "new_attribute_option_value set option_value ='" . $this->db->escape($value) . "'  WHERE option_id = '" . (int)$key . "' and language_id ='" . $key2 . "'");
						}
					}

				}
			}
		}else{
			//$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_option WHERE attribute_id='".(int)$attribute_id."'");
			//$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_option_value WHERE attribute_id='".(int)$attribute_id."'");
		}
	}
    
    public function deleteOptionValue($option_id){
        $this->db->query("Delete from " . DB_PREFIX . "new_attribute_option_value  WHERE option_id = " . (int)$option_id );
        $this->db->query("Delete from " . DB_PREFIX . "new_attribute_option  WHERE option_id = " . (int)$option_id );
    }

	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
		$sql = "select option_id from ". DB_PREFIX . "new_attribute_option where attribute_id = '" . (int)$attribute_id . "'";
		$query = $this->db->query($sql);
		foreach($query->rows as $row){
			$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_option_value WHERE option_id = '" . (int)$row['option_id'] . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_option WHERE attribute_id = '" . (int)$attribute_id . "'");
	}

	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "new_attribute a LEFT JOIN " . DB_PREFIX . "new_attribute_description ad ON (a.attribute_id = ad.attribute_id)WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getAttributes($data = array()) {
		$sql = "SELECT a.*,ad.* FROM " . DB_PREFIX . "new_attribute a LEFT JOIN " . DB_PREFIX . "new_attribute_description ad ON (a.attribute_id = ad.attribute_id)
		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			//$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);	
        $sql .= " Group BY a.attribute_code";	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ad.name";
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
		$res =array();
		foreach($query->rows as $key=>$row){
			$res[$row['attribute_id']]['attribute_id'] =$row['attribute_id'];
			$res[$row['attribute_id']]['name'] =$row['name'];
			$res[$row['attribute_id']]['attribute_code'] =$row['attribute_code'];
			$res[$row['attribute_id']]['sort_order'] =$row['sort_order'];
			$res[$row['attribute_id']]['value_type'] =$row['value_type'];
            $attribute_group_code_info =$this->getAttributeGroupById($row['attribute_id']);
            $attribute_group_code_str =array();
            foreach($attribute_group_code_info as $a_info){
                $attribute_group_code_str []=$a_info['attribute_group_code'];
            }
			$res[$row['attribute_id']]['attribute_group_code'] =$attribute_group_code_str;
			
		}
		return $res;
	}
    

    public function getAttributeGroupById($attribute_id){
        $sql ="select ag.attribute_group_id,ag.attribute_group_code from ".DB_PREFIX . "attribute_to_group as atg left join ".DB_PREFIX . "attribute_group as ag on atg.attribute_group_id=ag.attribute_group_id where atg.attribute_id=".$attribute_id;
        $query=$this->db->query($sql);
        return $query->rows;

    }
	public function getAttributeDescriptions($attribute_id) {
		$attribute_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "new_attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($query->rows as $result) {
			$attribute_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_data;
	}
	public function getAttributeOptions($attribute_id) {
		$attribute_option_data = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "new_attribute_option ao left join ".DB_PREFIX . "new_attribute_option_value ov on ao.option_id =ov.option_id WHERE ao.attribute_id = '" . (int)$attribute_id . "' order by ov.language_id,ov.option_id ASC";
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$attribute_option_data[$result['option_id']][$result['language_id']] = $result;
		}
		
		return $attribute_option_data;
	}

	
	public function getAttributeOptionsByLanguage($attribute_id) {
		$attribute_option_data = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "new_attribute_option ao left join ".DB_PREFIX . "new_attribute_option_value ov on ao.option_id =ov.option_id WHERE ao.attribute_id = '" . (int)$attribute_id . "' and ov.language_id='".(int)$this->config->get('config_language_id')."' order by ov.language_id ASC,ov.option_id ASC";
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$attribute_option_data[] = $result;
		}
		
		return $attribute_option_data;
	}
	

	public function getAttributesByAttributeGroupId($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "new_attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "new_attribute a LEFT JOIN " . DB_PREFIX . "new_attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ad.name";	
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

	public function getTotalAttributes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_attribute");

		return $query->row['total'];
	}	

	//得到属性组下的属性总数
	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		return $query->row['total'];
	}	
    
    public function getGroupFilter($group_id,$attribute_id){
        $sql = "select * from oc_attribute_group_filter  gf left join oc_attribute_group_filter_description gfd on gfd.filter_id = gd.filter_id where gf.group_id = '{$group_id}' and gf.filter_id = '{$filter_id}' order by gf.sort_order ";
        $query = $this->db->query($sql);
       
    }
    public function getFiltetOption($fileter_id){
        $sql = "select * from oc_attribute_group_filter_option where filter_id = '{$filter_id}'";
    }
}
?>