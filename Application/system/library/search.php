<?php

require_once DIR_SYSTEM . 'lib/Elasticsearch/vendor/autoload.php';

class Search {

    const PRE_INDEX = 'mk_';

    private $hosts;
    private $index;
    private $language_id;
    private $store_id;
    private $db;
    protected $client;

    function __construct($hosts, $language_id, $store_id) {

        $this->language_id = $language_id;
        $this->store_id = $store_id;
        $this->index = self::PRE_INDEX . $this->language_id . '_' . $this->store_id;
        $this->hosts = $hosts;
        $this->client = $this->getClient();
    }

    function getDb() {
        $host = DB_HOSTNAME;
        $dbname = DB_DATABASE;
        $user = DB_USERNAME;
        $passwd = DB_PASSWORD;
        if (!$this->db) {
            $db = mysqli_connect($host, $user, $passwd, $dbname);
            mysqli_select_db($db,$dbname);
            mysqli_query($db,"set names utf8;");
            $this->db = $db;
        }
        return $this->db;
    }

    function getLang() {
        $language_id = $this->language_id;
        $db = $this->getDb();
        $sql = "select * from oc_language where language_id = '{$language_id}'";
        $rs = mysqli_query($db,$sql );
        $row = mysqli_fetch_assoc($rs);
        $language_code = $row['code'];
        $language_code = strtoupper($language_code);
        $lang_code_arr = array(
            'EN' => 'English',
            'DE' => 'German',
            'FR' => 'French',
            'ES' => 'Spanish',
            'IT' => 'Italian',
            'PT' => 'Portuguese',
        );
        if (isset($lang_code_arr[$language_code])) {
            return $lang_code_arr[$language_code];
        }
        return false;
    }

    function getClient() {
        if (!$this->client) {
            $params = array();
            $params['hosts'] = $this->hosts;
            $client = new Elasticsearch\Client($params);
            $this->client = $client;
        }
        return $this->client;
    }
    
    function create(){
        $indexParams['index'] = $this->index;
        //$indexParams['body']['settings']['number_of_shards'] = 2;
        //$indexParams['body']['settings']['number_of_replicas'] = 0;
        $indexParams['body'] = json_decode('{
  "settings": {
    "analysis": {
      "analyzer": {
        "myAnalyzer": {
          "type": "custom",
          "tokenizer": "standard",
          "filter": [
            "standard",
            "lowercase",
            "my_pattern",
            "split_on_numerics",
            "my_length"
          ]
        }
      },
      "filter": {
        "split_on_numerics": {
          "type": "word_delimiter",
          "split_on_numerics": true,
          "split_on_case_change": false,
          "generate_word_parts": true,
          "generate_number_parts": true,
          "catenate_all": false,
          "preserve_original": true
        },
        "my_length": {
          "type": "length",
          "min": 2
        },
        "my_pattern":{
            "type" : "pattern_capture",
            "patterns" : [
                        "(\\\\d+)x(\\\\d)",
                        "(\\\\d+)x(\\\\D+)",
                        "(\\\\D+)x(\\\\d+)" 
            ]
        }
      }
    }
  },
  "mappings": {
    "keyword": {
      "properties": {
        "created_at": {
          "type": "date",
          "format": "dateOptionalTime"
        },
        "first": {
          "type": "string"
        },
        "is_active": {
          "type": "string"
        },
        "key_hash": {
          "type": "string"
        },
        "num_results": {
          "type": "string"
        },
        "popularity": {
          "type": "string"
        },
        "query_id": {
          "type": "string"
        },
        "query_text": {
          "type": "string"
        },
        "updated_at": {
          "type": "date",
          "format": "dateOptionalTime"
        }
      }
    },
    "product": {
      "properties": {
        "attribute": {
          "properties": {
            "attribute_id": {
              "type": "long"
            },
            "value_id": {
              "type": "long"
            }
          }
        },
        "category": {
          "properties": {
            "category_id": {
              "type": "long"
            },
            "position": {
              "type": "long"
            }
          }
        },
        "category_ids": {
          "type": "long"
        },
        "date_added": {
          "type": "date",
          "format": "dateOptionalTime"
        },
        "name": {
          "type": "string",
          "analyzer": "myAnalyzer"
        },
        "price": {
          "type": "double"
        },
        "product_id": {
          "type": "long"
        },
        "quantity": {
          "type": "long"
        },
        "review_rating": {
          "type": "long"
        },
        "sales_num": {
          "type": "long"
        },
        "sku": {
          "type": "string"
        },
        "status": {
          "type": "long"
        }
      }
    }
  }
}',true);
        print_r($indexParams);
        $client = $this->getClient();
        $client->indices()->create($indexParams);

    }
    
