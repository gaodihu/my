<?php


include_once('/home/www/new_myled.com/script/conf.php');
//include_once('D:\tinker0305\script\conf2.php');
ini_set('memory_limit','1024M');
set_time_limit(0);
function get_sku_sale_data($mouth){
    $data =array();
    if($mouth==1){
        $time_start ='2015-01-01 00:00:00';
        $time_end ='2015-01-31 24:00:00';
    }elseif($mouth==2){
        $time_start ='2015-02-01 00:00:00';
        $time_end ='2015-02-31 24:00:00';
    }elseif($mouth==3){
        $time_start ='2015-03-01 00:00:00';
        $time_end ='2015-03-31 24:00:00';
    }
    //得到所有完成的订单
    $sql_order ="select order_id,shipping_method,shipping_country,shipping_zone,shipping_city,base_shipping_amount from oc_order
    where order_status_id in (2,5) and date_added>='".$time_start."' and date_added<='".$time_end."' ";
    //$sql_order ="select order_id,shipping_method,shipping_country,shipping_zone,shipping_city,base_shipping_amount from oc_order where date_added>='".$time_start."' and date_added<='".$time_end."' ";
    $query_order =mysql_query($sql_order);

    while($row_order =mysql_fetch_assoc($query_order)){
        //得到订单下的商品
        //订单总运费
        $total_shipping_fee =$row_order['base_shipping_amount'];
        
        $sql_order_poroduct ="select model,quantity,total,base_price,original_price from oc_order_product where order_id=".$row_order['order_id'];
        $query_pro =mysql_query($sql_order_poroduct);
        while($row_pro =mysql_fetch_assoc($query_pro)){
            $data[$row_pro['model']]['model'] =$row_pro['model'];
            $data[$row_pro['model']]['sale_qty'] +=$row_pro['quantity'];
            $data[$row_pro['model']]['sale_total'] +=$row_pro['total'];
        }
   }
    return $data;
}

function get_product_info($sku){
     $sql ="select product_code,supplier_code from oc_product where model='".$sku."' limit 1";
     $query =mysql_query($sql);
     return mysql_fetch_assoc($query);
    
}
function get_excel(){
    
    
    require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
    require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
    require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
    
    /*
    require_once("D://tinker0305/system/lib/PHPExcel/PHPExcel.php");
    require_once ("D://tinker0305/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
    require_once ("D://tinker0305/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式
    */
    $objPHPExcel = new PHPExcel();
   for($j=1;$j<=3;$j++){
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($j-1);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '月份');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SKU');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '供应商型号');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '供应商代码');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '成交数量');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '成交金额');
        $product_datas =get_sku_sale_data($j);
        $i=2;
        foreach($product_datas as $sku=>$item){
            $pro_info =get_product_info($sku);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $j.'月份');
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $item['model']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $pro_info['product_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $pro_info['supplier_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $item['sale_qty']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $item['sale_total']);
           
            $i++;
        }
   }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$file_path="D://tinker0305/script/erp_api/ju_sale_".date('Y-m-d',time()).".xlsx";
    $file_path="/home/www/new_myled.com/script/erp_api/ju_sale_".date('Y-m-d H').".xlsx";
	$objWriter->save($file_path);
}
get_excel();
?>