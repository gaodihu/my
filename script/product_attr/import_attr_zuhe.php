<?php
/****
***
***  导入商品的归集组合
**
*/
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
    $file='/home/www/new_myled.com/script/product_attr/pro_zuhe_attr.xlsx' ;
    //$file='D://XAMPP/htdocs/new_myled/script/product_attr/pro_zuhe_attr.xlsx' ;    
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
function into_table(){
    $data =getexcelcontent();
    foreach($data as $pro_attr){
        $gorup_id =$pro_attr[0];
        $sku =$pro_attr[1];
        $product_id =get_product_id($sku);
        if($product_id){
            foreach($pro_attr as $key=>$attr_value){
                if($key>1&&$attr_value){
                    //得到属性ID
                    $query_attr_id =mysql_query("select attribute_id from oc_new_attribute_description where language_id=1 and name='".$attr_value."' ");
                    $row =mysql_fetch_assoc($query_attr_id);
                    if($row){
                        $attr_id =$row['attribute_id'];
                        //得到该sku属性值
                        $query_option_id =mysql_query("select attr_option_value_id from oc_new_product_attribute where product_id=".$product_id." and attribute_id='".$attr_id."' ");
                        $row_query_option_id =mysql_fetch_assoc($query_option_id);
                        $option_id =$row_query_option_id['attr_option_value_id'];
                        $query_rec_exit =mysql_query("select paf_id from oc_product_attr_filter where  group_id='".$gorup_id."' and product_id='".$product_id."' and attr_id='".$attr_id."' limit 1");
                        if(!$row =mysql_fetch_assoc($query_rec_exit)){
                            mysql_query("INSERT INTO oc_product_attr_filter set group_id='".$gorup_id."',product_id='".$product_id."',attr_id='".$attr_id."',value_id='".$option_id."' ");
                        }
                    }
                    else{
                        echo $sku."商品属性名".$attr_value."不存在"."<br>";
                    }
                }
            }
        }
        else{
            echo $sku."商品不存在"."<br>";
        }
        
    }
}

function get_product_id($sku){
    $query =mysql_query("select product_id from oc_product where model='".$sku."' limit 1");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['product_id'];
    }
    else{
        return false;
    }
}
into_table();
?>


