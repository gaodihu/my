<?php
include_once('/home/www/new_myled.com/script/conf.php'); 

  //clearance.html 254
  //top_seller 253
  //new arrivels 256
  mysql_query("delete from oc_product_to_category where category_id=254"); 
  //提取数据
  $sku_array =array(1000711368,1000723522,1010627025,1010711332,1020721682,1020730819,1030728120,1220921188,1500922477,1640126944,1700724765,1730723317,2500720512,2500825693,2510211264,2510911256,2520826748,2550726772,2830224942,2841031010,2851224934,3001126074,3540726496,3550224900);
  foreach($sku_array as $sku){
        $query_id =mysql_query("select product_id from oc_product where model ='".$sku."'");
        $res_query_id =mysql_fetch_assoc($query_id);
        $product_id =$res_query_id['product_id'];
        if($product_id){
            //mysql_query("update oc_product set is_new=1 where product_id=".$product_id);
            mysql_query("insert into oc_product_to_category set product_id='".$product_id."',category_id=254,position=0");
        }
  }
?>