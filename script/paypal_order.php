<?php

	include_once('conf.php'); 
  $file_path = "/home/www/www.myled.com/script/yes.txt";
  $file_w ="/home/www/www.myled.com/script/no.txt";
  $fp_r = fopen($file_path,'r');
  $fp_w = fopen($file_w,'a+');
  while(!feof($fp_r)){
	$str =trim(fgets($fp_r));
	$sql ="select order_status_id from oc_order where order_number='".$str."'";
	$query =mysql_query($sql);
	$res =mysql_fetch_row($query);
    
	if($res[0]!='5'){
        /*
        if($res[0]=='2'){
            mysql_query("update oc_order set order_status_id=5 where order_number='".$str."' ");
        }
        else{
            fwrite($fp_w, $str .",".$res[0]. "\n");
        }
        */
		fwrite($fp_w, $str .",".$res[0]. "\n");
	}
    
    
    /*
    if($res[0]!='2'&&$res[0]!='5'){
        fwrite($fp_w, $str .",".$res[0]. "\n");
    }
    */
  }
  fclose($fp_r);
  fclose($fp_w);
?>