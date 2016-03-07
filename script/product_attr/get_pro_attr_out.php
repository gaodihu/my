<?php

/****
***
***  分类别的导出商品的所有属性，一个类别一个sheet
**
*/
include_once('/home/www/new_myled.com/script/conf.php'); 



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 

/*
include_once('D://XAMPP/htdocs/new_myled/script/conf2.php');
require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/
ini_set('memory_limit','1024M');
set_time_limit(0);
function to_excel(){
    //$out_file ="D://XAMPP/htdocs/new_myled/script/product_attr/pro_attr_out.xlsx";
    $out_file ="/home/www/new_myled.com/script/product_attr/pro_attr_out.xlsx";
    //得到大类的分类
    $query_cat =mysql_query("select attribute_group_id,attribute_group_code from oc_attribute_group");
    $i=0;
    $objPHPExcel = new PHPExcel();
    while($row =mysql_fetch_assoc($query_cat)){
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($i);
        //设置当前活动sheet的名称
        $objPHPExcel->getSheet($i)->setTitle($row['attribute_group_code']);
        $objActSheet=$objPHPExcel->getActiveSheet();
        $cat_products =get_catgory_product($row['attribute_group_id']);
        $attr_array =get_all_attr_name($cat_products);
        $objActSheet->setCellValue('A1', 'sku');
        $k=1;
        foreach($attr_array as $attr_id=>$attr){
            $col=$k;
            $col_str =get_excel_col($col);
            
            $k++;
            $objActSheet->setCellValue($col_str.'1', $attr);
            $j=2;
            foreach($cat_products as $product_id){
                $sku=get_product_sku($product_id);
                $objActSheet->setCellValue('A'.$j, $sku);
                $attr_value =get_pro_attr_value($attr_id,$product_id);
                $objActSheet->setCellValue($col_str.$j, $attr_value['option_value']);
                $j++;
                
            }
        }
        $i++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);

}

//得到某个attribute_group下的所有商品
function get_catgory_product($attribute_group){
    $data =array();
    $query_child =mysql_query("select product_id from oc_product_attribute_group where attribute_group_id=".$attribute_group);
    while($row_child =mysql_fetch_assoc($query_child)){
        $data[] =$row_child['product_id'];
    }
    return $data;
}

//得到一批商品所有的属性名
function get_all_attr_name($data){
    $res =array();
    foreach($data as $product_id){
        $query =mysql_query("select pa.attribute_id,ad.name from oc_new_product_attribute as pa left join oc_new_attribute_description as ad on pa.attribute_id=ad.attribute_id where ad.language_id=1 and pa.product_id=".$product_id);
        while($row =mysql_fetch_assoc($query)){
            $res[$row['attribute_id']] =$row['name'];
        }
    }
    return $res;
}
//得到商品属性值
function get_pro_attr_value($attr_id,$pro_id){
    $query =mysql_query("select pav.option_value from oc_new_product_attribute as pa left join oc_new_attribute_option_value as pav on pa.attr_option_value_id=pav.option_id where pa.attribute_id='".$attr_id."' and pa.product_id='".$pro_id."' and pav.language_id=1");
    $row =mysql_fetch_assoc($query);
    return $row;
}
function get_product_sku($product_id){
    $query =mysql_query("select model from oc_product where product_id='".$product_id."' limit 1");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['model'];
    }
    else{
        return false;
    }
}

function get_excel_col($num){
    $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    if($num<=25){
        $col_str =$tmp_array[$num];
    }
    else{
        $n =floor(($num)/25);
        $left =($num)%25;
        if($left==0){
            $col_str =$tmp_array[$n-1].'Z';
        }
        else{
            $col_str =$tmp_array[$n-1].$tmp_array[$left-1];
        }
    }
    return $col_str;
}
to_excel();
?>


