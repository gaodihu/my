<?php

//导出银行结汇信息

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
    //$objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/caiwu/jiehui_1225.xlsx' ;
    //$file='D://XAMPP/htdocs/new_myled/script/caiwu/jiehui_1120.xlsx' ;    
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
            $excelData[$row][] = $content;
        }  
    }  
    
    return $excelData;  
}

function to_excel(){
     //$out_file ="D://XAMPP/htdocs/new_myled/script/caiwu/jiehui.xlsx";
    $out_file ="/home/www/new_myled.com/script/caiwu/jiehui.xlsx";
    $attr_array = getexcelcontent();
    $objPHPExcel = new PHPExcel();
    $objActSheet=$objPHPExcel->getActiveSheet();
    $i=2;
    foreach($attr_array as $item){
        $order_number =$item[1];
        //得到订单信息
        $sql ="select shipping_firstname,shipping_lastname,payment_code,payment_method,currency_code,subtotal,grand_total,base_grand_total from oc_order where order_number='".$order_number."' limit 1 ";
        $query =mysql_query($sql);
        $row =mysql_fetch_assoc($query);
        if($row){
            $objActSheet->setCellValue("A".$i, $item[0]);
            $objActSheet->setCellValue("B".$i, $item[1]);
            $objActSheet->setCellValue("C".$i, $row['shipping_firstname'].' '.$row['shipping_lastname']);
            $objActSheet->setCellValue("D".$i, $row['payment_code']);
            $objActSheet->setCellValue("E".$i, $row['payment_method']);
            $objActSheet->setCellValue("F".$i, $row['currency_code']);
            $objActSheet->setCellValue("G".$i, $row['subtotal']);
            $objActSheet->setCellValue("H".$i, $row['grand_total']);
            $objActSheet->setCellValue("I".$i, $row['base_grand_total']);
        }
        else{
            $objActSheet->setCellValue("A".$i, $item[0]);
            $objActSheet->setCellValue("B".$i, $item[1]);
            $objActSheet->setCellValue("C".$i, '订单不存在');
        }
        
        $i++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);

}
to_excel();

  
?>