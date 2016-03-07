<?php 
class ModelCatalogAttributeGroup extends Model {
	public function addAttributeGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET attribute_group_code = '".$this->db->escape($data['attribute_group_code'])."',sort_order = '" . (int)$data['sort_order'] . "'");

		$attribute_group_id = $this->db->getLastId();


		if($data['group_attribure']){
			foreach($data['group_attribure'] as $k => $attr){
				$attribute_id = $attr['attribute_id'];
				$sort = $attr['sort'];
				$status = $attr['status'];
				$filter_type = $attr['filter_type'];


				$attribute_sql = "SELECT * FROM " . DB_PREFIX . "new_attribute WHERE attribute_id = '{$attribute_id}'";
				$attribute = $this->db->query($attribute_sql);
				$attribute = $attribute->row;

				if($attribute['value_type'] != "text"){
					if($attribute['value_type'] == "numerical"){
						$filter_type = 2;
					}else if($attribute['value_type'] == "radio" || $attribute['value_type'] == "option"){
						$filter_type = 1;
					}
					$attribute_to_group_sql = "INSERT INTO ".DB_PREFIX."attribute_to_group(attribute_id,attribute_group_id,sort_order,	filter_type,status) value('{$attribute_id}','{$attribute_group_id}','{$sort}','{$filter_type}','{$status}')";
					$this->db->query($attribute_to_group_sql);
				}




			}

		}

