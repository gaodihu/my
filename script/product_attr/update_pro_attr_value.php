<?php

//更新商品率先的属性值


include_once('/home/www/new_myled.com/script/conf.php'); 


//include_once('D://XAMPP/htdocs/new_myled/script/conf2.php');

function update_pro_attr_value($old_attr_id,$new_attr_id){
    mysql_query("update oc_product_attr_filter set attr_id=".$new_attr_id." where attr_id=".$old_attr_id);
    $query =mysql_query("select paf_id,product_id,attr_id from oc_product_attr_filter where attr_id=".$new_attr_id);
    while($row =mysql_fetch_assoc($query)){
        $query_2 =mysql_query("select attr_option_value_id from  oc_new_product_attribute where product_id='".$row['product_id']."' and attribute_id='".$row['attr_id']."' ");
        $row_2 =mysql_fetch_assoc($query_2);
        if($row_2){
            mysql_query("update oc_product_attr_filter set value_id=".$row_2['attr_option_value_id']." where product_id='".$row['product_id']."' and attr_id='".$row['attr_id']."' ");
        }
    }
}

update_pro_attr_value(96,52);
?>


