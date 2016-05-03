<?php


include_once('/home/www/new_myled.com/script/conf.php');
//include_once('D:\tinker0305\script\conf2.php');
ini_set('memory_limit','1024M');
set_time_limit(0);

   
//得到所有完成的订单
$sql_order ="SELECT SUM(base_grand_total) AS total, sum(base_subtotal) as product_total,sum(base_shipping_amount) as total_shipping_cost FROM oc_order WHERE order_status_id in(2,5) AND date_added>='2015-05-01 00:00:00' and date_added<='2015-05-18 24:00:00' ";
/*
$sql_order ="select o.date_added,o.order_number,op.model as sku,op.quantity,op.original_price,
op.price as base_price ,o.base_shipping_amount,o.base_discount_amount,o.base_grand_total
from oc_order as o
left join oc_order_product as op  on o.order_id =op.order_id
where o.order_status_id in (2,5) and  o.date_added>='2015-05-01 00:00:00' and o.date_added<='2015-05-18 24:00:00' "
*/
$query_order =mysql_query($sql_order);
$res = mysql_fetch_assoc($query_order);
//计算毛利
 //订单毛利率 = (销售额-运费-订单产品原价/2) / 销售额 * 100% 
//产品毛利率 = (销售额-运费-订单产品原价/2) / (销售额-运费) * 100% 
$total_sub_price =getOrderSubOriginal('2015-05-01 00:00:00','2015-05-18 24:00:00');
$order_maoli =($res['total']-$res['total_shipping_cost']-$total_sub_price/2)/$res['total']*100;
$product_maoli =($res['total']-$res['total_shipping_cost']-$total_sub_price/2)/($res['total']-$res['total_shipping_cost'])*100;
echo  $order_maoli."\r\n";//27.410915813541
echo  $product_maoli."\r\n";//38.930806147654

function getOrderSubOriginal($start,$end){
        $SubOriginal =0;
        $sql ="select order_id from oc_order
        where order_status_id in(2,5) AND date_added >='".$start."' and date_added<='".$end."'";
        $query =mysql_query($sql);
        while($row =mysql_fetch_assoc($query)){
            $query_pro =mysql_query("select sum(quantity*original_price) as original_total from oc_order_product where order_id=".$row['order_id']);
            $res_row =mysql_fetch_assoc($query_pro);
            $original_total =$res_row['original_total'];
            $SubOriginal +=$original_total ;
        }
        return $SubOriginal;
    }
/*
total	product_total	total_shipping_cost
81980.88	60342.30	24258.70

*/
?>