    function importData() {
        $language_id = $this->language_id;
        $store_id = $this->store_id;
        $limit = 100;
        $cnt_sql = "SELECT count(*) as cnt FROM oc_product p LEFT JOIN  `oc_product_description` d ON d.product_id = p.product_id  LEFT JOIN oc_product_to_store s ON  s.product_id = p.product_id where  d.language_id = '{$language_id}'  AND  s.store_id = '{$store_id}' ";
        $db = $this->getDb();
        $cnt_rs = mysqli_query($db,$cnt_sql );
        $cnt_row = mysqli_fetch_assoc($cnt_rs);
        $cnt = $cnt_row['cnt'];

        $pages = ceil($cnt / $limit);

        $i = 0;
        while ($i < $pages) {
            $start = $i * $limit;
            $sql = "SELECT p.product_id,p.model,d.name,p.price,p.stock_status_id,p.quantity,d.language_id,p.date_added,p.status,s.sales_num,s.review_rating FROM oc_product p LEFT JOIN  `oc_product_description` d ON d.product_id = p.product_id LEFT JOIN oc_product_to_store s ON  s.product_id = p.product_id  where  d.language_id = '{$language_id}' AND  s.store_id = '{$store_id}' order by p.product_id  limit  $start, $limit";

            $db = $this->getDb();
            $stmt = mysqli_query($db,$sql);

            $doc_data = array();

            while ($rtn_row = mysqli_fetch_assoc($stmt)) {
                $product_id = $rtn_row['product_id'];
                $sku = $rtn_row['model'];
                $price = $rtn_row['price'];
                $date_added = $rtn_row['date_added'];
                $satus = $rtn_row['status'];
                $sales_num = $rtn_row['sales_num'];
                $review_rating = $rtn_row['review_rating'];
                $stock_status_id = $rtn_row['stock_status_id'];
                $quantity = $rtn_row['quantity'];
                $quantity = intval($quantity);
                if($stock_status_id != 7){
                    $satus = 0;
                }
                if($rtn_row['quantity'] <=0){
                    //$satus = 0;
                }

                //$sales_num = mt_rand(0, 9999);
                //$review_rating = mt_rand(0, 5);

                //category postion
                $category_arr = array();
                $cat_sql = "select category_id,	position from oc_product_to_category where product_id = '{$product_id}'";
                $db = $this->getDb();
                $cat_rs = mysqli_query($db,$cat_sql );
                $cat_data = array();
                while ($item = mysqli_fetch_assoc($cat_rs)) {
                    $cat_data[] = array('category_id' => intval($item['category_id']), 'position' => intval($item['position']));
                    $category_arr[] = intval($item['category_id']);
                }
                //all category
                $all_category_id_arr = array();
                $category_arr = array_unique($category_arr);
                foreach($category_arr as $_category_id) {
                    $all_category_sql = "select path from oc_category where category_id = '$_category_id'";
                    $db = $this->getDb();
                    $all_category_rs = mysqli_query($db,$all_category_sql);
                    $all_category_row = mysqli_fetch_assoc($all_category_rs);
                    $path = $all_category_row['path'];
                    $path_arr = explode('/',$path);
                    
                    foreach($path_arr as $item){
                        $item = intval($item);
                        if($item>0){
                            $all_category_id_arr[] = $item;
                        }
                    }
                }
                $all_category_id_arr = array_unique($all_category_id_arr);
                $all_category_id_arr = array_values($all_category_id_arr);
                //special price
                $special_sql = "select price from oc_product_special where product_id = '$product_id'  and (date_start > now() or date_start = '0000-00-00 00:00:00'  ) and (date_end < now() or  date_end = '0000-00-00 00:00:00') ";
                $db = $this->getDb();
                $special_rs = mysqli_query($db,$special_sql );
                $special_row = mysqli_fetch_array($special_rs);
                if ($special_row) {
                    $rtn_row['price'] = $special_row['price'];
                }
                //attribute
                $product_attribute = array();
                $attribute_sql = "select * from oc_new_product_attribute where product_id = '{$product_id}'";
                $db = $this->getDb();
                $attribute_rs = mysqli_query($db,$attribute_sql);
                while($attribute_row = mysqli_fetch_assoc($attribute_rs)){
                    $attribute_id = $attribute_row['attribute_id'];
                    $attr_option_value_id = $attribute_row['attr_option_value_id'];
                    if(isset($product_attribute[$attribute_id])){
                        $product_attribute[$attribute_id][] = intval($attr_option_value_id);
                    }else{
                        $product_attribute[$attribute_id] = array();
                        $product_attribute[$attribute_id][] = intval($attr_option_value_id);
                    }
                   
                }
                $product_attribute_data = array();
                foreach($product_attribute as $_k => $_v){
                    $_v = array_unique($_v,SORT_NUMERIC);
                    
                   // if($_k>0){
                        $product_attribute_data[]  = array('attribute_id' => $_k, 'value_id' => $_v);
                   // }
                                     
                    
                }
                
                $product_data = array(
                    'product_id' => intval($rtn_row['product_id']),
                    'sku' => $rtn_row['model'],
                    'name' => $rtn_row['name'],
                    'price' => floatval($rtn_row['price']),
                    'date_added' => date('Y-m-d', strtotime($rtn_row['date_added'])) . 'T' . date('m:i:s', strtotime($rtn_row['date_added'])),
                    'status' => intval($satus),
                    'sales_num' => intval($sales_num),
                    'review_rating' => intval($review_rating),
                    'category' => $cat_data,
                    'attribute' => $product_attribute_data,
                    'category_ids' => $all_category_id_arr,
                    'quantity' => $quantity,
                );
                $params = array();
                $params['index'] = $this->index;
                $params['type'] = 'product';
                $params['id'] = $rtn_row['model'];
                $params['refresh'] = true;
                $params['body'] = $product_data;
                $client = $this->getClient();
               
                $client->index($params);
                

            }
            $i ++;
        }
    }

