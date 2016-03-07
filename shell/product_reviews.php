<?php
include_once('/home/www/new_myled.com/config.php');
function getDb() {
    static $db;
    $host = DB_HOSTNAME;
    $dbname = DB_DATABASE;
    $user = DB_USERNAME;
    $passwd = DB_PASSWORD;
    if (!$db) {
        $db = mysql_connect($host, $user, $passwd, true);
        mysql_select_db($dbname, $db);
        mysql_query("set names utf8;");
    }
    return $db;
}
$store_data = array();
$store_data[] = 0;
$db = getDb();
$sql_store = "SELECT * FROM  `oc_store` ";
$rs_store  = mysql_query($sql_store,$db);
while($row_store = mysql_fetch_assoc($rs_store)){
    $store_data[] = $row_store['store_id'];
}
$store_data = array_unique($store_data);
foreach($store_data as $store_id){
    $sql_product_num = "select product_id, avg(rating) as rating  from oc_review where store_id = '{$store_id}' and  status = 1 group by product_id";
    $db = getDb();
    $rs_product_num = mysql_query($sql_product_num,$db);
    while($row_product_num = mysql_fetch_assoc($rs_product_num)){
        $product_id = $row_product_num['product_id'];
        $rating     = $row_product_num['rating'];
        $sql_u = "update oc_product_to_store set review_rating = '{$rating}' where product_id = '{$product_id}' and store_id = '{$store_id}'";
        $db = getDb();
        mysql_query($sql_u,$db);
    }
}

