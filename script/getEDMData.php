<?php
include_once('./conf.php'); 
define('DIR_SYSTEM', '/home/www/new_myled.com/system/');
error_reporting(7);
ini_set('display_errors', 1);
$order_start_time =  '2014-11-12 00:00:00';

/*
$header_str =  'order_number'. ',' . 'email' . ',' .'store' . "," . 'payment country code' . "," . 'shipping country code' . ',' . 'paypal email' ;
$header_str .= $header_str ."\n";
file_put_contents('order-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $header_str,FILE_APPEND);
$order_sql = "select * from oc_order where date_added > '{$order_start_time}' ";
$order_rs  = mysql_query($order_sql);
$order_str = '';
while($order = mysql_fetch_assoc($order_rs)){
    $order_str = '';
    $order_id = $order['order_id'];
    $paypal_payer_email = '';
    
    if( in_array($order['payment_code'],array('pp_standard','pp_express')) ){
        $paypal_sql = "select debug_data FROM oc_paypal_order_transaction  t left join oc_paypal_order o on o.paypal_order_id = t.paypal_order_id   where o.order_id = '{$order_id}' and debug_data != ''";
        $paypal_rs  = mysql_query($paypal_sql);
        
        while($paypal_row = mysql_fetch_assoc($paypal_rs)){
            $debug_data = $paypal_row['debug_data'];
            $debug_data_arr = json_decode($debug_data,true);
           
            if(isset($debug_data_arr['payer_email'])){
                $paypal_payer_email = $debug_data_arr['payer_email'];
                
            }
        }
    }
    $str =  $order['order_number'] . ',' . $order['email'] . ',' . get_store_name($order['store_id']) . "," . $order['payment_country_code'] . "," . $order['shipping_country_code'] . ',' . $paypal_payer_email ;
    $order_str .= $str ."\n";
    file_put_contents('order-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $order_str,FILE_APPEND);
    unset($order_str);
}

*/

$header_c_str = 'email' . ',' . 'store_id' . ',' . 'country_code' . "\n";
 
file_put_contents('customer-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $header_c_str,FILE_APPEND) ;


require_once  DIR_SYSTEM .'lib/geoip/geoip.inc';
$gi = geoip_open( DIR_SYSTEM ."lib/GeoIP.dat/GeoIP.dat", GEOIP_STANDARD );

$customer_str = '';
$start_customer_id = 0;
$customer_sql = "SELECT * FROM  `oc_customer` WHERE  `date_added` > '{$order_start_time}'";
$customer_rs  = mysql_query($customer_sql);
while($customer_row = mysql_fetch_assoc($customer_rs)){
    $customer_str = '';
    $ip = $customer_row['ip'];
    $country_code = '';
    if($ip){
        $record = geoip_country_code_by_addr($gi,$ip);
        var_dump($record);
        $country_code = $record;
    }
    $c_str = $customer_row['email'] . ',' . get_store_name($customer_row['store_id']) . ',' . $country_code . "\n";
    $customer_str .= $c_str;
    file_put_contents('customer-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $customer_str,FILE_APPEND) ;
}



$header_c_str = 'email' . ',' . 'store_id' . ',' . 'country_code' ."\n";
file_put_contents('newsletter-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $header_c_str,FILE_APPEND) ;

$newsletter_str = '';
$start_newsletter_id = 0;
$newsletter_sql = "SELECT * FROM  `oc_newsletter` WHERE  `created_time` >'{$order_start_time}'";
$newsletter_rs  = mysql_query($newsletter_sql);
while($newsletter_row = mysql_fetch_assoc($newsletter_rs)){
    $newsletter_str = '';
    $ip = $newsletter_row['ip_address'];
    $country_code = '';
    if($ip){
        $record = geoip_country_code_by_addr($gi,$ip);
        $country_code =  $record;
    }
    $c_str = $newsletter_row['email'] . ',' . get_store_name($newsletter_row['store_id']) . ',' . $country_code ."\n";
    $newsletter_str .= $c_str;
    file_put_contents('newsletter-' . date('Y-m-d',strtotime($order_start_time)).'.csv', $newsletter_str,FILE_APPEND) ;
}




function get_store_name($store_id){
    static $store_arr = array(
        '0'  => 'www',
        '52' => 'de',
        '53' => 'es',
        '54' => 'fr',
        '55' => 'it',
        '56' => 'pt',
        '57' => 'm',
    );
    return $store_arr[$store_id];
}