    function updateProductData($product_id = '', $sku = '') {
        if (empty($product_id) && empty($sku)) {
            return false;
        }
        $where = '';
        if ($product_id) {
            $where = " p.product_id = '{$product_id}' ";
        } else if ($sku) {
            $where = " p.model = '{$sku}' ";
        }
        $language_id = $this->language_id;
        $store_id = $this->store_id;


        $sql = "SELECT p.product_id,p.model,d.name,p.price,d.language_id,p.date_added,p.status,s.sales_num,s.review_rating FROM oc_product p LEFT JOIN  `oc_product_description` d ON d.product_id = p.product_id LEFT JOIN oc_product_to_store s ON  s.product_id = p.product_id  where  d.language_id = '{$language_id}' AND  s.store_id = '{$store_id}' and $where";

        $db = $this->getDb();
        $stmt = mysqli_query($db,$sql );

        $doc_data = array();

        while ($rtn_row = mysqli_fetch_assoc($stmt)) {
            $product_id = $rtn_row['product_id'];
            $sku = $rtn_row['model'];
            $price = $rtn_row['price'];
            $date_added = $rtn_row['date_added'];
            $satus = $rtn_row['status'];
            $sales_num = $rtn_row['sales_num'];
            $review_rating = $rtn_row['review_rating'];

            //category
            $cat_sql = "select category_id,	position from oc_product_to_category where product_id = '{$product_id}'";
            $db = $this->getDb();
            $cat_rs = mysqli_query($db,$cat_sql);
            $cat_data = array();
            while ($item = mysqli_fetch_assoc($cat_rs)) {
                $cat_data[] = array('category_id' => $item['category_id'], 'position' => $item['position']);
            }
            //special price
            $special_sql = "select price from oc_product_special where product_id = '$product_id'  and (date_start > now() or date_start = '0000-00-00 00:00:00'  ) and (date_end < now() or  date_end = '0000-00-00 00:00:00') ";
            $db = $this->getDb();
            $special_rs = mysqli_query($db, $special_sql );
            $special_row = mysqli_fetch_array($special_rs);
            if ($special_row) {
                $rtn_row['price'] = $special_row['price'];
            }
             //attribute
                $product_attribute = array();
                $attribute_sql = "select * from oc_new_product_attribute where product_id = '{$product_id}'";
                $db = $this->getDb();
                $attribute_rs = mysqli_query($db,$attribute_sql);
                while($attribute_row = mysqli_fetch_assoc($attribute_rs)){
                    $attribute_id = $attribute_row['attribute_id'];
                    $attr_option_value_id = $attribute_row['attr_option_value_id'];
                    if(isset($product_attribute[$attribute_id])){
                        $product_attribute[$attribute_id][] = $attr_option_value_id;
                    }else{
                        $product_attribute[$attribute_id] = array();
                        $product_attribute[$attribute_id][] = $attr_option_value_id;
                    }
                   
                }
                $product_attribute_data = array();
                foreach($product_attribute as $_k => $_v){
                    $_v = array_unique($_v);
                   // if($_k>0){
                        $product_attribute_data[]  = array('attribute_id' => $_k, 'value_id' => $_v);
                   // }
                                     
                    
                }
                
                $product_data = array(
                    'product_id' => $rtn_row['product_id'],
                    'sku' => $rtn_row['model'],
                    'name' => $rtn_row['name'],
                    'price' => $rtn_row['price'],
                    'date_added' => date('Y-m-d', strtotime($rtn_row['date_added'])) . 'T' . date('m:i:s', strtotime($rtn_row['date_added'])),
                    'status' => $satus,
                    'sales_num' => $sales_num,
                    'review_rating' => $review_rating,
                    'category' => $cat_data,
                    'attribute' => $product_attribute_data,
                );
                $params = array();
                $params['index'] = $this->index;
                $params['type'] = 'product';
                $params['id'] = $rtn_row['model'];
                $params['refresh'] = true;
                $params['body'] = $product_data;
                $client = $this->getClient();
                $client->index($params);
        }
       
        return true;
    }

