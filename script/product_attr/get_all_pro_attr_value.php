<?php

/****
***
***  导出商品的所有属性
**
*/
//导出商品属性数据
include_once('/home/www/new_myled.com/script/conf.php'); 



require_once("/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel.php");
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
require_once ('/home/www/new_myled.com/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 

/*
include_once('conf2.php');
require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once ("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
*/
function get_attr_info(){
    $query_attr =mysql_query("select attribute_id,name from oc_new_attribute_description where language_id=1");
    $data =array();
    $data[] =array('name'=>'sku');
    $data[] =array('name'=>'attrbute_set');
    $data[] =array('name'=>'catagory_level_2');
    while($row =mysql_fetch_assoc($query_attr)){
        $data[] =$row;
    }
    return $data;
}
function to_excel(){
    //$out_file ="D://XAMPP/htdocs/new_myled/script/pro_attr_out_1117.xlsx";
    $out_file ="/home/www/new_myled.com/script/product_attr/pro_attr_out_1117.xlsx";
    $attr_array = get_attr_info();
 
    $objPHPExcel = new PHPExcel();
    $objActSheet=$objPHPExcel->getActiveSheet();
    foreach($attr_array as $key=>$attr){
        $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $col=$key;
        if($col<=25){
            $col_str =$tmp_array[$col];
        }
        else{
            $n =floor(($col+1)/26);
            $left =($col+1)%26;
            if($left==0){
                $col_str =$tmp_array[$n-2].'Z';
            }
            else{
                $col_str =$tmp_array[$n-1].$tmp_array[$left-1];
            }
        }
        $objActSheet->setCellValue($col_str.'1', $attr['name']);
    }
    //得到商品的属性
    $query_sku =mysql_query("select npa.product_id,ag.attribute_group_code,npa.attribute_id,naov.option_value 
    from oc_new_product_attribute as npa 
    left join oc_product_attribute_group as pag on npa.product_id=pag.product_id
    left join oc_attribute_group as ag on pag.attribute_group_id=ag.attribute_group_id
    left join  oc_new_attribute_option_value as naov on npa.attr_option_value_id=naov.option_id 
    where naov.language_id=1  order by npa.product_id ASC");
    $i=2;
    Session_start();
    unset($_SESSION['product_info']);
    while($row_sku =mysql_fetch_assoc($query_sku)){
        if(!isset($_SESSION['product_info']['product_id'])){
            $_SESSION['product_info'] = array('product_id'=>$row_sku['product_id'],'row'=>$i);
        }
         if($row_sku['product_id']!=$_SESSION['product_info']['product_id']){
            $_SESSION['product_info'] = array('product_id'=>$row_sku['product_id'],'row'=>$i);
            $i++;
        }
         $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            foreach($attr_array as $key=>$attr){
                if(isset($attr['attribute_id'])){
                    if($row_sku['attribute_id']==$attr['attribute_id']){
                        $col=$key;
                    }
                }
            }
            if($col<=25){
                $col_str =$tmp_array[$col];
            }
            else{
                $n =floor(($col+1)/26);
                $left =($col+1)%26;
                if($left==0){
                    $col_str =$tmp_array[$n-2].'Z';
                }
                else{
                    $col_str =$tmp_array[$n-1].$tmp_array[$left-1];
                }
            }
            $sku =get_product_sku($row_sku['product_id']);
            $catagory_2_name =get_pro_level_2_catagory($row_sku['product_id']);
            $objActSheet->setCellValue('A'.$i, $sku);
            $objActSheet->setCellValue('B'.$i, $row_sku['attribute_group_code']);
            $objActSheet->setCellValue('C'.$i, $catagory_2_name);
            $objActSheet->setCellValue($col_str.$i, $row_sku['option_value']);
    }
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);

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

function get_pro_level_2_catagory($product_id){
    $sql ="select cd.name from  
    oc_product_to_category as p2c left join oc_category as c on p2c.category_id =c.category_id
    left join oc_category_description as cd on  p2c.category_id =cd.category_id
    where p2c.product_id =".$product_id." and c.level=2 and cd.language_id=1";
    $query =mysql_query($sql);
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row['name'];
    }
    else{
        return 'NULL';
    }
}
to_excel();
?>


