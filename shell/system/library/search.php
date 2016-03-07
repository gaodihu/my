<?php

require_once DIR_SYSTEM . 'lib/Elasticsearch/vendor/autoload.php';

class Search {

    const PRE_INDEX = 'myled_';

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
            $db = mysql_connect($host, $user, $passwd, true);
            mysql_select_db($dbname, $db);
            mysql_query("set names utf8;");
            $this->db = $db;
        }
        return $this->db;
    }

    function getLang() {
        $language_id = $this->language_id;
        $db = $this->getDb();
        $sql = "select * from oc_language where language_id = '{$language_id}'";
        $rs = mysql_query($sql, $db);
        $row = mysql_fetch_assoc($rs);
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
            $client = new Elastica\Client($this->hosts);
            $this->client = $client;
        }
        return $this->client;
    }

    public function createIndex() {
        $elasticaClient = $this->getClient();
        // Load index
        $elasticaIndex = $elasticaClient->getIndex($this->index);
        // Create the index new
        // 创建index的参数自行参见官网，就不一一解释了
        $lang = $this->getLang();
        if (!$lang) {
            $lang = 'English';
        }
        $elasticaIndex->create(
        array(
            'number_of_shards' => 5,
            'number_of_replicas' => 1,
            'analysis' => array(
                'analyzer' => array(
                        'indexAnalyzer' => array(
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => array('lowercase', 'mySnowball')
                    ),
                    'searchAnalyzer' => array(
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => array('standard', 'lowercase', 'mySnowball')
                    )
                ),
                'filter' => array(
                    'mySnowball' => array(
                        'type' => 'snowball',
                        'language' => $lang
                        )
                    )
            )
        ), true
        );



        //创建media的mapping,作为父级
        $mediaType = $elasticaIndex->getType('product');

        // Define mapping
        $mapping = new Elastica\Type\Mapping();
        $mapping->setType($mediaType);
        $mapping->setParam('index_analyzer', 'indexAnalyzer');
        $mapping->setParam('search_analyzer', 'searchAnalyzer');

        // Define boost field
        $mapping->setParam('_boost', array('name' => '_boost', 'null_value' => 1.0));

        // Set mapping
        // 定义media的字段和属性
        $mapping->setProperties(array(
            'product_id' => array('type' => 'integer', 'include_in_all' => FALSE),
            'sku' => array('type' => 'string', 'include_in_all' => TRUE),
            'name' => array('type' => 'string', 'include_in_all' => FALSE),
            'price' => array('type' => 'float', 'include_in_all' => FALSE),
            'date_added' => array('type' => 'date', 'include_in_all' => FALSE),
            'status' => array('type' => 'integer', 'include_in_all' => FALSE),
            'sales_num' => array('type' => 'integer', 'include_in_all' => FALSE),
            'review_rating' => array('type' => 'float', 'include_in_all' => FALSE),
            'category' => array(
                'properties' => array(
                    'category_id' => array('type' => 'integer', 'include_in_all' => FALSE),
                    'postion' => array('type' => 'integer', 'include_in_all' => FALSE),
                )
            ),
            '_boost' => array('type' => 'float', 'include_in_all' => FALSE)
        ));


        // Send mapping to type
        // 保存media的mapping
        $mapping->send();


        //搜索提示库：

        $mediaType = $elasticaIndex->getType('keyword');

        // Define mapping
        $mapping = new Elastica\Type\Mapping();
        $mapping->setType($mediaType);
        $mapping->setParam('index_analyzer', 'indexAnalyzer');
        $mapping->setParam('search_analyzer', 'searchAnalyzer');

        // Define boost field
        $mapping->setParam('_boost', array('name' => '_boost', 'null_value' => 1.0));

        // Set mapping
        // 定义media的字段和属性
        $mapping->setProperties(array(
            'query_id' => array('type' => 'integer', 'include_in_all' => FALSE),
            'query_text' => array('type' => 'string', 'include_in_all' => TRUE),
            'num_results' => array('type' => 'integer', 'include_in_all' => FALSE),
            'popularity' => array('type' => 'integer', 'include_in_all' => FALSE),
            'is_active' => array('type' => 'integer', 'include_in_all' => FALSE),
            'updated_at' => array('type' => 'date', 'include_in_all' => FALSE),
            'created_at' => array('type' => 'date', 'include_in_all' => FALSE),
            'key_hash' => array('type' => 'string', 'include_in_all' => FALSE),
            'first' => array('type' => 'string', 'include_in_all' => FALSE),
            '_boost' => array('type' => 'float', 'include_in_all' => FALSE)
        ));


        // Send mapping to type
        // 保存media的mapping
        $mapping->send();
    }

    function importData() {
        $language_id = $this->language_id;
        $store_id = $this->store_id;
        $limit = 100;
        $cnt_sql = "SELECT count(*) as cnt FROM oc_product p LEFT JOIN  `oc_product_description` d ON d.product_id = p.product_id  LEFT JOIN oc_product_to_store s ON  s.product_id = p.product_id where  d.language_id = '{$language_id}'  AND  s.store_id = '{$store_id}' ";
        $db = $this->getDb();
        $cnt_rs = mysql_query($cnt_sql, $db);
        $cnt_row = mysql_fetch_assoc($cnt_rs);
        $cnt = $cnt_row['cnt'];

        $pages = ceil($cnt / $limit);

        $i = 0;
        while ($i < $pages) {
            $start = $i * $limit;
            $sql = "SELECT p.product_id,p.model,d.name,p.price,p.stock_status_id,d.language_id,p.date_added,p.status,s.sales_num,s.review_rating FROM oc_product p LEFT JOIN  `oc_product_description` d ON d.product_id = p.product_id LEFT JOIN oc_product_to_store s ON  s.product_id = p.product_id  where  d.language_id = '{$language_id}' AND  s.store_id = '{$store_id}' order by p.product_id  limit  $start, $limit";

            $db = $this->getDb();
            $stmt = mysql_query($sql, $db);

            $doc_data = array();

            while ($rtn_row = mysql_fetch_assoc($stmt)) {
                $product_id = $rtn_row['product_id'];
                $sku = $rtn_row['model'];
                $price = $rtn_row['price'];
                $date_added = $rtn_row['date_added'];
                $satus = $rtn_row['status'];
                $sales_num = $rtn_row['sales_num'];
                $review_rating = $rtn_row['review_rating'];
                $stock_status_id = $rtn_row['stock_status_id'];
                if($stock_status_id != 7){
                    $satus = 0;
                }

                $sales_num = mt_rand(0, 9999);
                $review_rating = mt_rand(0, 5);

                //category
                $cat_sql = "select category_id,	position from oc_product_to_category where product_id = '{$product_id}'";
                $db = $this->getDb();
                $cat_rs = mysql_query($cat_sql, $db);
                $cat_data = array();
                while ($item = mysql_fetch_assoc($cat_rs)) {
                    $cat_data[] = array('category_id' => $item['category_id'], 'position' => $item['position']);
                }
                //special price
                $special_sql = "select price from oc_product_special where product_id = '$product_id'  and (date_start > now() or date_start = '0000-00-00 00:00:00'  ) and (date_end < now() or  date_end = '0000-00-00 00:00:00') ";
                $db = $this->getDb();
                $special_rs = mysql_query($special_sql, $db);
                $special_row = mysql_fetch_array($special_rs);
                if ($special_row) {
                    $rtn_row['price'] = $special_row['price'];
                }
                $params = array(
                    'product_id' => $rtn_row['product_id'],
                    'sku' => $rtn_row['model'],
                    'name' => $rtn_row['name'],
                    'price' => $rtn_row['price'],
                    'date_added' => date('Y-m-d', strtotime($rtn_row['date_added'])) . 'T' . date('m:i:s', strtotime($rtn_row['date_added'])),
                    'status' => $satus,
                    'sales_num' => $sales_num,
                    'review_rating' => $review_rating,
                    'category' => $cat_data,
                );
                $doc = new Elastica\Document($rtn_row['model'], $params, 'product', $this->index);
                $doc_data[] = $doc;
            }
            $client = $this->getClient();
            $bulk = new Elastica\Bulk($client);
            $bulk->addDocuments($doc_data);
            $bulk->send();
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
        $stmt = mysql_query($sql, $db);

        $doc_data = array();

        while ($rtn_row = mysql_fetch_assoc($stmt)) {
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
            $cat_rs = mysql_query($cat_sql, $db);
            $cat_data = array();
            while ($item = mysql_fetch_assoc($cat_rs)) {
                $cat_data[] = array('category_id' => $item['category_id'], 'position' => $item['position']);
            }
            //special price
            $special_sql = "select price from oc_product_special where product_id = '$product_id'  and (date_start > now() or date_start = '0000-00-00 00:00:00'  ) and (date_end < now() or  date_end = '0000-00-00 00:00:00') ";
            $db = $this->getDb();
            $special_rs = mysql_query($special_sql, $db);
            $special_row = mysql_fetch_array($special_rs);
            if ($special_row) {
                $rtn_row['price'] = $special_row['price'];
            }
            $params = array(
                'product_id' => $rtn_row['product_id'],
                'sku' => $rtn_row['model'],
                'name' => $rtn_row['name'],
                'price' => $rtn_row['price'],
                'date_added' => date('Y-m-d', strtotime($rtn_row['date_added'])) . 'T' . date('m:i:s', strtotime($rtn_row['date_added'])),
                'status' => $rtn_row['status'],
                'sales_num' => $rtn_row['sales_num'],
                'review_rating' => $rtn_row['review_rating'],
                'catetoty' => $cat_data,
            );
            $doc = new Elastica\Document($rtn_row['model'], $params, 'product', $this->index);
            $doc_data[] = $doc;
        }
        $client = $this->getClient();
        $bulk = new Elastica\Bulk($client);
        $bulk->addDocuments($doc_data);
        $bulk->send();
        return true;
    }

    function deleteProduct($sku) {
        $doc = new Elastica\Document($sku, array(), 'product', $this->index);
        $client = $this->getClient();
        $client->deleteDocuments($doc);
    }

    function addKeyword($data) {
        $doc_data = array();
        foreach ($data as $item) {
            $doc = new Elastica\Document($item['query_id'], $item, 'keyword', $this->index);
            $doc_data[] = $doc;
        }
        $client = $this->getClient();
        $bulk = new Elastica\Bulk($client);
        $bulk->addDocuments($doc_data);
        $bulk->send();
    }

    function deleteKeyword($query_id) {
        $doc = new Elastica\Document($query_id, array(), 'keyword', $this->index);
        $client = $this->getClient();
        $client->deleteDocuments($doc);
    }

    function suggest($keyword) {
        $query = new Elastica\Query();

        $elasticaFilterAnd = new Elastica\Query\Bool();
        $elasticaFilterAnd->addMust(new Elastica\Query\MatchAll());

        $query_string = new Elastica\Query\QueryString();
        $query_string->setDefaultField('keyword.query_text');
        $query_string->setQuery($keyword);
        $elasticaFilterAnd->addMust($query_string);

        $filter_status = new Elastica\Query\Term();
        $filter_status->setTerm('keyword.is_active', 1);
        $elasticaFilterAnd->addMust($filter_status);

        $filter_number = new Elastica\Query\Range();
        $filter_number->addField('keyword.num_results', array('gt' => 0));
        $elasticaFilterAnd->addMust($filter_number);

        $query->setQuery($elasticaFilterAnd);
        $query->setFrom(0);
        $query->setSize(5);
        $query->setFields(array('query_text'));

        $client = $this->getClient();
        $search = new Elastica\Search($client);
        $search->addIndex($this->index);
        $search->addType('keyword');
        $search->setQuery($query);

        return $search->search();
    }

    function search($keyword, $cat_id = '', $order_by = array(), $from = 0, $size = 99999, $fields = array('product_id')) {
        $query = new Elastica\Query();

        $elasticaFilterAnd = new Elastica\Query\Bool();
        $elasticaFilterAnd->addMust(new Elastica\Query\MatchAll());

        $query_string = new Elastica\Query\QueryString();
        $query_string->setDefaultField('product.name');
        $query_string->setQuery($keyword);
        $elasticaFilterAnd->addMust($query_string);

        $filter_status = new Elastica\Query\Term();
        $filter_status->setTerm('product.status', 1);
        $elasticaFilterAnd->addMust($filter_status);


        if ($cat_id) {
            $filter_cat = new Elastica\Query\Term();
            $filter_cat->setTerm('product.category.category_id', $cat_id);
            $elasticaFilterAnd->addMust($filter_cat);
        }
        $query->setQuery($elasticaFilterAnd);
        $query->setFrom($from);
        $query->setSize($size);
        $query->setFields($fields);

        if (count($order_by) > 0) {
            $query->setSort($order_by);
        }

        $client = $this->getClient();
        $search = new Elastica\Search($client);
        $search->addIndex($this->index);
        $search->addType('product');
        $search->setQuery($query);

        return $search->search();
    }

}
