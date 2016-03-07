<?php 
//mageto 数据和现有网站数据同步

include_once('./conf.php'); 
 $start_time = strtotime('2014-10-25 00:00:00');
 $str_data = '';
 $array_pay_method = array('pp_standard','pp_express','globebill_credit','globebill_sofortbanking','globebill_giropay','globebill_safety','globebill_qiwi','globebill_webmoney','globebill_poli','globebill_boleto');
 $pay_method = 'pp_standard';
 for($i=0;$i<7;$i++){
     $start_time_s =  date('Y-m-d H:i:s',$start_time + $i * 24 * 60 * 60);
     $end_time_s   =  date('Y-m-d H:i:s',$start_time + ($i+1) * 24 * 60 * 60)  ;
     $row_str_data = '';
     foreach($array_pay_method as $pay_method){
        //支付成功
        $sql_yes = "SELECT count(*) as cnt FROM `oc_order` WHERE  `order_status_id` in (2,5) and  payment_code = '{$pay_method}' and  `date_added` >= '{$start_time_s}' and   `date_added` < '{$end_time_s}' and  email not like '%myled.com'";
        $rs_yes  = mysql_query($sql_yes);
        $row_yes = mysql_fetch_assoc($rs_yes);
        $cnt_yes = $row_yes['cnt'];
        
        //支付pending
        $sql_pending = "SELECT count(*) as cnt FROM `oc_order` WHERE  `order_status_id` in (1) and  payment_code = '{$pay_method}' and  `date_added` >= '{$start_time_s}' and   `date_added` < '{$end_time_s}' and  email not like '%myled.com'";
        $rs_pending  = mysql_query($sql_pending);
        $row_pending = mysql_fetch_assoc($rs_pending);
        $cnt_pending = $row_pending['cnt'];
        
        
        //支付 canceled
        $sql_canceled = "SELECT count(*) as cnt FROM `oc_order` WHERE  `order_status_id` in (7) and  payment_code = '{$pay_method}' and  `date_added` >= '{$start_time_s}' and   `date_added` < '{$end_time_s}' and  email not like '%myled.com'";
        $rs_canceled  = mysql_query($sql_canceled);
        $row_canceled = mysql_fetch_assoc($rs_canceled);
        $cnt_canceled = $row_canceled['cnt'];
        
        
        
        $sql_all = "SELECT count(*) as cnt FROM `oc_order` WHERE   `date_added` >= '{$start_time_s}' and  payment_code = '{$pay_method}' and   `date_added` < '{$end_time_s}' and  email not like '%myled.com'";
        $rs_all  = mysql_query($sql_all);
        $row_all = mysql_fetch_assoc($rs_all);
        $cnt_all = $row_all['cnt'];

        $row_str_data .=  "," .$cnt_all . "," .$cnt_yes . "," .$cnt_pending . "," .$cnt_canceled . "," .$cnt_yes/$cnt_all ;
     }
     $str_data  .=  date('Y-m-d', strtotime($start_time_s)) .  $row_str_data . "\n";
 }
 $header = '';
  foreach($array_pay_method as $pay_method){
      $header_2 = '';
      for($i=0;$i<5;$i++){
          $header .= ',' . $pay_method ;
      }
      $header_2 .= ',all,success,pending,cabceled,rate';
 
 }
$header  = 'date' . $header. "\n";
$header .= 'date' . $header_2 ."\n";

file_put_contents('order_pay.csv', $header. $str_data);
