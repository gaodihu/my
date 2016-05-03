<?php


include_once('/home/www/new_myled.com/script/conf.php');
//include_once('D:\tinker0305\script\conf2.php');
ini_set('memory_limit','1024M');
set_time_limit(0);
function get_sku_sale_data(){
    $data =array();
    $time_start ='2015-01-01 00:00:00';
    $time_end ='2015-03-25 24:00:00';
   
    $sql ="select distinct p.product_id,p.model,p.weight,p.date_added,ag.attribute_group_code as attribute_set,pd.name,p.product_code,p.supplier_code,p.stock_status_id,p.quantity
from oc_product as p
left join oc_product_description as pd on p.product_id =pd.product_id and pd.language_id=1
left join oc_product_attribute_group as pag on p.product_id =pag.product_id
left join oc_attribute_group as ag on pag.attribute_group_id =ag.attribute_group_id 
order by p.product_id ASC";
    $query =mysql_query($sql);
    $all_pro =array();
    while($row =mysql_fetch_assoc($query)){
        if($row['stock_status_id']!=7){
            $row['in_stock'] =0;
        }else{
            $row['in_stock'] =1;
        }
        $row['ship_fee'] =0;
        $row['sale_qty'] =0;
        $row['sale_original_total'] =0;
        $row['sale_total'] =0;
        $row['shipping_method'] ='';
        $row['shipping_country'] ='';
        $row['shipping_city'] ='';
        $all_pro[$row['model']] =$row;
    }
    //得到所有完成的订单
    $sql_order ="select order_id,shipping_method,shipping_country,shipping_zone,shipping_city,base_shipping_amount from oc_order  where order_status_id in (2,5) and date_added>='".$time_start."' and date_added<='".$time_end."' ";
    //$sql_order ="select order_id,shipping_method,shipping_country,shipping_zone,shipping_city,base_shipping_amount from oc_order  where date_added>='".$time_start."' and date_added<='".$time_end."' ";
    $query_order =mysql_query($sql_order);

    while($row_order =mysql_fetch_assoc($query_order)){
        //得到订单下的商品
        //订单总运费
        $total_shipping_fee =$row_order['base_shipping_amount'];
        
        $sql_order_poroduct ="select model,quantity,total,base_price,original_price from oc_order_product where order_id=".$row_order['order_id'];
        $query_pro =mysql_query($sql_order_poroduct);
        //订单总重量
        $total_order_pro_weight =get_product_total_weight($row_order['order_id']); 
        while($row_pro =mysql_fetch_assoc($query_pro)){
            $all_pro[$row_pro['model']]['shipping_method'] =$row_order['shipping_method'];
            $all_pro[$row_pro['model']]['shipping_country'] =$row_order['shipping_country'];
            $all_pro[$row_pro['model']]['shipping_city'] =$row_order['shipping_city'];
            $all_pro[$row_pro['model']]['sale_qty'] +=$row_pro['quantity'];
            $all_pro[$row_pro['model']]['sale_total'] +=$row_pro['total'];
            $all_pro[$row_pro['model']]['sale_original_total'] +=$row_pro['quantity']*$row_pro['original_price'];
            $all_pro[$row_pro['model']]['ship_fee'] +=(($all_pro[$row_pro['model']]['weight']*$row_pro['quantity'])/$total_order_pro_weight)*$total_shipping_fee;
        }
    }
    //计算毛利额和毛利率
    //订单毛利率 = (销售额-运费-订单产品原价/2) / 销售额 * 100% 
    //商品毛利率 = (销售额-运费-订单产品原价/2) / (销售额-运费) * 100% 
    foreach($all_pro as $key=>$item){
        $maoli =round($item['sale_total']-($item['sale_original_total']/2),4);
        if($maoli){
            $maoli_pre =round((($item['sale_total']-($item['sale_original_total']/2))/$item['sale_total']),4)*100;
        }
        else{
            $maoli_pre=0;
        }
        $item['maoli_e'] =$maoli;
        $item['maoli_pre'] =$maoli_pre;
        $data[$key] =$item;
    }
    return $data;
}

//得到订单商品总重量
function get_product_total_weight($order_id){
    $sql_order_poroduct ="select model,quantity,base_price,original_price from oc_order_product where order_id=".$order_id;
    $query_pro =mysql_query($sql_order_poroduct);
    //订单总重量
    $total_order_pro_weight =0;
    while($row_pro =mysql_fetch_assoc($query_pro)){
        $pro_weight =get_product_weight($row_pro['model']);
        $total_order_pro_weight +=$pro_weight*$row_pro['quantity'];
    }
    return $total_order_pro_weight;
}
//得到商品重量
function get_product_weight($model){
    $query =mysql_query("select weight from oc_product where model ='".$model."' limit 1");
    while($row =mysql_fetch_assoc($query)){
        return $row['weight'];
    }
    
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
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SKU');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SKU大类');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', '产品名称');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', '供应商型号');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'RMB成本');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', '供应商代码');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', '供应商名称');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', '上架时间');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', '上下架状态');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', '库存模式');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', '库存数量');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', '库存金额');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', '销售数量');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', '销售成本');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', '销售金额');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', '订单号码');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', '订单日期');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', '订单金额');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', '买家支付运费');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', '保宏手续费');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', '包装费用');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', '运输方式');
    $objPHPExcel->getActiveSheet()->setCellValue('W1', '我方标准运费合计');
    $objPHPExcel->getActiveSheet()->setCellValue('X1', '收货人国家');
    $objPHPExcel->getActiveSheet()->setCellValue('Y1', '收货人城市');
    $objPHPExcel->getActiveSheet()->setCellValue('Z1', '退款金额');
    $objPHPExcel->getActiveSheet()->setCellValue('AA1', '毛利额');
    $objPHPExcel->getActiveSheet()->setCellValue('AB1', '毛利率');

    $product_datas =get_sku_sale_data();
    $i=2;
    foreach($product_datas as $item){
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $item['model']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $item['attribute_set']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $item['name']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $item['product_code']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, '');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $item['supplier_code']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, '');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $item['date_added']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,  $item['in_stock']?'在售':'下架');
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,  $item['quantity']>0?$item['quantity']:0);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,  $item['sale_qty']);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i,  $item['sale_total']);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$i,  $item['ship_fee']);
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$i,  $item['shipping_method']);
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$i,  $item['shipping_country']);
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i,  $item['shipping_city']);
        $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i,  '');
        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i,  $item['maoli_e']);
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i,  $item['maoli_pre']);
        $i++;
    }
    $objPHPExcel->getActiveSheet()->setTitle("网站订单统计数据");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$file_path="D://tinker0305/script/erp_api/sale_".date('Y-m-d',time()).".xlsx";
    $file_path="/home/www/new_myled.com/script/erp_api/sale_".date('Y-m-d H').".xlsx";
	$objWriter->save($file_path);
}
get_excel();
?>