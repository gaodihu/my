<?php
$dir = str_replace("\\","/",substr(dirname(__FILE__),0,-5));

include_once( $dir . 'config.php');
$db = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,true);
mysql_select_db(DB_DATABASE,$db);
mysql_query("set names utf8");

function unlock($db,$is_online,$start_time,$end_time){
    if($is_online){
        $online_sql = "select order_id from oc_order where   date_added >= '{$start_time}' AND  date_added <'{$end_time}' AND order_status_id = 1  AND  payment_code not in ('westernunion','bank_transfer')";
    }else{
        $online_sql = "select order_id from oc_order where   date_added >= '{$start_time}' AND  date_added <'{$end_time}' AND order_status_id = 1  AND  payment_code in ('westernunion','bank_transfer')";
    }
    $online_rs = mysql_query($online_sql,$db);
    while($online_row = mysql_fetch_assoc($online_rs)){
        $order_id = $online_row['order_id'];
        $order_product_query = mysql_query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'",$db);
        while ($order_product_row = mysql_fetch_assoc($order_product_query)) {
            $quantity   =  $order_product_row['quantity'];
            $product_id =  $order_product_row['product_id'];
            $order_product_id = $order_product_row['order_product_id'];
            $order_product_status_sql = "select * from oc_order_product_stock_unlock where order_product_id = '{$order_product_id}' ";
            $order_product_status_rs = mysql_query($order_product_status_sql,$db);
            $order_product_status_row = mysql_fetch_assoc($order_product_status_rs);
            if(!$order_product_status_row){
                 mysql_query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int) $quantity . ") WHERE product_id = " . (int) $product_id);
                 $update_order_product_status_sql = "insert into oc_order_product_stock_unlock(order_product_id,status,date_add) value('{$order_product_id}','1',now())";
                 
                 mysql_query($update_order_product_status_sql,$db);
            }else{
                if($order_product_status_row['status'] == 0){
                    mysql_query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int) $quantity . ") WHERE product_id = " . (int) $product_id);
                    $update_order_product_status_sql = "update oc_order_product_stock_unlock set status = 1, date_add = now() where order_product_id = '{$order_product_id}'";
                    mysql_query($update_order_product_status_sql,$db);
                   
                }
            }

        }
    }

}
//更新在线支付的，30分钟没有支付的订单就回退库存数量
$end_time_30     = time() - 30 * 60;
$end_time_30_s   = date('Y-m-d H:i:s',$end_time_30);
$start_time_30   = time() - 60 * 60;
$start_time_30_s = date('Y-m-d H:i:s',$start_time_30);
unlock($db,1,$start_time_30_s,$end_time_30_s);

//更新在线支付的，7天没有支付的订单就回退库存数量
$end_time_30     = time() - 7 * 60 * 60;
$end_time_30_s   = date('Y-m-d H:i:s',$end_time_30);
$start_time_30   = time() - 14 * 60 * 60;
$start_time_30_s = date('Y-m-d H:i:s',$start_time_30);
unlock($db,0,$start_time_30_s,$end_time_30_s);

//支付失败的直接恢复库存
function cancel_unlock($db,$start_time,$end_time){
    $online_sql = "select order_id from oc_order where   date_added >= '{$start_time}' AND  date_added <'{$end_time}' AND order_status_id = 7 ";
    $online_rs = mysql_query($online_sql,$db);
    while($online_row = mysql_fetch_assoc($online_rs)){
        $order_id = $online_row['order_id'];
        
        $order_history_sql = "SELECT count(cnt) as cnt FROM " . DB_PREFIX ."order_history WHERE order_id = '{$order_id}' and  order_status_id in(2,5)";
        $order_history_query = mysql_query($order_history_sql,$db);
		if($order_history_query){
			$order_history_row = mysql_fetch_assoc($order_history_query);
			
			if($order_history_row){
				$order_history_cnt = $order_history_row['cnt'];
				if($order_history_cnt > 0){
					continue;
				}
			}
		}
        
        $order_product_query = mysql_query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'",$db);
        while ($order_product_row = mysql_fetch_assoc($order_product_query)) {
            $quantity   =  $order_product_row['quantity'];
            $product_id =  $order_product_row['product_id'];
            $order_product_id = $order_product_row['order_product_id'];
            $order_product_status_sql = "select * from oc_order_product_stock_unlock where order_product_id = '{$order_product_id}' ";
            $order_product_status_rs = mysql_query($order_product_status_sql,$db);
            $order_product_status_row = mysql_fetch_assoc($order_product_status_rs);
            if(!$order_product_status_row){
                 mysql_query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int) $quantity . ") WHERE product_id = " . (int) $product_id);
                 $update_order_product_status_sql = "insert into oc_order_product_stock_unlock(order_product_id,status,date_add) value('{$order_product_id}','1',now())";
                 
                 mysql_query($update_order_product_status_sql,$db);
            }else{
                if($order_product_status_row['status'] == 0){
                    mysql_query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int) $quantity . ") WHERE product_id = " . (int) $product_id);
                    $update_order_product_status_sql = "update oc_order_product_stock_unlock set status = 1, date_add = now() where order_product_id = '{$order_product_id}'";
                    mysql_query($update_order_product_status_sql,$db);
                   
                }
            }

        }
    }
}

//更新在线支付的，30分钟没有支付的订单就回退库存数量
$end_time_30     = time() - 30 * 60;
$end_time_30_s   = date('Y-m-d H:i:s',$end_time_30);
$start_time_30   = time() - 60 * 60;
$start_time_30_s = date('Y-m-d H:i:s',$start_time_30);
cancel_unlock($db,$start_time_30_s,$end_time_30_s);
