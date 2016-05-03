<?php
include_once('/home/www/new_myled.com/script/conf.php');

require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
 
/*
require_once("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/  
function getexcelcontent(){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    //$objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/attr.xls' ;

    if(!$objReader->canRead($file)){
        $objReader = new PHPExcel_Reader_Excel5(); 
    }
    $objPHPExcel = $objReader->load($file);
    $objWorksheet = $objPHPExcel->getActiveSheet();  
    
    $highestRow = $objWorksheet->getHighestRow();   
    $highestColumn = $objWorksheet->getHighestColumn();   
     
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
     
    $excelData = array();  
     
    for ($row = 1; $row <= $highestRow; ++$row) { 
        //for ($row = 2; $row <= 10; ++$row) { 
        for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
             $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
             $excelData[$row][] = $content;
            
        }  
    }  
    
    return $excelData;  
}


function set($data){
    foreach($data as $key=>$item){
        if($key==1){
            $attr_code =$item[1];
            $query_attr =mysql_query("select attribute_id from oc_attribute where attribute_code ='".strtolower($attr_code)."'");
            if($row_attr =mysql_fetch_assoc($query_attr)){
                $attr_id =$row_attr['attribute_id'];
            }
            else{
                echo "属性不存在";exit;
            }

        }
        else{
            $sku =$item[0];
            $query_product =mysql_query("select product_id from oc_product where model ='".$sku."' ");
            $row_product =mysql_fetch_assoc($query_product);
            $product_id =$row_product['product_id'];
            $option_value =$item[1];
            $query_option =mysql_query("select ao.option_id from oc_attribute_option_value as aov left join oc_attribute_option  as ao on aov.option_id=ao.option_id where ao.attribute_id = ".$attr_id." and aov.option_value ='".$option_value."' and aov.language_id=1");
            if($row_option =mysql_fetch_assoc($query_option)){
                $option_id =$row_option['option_id'];
            }
            else{
                echo "属性值不存在\n";
            }
            $query_exict =mysql_query("select attr_option_value_id from oc_product_attribute where product_id=".$product_id." and attribute_id=".$attr_id);
            if($row_exit =mysql_fetch_assoc($query_exict )){

                mysql_query("update oc_product_attribute set attr_option_value_id='".$option_id."' where product_id=".$product_id." and attribute_id=".$attr_id);
            }
            else{
           
                mysql_query("insert into  oc_product_attribute set attr_option_value_id='".$option_id."', product_id=".$product_id.",attribute_id=".$attr_id);
            }
        }
    }
}
$data =getexcelcontent();
set($data);

?>