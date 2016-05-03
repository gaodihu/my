<?php
include_once('../config.php');
error_reporting(E_ALL);
ini_set('dispaly_errors',1);
require_once DIR_SYSTEM . 'lib/Elasticsearch/vendor/autoload.php';
require_once DIR_SYSTEM . 'library/search.php';

$db = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,true);
mysql_select_db(DB_DATABASE);

mysql_query("truncate oc_attribute_to_group;");
mysql_query("truncate oc_attribute_option_value_to_group;");

$cat_sql =  "select category_id from  oc_category  where level = 1";
$cat_rs  = mysql_query($cat_sql);
while($cat_row = mysql_fetch_assoc($cat_rs)){
    $category_id = $cat_row['category_id'];
    
    //属性
    $sql_att = "select  pa.attribute_id ,count(pa.product_id) as cnt from oc_new_product_attribute  pa 
left join oc_product p on p.product_id = pa.product_id
left join oc_product_to_category  pc on pc.product_id = p.product_id
LEFT JOIN oc_product_description pd ON (pd.product_id = p.product_id)
where pd.language_id = '1' and pc.category_id = '{$category_id}' and  p.status = 1 group by  pa.attribute_id ";

    $rs_att = mysql_query($sql_att);
    while($row_att = mysql_fetch_assoc($rs_att)){
        $sql_cat_group = "select * from oc_category_attribute_group where category_id = '{$category_id}' ";
        $rs_cat_group = mysql_query($sql_cat_group);
        $row_cat_group = mysql_fetch_assoc($rs_cat_group);
        $group_id = $row_cat_group['attribute_group_id'];
        
        $attribute_id = $row_att['attribute_id'];
        $sort = $row_att['cnt'];
        $insert_new = "replace  into  oc_attribute_to_group(attribute_id,attribute_group_id,sort) VALUES ('{$attribute_id}','{$group_id}','{$sort}')";
        mysql_query($insert_new);
   }
   
   //值
       $sql_att = "select  pa.attr_option_value_id, count(pa.product_id) as cnt from oc_new_product_attribute  pa 
left join oc_product p on p.product_id = pa.product_id
left join oc_product_to_category  pc on pc.product_id = p.product_id
LEFT JOIN oc_product_description pd ON (pd.product_id = p.product_id)
where pd.language_id = '1' and pc.category_id = '{$category_id}' and  p.status = 1 group by  pa.attr_option_value_id ";
    $rs_att = mysql_query($sql_att);
    while($row_att = mysql_fetch_assoc($rs_att)){
        $sql_cat_group = "select * from oc_category_attribute_group where category_id = '{$category_id}' ";
        $rs_cat_group = mysql_query($sql_cat_group);
        $row_cat_group = mysql_fetch_assoc($rs_cat_group);
        $group_id = $row_cat_group['attribute_group_id'];
        
        
        $option_id = $row_att['attr_option_value_id'];
        $sort = $row_att['cnt'];
        $insert_new = "replace  into  oc_attribute_option_value_to_group(option_id,attribute_group_id,status,sort) VALUES ('{$option_id}','{$group_id}',1,'{$sort}')";
        mysql_query($insert_new);
   }
   
   
}