    function deleteProduct($sku) {
        $params = array();
        $params['index'] = $this->index;
        $params['type'] = 'product';
        $params['id'] = $sku;
        $client = $this->getClient();
        $client->delete($params);
    }

    function addKeyword($data) {
        foreach ($data as $item) {
            $params = array();
            $params['index'] = $this->index;
            $params['type'] = 'keyword';
            $params['id'] = $item['query_id'];
            $params['refresh'] = true;
            $params['body'] = $item;
            $client = $this->getClient();
            $client->index($params);
        }
    }

    function deleteKeyword($query_id) {
        $params = array();
        $params['index'] = $this->index;
        $params['type'] = 'keyword';
        $params['id'] = $query_id;
        $client = $this->getClient();
        $client->delete($params);
    }

    function suggest($keyword) {
        $search_data['query']['filtered']['filter']['and'][] = array(
                "range" => array(
                    'num_results' => array(
                        'from' => 1
                    )
                )
         );
         $search_data['query']['filtered']['filter']['and'][] = array(
                "term" => array(
                    'is_active' => 1
                )
         );
         
        $search_data['query']['filtered']['query'] = array(
                "match" => array(
                    'query_text' => $keyword
                )
         );
      
        $params = array();
        $params['index'] = $this->index;
        $params['type'] = 'keyword';
        $params['fields'] = 'query_text';
        $params['body'] = $search_data;
        $params['from'] = 0;
        $params['size'] = 5;
        $params['sort'] = array('num_results:desc');

        $client = $this->getClient();
        return $client->search($params);
    }

