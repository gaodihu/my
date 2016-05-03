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
    $file='/home/www/new_myled.com/script/erp_api/update_keyword.xls' ;
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
    foreach($data as $item){
        //echo "update oc_category_description SET title='".mysql_real_escape_string(trim($item[1]))."' where language_id=1 and category_id=".$item[0];exit;
        mysql_query("update oc_category_description SET title='".mysql_real_escape_string(trim($item[3]))."' where language_id=6 and category_id=".$item[0]);
        //mysql_query("update oc_category_description SET title='".mysql_real_escape_string(trim($item[2]))."' where language_id=4 and category_id=".$item[0]);
        //mysql_query("update oc_category_description SET title='".mysql_real_escape_string(trim($item[3]))."' where language_id=5 and category_id=".$item[0]);
        //mysql_query("update oc_category_description SET title='".mysql_real_escape_string(trim($item[4]))."' where language_id=7 and category_id=".$item[0]);
      
        
    }
}
update();
?>