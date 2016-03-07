<?php
include_once('/home/www/new_myled.com/script/conf.php');
//include_once("D://tinker0414/script/conf2.php");
function update_order_close(){
    $_31_days_time = date('Y-m-d H:i:s',time()-31*24*3600);
    $sql ="select order_id from oc_order where order_status_id=1  and date_added<='".$_31_days_time."'";
    $query =mysql_query($sql);
    while($row =mysql_fetch_assoc($query)){
        //归还积分
        mysql_query("UPDATE oc_customer_reward SET points_spent=0 where  order_id=".$row['order_id']);
        mysql_query("update oc_order set order_status_id=18 where order_id=".$row['order_id']);
    }

}
update_order_close();

?>