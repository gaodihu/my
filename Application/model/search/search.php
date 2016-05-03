<?php

class ModelSearchSearch extends Model {
    public function search($keyword, $cat_id = array(),$attribute_arr = array(), $order_by = array(), $from = 0, $size = 99999,$price_range=array()) {
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        $hash = md5($keyword);
        $first = substr($keyword, 0, 1);
        $first = strtoupper($first);
        
        $result_set = $search->search($keyword, $cat_id,$attribute_arr, $order_by, $from, $size,array('product_id'),$price_range,1);
        $total_hits = $result_set['hits']['total'];


        $sql = "select query_id,created_at from " . DB_PREFIX . "ela_search_query where key_hash = '" . $this->db->escape($hash) . "' and language_id = '" . $this->config->get('config_language_id') . "'  limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $query_id = $query->row['query_id'];
            //更新查询次数
            $sql_query_u = "update  " . DB_PREFIX . "ela_search_query set popularity = popularity + 1,num_results= '{$total_hits}' where query_id = '{$query_id}' ";
            $this->db->query($sql_query_u);
        } else {
            $sql_query = "INSERT INTO " . DB_PREFIX . "ela_search_query(query_text,num_results,popularity,language_id,is_active,created_at,key_hash,first) values  ";
            $sql_value = "('" . $this->db->escape($keyword) . "', '{$total_hits}','1','" . $this->config->get('config_language_id') . "','1',now(),'" . $this->db->escape($hash) . "','{$first}')";
            $sql_query = $sql_query . $sql_value;
            $this->db->query($sql_query);
            $query_id = $this->db->getLastId();
        }
        $kw_sql_query = "SELECT * FROM  " . DB_PREFIX . "ela_search_query where query_id = '{$query_id}'  ";
      
        $kw_query = $this->db->query($kw_sql_query);
        $kw_data = array();
        $kw_data[] = array(
            'query_id' => $kw_query->row['query_id'], 
            'query_text' => $kw_query->row['query_text'], 
            'num_results' => $kw_query->row['num_results'], 
            'popularity' => $kw_query->row['popularity'], 
            'is_active' => $kw_query->row['is_active'], 
            'created_at' => date('Y-m-d', strtotime($kw_query->row['created_at'])) . 'T' . date('m:i:s', strtotime($kw_query->row['created_at'])),
            'updated_at' => date('Y-m-d', strtotime($kw_query->row['updated_at'])) . 'T' . date('m:i:s', strtotime($kw_query->row['updated_at'])),
            'key_hash' => $kw_query->row['key_hash'], 
            'first' => $kw_query->row['first'], 
            );
        $search->addKeyword($kw_data);
        
        $results = $result_set['hits']['hits'];
        $product_data = array();
        $product_hightlight_name = array();
        foreach ($results as $item) {
           
            $product_data[] = $item['fields']['product_id'][0];
            
            $product_hightlight_name[$item['fields']['product_id'][0]] = $item['highlight']['name'][0];
        }
        return array('total' => $total_hits, 'data' => $product_data,'hightlight'=>$product_hightlight_name);
    }

    public function getSearchCategory($keyword, $cat_id = array(),$price_range=array()) {
        $from = 0;
        $size = 99999;
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        $hash = md5($keyword);
        $first = substr($keyword, 0, 1);
        $first = strtoupper($first);

        $result_set = $search->aggs($keyword, $cat_id, array(),$price_range);
        $total_hits = $result_set['hits']['total'];
        //var_dump($result_set);
        $results = $result_set['aggregations']['all_interests']['buckets'];

        $category_data = array();
        foreach ($results as $item) {
            $category_id = $item['key'];
            $doc_count = $item['doc_count'];
            $category_data[$category_id] = $doc_count;
            
        }
        return array('data' => $category_data);
    }

    public function suggest($keyword) {
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        
        $result_set = $search->suggest($keyword);

        $total_hits = $result_set['hits']['total'];

        $results = $result_set['hits']['hits'];
        $data = array();
        foreach ($results as $item) {
            $data[] = $item['fields']['query_text'][0];
        }
        return array('total' => $total_hits, 'data' => $data);
    }
    
    function getCategoryProductsNumber($keyword,$cat_id = array() , $attribute_arr = array(),$price_range=array()){
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        $result = $search->aggCategoryProductNumber($keyword, $cat_id , $attribute_arr,$price_range);
        //var_dump($result['count']);
        //die;
        $data = $result['aggregations']['group_by_category_ids']['buckets'];
        
        $category_product_number = array();
        foreach($data as $item ){
            $category_id = $item['key'];
            $number = $item['doc_count'];
            $category_product_number[$category_id] = $number;
        }
        return $category_product_number;
    }
    
    function getAttribute($keyword = '', $cat_id = array(),$attribute_arr = array(),$price_range=array()){
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
       
        $result_set = $search->aggsAttr($keyword, $cat_id,$attribute_arr,$price_range);
       
        $results = $result_set['aggregations']['group_by_attribute']['buckets'];
        $attribute_arr_data = array();
        foreach ($results as $item) {
            $key = $item['key'];
            $doc_count = $item['doc_count'];
            $group_by_value = $item['group_by_value']['buckets'];
            foreach($group_by_value as $_v_item){
                $_v_key = $_v_item['key'];
                $_v_doc_count = $_v_item['doc_count'];
                $attribute_arr_data[$key][$_v_key] = $_v_doc_count;
            }
        }
        return $attribute_arr_data;
    }
 
    
    function aggsPriceRange($keyword = '', $cat_id = array(),$attribute_arr = array(),$price_range=array(),$price_range_option=array()){
          global $ELASTICSEARCH_HOST;
          require_once DIR_SYSTEM . 'library/search.php';
          $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));

          $result_set = $search->aggsPriceRange($keyword, $cat_id,$attribute_arr,$price_range,$price_range_option);

          $results = $result_set['aggregations']['price_ranges']['buckets'];
          $attribute_arr_data = array();
          if($results && is_array($results)) {
            foreach ($results as $item) {
                $key = $item['key'];
                $doc_count = $item['doc_count'];
                $attribute_arr_data[$key] = $doc_count;

            }
          }
          return $attribute_arr_data;
      }
 
    
    
    public function getCategoryProduct($keyword, $cat_id = array(),$attribute_arr = array(), $order_by = array(), $from = 0, $size = 99999,$price_range=array()) {
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        $result_set = $search->search($keyword, $cat_id,$attribute_arr, $order_by, $from, $size,array('product_id'),$price_range);
        $total_hits = $result_set['hits']['total'];
        
        $results = $result_set['hits']['hits'];
        $product_data = array();
        foreach ($results as $item) {
            $product_data[] = $item['fields']['product_id'][0];
        }
        return array('number'=>$total_hits,'data'=>$product_data);
    }

    public function priceRang($keyword, $cat_id = array(),$attribute_arr = array()){
        global $ELASTICSEARCH_HOST;
        require_once DIR_SYSTEM . 'library/search.php';
        $search = new Search($ELASTICSEARCH_HOST, $this->config->get('config_language_id'), $this->config->get('config_store_id'));
        $result_set = $search->priceRang($keyword, $cat_id,$attribute_arr);
        
        return $result_set;
    }
}
