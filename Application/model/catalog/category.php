<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
        
		return $query->rows;
	}

	public function getHotCatalogs($limit){
		$hot_catalog =array();
		//得到热销的一级分类
		$query_top = $this->db->query("SELECT c.category_id,cd.name,c.small_image as image,cd.description FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '0' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.hot =1 ORDER BY c.sort_order, LCASE(cd.name) limit ".$limit);
		//循环得到下属的热销子分类
		foreach ($query_top->rows as $catalog){
            $child_cat_array =array();
			$catalog['url'] = $this->url->link('product/category', 'path=' . $catalog['category_id']);
			$query_chid = $this->db->query("SELECT DISTINCT c.category_id,cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$catalog['category_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1' and c.hot=1");
			
			foreach ($query_chid->rows as $child){
				$child['url'] = $this->url->link('product/category', 'path=' . $catalog['category_id']."_".$child['category_id']);
				$child_cat_array[]=$child;
			}
			$hot_catalog[$catalog['category_id']]=$catalog;
			$hot_catalog[$catalog['category_id']]['child']=$child_cat_array;
		}
		return $hot_catalog;
	}
	
	public function getCategoryFilters($category_id) {
		$implode = array();
		
		$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}
		
		
		$filter_group_data = array();
		
		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");
			
			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();
				
				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
				
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']			
					);
				}
				
				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);	
				}
			}
		}
		
		return $filter_group_data;
	}
				
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}
					
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	//得到分类对应所有属性及值
	public function getCategoriesAttrbuteGroup($group_id,$category_id,$select_attr_array=array(),$attr_id='',$option_id='',$price_range=array(),$price_range_option=array()) {
        $cache_key = serialize($category_id) . serialize($select_attr_array) . serialize($attr_id) .serialize($option_id);
        $cache_key = md5($cache_key);
        $cache = $this->cache->get('categories-attribute-group-'.$cache_key);
        if($cache){
            //return $cache;
        }
        $CatalogAttrGroup = array();
        if(ELASTICSEARCH_CATRGORY_ENABLE){
            $CatalogAttrGroup =  $this->getCategoriesAttrbuteGroupFromElasticsearch($group_id,'',$category_id,$select_attr_array,$attr_id,$option_id,$price_range,$price_range_option);
        }
        $this->cache->set('categories-attribute-group-'.$cache_key,$CatalogAttrGroup);
        return $CatalogAttrGroup;
	}

	public function getOptionInfo($option_id) {
		$sql ="select ao.option_id,ao.attribute_id,opv.option_value from ".DB_PREFIX."new_attribute_option as ao left join ".DB_PREFIX."new_attribute_option_value as opv on ao.option_id=opv.option_id where ao.option_id=".$option_id." and opv.language_id='".(int)$this->config->get('config_language_id')."' ";
		$query =$this->db->query($sql);
		return $query->row;
	}
    
    public function getCategoryUrl($category_id){
        $cache = $this->cache->get('categories-url-'.$category_id );
        if($cache){
            return $cache;
        }else{
            $sql ="select url_path from ".DB_PREFIX."category  where category_id = '{$category_id}'";
            $query =$this->db->query($sql);
            if($query->row){
                $this->cache->set('categories-url-'.$category_id,$query->row['url_path']);
                return $query->row['url_path'];
            }
        }
	     return false;
    }
    public function getCategoryByUrl($url_path){
        $url_path =  $this->db->escape($url_path);
        $sql ="select `category_id`,`path` from ".DB_PREFIX."category  where url_path = '{$url_path}'";
		$query =$this->db->query($sql);
		return $query->row; 
    }
    //得到分类的子分类，并组装厂(325,3464,57)形式
    public function getCatgoryInStr($parent_id){
        $data =array();
        $data[] =$parent_id;
        $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "' AND status = '1' ");
		foreach($query->rows as $cat_id){
            $data[] = $cat_id['category_id'];
        }
        return '('.implode(',',$data).")";
    }
    
    
    //得到分类对应所有属性及值
	public function getCategoriesAttrbuteGroupFromElasticsearch($group_id,$keyword,$category_id,$select_attr_array=array(),$attr_id='',$option_id='',$price_range=array(),$price_range_option=array()) {
        
        $attribute_group_id = $group_id;
        
        $this->load->model('search/search');
        
        $all_category_arr = $this->getCategoryPathId($category_id);
        
        
        $attr_option_arr = $this->getAttrOptionParam($attr_id,$option_id);
        //filter对应成option
        $attr_option_arr = $this->getAttrFilterOptionParam($attribute_group_id,$attr_option_arr);
        $category_attribute_data = $this->model_search_search->getAttribute($keyword,$all_category_arr,$attr_option_arr,$price_range);
        //print_r($category_attribute_data);
        
		$CatalogAttrGroup = array();
        $in_str = implode(',',$select_attr_array);
        if(!empty($in_str)){
            $not_in_str =" and a.attribute_id not in(".$in_str .")";
        }
        else{
            $not_in_str='';
        }

           
        //得到属性列表
        $sql = "select a.attribute_id,a.attribute_code,ad.name,atg.filter_type,atg.sort_order from " . DB_PREFIX . "new_attribute as a "
            . "left join ".DB_PREFIX."new_attribute_description as ad on a.attribute_id=ad.attribute_id "
            . "left join ".DB_PREFIX."attribute_to_group as atg on a.attribute_id=atg.attribute_id "
            . "where  ad.language_id='".(int)$this->config->get('config_language_id')."' and atg.attribute_group_id=".$attribute_group_id ." and atg.status =1 ".$not_in_str." order by atg.sort_order ASC,atg.attribute_id asc";
        //echo $sql . '<br/>';
        $query_attr = $this->db->query($sql);
        $attribute_info = $query_attr->rows;
        $attr_option = array();
        foreach($attribute_info as $key => $attribute){
            $filter_type = $attribute['filter_type'];
            $attribute_id = $attribute['attribute_id'];
            if(isset($category_attribute_data[$attribute_id])&& $category_attribute_data[$attribute_id]){
                $cur_value_data = $category_attribute_data[$attribute_id];

                arsort($cur_value_data,SORT_NUMERIC );
                $value_id_arr = array_keys($cur_value_data);
                $value_id_str = implode(',',$value_id_arr);
            }
            
            if($filter_type == 1){


                //print_r($cur_value_data);

                $sql_option ="select ao.option_id,ao.sort_order,aov.value_id,aov.option_value from ". DB_PREFIX ."new_attribute_option as ao left join ".DB_PREFIX."new_attribute_option_value as aov on ao.option_id=aov.option_id   where ao.attribute_id=".$attribute['attribute_id']."  and aov.language_id='".(int)$this->config->get('config_language_id')."' order by ao.sort_order ASC ";

                 //echo $sql_option;

                $query_attr_option = $this->db->query($sql_option);
                $attribute_option_info = $query_attr_option->rows;
                 //print_r($attribute_option_info);
                $attribute_option_data = array();
                if(isset($cur_value_data) &&is_array($cur_value_data)){
                    foreach($cur_value_data as $_vk => $_item){
                         foreach($attribute_option_info as $row){
                             if($row['option_id'] == $_vk){
                                 $attribute_option_data[] = $row;
                             }
                         }
                    }
                }
                if(!empty($attribute_option_data)){
                     $attr_option[$key] = $attribute;
                     $attr_option[$key]['option'] = $attribute_option_data;
                }
            }else if($filter_type == 2){
                $attribute_id = $attribute['attribute_id'];

                $filter_sql = "SELECT * FROM ".DB_PREFIX."attribute_group_numerical_range_filter WHERE attribute_group_id	 = '".$attribute_group_id."'  AND attribute_id='".$attribute['attribute_id']."' ORDER BY sort_order  ASC ";

                $filter_query = $this->db->query($filter_sql);
                 
                 if($filter_query->rows){
                     $attr_option[$key] = $attribute;
                     $attr_option[$key]['option'] = $filter_query->rows;
                }
          }

        }
        $CatalogAttrGroup[] = $attr_option;
		
        
		return $CatalogAttrGroup;
	}

   	public function getCategoriesPriceRangeFromElasticsearch($group_id,$keyword,$category_id,$select_attr_array=array(),$attr_id='',$option_id='',$price_range=array(),$price_range_option=array()) {
        

        $attribute_group_id = $group_id;
        
        $this->load->model('search/search');
        
        $all_category_arr = $this->getCategoryPathId($category_id);
        
        
        $attr_option_arr = $this->getAttrOptionParam($attr_id,$option_id);
        //filter对应成option
        $attr_option_arr = $this->getAttrFilterOptionParam($attribute_group_id,$attr_option_arr);
        $category_attribute_data = $this->model_search_search->aggsPriceRange($keyword,$all_category_arr,$attr_option_arr,$price_range,$price_range_option);
        //print_r($category_attribute_data);
        
		return $category_attribute_data;
	}

   
    
    public function getCategoryPathId($category_id){
        static $all_category_arr;
        if(!$all_category_arr){
            $all_category_arr = array();
            if($category_id){
                $query_path = $this->db->query("select path from " . DB_PREFIX ."category where category_id='".$category_id."'");
                $path = $query_path->row['path'];
                //echo "select path from " . DB_PREFIX ."category where category_id='".$category_id."'"."<br/>";
                $all_category_sql = "select distinct category_id from " .DB_PREFIX."category where `path` like '{$path}%' and status = 1" ;
                $all_category_query = $this->db->query($all_category_sql);
                //echo $all_category_sql."<br/>";
                foreach($all_category_query->rows as $row){
                    $all_category_arr[] = $row['category_id'];
                }
            }
        }
        return $all_category_arr;
    }

  
    public function getAttrOptionParam($attr_id= '',$option_id=''){
        $data = array(
            'attr_id' => $attr_id,
            'option_id' => $option_id,
        );
        $attribute_arr = array();
        if(isset($data['attr_id'])  && $data['attr_id'] && isset($data['option_id']) && $data['option_id']){
            $attr_id_array=array();
            $attr_str ='';
			if(strpos($data['attr_id'],'-')!==false){
				$attr_id_array =explode('-',$data['attr_id']);
			}
			else{
				$attr_id_array[] =$data['attr_id'];
			}
            $option_id_array=array();
			if(strpos($data['option_id'],'-')!==false){
				$option_id_array=explode('-',$data['option_id']);
			}
			else{
				$option_id_array[] =$data['option_id'];
			}
            if(count($attr_id_array)){
                for($i=0;$i<count($attr_id_array);$i++){
                    $attribute_arr[$attr_id_array[$i]] = array(
                        $option_id_array[$i]
                    );
                }
            }
        }
        return $attribute_arr;
      
     }
    
     
    public function getAttrFilterOptionParam($group_id,$attribute_arr){
        $result_data = array();
        foreach($attribute_arr as $attribute_id=>$option_arr){
            $group_attribute_query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "attribute_to_group WHERE attribute_group_id = '{$group_id}' AND attribute_id = '{$attribute_id}' and status = 1 ");
            if($group_attribute_query->row){
                $filter_type  = $group_attribute_query->row['filter_type'];
                if($filter_type == 2){

                }else{
                    $result_data[$attribute_id] = $option_arr;
                }
            }
       }
         return $result_data;
    }
    
    function getGroupAttributeFilterType($group_id,$attribute_id){
        $sql = "select filter_type from oc_attribute_to_group where  attribute_group_id = '{$group_id}' and attribute_id = '{$attribute_id}' ";
        $query = $this->db->query($sql);
        if($query->row){
            return $query->row['filter_type'];
        } else {
            false;
        }
   }
   
       
    function getFilterName($filter_id){
        $sql = "select * from oc_attribute_group_filter_description where  filter_id = '{$filter_id}' and language_id = '".(int)$this->config->get('config_language_id') ."'";
        $query = $this->db->query($sql);
        if($query->row){
            return $query->row;
        } else {
            false;
        }
   }
   
    function getGroupId($category_id){
        $query = $this->db->query("SELECT attribute_group_id  FROM " . DB_PREFIX . "category_attribute_group WHERE category_id = '" . (int)$category_id . "'");
        if(!$query->row){
            return false;
        }
        $group_id = $query->row['attribute_group_id'];
        return $group_id;
    }
    function getPriceRange($group_id){
        $sql = "select * from oc_attribute_group_price_filter where group_id = '{$group_id}' order by sort_order asc";
        $query = $this->db->query($sql);
        if(!$query->rows){
            return false;
        }
        return $query->rows;
    }
    function getPriceRangeValue($group_id,$price_range_id){
        $sql = "select * from oc_attribute_group_price_filter where group_id = '{$group_id}' and  pf_id = '{$price_range_id}' order by sort_order asc";
        $query = $this->db->query($sql);
        if(!$query->row){
            return false;
        }
        return $query->row;
    }

    //得到商品的英语名称
    public function get_category_en_name($category_id){
        $sql = "select name from ".DB_PREFIX."category_description where category_id = '{$category_id}' and language_id=1 ";
        $query = $this->db->query($sql);
        if($query->num_rows){
            $row = $query->row;
            return $row['name'];
        }
        return false;
    } 
}
?>

