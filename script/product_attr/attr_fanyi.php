<?php

/****
***
***  属性值批量翻译
**
*/
include_once('/home/www/new_myled.com/script/conf.php'); 



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 

/*
include_once('D://XAMPP/htdocs/new_myled/script/conf2.php');
require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/

function getexcelcontent(){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    $file='/home/www/new_myled.com/script/product_attr/pro_attr_fanyi.xlsx' ;
    //$file='D://XAMPP/htdocs/new_myled/script/product_attr/pro_attr_fanyi.xls' ;    
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
        for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
            $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            $excelData[$row][] = $content;
            
        }  
    }  
    
    return $excelData;  
}

function update(){
    $data =getexcelcontent();
    foreach($data as $item){
        if($item[0]){
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[1])."' where language_id=99 and option_value='".trim($item[0])."' ");
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[2])."' where language_id=4 and option_value='".trim($item[0])."' ");
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[3])."' where language_id=5 and option_value='".trim($item[0])."' ");
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[4])."' where language_id=6 and option_value='".trim($item[0])."' ");
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[5])."' where language_id=7 and option_value='".trim($item[0])."' ");
            mysql_query("update oc_new_attribute_option_value set option_value='".trim($item[6])."' where language_id=8 and option_value='".trim($item[0])."' ");
        }
    }
}
update();
?>