		//$clear_price_sql = "DELETE FROM  ".DB_PREFIX."attribute_group_price_filter where group_id = '{$attribute_group_id}'";
		//$this->db->query($clear_price_sql);
		$price_range_sort = $data['price_range_sort'];
		foreach($price_range_sort as $_k => $_item){
			$sort  = $_item;
			$start = $data['price_range_start'][$_k];
			$end   = $data['price_range_end'][$_k];
			$sort = intval($sort);
			$start = intval($start);
			if(!empty($end)){
				$end = intval($end);
			}else{
				$end  = 'NULL';
			}

			if($sort>=0 && $start >=0 && $end > 0){
				$range_price_sql = "INSERT INTO ".DB_PREFIX."attribute_group_price_filter(group_id,start,end,sort_order) value('{$attribute_group_id}','{$start}','{$end}','{$sort}')";
				$this->db->query($range_price_sql);
			}else if($sort>=0 && $start >=0 && $end == 'NULL'){
				$range_price_sql = "INSERT INTO ".DB_PREFIX."attribute_group_price_filter(group_id,start,end,sort_order) value('{$attribute_group_id}','{$start}',NULL,'{$sort}')";
				$this->db->query($range_price_sql);
			}
		}

	}

	public function editAttributeGroup($attribute_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute_group SET attribute_group_code='".$this->db->escape($data['attribute_group_code']). "' WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		
        $attribute_group_id  = intval($attribute_group_id );
        foreach($data['attribute_sort'] as $_k => $_v){
            $sort = intval($_v);
            $attribute_id = intval($_k);
            $sql_attribute_sort = "update oc_attribute_to_group set sort_order = '{$sort}' where attribute_id='{$attribute_id}' and  attribute_group_id='{$attribute_group_id}'";
            $this->db->query($sql_attribute_sort);
        }

		foreach($data['attribute_status'] as $_k => $_v){
			$status = intval($_v);
			$attribute_id = intval($_k);
			$sql_attribute_status = "update oc_attribute_to_group set status = '{$status}' where attribute_id='{$attribute_id}' and  attribute_group_id='{$attribute_group_id}'";
			$this->db->query($sql_attribute_status);
		}

		if($data['group_attribure']){
			foreach($data['group_attribure'] as $k => $attr){
				$attribute_id = $attr['attribute_id'];
				$sort = $attr['sort'];
				$status = $attr['status'];

				$attribute_sql = "SELECT * FROM " . DB_PREFIX . "new_attribute WHERE attribute_id = '{$attribute_id}'";
				$attribute = $this->db->query($attribute_sql);
				$attribute = $attribute->row;

				if($attribute['value_type'] != "text"){
					if($attribute['value_type'] == "numerical"){
						$filter_type = 2;
					}else if($attribute['value_type'] == "radio" || $attribute['value_type'] == "option"){
						$filter_type = 1;
					}
					$attribute_to_group_sql = "INSERT INTO ".DB_PREFIX."attribute_to_group(attribute_id,attribute_group_id,sort_order,	filter_type,status) value('{$attribute_id}','{$attribute_group_id}','{$sort}','{$filter_type}','{$status}')";
					$this->db->query($attribute_to_group_sql);
				}




			}

		}

		$clear_price_sql = "DELETE FROM  ".DB_PREFIX."attribute_group_price_filter where group_id = '{$attribute_group_id}'";
		$this->db->query($clear_price_sql);
		$price_range_sort = $data['price_range_sort'];
		foreach($price_range_sort as $_k => $_item){
			$sort  = $_item;
			$start = $data['price_range_start'][$_k];
			$end   = $data['price_range_end'][$_k];
			$sort = intval($sort);
			$start = intval($start);
			if(!empty($end)){
				$end = intval($end);
			}else{
				$end  = 'NULL';
			}

			if($sort>=0 && $start >=0 && $end > 0){
				$range_price_sql = "INSERT INTO ".DB_PREFIX."attribute_group_price_filter(group_id,start,end,sort_order) value('{$attribute_group_id}','{$start}','{$end}','{$sort}')";
				$this->db->query($range_price_sql);
			}else if($sort>=0 && $start >=0 && $end == 'NULL'){
				$range_price_sql = "INSERT INTO ".DB_PREFIX."attribute_group_price_filter(group_id,start,end,sort_order) value('{$attribute_group_id}','{$start}',NULL,'{$sort}')";
				$this->db->query($range_price_sql);
			}
		}



	}

	public function deleteAttributeGroup($attribute_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "new_attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
	}

	public function getAttributeGroup($attribute_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$group = $query->row;




		return $group;
	}

	public function getGroupAttribute($attribute_group_id,$attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "' AND attribute_id = '".(int)$attribute_id."'");
		return  $query->row;

	}


	public function getAttributeToGroup($attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->rows;
	}

	public function getAttributeByGroup($attribute_group_id) {
		$query = $this->db->query("SELECT distinct(attribute_id) FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		return $query->rows;
	}
    public function getAttributeListByGroup($attribute_group_id){
        //取得对应的attribute
        $attribute_group_id = intval($attribute_group_id);
        $language_id = (int)$this->config->get('config_language_id');
        $sql_attribute = "select  oa.attribute_id,oa.attribute_code,ag.sort_order ,ad.name,ag.filter_type,ag.status
FROM oc_new_attribute oa 
left join oc_new_attribute_description ad on ad.attribute_id = oa.attribute_id
left join oc_attribute_to_group ag on ag.attribute_id = oa.attribute_id
where ag.attribute_group_id = '{$attribute_group_id}'  and  ad.language_id = '{$language_id}'
order by ag.sort_order asc ";

        $query = $this->db->query($sql_attribute);
        return $query->rows;
    }

	public function getAttributeByGroupIn($in_attribute_group_id) {
		$query = $this->db->query("SELECT distinct(attribute_id) FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id in (".$in_attribute_group_id.")");
		return $query->rows;
	}
	public function getAttributeGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_group ag";

		$sort_data = array(
			'ag.attribute_group_code',
			'ag.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ag.sort_order";	
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

	/*
	public function getAttributeGroupDescriptions($attribute_group_id) {
		$attribute_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "new_attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($query->rows as $result) {
			$attribute_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_group_data;
	}
	*/
	public function getTotalAttributeGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_group");

		return $query->row['total'];
    }
    
    public function getGroupPriceRange($group_id){
        $sql = "select * from oc_attribute_group_price_filter where group_id = '{$group_id}' order by sort_order asc";
        $query = $this->db->query($sql);
        return $query->rows;
    }

	public function deleteAttributeGroupUnderAttribute($attribute_group_id,$attribute_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "' AND attribute_id='".$attribute_id."'");
    //@todo 添加删除关系的部分
		$attribute_sql = "SELECT * FROM " . DB_PREFIX . "new_attribute WHERE attribute_id = '{$attribute_id}'";
		$attribute = $this->db->query($attribute_sql);
		$attribute = $attribute->row;
		if($attribute['value_type'] == 'numerical'){
			$this->db->query("DELETE FROM  " . DB_PREFIX . "attribute_group_numerical_range_filter WHERE attribute_group_id = '{$attribute_group_id}' AND attribute_id = '{$attribute_id}' ");
		}


	}

	public function getGroupAttributeNumericalRangeFilter($attribute_group_id , $attribute_id){
		$numerical_range_sql = "SELECT *  FROM  ".DB_PREFIX."attribute_group_numerical_range_filter where attribute_group_id = '{$attribute_group_id}' AND attribute_id = '{$attribute_id}' ORDER BY sort_order asc";
		$query = $this->db->query($numerical_range_sql);
		return $query->rows;
	}

	public function addGroupAttributeNumericalRangeFilter($attribute_group_id,$attribute_id,$data){
		$clear_numerical_range_sql = "DELETE FROM  ".DB_PREFIX."attribute_group_numerical_range_filter where attribute_group_id = '{$attribute_group_id}' AND attribute_id = '{$attribute_id}' ";
		$this->db->query($clear_numerical_range_sql);
		$numerical_range_sort = $data['numerical_range_sort'];
		foreach($numerical_range_sort as $_k => $_item){
			$sort  = $_item;
			$start = $data['numerical_range_start'][$_k];
			$end   = $data['numerical_range_end'][$_k];
			$sort = intval($sort);
			$start = intval($start);
			if(!empty($end)){
				$end = intval($end);
			}else{
				$end  = 'NULL';
			}

			if($sort>=0 && $start >=0 && $end > 0){
				$numerical_range_sql = "INSERT INTO ".DB_PREFIX."attribute_group_numerical_range_filter(attribute_group_id,attribute_id,start,end,sort_order) value('{$attribute_group_id}','{$attribute_id}','{$start}','{$end}','{$sort}')";
				$this->db->query($numerical_range_sql);
			}else if($sort>=0 && $start >=0 && $end == 'NULL'){
				$numerical_range_sql = "INSERT INTO ".DB_PREFIX."attribute_group_numerical_range_filter(attribute_group_id,attribute_id,start,end,sort_order) value('{$attribute_group_id}','{$attribute_id}','{$start}',NULL,'{$sort}')";
				$this->db->query($numerical_range_sql);
			}
		}
	}
}
?>