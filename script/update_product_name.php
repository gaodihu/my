<?php 
//mageto 数据和现有网站数据同步


include_once('./conf.php');  
$lang =array('1'=>'1','6'=>'4','7'=>'5','8'=>'6','9'=>'7','10'=>'8');


$file ='/home/www/new_myled.com/script/fanyi.csv';
$fp = fopen($file ,'r');
$num=0;
while(!feof($fp)){
        $products_arr = fgetcsv($fp);
        $products_arr[0] =addslashes($products_arr[0]);
        $products_arr[2] =addslashes($products_arr[2]);
        $products_arr[3] =addslashes($products_arr[3]);
        $sql ="select product_id from oc_product where model ='$products_arr[0]' ";
        $query =mysql_query($sql);
        $row_pro =mysql_fetch_row($query);
        $pid = $row_pro[0];
        $sql_exit = "select name from oc_product_description where language_id=".$lang[$products_arr[1]]." and product_id=$pid ";
        
        $query_exit=mysql_query($sql_exit);
        $row=mysql_fetch_row($query_exit);
        if($row){
            $sql_update ="update oc_product_description set name='$products_arr[3]' where language_id=".$lang[$products_arr[1]]." and product_id=$pid";
            mysql_query($sql_update);
        }
        else{
            $sql_insert ="insert into oc_product_description set product_id='".$pid."',language_id='".$lang[$products_arr[1]]."',name='".mysql_real_escape_string($products_arr[3])."' ";
            mysql_query($sql_insert);
        }
}

?>