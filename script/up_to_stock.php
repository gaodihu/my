<?php

include_once('./conf.php');

$_file = "up_to_stock.txt";
$data =  file($_file);
foreach($data as $item){
	$item = trim($item);
	$sql = "update new_myled.oc_product set stock_status_id = 7,quantity=9999 where model = '{$item}'";
	echo $sql ."\n";
	mysql_query($sql);
}

?>