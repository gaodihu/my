<?php

/****
***
***  导出所有商品的信息
**
*/

define('ROOT_PATH','/home/www/new_myled.com/');
//define('ROOT_PATH','D://tinker20150107/');
include_once(ROOT_PATH.'script/conf.php'); 

function get_pro_info(){
    $sql ="select p.product_id,p.model,p.battery_type,pd.description,p.price,p.weight,pd.name, p.length,p.width,p.height,p.product_code,p.supplier_code,p.image,
pd.meta_keyword as keywords, p.stock_status_id,p.quantity,p.date_added,CONCAT('http://www.myled.com/',p.url_path) as url
from oc_product as p
left join oc_product_description as pd on p.product_id =pd.product_id and pd.language_id=1
order by p.product_id desc";
    $query =mysql_query($sql);
    $data =array();
    while($row =mysql_fetch_row($query)){
        $category_name =get_pro_category($row[0]);
        $gallery =get_pro_gallery($row[0]);
        array_unshift($row,$category_name);
        $row[] =$gallery;
        $data[] =$row;
    }
    return $data;
}
 function get_pro_category($product_id){
    $query =mysql_query("select category_id from oc_product_to_category where product_id=".$product_id." order by category_id asc limit 1");
    $row =mysql_fetch_assoc($query );
    $category_id =$row['category_id'];
    $query_parent =mysql_query("select parent_id from oc_category where category_id=".$category_id);
    $row_parent =mysql_fetch_assoc($query_parent);
    if($row_parent['parent_id']){
        $category_id=$row_parent['parent_id'];
    }
    $query_name =mysql_query("select name from oc_category_description where category_id=".$category_id." and language_id=1");
    $res =mysql_fetch_assoc($query_name );
    if($res){
        return $res['name'];
    }else{
        return NUll;
    }
    
}

//得到商品的附图
   function get_pro_gallery($product_id){
        $query =mysql_query("select image from oc_product_image where product_id=".$product_id." limit 1");
        $row =mysql_fetch_assoc($query );
        if($row['image']){
        return $row['image'];
        }else{
            return null;
        }
 }
function out_excel(){
    $data =get_pro_info();
    $out_file =ROOT_PATH."script/product_attr/product_info.csv";
    if(file_exists($out_file)){
        unlink($out_file);
    }
    $fp =fopen($out_file,'a');
    foreach($data as $con){
        fputcsv($fp,$con);
    }
    exit;
}
out_excel();
?>


