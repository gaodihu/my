<?php


include_once('/home/www/new_myled.com/script/conf.php');
ini_set('memory_limit','1024M');
set_time_limit(0);

function get_all_sku($file_path){
    $data =array();
    $fp =fopen($file_path,'r');
     while(!feof($fp)){
        $res =fgetcsv($fp );
        $sku =trim($res[0]);
        $data[] =$sku;
    }
    return $data;
}
function get_sku_image($sku){
   $image_data =array();
   $sql ="select product_id,image from oc_product where model ='".$sku."' ";
   $query = mysql_query($sql);
   $res1 =mysql_fetch_assoc($query );
   $image_data[] =$res1['image'];
   $sql2 ="select image from oc_product_image where product_id ='".$res1['product_id']."' ";
   $query2 =mysql_query($sql2);
   while($row =mysql_fetch_assoc($query2 )){
        $image_data[] =$row['image'];
   }
    
    return $image_data;
}
$file_path ="/home/www/new_myled.com/script/erp_api/sku_pic.csv";
$input_dir ="/home/www/new_myled.com/script/erp_api/sku_image/";
if(!is_dir($input_dir)){
    mkdir($input_dir);
}

$sku_data =get_all_sku($file_path);
foreach($sku_data as $sku){
    if($sku){
        $image_data =array_unique(get_sku_image($sku));
        if(!is_dir($input_dir.$sku)){
            mkdir($input_dir.$sku);
        }
        foreach($image_data as $key=>$image){
            $str_arr =explode('/',$image);
            $name =end($str_arr);
            copy('https://www.myled.com/image/'.$image,$input_dir.$sku.'/'.$name);
        }
    }
}
?>