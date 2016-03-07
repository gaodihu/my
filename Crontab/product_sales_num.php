<?php


include_once('/home/www/new_myled.com/script/conf.php');
//include_once('D:\tinker0305\script\conf2.php');
ini_set('memory_limit','1024M');
set_time_limit(0);
function get_sku_sale_data(){
    $data =array();
    $store_array =array(0,52,53,54,55,56,57);
    $time_start =date("Y-m-d H:i:s",strtotime('-30days'));
    $time_end =date("Y-m-d H:i:s",time());
   
    $sql ="select product_id from oc_product order by product_id ASC";
    $query =mysql_query($sql);
    $all_pro =array();
    while($row =mysql_fetch_assoc($query)){
        foreach($store_array as $store_id){
            $all_pro[$store_id]['sale_qty'] =0;
        }
        $data[$row['product_id']] =$all_pro;
    }
    //得到所有完成的订单
    
    foreach($store_array as $store_id){
        $sql_order ="select order_id from oc_order where order_status_id in (2,5) and date_added>='".$time_start."' and date_added<='".$time_end."' and store_id= ".$store_id;
        //$sql_order ="select order_id from oc_order where date_added>='".$time_start."' and date_added<='".$time_end."' and store_id= ".$store_id;
        $query_order =mysql_query($sql_order);

         while($row_order =mysql_fetch_assoc($query_order)){
            //得到订单下的商品
            $sql_order_poroduct ="select product_id,quantity from oc_order_product where order_id=".$row_order['order_id'];
            $query_pro =mysql_query($sql_order_poroduct);
          
            while($row_pro =mysql_fetch_assoc($query_pro)){
                if(isset($data[$row_pro['product_id']])){
                    $data[$row_pro['product_id']][$store_id]['sale_qty'] +=$row_pro['quantity'];
                }
                
               
            }
        }
    }
    return $data;
}

function update_sales_num(){
    $data =get_sku_sale_data();
    foreach($data as $key=>$value){
        foreach($value as $store=>$item){
            $sql ="update oc_product_to_store set sales_num='".$item['sale_qty']."' where product_id='".$key."' and store_id='".$store."' ";
            mysql_query($sql );
        }
    }
}
update_sales_num();
?>