<?php
$dir = str_replace("\\",'/',__DIR__);
$dir = substr($dir,0,0 -strlen('Crontab'));
define('CURRENT_ATH',$dir);

include_once(CURRENT_ATH . 'Application/config.php');
require_once(CURRENT_ATH . "Application/system/lib/PHPExcel/PHPExcel.php");
require_once(CURRENT_ATH . "Application/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once(CURRENT_ATH . "Application/system/lib/PHPExcel/PHPExcel/Writer/Excel2007.php"); // 用于 excel-2007 格式
ini_set('memory_limit','1024M');

$db=mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD) or die("Unable to connect to the mageto MySQL!");
//$db_connect_opencart=mysql_connect($opencart_db['host'],$opencart_db['user'],$opencart_db['password'],true) or die("Unable to connect to the opencart MySQL!");
mysql_query("SET NAMES UTF8"); 
mysql_select_db(DB_DATABASE,$db); 
date_default_timezone_set('Asia/Chongqing');


function getexcelcontent($sheet){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    $file = CURRENT_ATH.'shell/all-no-watt.xlsx' ;    
    if(!$objReader->canRead($file)){
        $objReader = new PHPExcel_Reader_Excel5(); 
    }
    $objPHPExcel = $objReader->load($file);
    $objWorksheet = $objPHPExcel->getSheet($sheet);  
    
    $highestRow = $objWorksheet->getHighestRow();   
    $highestColumn = $objWorksheet->getHighestColumn();   
     
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
     
    $excelData = array();  
     
    for ($row =1; $row <= $highestRow; ++$row) { 
        //for ($row = 2; $row <= 10; ++$row) { 
        for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
            $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            //富文本转换字符串  
             if($content instanceof PHPExcel_RichText){    
                $content = $content->__toString();  
             }
            $excelData[$row][] = $content;
            
        }  
    }  
    
    return $excelData;  
}


function update_attr_value($sheet_count){
    $lang_array =array(1,4,5,6,7,8,99);
    for($i=0;$i<$sheet_count;$i++){
        $data =getexcelcontent($i);
        //$data =getexcelcontent($sheet_count);
        file_put_contents($i.'.txt', var_export($data,true));
        $sort_order = 1;
        foreach($data as $key2=>$item){
            if($key2>1){
                $group_name     = $item[0];
                $group_name     = trim($group_name);
                
                $attribute_name = $item[1];
                $attribute_name =  trim($attribute_name);
                
                $filter_name    = $item[2];
                $filter_name    = trim($filter_name);
                
                $option_name    = $item[3];
                $option_name    = trim($option_name);
                
                $group_id       = get_group_id($group_name);
                if(!$group_id){
                    echo $group_name ." not found\n";
                    continue;
                }
                $attribute_id   = get_attr_id($attribute_name);
                if(!$attribute_id){
                    echo $group_name.'|'.$attribute_name ." not found\n";
                    continue;
                }
                $option_id      = get_option_id($attribute_id, $option_name);
                if(!$option_id){
                    echo $group_name.'|'.$attribute_name.'|'.$option_name ." not found\n";
                    continue;
                }else{
                    echo $group_name.'|'.$attribute_name.'|'.$option_name ." \n";
                }
                
                $filter_code = str_replace('/[^a-zA-Z0-9-\-]/','-', $filter_name);
                $filter_code = $group_id . '-' . $attribute_id . '-' . $filter_code;
                
                $filter_id = get_filter_id($filter_code);
                echo $filter_id ."\n";
                if(!$filter_id){
                    $filter_sql = "insert into oc_attribute_group_filter(filter_code,group_id,attribute_id,sort_order) value('{$filter_code}','{$group_id}','{$attribute_id}','{$sort_order}')";
                    mysql_query($filter_sql);
                    $filter_id = mysql_insert_id();
                    
                    $lang_arr = array(1,4,5,6,7,8);
                    foreach($lang_arr as $item){
                        $lang_id = $item;
                        $filter_name = mysql_real_escape_string($filter_name);
                        $filter_desc_sql = "insert into oc_attribute_group_filter_description(filter_id,language_id,name) value ('{$filter_id}','{$lang_id}','{$filter_name}')";
                        mysql_query($filter_desc_sql);
                    }
                    $sort_order ++ ;
                }
                
                $filter_option_sql = "insert into oc_attribute_group_filter_option(filter_id,option_id) value ('{$filter_id}','{$option_id}')";
                mysql_query($filter_option_sql);
                
                $filter_type_exist_sql = "select * from oc_attribute_to_group where attribute_id = '{$attribute_id}'and attribute_group_id='{$group_id}'";
                $filter_type_exist_rs  = mysql_query($filter_type_exist_sql);
                $filter_type_exist_row = mysql_fetch_assoc($filter_type_exist_rs);
                if($filter_type_exist_row){
                    $filter_type_sql = "update oc_attribute_to_group set filter_type = 2,sort_order = '{$sort_order}' where attribute_id = '{$attribute_id}'and attribute_group_id='{$group_id}'";
                    mysql_query($filter_type_sql);
                }else{
                    $filter_type_sql = "INSERT INTO oc_attribute_to_group(attribute_id,attribute_group_id,sort_order,filter_type,status) value('{$attribute_id}','{$group_id}','{$sort_order}','2','1')";
                    mysql_query($filter_type_sql);
                }
                 


            }
        }
    }
}

function get_attr_id($attr_name){
    $query =mysql_query("select attribute_id from oc_new_attribute_description where name='".mysql_real_escape_string(trim($attr_name))."' and language_id=1");
    $row =mysql_fetch_assoc($query);
    if($row){
        
        return $row['attribute_id'];
    }
    else{
        return false;
    }
}

function get_product_id($sku){
    $query =mysql_query("select product_id from  oc_product where model='".$sku."'");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['product_id'];
    }
    else{
        return false;
    }
}

function get_option_id($attr_id,$option_name){
    $query =mysql_query("select ov.option_id from oc_new_attribute_option_value as ov left join oc_new_attribute_option as o on ov.option_id=o.option_id  where ov.option_value='".mysql_real_escape_string(trim($option_name))."' and ov.language_id=1 and o.attribute_id=".$attr_id);
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['option_id'];
    }
    else{
        return false;
    }
}

function get_group_id($group_name){
    $query =mysql_query("select * from oc_attribute_group where attribute_group_code	='".mysql_real_escape_string(trim($group_name))."'");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['attribute_group_id'];
    }
    else{
        return false;
    }
}

function get_filter_id($filter_code){
    $query =mysql_query("select * from oc_attribute_group_filter where 	filter_code	='".mysql_real_escape_string(trim($filter_code))."'");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['filter_id'];
    }
    else{
        return false;
    }
}

function if_product_attr($product_id,$attribute_id){
    $query =mysql_query("select product_id from oc_new_product_attribute where product_id='".$product_id."' and attribute_id='".$attribute_id."'");
    $row =mysql_fetch_assoc($query);
    if($row){
        return true;
    }
    else{
        return false;
    }
}
//update_attr_value(12);


update_attr_value(1);