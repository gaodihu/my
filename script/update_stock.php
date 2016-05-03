<?php


include_once('/home/www/new_myled.com/script/conf.php'); 



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 

/*
include_once('D:/tinker20150107/script/conf.php'); 



require_once("D:/tinker20150107/system/lib/PHPExcel/PHPExcel.php");
require_once ('D:/tinker20150107/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('D:/tinker20150107/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
*/
function getexcelcontent(){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    //$objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/stock.xlsx' ;
    //$file='D:/tinker20150107/script/stock.xlsx' ;
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
    foreach($data as $pro_attr){
       
        $sql ="update oc_product set quantity =quantity +".$pro_attr[1]." where model ='".trim($pro_attr[0])."' ";
        mysql_query($sql);
    }
}
update();
?>


