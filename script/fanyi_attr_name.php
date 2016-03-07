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
    $file='/home/www/new_myled.com/script/attr_fanyi.xls' ;
    //$file='D://XAMPP/htdocs/new_myled/script/attr_fanyi.xls' ;    
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
function update_fanyi(){
    $data =getexcelcontent();
    foreach($data as $item){
        $query_exit =mysql_query("select attribute_id from oc_new_attribute_description where language_id=1 and name='".$item[0]."' limit 1 ");
        $row =mysql_fetch_assoc($query_exit);
        if($row['attribute_id']){
            $attribute_id =$row['attribute_id'];
            $query_cn_exit =mysql_query("select name from oc_new_attribute_description where language_id=99 and attribute_id='".$attribute_id."' limit 1");
            $row_cn =mysql_fetch_assoc($query_cn_exit);
            if($row_cn){
                mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[1])."' where attribute_id='".$attribute_id."' and language_id=99");
            }else{
                mysql_query("INSERT INTO oc_new_attribute_description set attribute_id='".$attribute_id."',language_id='99',name='".mysql_real_escape_string($item[1])."'"); 
            }
            
            mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[2])."' where attribute_id='".$attribute_id."' and language_id=4");
            mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[3])."' where attribute_id='".$attribute_id."' and language_id=5");
            mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[4])."' where attribute_id='".$attribute_id."' and language_id=6");
            mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[5])."' where attribute_id='".$attribute_id."' and language_id=7");
            mysql_query("update oc_new_attribute_description set name='".mysql_real_escape_string($item[6])."' where attribute_id='".$attribute_id."' and language_id=8");
            
        }
        else{
            echo $item[0]."不存在<br>";
        }
    }
}
update_fanyi();
?>


