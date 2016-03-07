<?php

  include_once('/home/www/new_myled.com/script/conf.php'); 



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 

/*
include_once('conf2.php');
require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/
function getexcelcontent(){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    //$objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/update_keyword.xlsx' ;
    //$file='D://XAMPP/htdocs/new_myled/script/update_keyword.xlsx' ;    
    if(!$objReader->canRead($file)){
        $objReader = new PHPExcel_Reader_Excel5(); 
    }
    $objPHPExcel = $objReader->load($file);
    $objWorksheet = $objPHPExcel->getActiveSheet();  
    
    $highestRow = $objWorksheet->getHighestRow();   
    $highestColumn = $objWorksheet->getHighestColumn();   
     
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
     
    $excelData = array();  
     
    for ($row = 2; $row <= $highestRow; ++$row) { 
        //for ($row = 2; $row <= 10; ++$row) { 
        for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
            $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            if($content instanceof PHPExcel_RichText){    
                    $content = $content->__toString();  
            }
           $excelData[$row][] = $content;  
        }
    }  
    
    return $excelData;  
}

function update(){
    $data =getexcelcontent();
    $id_where =" and product_id >=9051 and product_id<=10807";
    foreach($data as $item){
        mysql_query("update oc_product_description SET meta_keyword=REPLACE(meta_keyword,'".trim($item[0])."','".trim($item[1])."') where language_id=4 ".$id_where);
        mysql_query("update oc_product_description SET meta_keyword=REPLACE(meta_keyword,'".trim($item[0])."','".trim($item[2])."') where language_id=5".$id_where);
        mysql_query("update oc_product_description SET meta_keyword=REPLACE(meta_keyword,'".trim($item[0])."','".trim($item[3])."') where language_id=6".$id_where);
        mysql_query("update oc_product_description SET meta_keyword=REPLACE(meta_keyword,'".trim($item[0])."','".trim($item[4])."') where language_id=7".$id_where);
        mysql_query("update oc_product_description SET meta_keyword=REPLACE(meta_keyword,'".trim($item[0])."','".trim($item[5])."') where language_id=8".$id_where);
    }
}

function update_up(){
    $data =getexcelcontent();
    foreach($data as $item){
        $query_goods_id =mysql_query("select product_id from oc_product where model='".trim($item[0])."' ");
        $row =mysql_fetch_assoc($query_goods_id);
        $product_id =$row['product_id'];
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[1]))."' where product_id=".$product_id." and language_id=1");
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[2]))."' where product_id=".$product_id." and language_id=4");
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[3]))."' where product_id=".$product_id." and language_id=5");
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[4]))."' where product_id=".$product_id." and language_id=6");
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[5]))."' where product_id=".$product_id." and language_id=7");
        mysql_query("update oc_product_description SET meta_keyword='".mysql_real_escape_string(trim($item[6]))."' where product_id=".$product_id." and language_id=8");
    }
}
update();
?>