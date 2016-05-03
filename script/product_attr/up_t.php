<?php

//设置商品属性
include_once('/home/www/new_myled.com/script/conf.php'); 
//include_once('D:\XAMPP\htdocs\new_myled\script\conf2.php');
$sql="select product_id from oc_product_attr_filter where attr_id=10";
$query_1 =mysql_query($sql);
while($row=mysql_fetch_assoc($query_1)){
    $query_2 =mysql_query("select attr_option_value_id from oc_new_product_attribute where product_id =".$row['product_id']." and attribute_id=10 ");
    $row2 =mysql_fetch_assoc($query_2);
    mysql_query("update oc_product_attr_filter set value_id='".$row2['attr_option_value_id']."' where product_id =".$row['product_id']." and attr_id=10");
}
?>


