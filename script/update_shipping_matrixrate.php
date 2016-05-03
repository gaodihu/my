<?php

ini_set('memory_limit','1024M');
set_time_limit(0);
include_once('/home/www/new_myled.com/script/conf.php'); 


$file = '/home/www/new_myled.com/script/shipping.csv';
$fp = fopen($file ,'r');
while(!feof($fp)){
    $row =fgetcsv($fp);
    //$sql_county = "select country_id from directory_country where iso3_code ='$row[0]' ";
    //$query =mysql_query($sql_county);
    //$res =mysql_fetch_row($query);
    //$iso_code = $res[0];
    $sql_insert = "INSERT INTO oc_shipping_matrixrate set website_id=1,dest_country_id='".$row[1]."',condition_name='".$row[6]."',condition_from_value='".$row[7]."',condition_to_value='".$row[8]."',price='".$row[9]."',cost=0,delivery_type='".$row[11]."',delivery_method='".$row[12]."' ";
    mysql_query($sql_insert);
}

?>