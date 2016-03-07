<?php

//得到销售订单信息


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

ini_set('memory_limit','1024M');
set_time_limit(0);
function getexcelcontent(){
    $file='/home/www/new_myled.com/script/caiwu/sale_order.csv' ;
    $line_data = file($file);
    $excelData = array();
    $i = 1;
    foreach($line_data as $line){
        if($i>1) {
            $line_arr = explode(",", $line);
            foreach ($line_arr as $item) {
                $excelData[$i][] = trim($item);

            }
        }
        $i ++;

    }
    return $excelData;


    $objReader = new PHPExcel_Reader_Excel2007(); 
    $objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/caiwu/sale_order.xlsx' ;
     //$file='D://XAMPP/htdocs/new_myled/script/caiwu/sale_order.xlsx' ;
    
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
     
            $excelData[$row][] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            
        }  
    }  

    return $excelData;  
}
    

function get_order_info($order_info,$huilv=6.2244){
  $sql ="select o.order_id,o.order_number,CONCAT_WS(',',o.shipping_address_1,o.shipping_city,o.shipping_country,o.shipping_postcode) AS `o_address`,op.name,op.base_price,o. 	base_discount_amount,o.base_subtotal
    from  oc_order as o
    left join  oc_order_product as op on o.order_id=op.order_id
    where o.order_number='".$order_info[0]."' and op.model='".$order_info[1]."'";
    $query =mysql_query($sql);
    $row =mysql_fetch_assoc($query);
    if($row){
        $row['sku'] =$order_info[1];
        $row['qty'] =$order_info[2];
        $product_total =$row['base_price']*$row['qty'];
        //按比例得到商品折扣
        if(abs($row['base_discount_amount'])>0&&$row['base_subtotal']>0){
            $bili =$product_total/$row['base_subtotal'];
            $product_discount =(abs($row['base_discount_amount']))*$bili;
            $row['product_discpunt'] =number_format($product_discount*$huilv,2);
        }
        else{
            $row['product_discpunt'] =0;
        }
        
        $row['base_discount_amount'] =number_format(abs($row['base_discount_amount'])*$huilv,2);
        $row['base_price'] =number_format($row['base_price']*$huilv,2);
        $row['sales_price'] =number_format($product_total*$huilv,2);
        return $row;
    }
    else{
        $sql ="select o.order_id,o.order_number,CONCAT_WS(',',o.shipping_address_1,o.shipping_city,o.shipping_country,o.shipping_postcode) AS `o_address`
        from  oc_order as o
        where o.order_number='".$order_info[0]."'";
        $query =mysql_query($sql);
        $row2 =mysql_fetch_assoc($query);
        $row2['sku'] =$order_info[1];
        $row2['qty'] =$order_info[2];
        $row2['name'] =get_sku_name($order_info[1]);
        $row2['product_discpunt'] =0;
        if($order_info[1]=='2800926557'){
            $row2['base_price'] =0;
            $row2['sales_price'] =0;
        }
        else{
            $price =get_sku_price($order_info[1]);
            $row['base_price'] =number_format($price*$huilv,2);
            $row['sales_price'] =number_format($price*$order_info[2]*$huilv,2);
        }
        
        return $row2;
    }
}

function to_excel($data){
    $i=2;
    $objPHPExcel = new PHPExcel();
    $time =date('Y-m-d',time());
    foreach($data as $info){
        $order_data = get_order_info($info);
        if(is_array($order_data)){
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $time);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'XS'.$order_data['order_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '未付款');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'myled.com');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'RMB');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, '1');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, '贾勇江');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $order_data['order_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $order_data['o_address']);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, '534920140495694147');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'ICY');
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $time);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $order_data['sku']);
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $order_data['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $order_data['sku']);
                $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, '5001');
                $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, 'SZZW');
                $objPHPExcel->getActiveSheet()->setCellValue('AD' . $i, $order_data['qty']);
                $objPHPExcel->getActiveSheet()->setCellValue('AE' . $i, $order_data['base_price']);
                $objPHPExcel->getActiveSheet()->setCellValue('AF' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('AG' . $i, $order_data['product_discpunt']);
                $objPHPExcel->getActiveSheet()->setCellValue('AH' . $i, $order_data['sales_price']);
                $objPHPExcel->getActiveSheet()->setCellValue('AI' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('AJ' . $i, '0');
                $objPHPExcel->getActiveSheet()->setCellValue('AK' . $i,$order_data['sales_price']);
                $objPHPExcel->getActiveSheet()->setCellValue('AL' . $i, '');
                $objPHPExcel->getActiveSheet()->setCellValue('AM' . $i, $order_data['order_number']);
            }
        else{
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $order_data);
        }
        $i++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('/home/www/new_myled.com/script/caiwu/01.xlsx');
    //$objWriter->save('D://XAMPP/htdocs/new_myled/script/caiwu/01.xlsx');

}


function get_sku_name($sku){
    $query =mysql_query("select name from oc_product_description where product_id =(select product_id from oc_product where model='".$sku."') and language_id=1");
    $row =mysql_fetch_assoc($query);
    return $row['name'];
}
function get_sku_price($sku){
    $query =mysql_query("select price from oc_product where  model='".$sku."'");
    $row =mysql_fetch_assoc($query);
    return $row['price'];
}
$data =getexcelcontent();
to_excel($data);

?>