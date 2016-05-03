<?php

//导出商品归集数据
/*
include_once('/home/www/new_myled.com/script/conf.php');



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
*/
//define('ROOT_PATH','D://XAMPP/htdocs/new_myled/');
define('ROOT_PATH','/home/www/new_myled.com/');
include_once(ROOT_PATH.'script/conf.php');
require_once(ROOT_PATH."system/lib/PHPExcel/PHPExcel.php");
require_once (ROOT_PATH."system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once (ROOT_PATH."system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式

function getData(){
    $data =array();
    $sql ="select  pa.group_id,p.model, p.supplier_code,nad.name,naov.option_value from oc_product_attr_filter as pa
            left join oc_product as p on pa.product_id =p.product_id
            left join oc_new_attribute_description as nad on pa.attr_id=nad.attribute_id and nad.language_id=1
            left join oc_new_attribute_option_value as naov on pa.value_id=naov.option_id and naov.language_id=1";
    $query=mysql_query($sql);
    while($row =mysql_fetch_assoc($query)){
        if(isset($data[$row['model']])){
            $data[$row['model']][] =$row['name'];
            $data[$row['model']][] =$row['option_value'];
        }
        else{
            $data[$row['model']][] =$row['group_id'];
            $data[$row['model']][] =$row['model'];
            $data[$row['model']][] =$row['supplier_code'];
            $data[$row['model']][] =$row['name'];
            $data[$row['model']][] =$row['option_value'];
        }
    }
    return $data;
}
function out_excel(){
    $data =getData();
    $out_file =ROOT_PATH."script/product_attr/pro_zuhe_attr_out.xlsx";
    $objPHPExcel = new PHPExcel();
    $objActSheet=$objPHPExcel->getActiveSheet();
    $objActSheet->setTitle("商品组合属性");
    $objPHPExcel->setActiveSheetIndex(0);
    $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','I','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH');
    $i=1;
    foreach($data as $model=>$item){
        $count =count($item);
        
        foreach($item as $key=>$value){
             $objActSheet->setCellValue($tmp_array[$key].$i, $value);
        }
       $i++;
    }
  
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);
}
out_excel();
?>