    function search($keyword = '', $cat_id = array(), $attribute_arr = array(),$order_by = array(), $from = 0, $size = 99999, $fields = array('product_id'),$price_range = array(),$is_highlight=0) {
        $params = array();
        $search_data = array();
        
        if(!empty($cat_id)){
            
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                         
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                        
                    )
                ),
         );
        }
        if($attribute_arr){
            
            foreach($attribute_arr as $_k => $_v){
               $search_attr = array();
               $search_attr = array(
                  'and' => array(
                      array(
                          'term' => array(
                              'attribute.attribute_id' => $_k
                          ),
                        ),
                        array(
                          'terms' => array(
                              'attribute.value_id' => $_v
                          )
                      ),
                   ),
                 );
               $search_data['query']['filtered']['filter']['and'][] = $search_attr;
            }
        }
        
       if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        
        if(isset($search_data['query']['filtered']['filter']['and']) && $search_data['query']['filtered']['filter']['and']){
                $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
            $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
            $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
            if($is_highlight){
                $search_data['highlight']['fields']['name'] = array(
                    'pre_tags' => '<em>',
                    'post_tags' => '</em>'
                );
            }
        }
        
        
        $search_data['from'] = $from;
        $search_data['size'] = $size;
        if (count($order_by) > 0) {
             $search_data['sort'] = $order_by;
        }
        //echo json_encode($search_data);
        $params['body'] = $search_data;
        $params['index'] = $this->index;
        $params['type'] = 'product';
        $params['fields'] = $fields;
        
        $client = $this->getClient();
        
        $results = $client->search($params);
       
        
        return $results;
        
    }

    function aggs($keyword = '', $cat_id = array(), $attribute_arr = array(),$price_range=array()) {
        $params = array();
        $search_data = array();
/*
 * {
    "query": {
        "filtered": {
            "filter": {
                "and": [
                    {
                        "bool": {
                            "must": {
                                "term": {
                                    "status": 1
                                }
                            }
                        }
                    },
                    {
                        "and": [
                            {
                                "term": {
                                    "attribute.attribute_id": 28
                                }
                            },
                            {
                                "terms": {
                                    "attribute.value_id": [
                                        1720,
                                        1154
                                    ]
                                }
                            }
                        ]
                    }
                ]
            },
            "query": {
                "match": {
                    "name": "3w"
                }
            }
        }
    }
}
 */
        
        
        
        

        
        if($cat_id){
            
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                         
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                        
                    )
                ),
         );
        }
        if($attribute_arr){
            
            foreach($attribute_arr as $_k => $_v){
               $search_attr = array();
               $search_attr = array(
                  'and' => array(
                      array(
                          'term' => array(
                              'attribute.attribute_id' => $_k
                          ),
                        ),
                        array(
                          'terms' => array(
                              'attribute.value_id' => $_v
                          )
                      ),
                   ),
                 );
               $search_data['query']['filtered']['filter']['and'][] = $search_attr;
            }
        }
        
                if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        
                if(isset($search_data['query']['filtered']['filter']['and']) &&  $search_data['query']['filtered']['filter']['and']){
             $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
             $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
             $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
        }
        
        
        
        $search_data['aggs'] = array(
            'all_interests' => array(
                'terms' => array(
                    'field' => 'category.category_id'
                )
            )
        );
        
        
        //echo json_encode($search_data)."<br/>";;
        $params['body'] = $search_data;
        $params['index'] = $this->index;
        $params['type'] = 'product';
        
        
        $client = $this->getClient();
        $results = $client->search($params);
        return $results;
        
    }
    
    function count($keyword = '', $cat_id = array(), $attribute_arr = array(),$price_range=array()){
        $params = array();
        $search_data = array();

        
        if($cat_id){
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                         
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                        
                    )
                ),
         );
        }
        if(!empty($attribute_arr)){
            
            foreach($attribute_arr as $_k => $_v){
                if(!empty($_v)){
                    $search_attr = array();
                    $search_attr = array(
                       'and' => array(
                           array(
                               'term' => array(
                                   'attribute.attribute_id' => $_k
                               ),
                             ),
                             array(
                               'terms' => array(
                                   'attribute.value_id' => $_v
                               )
                           ),
                        ),
                      );
                    $search_data['query']['filtered']['filter']['and'][] = $search_attr;
                }
            }
        }
                if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        if(isset($search_data['query']['filtered']['filter']['and']) &&  $search_data['query']['filtered']['filter']['and']){
             $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
             $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
             $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
        }
        //echo json_encode($search_data)."<br/>";;
        $params['body'] = $search_data;
        $params['index'] = $this->index;
        $params['type'] = 'product';
        
       
        $client = $this->getClient();
        $results = $client->count($params);
        return $results;
    }

    function aggsPriceRange($keyword = '', $cat_id = array(), $attribute_arr = array(),$price_range=array(),$aggs_price_range_option=array()) {
        $params = array();
        $search_data = array();

        
        if($cat_id){
            
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                    )
                ),
         );
        }
        if($attribute_arr){
            foreach($attribute_arr as $_k => $_v){
               if(!empty($_v)){
                    $search_attr = array();
                    $search_attr = array(
                       'and' => array(
                           array(
                               'term' => array(
                                   'attribute.attribute_id' => $_k
                               ),
                             ),
                             array(
                               'terms' => array(
                                   'attribute.value_id' => $_v
                               )
                           ),
                        ),
                      );
                    $search_data['query']['filtered']['filter']['and'][] = $search_attr;
               }
            }
        }
        if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        if(isset($search_data['query']['filtered']['filter']['and']) &&  $search_data['query']['filtered']['filter']['and']){
             $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
             $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
             $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
        }
        
        if($aggs_price_range_option){
            $ranges = array();
            foreach($aggs_price_range_option as $item){
                $start = $item['start'];
                $start = floatval($start);
                $end   = $item['end'];
                $pf_id = $item['price_range'];
                if($end){
                    $end = floatval($end);
                    $ranges[] = array('key'=>$pf_id,'from'=>$start,'to'=>$end);
                }else{
                    $ranges[] = array('key'=>$pf_id,'from'=>$start);
                }
            }
           if(count($ranges)){
                $search_data['aggs']['price_ranges'] = array(
                 
                     "range" => array(
                         "field" => 'price',
                         "ranges" => $ranges,
                     )
                 
                );
           }
        }
        
  
        //echo json_encode($search_data)."<br/>";
        $params['body']   = $search_data;
        $params['index']  = $this->index;
        $params['type']   = 'product';
        
        
        $client  = $this->getClient();
        $results = $client->search($params);
        //print_r($results);
        return $results;
        
    }
    
    function aggsAttr($keyword = '', $cat_id = array(), $attribute_arr = array(),$price_range=array()) {
        $params = array();
        $search_data = array();

        
        if($cat_id){
            
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                    )
                ),
         );
        }
        if($attribute_arr){
            
            foreach($attribute_arr as $_k => $_v){
               if(!empty($_v)){
                    $search_attr = array();
                    $search_attr = array(
                       'and' => array(
                           array(
                               'term' => array(
                                   'attribute.attribute_id' => $_k
                               ),
                             ),
                             array(
                               'terms' => array(
                                   'attribute.value_id' => $_v
                               )
                           ),
                        ),
                      );
                    $search_data['query']['filtered']['filter']['and'][] = $search_attr;
               }
            }
        }
        if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        if(isset($search_data['query']['filtered']['filter']['and']) &&  $search_data['query']['filtered']['filter']['and']){
             $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
             $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
             $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
        }
        
        
        
        $search_data['aggs'] = array(
            'group_by_attribute' => array(
                'terms' => array(
                    'field' => 'attribute.attribute_id',
                    'size' => 0,
                ),
                "aggs" => array(
                    "group_by_value" => array(
                        'terms' => array(
                            'field' => 'attribute.value_id',
                            'size' => 0,
                        ),
                    )
                )
            )
        );
  
        //echo json_encode($search_data)."<br/>";;
        $params['body']   = $search_data;
        $params['index']  = $this->index;
        $params['type']   = 'product';
        
        
        $client  = $this->getClient();
        $results = $client->search($params);
        //print_r($results);
        return $results;
        
    }
    
    
   function aggCategoryProductNumber($keyword = '', $cat_id = array(), $attribute_arr = array(),$price_range=array()){
        $params = array();
        $search_data = array();

        if($cat_id){
            $search_data['query']['filtered']['filter']['and'][] = array(
                'bool' => array(
                    'must' => array(
                         
                            'terms' => array(
                                'category.category_id' => $cat_id
                            )
                        
                    )
                ),
         );
        }
        if(!empty($attribute_arr)){
            
            foreach($attribute_arr as $_k => $_v){
                if(!empty($_v)){
                    $search_attr = array();
                    $search_attr = array(
                       'and' => array(
                           array(
                               'term' => array(
                                   'attribute.attribute_id' => $_k
                               ),
                             ),
                             array(
                               'terms' => array(
                                   'attribute.value_id' => $_v
                               )
                           ),
                        ),
                      );
                    $search_data['query']['filtered']['filter']['and'][] = $search_attr;
                }
            }
        }
                if($price_range){
            $start = $price_range['start'];
            $end   = $price_range['end'];
            $search_price = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                    'range' => array(
                                        'price' => array(
                                            'gte' => $start,
                                            'lt'  => $end,
                                        )
                                    )
                            )
                        )
                    ),
             );
            $search_data['query']['filtered']['filter']['and'][] = $search_price;
        }
        
        if(isset($search_data['query']['filtered']['filter']['and']) &&  $search_data['query']['filtered']['filter']['and']){
             $search_data['query']['filtered']['filter']['and'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }else{
             $search_data['query']['filtered']['filter'][] = array(
                    'bool' => array(
                        'must' => array(
                                array(
                                'term' => array(
                                    'status' => 1
                                )
                            )
                        )
                    ),
             );
        }
        
        if($keyword){
            //$search_data['query']['filtered']['query']['match']['name'] = $keyword;
            //$search_data['query']['filtered']['query']['match']['minimum_should_match'] = 2;
             $search_data['query']['filtered']['query']['match']['name'] = array(
                'query' => $keyword,
                   'minimum_should_match' => '70%',
                'operator' => 'or',
            );
        }
        
        $search_data['aggs'] = array(
            'group_by_category_ids' => array(
                'terms' => array(
                    'field' => 'category_ids',
                    'size' => 0,
                ),
                
            )
        );
        
        //echo json_encode($search_data)."<br/>";;
        $params['body'] = $search_data;
        $params['index'] = $this->index;
        $params['type'] = 'product';
        
       
        $client = $this->getClient();
        $results = $client->search($params);
        return $results;
    }

}
