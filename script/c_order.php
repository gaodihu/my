<?php 
error_reporting(E_ALL);
ini_set('display_errors','1');
include_once('./conf.php');  

mysql_select_db('new_myled');

$data = file('order_list');
var_dump($data);
foreach($data as $item){
	$order_no = trim($item);
	if($order_no){
		$sql = "UPDATE oc_order SET order_status_id =5 WHERE order_number = '{$order_no}' ";
		mysql_query($sql);
	}
	
}

?>
