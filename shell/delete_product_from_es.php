<?php
include_once('../config.php');
error_reporting(E_ALL);
ini_set('dispaly_errors',1);
require_once DIR_SYSTEM . 'lib/Elasticsearch/vendor/autoload.php';

require_once DIR_SYSTEM . 'library/search.php';



//53-57
$data = array(
    array(1,0),
    array(4,52),
    array(5,54),
    array(6,53),
    array(7,55),
    array(8,56),   
);
function getDB(){
    static $db;
    if(!$db){
        $db = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,true);
        mysql_select_db(DB_DATABASE,$db);
        mysql_query("set names utf8;");
    }
    return $db;
}
function hasProduct($sku){
    $db = getDB();
    $sql = "select product_id from oc_product where model = '{$sku}'";
    $rs  = mysql_query($sql,$db);
    if($rs){
        $row = mysql_fetch_assoc($rs);
        if($row){
        }else{
            return false;
        }
    }
    return true;
}

foreach($data as $item){
    $search = new Search($ELASTICSEARCH_HOST, $item[0], $item[1]);

    $results = $search->search();

    $product_list = $results['hits']['hits'];

    foreach($product_list as $_product){
        $sku = $_product['_id'];
        $has = hasProduct($sku);
        if(!$has){
            $rs =  $search->deleteProduct($sku);
            var_dump($sku);
            var_dump($rs);
        }
       
    }
    
}
