<?php

define('CURRENT_ATH', 'E:/www/code/branches/charles0721/');

include_once(CURRENT_ATH . 'config.php');

$db = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die("Unable to connect to the mageto MySQL!");
//$db_connect_opencart=mysql_connect($opencart_db['host'],$opencart_db['user'],$opencart_db['password'],true) or die("Unable to connect to the opencart MySQL!");
mysql_query("SET NAMES UTF8");
mysql_select_db(DB_DATABASE, $db);
date_default_timezone_set('Asia/Chongqing');

$product_sales = getProductSales();

$sql_groups = "select distinct group_id from oc_product_attr_filter";
$rs_groups = mysql_query($sql_groups);
while ($row_groups = mysql_fetch_assoc($rs_groups)) {
    $group_id = $row_groups['group_id'];
    echo $group_id ."\n";
    $sql_products = "select distinct product_id from oc_product_attr_filter where group_id = '" . $group_id ."'";
    $query_products = mysql_query($sql_products);
    $max_sales_product_id = '';
    $_tmp_max_sales = -100;
    while($row_products = mysql_fetch_assoc($query_products)){
        echo $product_id . "\n";
        $product_id  = $row_products['product_id'];
        if(isset($product_sales[$product_id])){
            if($product_sales[$product_id] >= $_tmp_max_sales){
                $max_sales_product_id = $product_id;
                $_tmp_max_sales = $product_sales[$product_id];
                echo "YES\n";
            }else{
                echo "no1\n";
            }
        }else{
            if($_tmp_max_sales<0 || $max_sales_product_id == '' ){
                $max_sales_product_id = $product_id;
                $_tmp_max_sales = 0;
                echo "No2\n";
            }else{
                echo "no3\n";
            }
            
        }
    }
    $update_sql = "INSERT INTO oc_product_attr_filter_main_product(group_id,product_id) values('{$group_id}','{$max_sales_product_id}')";
    echo $update_sql . "\n";
    mysql_query($update_sql);
}

function getProductSales() {
    $data = array();
    $time_start =date("Y-m-d H:i:s",strtotime('-30days'));
    $time_end =date("Y-m-d H:i:s",time());
    $sql_order = "select order_id from oc_order where order_status_id in (2,5) and date_added>='" . $time_start . "' and date_added<='" . $time_end . "'";
    $query_order = mysql_query($sql_order);

    while ($row_order = mysql_fetch_assoc($query_order)) {
        //得到订单下的商品
        $sql_order_poroduct = "select product_id,quantity from oc_order_product where order_id=" . $row_order['order_id'];
        $query_pro = mysql_query($sql_order_poroduct);

        while ($row_pro = mysql_fetch_assoc($query_pro)) {
            if (isset($data[$row_pro['product_id']])) {
                $data[$row_pro['product_id']] += $row_pro['quantity'];
            }else{
                $data[$row_pro['product_id']] = $row_pro['quantity'];
            }
        }
    }
    return $data;
}

?>