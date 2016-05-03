<?php
/*
*
*得到gogo所需备案商品信息
* 只需更改sql语句中where条件的月份
*/

	include_once('conf.php'); 
	
	function get_gogo_order_info(){
		$sql = "SELECT
				o.order_id,
                o.date_added as created_at,
				p.model as SKU,
				pd.name as pName,
				p.product_code as pSupplierSKU,
				p.supplier_code as psupplierCode,
				p.price as price,
				op.quantity as qty_ordered
				FROM oc_order as o
				INNER JOIN oc_order_product as op  ON o.order_id = op.order_id
				INNER JOIN oc_product as p on op.product_id=p.product_id
				INNER JOIN oc_product_description as pd  ON pd.product_id=op.product_id
                where (o.order_status_id=2 or o.order_status_id=5) and pd.language_id=1 and o.date_added >='2014-12-00 00:00:00' and o.date_added <='2014-12-31 24:00:00'
				";
		$query =mysql_query($sql);
		$res =array();
		while($row=mysql_fetch_assoc($query)){
			$res[] =$row;
		}
		return $res;
	}
	
	function get_pur_order_info(){
		$order_info =get_gogo_order_info();
		$tmpArray = array();
		foreach ($order_info as $row) {
			$key = $row['SKU'];
			if (array_key_exists($key, $tmpArray)) {
				$tmpArray[$key]['qty'] = $tmpArray[$key]['qty']+ intval($row['qty_ordered']);
				$tmpArray[$key]['order_count']++;
                $end=strtotime($row['created_at']);
                $start =strtotime($tmpArray[$key]['created_at']);
                $day=intval(($end-$start)/(24*3600));
                $tmpArray[$key]['day']=$day;
			} else {
				$tmpArray[$key]['sku'] = $row['SKU'];
                 $tmpArray[$key]['created_at'] = $row['created_at'];
                $tmpArray[$key]['day'] = 0;
				$tmpArray[$key]['psupplierSKU'] = $row['pSupplierSKU'];
				$tmpArray[$key]['psupplierCode'] = $row['psupplierCode'];
				$tmpArray[$key]['qty'] = intval($row['qty_ordered']);
				$tmpArray[$key]['order_count']=1;
				$tmpArray[$key]['price']=$row['price'];
				$tmpArray[$key]['pname'] = $row['pName'];
			}
		}
		return $tmpArray;
	}
	function get_gogo_csv(){
		$order_info =get_pur_order_info();
		//$date =date('Ymd',time());
		$file_path ='/home/www/new_myled.com/script/';
        //$file_path ='D://';
		if(!is_dir($file_path)){
			mkdir($file_path,0777,1);
		}
		$file ='gogo_order_info.csv';
		$fp = fopen($file_path.$file, 'w+');
		chmod($file_path.$file, 0777);
		fputcsv($fp,array('SKU','created_at','day','pSupplierSKU','psupplierCode','qty_count','order_count','price','pName',));
		foreach($order_info as $order){
			if($order){
				fputcsv($fp,$order); 
			}
		}
		fclose($fp);
	}
	get_gogo_csv();
?>