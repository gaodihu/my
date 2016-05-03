<?php

define('CURRENT_ATH','D:/www/charles20150107/');

include_once(CURRENT_ATH . 'config.php');
require_once(CURRENT_ATH . "system/lib/PHPExcel/PHPExcel.php");
require_once (CURRENT_ATH . "system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once (CURRENT_ATH . "system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
ini_set('memory_limit','1024M');

$db=mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD) or die("Unable to connect to the mageto MySQL!");
//$db_connect_opencart=mysql_connect($opencart_db['host'],$opencart_db['user'],$opencart_db['password'],true) or die("Unable to connect to the opencart MySQL!");
mysql_query("SET NAMES UTF8"); 
mysql_select_db(DB_DATABASE,$db); 
date_default_timezone_set('Asia/Chongqing');


function getexcelcontent($sheet){			
    $objReader = new PHPExcel_Reader_Excel2007(); 
    //$objReader->setReadDataOnly(true);  
    //$file='/home/www/new_myled.com/script/attr.xls' ;
    $file = CURRENT_ATH.'shell/price.xlsx' ;    
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

        
        foreach($data as $key2=>$item){
            $group_name     = $item[0];
            $group_name     = trim($group_name);
                
            $attribute_name = $item[1];
            $attribute_name =  trim($attribute_name);
            
            $group_id       = get_group_id($group_name);
            if(!$group_id){
                echo $group_name ." not found<br/>";
                continue;
            }
           
            $sort_order = 1;
            foreach($item as $_k => $_filter){
                if($_k > 1){
                    $filter_name    = $_filter;
                    $filter_name    = trim($filter_name);
                    if($filter_name == ''){
                        break;
                    }
                    $filter_num = strtolower($filter_name);
                    if(strpos($filter_num,'+')!== false){
                        $start = str_replace('+', '', $filter_num);
                        echo $start."\n";
                        $end = '';
                    }else{
                        echo $filter_num."\n";
                        $_data = explode('-', $filter_num);
                        $start = $_data[0];
                        $end   = $_data[1];
                    }
                    $sort = $_k - 1;
                    if($end != ''){
                        $sql = "INSERT INTO oc_attribute_group_price_filter(group_id,start,end,sort_order) value('{$group_id}','{$start}','{$end}','{$sort}')";
                    }else{
                        $sql = "INSERT INTO oc_attribute_group_price_filter(group_id,start,end,sort_order) value('{$group_id}','{$start}',NULL,'{$sort}')";
                    }
                    mysql_query($sql);
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


update_attr_value(1);


