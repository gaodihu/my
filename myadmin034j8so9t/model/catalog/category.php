<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', hot = '".(isset($data['hot']) ? (int)$data['hot'] : 0)."',`top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW(),url_path='".$data['url_path']."'");

		$category_id = $this->db->getLastId();
		if($data['attrbute_group'] >0){
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group SET category_id ='".(int)$category_id."',attribute_group_id='".(int)$data['attrbute_group']."' ");
		}
		$parent_info =$this->getCategory((int)$data['parent_id']);
		if(empty($parent_info)){
			$parent_info['path'] ='0';
			$parent_info['level'] =0;
		}
		$this->db->query("UPDATE " . DB_PREFIX . "category SET path = '" . $parent_info['path'] . "/$category_id',level =$parent_info[level]+1  WHERE category_id = '" . (int)$category_id . "'");
		if (isset($data['bg_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET bg_image = '" . $this->db->escape(html_entity_decode($data['bg_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		if (isset($data['seo_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET seo_image = '" . $this->db->escape(html_entity_decode($data['seo_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		if (isset($data['small_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET small_image = '" . $this->db->escape(html_entity_decode($data['small_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "',title='".$this->db->escape($value['title'])."', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "',action_description = '" . $this->db->escape($value['action_description']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('category');
	}

	public function editCategory($category_id, $data) {
        if((int)$data['parent_id']){
            $path ='0/'.(int)$data['parent_id'].'/'.$category_id;
            $level =2;
        }else{
            $path ='0/'.$category_id;
            $level =1;
        } 
		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "',path='".$path."',level='".$level ."',hot = '".(isset($data['hot']) ? (int)$data['hot'] : 0)."', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(),url_path='".$data['url_path']."' WHERE category_id = '" . (int)$category_id . "'");

		if($data['attrbute_group'] >0){
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_group WHERE category_id = '" . (int)$category_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_attribute_group SET category_id ='".(int)$category_id."',attribute_group_id='".(int)$data['attrbute_group']."' ");
		}
		

		if (isset($data['bg_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET bg_image = '" . $this->db->escape(html_entity_decode($data['bg_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		if (isset($data['seo_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET seo_image = '" . $this->db->escape(html_entity_decode($data['seo_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		if (isset($data['small_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET small_image = '" . $this->db->escape(html_entity_decode($data['small_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "',title='".$this->db->escape($value['title'])."', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "',action_description = '" . $this->db->escape(isset($value['action_description'])?$value['action_description']:'') . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		
		//修改分类下商品的默认排序
		if(isset($data['pro_check'])&&$data['position']){
			$pro_edit_arr = array_combine($data['pro_check'],$data['position']);
			foreach ($pro_edit_arr as $key => $value){
				$this->db->query("UPDATE ". DB_PREFIX ."product_to_category SET position = $value where product_id=$key and category_id=".$category_id);
			}
		}
		$this->cache->delete('category');
	}
	

	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {	
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_attribute_group WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		$this->cache->delete('category');
	} 

	// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		//$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$sql = "SELECT  DISTINCT * FROM " . DB_PREFIX ."category as c LEFT JOIN ". DB_PREFIX ."category_description as cd ON c.category_id = cd.category_id
		left join ".DB_PREFIX."category_attribute_group as cag on cag.category_id= c.category_id 
		WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id')."'";
		$query =$this->db->query($sql);
		return $query->row;
	} 

/*
	public function getCategories($data) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";

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
	*/


	
	public function getCategoriesTree() {
		$sql = 'SELECT c.category_id,cd.name,c.parent_id,c.path,c.level,c.sort_order FROM '. DB_PREFIX ."category as c LEFT JOIN ". DB_PREFIX ."category_description as cd ON c.category_id=cd.category_id WHERE cd.language_id ='".(int)$this->config->get('config_language_id')."' order by c.sort_order asc";

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getCategories($data) {
		//$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		//得到所有一级分类
		$sql = 'SELECT c.category_id,cd.name,c.parent_id,c.path,c.level,c.sort_order FROM '. DB_PREFIX ."category as c LEFT JOIN ". DB_PREFIX ."category_description as cd ON c.category_id=cd.category_id WHERE cd.language_id ='".(int)$this->config->get('config_language_id')."' and c.level =1";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		//$sql .= " GROUP BY cp.category_id ORDER BY name";
		$query = $this->db->query($sql);
		$row = $query->rows;
		$res =array();
		foreach($row  as  $key=>$cat){
				$cat['child'] = $this->getChildCategory($cat['category_id'],2);
				foreach($cat['child'] as $k =>$v ){
					$v['child'] = $this->getChildCategory($v['category_id'],$cat['category_id'],3);
					$cat['child'][$k]=$v;
				}
			$row[$key] =$cat;
		}
		return $row;
	}

    public function getDetailCategories($data) {
		//$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		//得到所有一级分类
		$sql = 'SELECT c.category_id,cd.name,c.parent_id,c.path,c.level,c.sort_order FROM '. DB_PREFIX ."category as c LEFT JOIN ". DB_PREFIX ."category_description as cd ON c.category_id=cd.category_id WHERE cd.language_id ='".(int)$this->config->get('config_language_id')."' ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		//$sql .= " GROUP BY cp.category_id ORDER BY name";
		$query = $this->db->query($sql);
		return  $query->rows;
	}
	
	/*
	*得到分类下的子分类
	*$category_id  分类ID
	*$level  所取分类level等级
	*/
	public function getChildCategory($category_id,$parent_id=0,$level=2){
		$this->getSubCat($category_id);
		$sql = 'SELECT c.category_id,cd.name,c.parent_id,c.path,c.level,c.sort_order FROM '. DB_PREFIX ."category as c LEFT JOIN ". DB_PREFIX ."category_description as cd ON c.category_id=cd.category_id WHERE cd.language_id ='".(int)$this->config->get('config_language_id')."' and c.level =".$level;
		if($level ==2){
		$sql .= " and c.path like '0/$category_id/%' ";
		}
		elseif($level ==3){
			$sql .= " and c.path like '0/$parent_id/$category_id/%' ";
		}
		$query = $this->db->query($sql);
		return $query->rows;

	}
	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
                'title'     => $result['title'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description'],
				'action_description'      => $result['action_description']
			);
		}

		return $category_description_data;
	}	

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}	

	public function getTotalCategoriesByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}		


	//得到分类树的json数据
	/* $open 树形结构是否打开
		$select_id 被选中的ID
	*/
	
	public function getCatTreeJson($open='true',$show_count=true){
		$cat_tree = $this->getCategoriesTree();
		$tree_array = array(); 
		foreach ($cat_tree as $cat){
			$cat['name']=strip_tags(html_entity_decode($cat['name'], ENT_QUOTES, 'UTF-8'));
			$pro_count = $this->getTotalCatPro($cat['category_id']);
			if($show_count){
				$str = "{id:$cat[category_id], pId:$cat[parent_id], name:\"$cat[name]($pro_count)\" , open:$open,drag:true}";
			}
			else{
				$str = "{id:$cat[category_id], pId:$cat[parent_id], name:\"$cat[name]\" , open:$open,drag:true}";
			}
			
			$tree_array[] = $str;
		}
		$json_data = json_encode($tree_array);
		//$this->response->setOutput($json_data);
		return $json_data;
	}
	
	//得到分类下的子分类,$parent_id 当前分类的父分类 $level 当前分类LEVEL
	public function getSubCat($category_id){
		$sql_path = "select path from " . DB_PREFIX . "category  where category_id = $category_id";
		$query = $this->db->query($sql_path);
		$path = $query->row['path'];
		$sql = "select category_id from ". DB_PREFIX . "category ";
		$sql .=" where path like '$path%' ";
		$query = $this->db->query($sql);
		$str ='in (';
		foreach ($query->rows as $result) {
		$str .=" $result[category_id],";
		}
		$str =substr($str,0,strlen($str)-1);
		$str .=')';
		return $str;
	}
	//得到分类下的商品总数
	public function getTotalCatPro($category_id,$filter=array()){
        $sql ="select COUNT(DISTINCT pc.product_id) as total from ".DB_PREFIX."product_to_category pc left join ".DB_PREFIX."product_description pd on pc.product_id=pd.product_id left join ".DB_PREFIX."product p on p.product_id =pc.product_id";
       
        //$where =" where pc.category_id ".$this->getSubCat($category_id);
		$where =" where pc.category_id =".$category_id;
        if(isset($filter['filter_sku'])&&$filter['filter_sku']){
            $where .=" AND p.model='".$filter['filter_sku']."'";
        }
         if(isset($filter['filter_name'])&&$filter['filter_name']){
            $where .=" AND pd.name like '%". $this->db->escape($filter['filter_name'])."%' ";
        }
        $sql .=$where;
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	//得到分类下的商品信息
	public function getCatPro($data){
		$sql ="select distinct(p.product_id),pd.name,p.model,p.price,pc.position from ".DB_PREFIX."product p left join ".DB_PREFIX."product_description pd on p.product_id=pd.product_id
		left join ".DB_PREFIX."product_to_category pc on p.product_id=pc.product_id ";
        //$where =" where pc.category_id  ".$this->getSubCat($data['category_id'])." and pd.language_id='".(int)$this->config->get('config_language_id')."'";
        $where =" where pc.category_id = ".$data['category_id']." and pd.language_id='".(int)$this->config->get('config_language_id')."'";
        if(isset($data['filter_sku'])&&$data['filter_sku']){
            $where .=" AND p.model='".$data['filter_sku']."'";
        }
         if(isset($data['filter_name'])&&$data['filter_name']){
            $where .=" AND pd.name like '%". $this->db->escape($data['filter_name'])."%' ";
        }
        $sql .=$where;
        if(isset($data['sort_name'])){
            $sql .="order by  ".$data['sort_name']." ";
        }
        else{
            $sql .="order by p.product_id ";
        }
        if(isset($data['sort_order'])){
            $sql .=$data['sort_order'];
        }
        else{
            $sql .=" DESC";
        }
		if(!isset($data['limit'])){
			$data['limit'] =$this->config->get('config_admin_limit');
		}
		if(!isset($data['page'])){
			$data['page'] =1;
		}
		$start =($data['page']-1)*$data['limit'];
		$sql.= " limit $start,".$data['limit'];
		$query = $this->db->query($sql);
		return $query->rows;
	}
}
?>