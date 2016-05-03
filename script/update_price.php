<?php
include_once('/home/www/new_myled.com/script/conf.php');

require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
 
/*
require_once("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/www.myled.com/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/  
function getexcelcontent(){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    //$objReader->setReadDataOnly(true);  
    $file='/home/www/new_myled.com/script/price.xls' ;

    if(!$objReader->canRead($file)){
        $objReader = new PHPExcel_Reader_Excel5(); 
    }
    $objPHPExcel = $objReader->load($file);
    $objWorksheet = $objPHPExcel->getActiveSheet();  
    
    $highestRow = $objWorksheet->getHighestRow();   
    $highestColumn = $objWorksheet->getHighestColumn();   
     
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
     
    $excelData = array();  
     
    for ($row = 2; $row <= $highestRow; ++$row) { 
        //for ($row = 2; $row <= 10; ++$row) { 
        for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
             $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
             $excelData[$row][] = $content;
            
        }  
    }  
    
    return $excelData;  
}


function set($data){
    foreach($data as $item){
        $sku =$item[0];
        
        $price =$item[1];
       
        
        $query_id =mysql_query("select product_id from oc_product where model='".$sku."' ");
        $res_product_id =mysql_fetch_assoc($query_id);
        $product_id =$res_product_id['product_id'];
        //设置商品special 
        $sql_u = "update oc_product set price = '{$price}' WHERE product_id = '{$product_id}'";
        echo $sql_u ."\n";
        mysql_query($sql_u);

        $sql_exit ="select product_discount_id from oc_product_discount  where product_id = ".$product_id;
		$res = mysql_fetch_row(mysql_query($sql_exit));
		if($res){
			mysql_query("update oc_product_discount set  price =".$price*0.97." where quantity =2 and product_id =".$product_id);
			mysql_query("update oc_product_discount set  price =".$price*0.93." where quantity =10 and product_id =".$product_id);
			mysql_query("update oc_product_discount set  price =".$price*0.88." where quantity =50 and product_id =".$product_id);
		}
		else{
            $data_end =date("Y-m-d H:i:s",time()+100*24*3600);
			mysql_query("insert into oc_product_discount set product_id='".$product_id."',customer_group_id=0,quantity=2,priority=1,price='".($row['value']*0.97)."',date_start=NOW(),date_end ='".$data_end."'");
            mysql_query("insert into oc_product_discount set product_id='".$product_id."',customer_group_id=0,quantity=10,priority=1,price='".($row['value']*0.93)."',date_start=NOW(),date_end ='".$data_end."'");
            mysql_query("insert into oc_product_discount set product_id='".$product_id."',customer_group_id=0,quantity=50,priority=1,price='".($row['value']*0.88)."',date_start=NOW(),date_end ='".$data_end."'");
		}

    }
}
$data =getexcelcontent();
print_r($data);

set($data);

?>