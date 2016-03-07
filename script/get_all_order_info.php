<?php
include_once('/home/www/new_myled.com/script/conf.php'); 
//include_once('D:\XAMPP\htdocs\new_myled\script\conf2.php'); 

  $file ='/home/www/new_myled.com/script/goods_info.csv';
  //$file ='D://goods_info.csv';
  $fp = fopen($file, 'w+');
  chmod($file, 0777);
  $sql ="select distinct p.product_id,p.model,ag.attribute_group_code as attribute_set,'' as tow_catagory,pd.description,p.price,p.weight,pd.name, p.length,p.width,p.height,p.product_code,p.supplier_code,p.image,
(select image from oc_product_image where product_id=p.product_id limit 1) as gallery,pd.meta_keyword as keywords, p.stock_status_id,p.quantity,CONCAT('http://www.myled.com/',p.url_path) as url
from oc_product as p
left join oc_product_description as pd on p.product_id =pd.product_id and pd.language_id=1
left join oc_product_attribute_group as pag on p.product_id =pag.product_id
left join oc_attribute_group as ag on pag.attribute_group_id =ag.attribute_group_id 
order by attribute_set ASC" ;
$query =mysql_query($sql);
while($row =mysql_fetch_assoc($query)){
    //商品分类
    $query_catagory =mysql_query("select max(category_id) as category_id  from oc_product_to_category where product_id=".$row['product_id']);
    $res_catagory =mysql_fetch_assoc($query_catagory);
    $category_id =$res_catagory['category_id'];
    $query_cat_name =mysql_query("SELECT name from oc_category_description where category_id='".$category_id."' and language_id=1");
    $row_cat =mysql_fetch_assoc($query_cat_name);       
    $row['tow_catagory'] =$row_cat['name'];
    fputcsv($fp,$row);
}
fclose($fp);
?>