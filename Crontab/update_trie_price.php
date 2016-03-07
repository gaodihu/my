<?php

include_once('/home/www/new_myled.com/script/conf.php');
//include_once('E:/www/code/branches/charles0907/script/conf.php');

  $expired_trie_sql = "DELETE FROM  `oc_product_discount` WHERE  `date_end` < NOW() ";
  mysql_query($expired_trie_sql);
  $result=mysql_query("select product_id,price from oc_product order by product_id ASC"); 
  //提取数据
  while($row=mysql_fetch_assoc($result)){
		$sql_exit ="select product_discount_id from oc_product_discount  where product_id = ".$row['product_id'];
		$res = mysql_fetch_row(mysql_query($sql_exit));
		if($res){
			mysql_query("update oc_product_discount set  price =".$row['price']*0.97." where 
quantity =2 and product_id =".$row['product_id']);
			mysql_query("update oc_product_discount set  price =".$row['price']*0.93." where 
quantity =10 and product_id =".$row['product_id']);
			mysql_query("update oc_product_discount set  price =".$row['price']*0.88." where 
quantity =50 and product_id =".$row['product_id']);
		}
		else{
            $data_end =date("Y-m-d H:i:s",time()+100*24*3600);
			mysql_query("insert into oc_product_discount set product_id='".$row['product_id']."',customer_group_id=0,quantity=2,priority=1,price='".($row['price']*0.97)."',date_start=NOW(),date_end ='".$data_end."'");
            mysql_query("insert into oc_product_discount set product_id='".$row['product_id']."',customer_group_id=0,quantity=10,priority=1,price='".($row['price']*0.93)."',date_start=NOW(),date_end ='".$data_end."'");
           mysql_query("insert into oc_product_discount set product_id='".$row['product_id']."',customer_group_id=0,quantity=50,priority=1,price='".($row['price']*0.88)."',date_start=NOW(),date_end ='".$data_end."'");
		}
  }
    

?>