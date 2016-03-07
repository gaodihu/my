<?php
/****
***
***  导出商品的所有中文属性
**
*/

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
    $query_attr =mysql_query("select attribute_id,name from oc_new_attribute_description where language_id=99");
    $data =array();
    $data[] =array('name'=>'sku');
    $data[] =array('name'=>'分类');
    $data[] =array('name'=>'供应商代码');
    $data[] =array('name'=>'供应商商品代码');
    $data[] =array('name'=>'商品名称');
    $data[] =array('name'=>'商品链接');
    $data[] =array('name'=>'商品图片链接');
    while($row =mysql_fetch_assoc($query_attr)){
        $data[] =$row;
    }
    return $data;
}
function to_excel(){
    //$out_file ="D://XAMPP/htdocs/new_myled/script/pro_attr_out_cn_1119.xlsx";
    $out_file ="/home/www/new_myled.com/script/product_attr/pro_attr_out_cn_1119.xlsx";
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
    $query_sku =mysql_query("select npa.product_id,npa.attribute_id,naov.option_value 
    from oc_new_product_attribute as npa
    left join  oc_new_attribute_option_value as naov on npa.attr_option_value_id=naov.option_id 
    where naov.language_id=99 order by npa.product_id ASC");
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
            $product_info =get_product_info($row_sku['product_id']);
            //商品分类
            $query_catagory =mysql_query("select min(category_id) as category_id  from oc_product_to_category where product_id=".$row_sku['product_id']);
            $res_catagory =mysql_fetch_assoc($query_catagory);
            $category_id =$res_catagory['category_id'];
            $query_path =mysql_query("SELECT path from oc_category where category_id='".$category_id."'");
            $res_path =mysql_fetch_assoc($query_path);
            $path =$res_path['path'];
            $cat_arr =explode("/",$path);
            $catagory_arr =array();
            foreach($cat_arr as $cat_id){
                if($cat_id>0){
                    $query_cat_name =mysql_query("SELECT name from oc_category_description where category_id='".$cat_id."' and language_id=1");
                    $row_cat =mysql_fetch_assoc($query_cat_name);
                    $catagory_arr[]=$row_cat['name'];
                }
            }
            $product_info['catagory'] =implode(">",$catagory_arr);

            $product_info['url_path'] ="https://www.myled.com/".$product_info['url_path'].".html";
            $ext =substr($product_info['image'], strrpos($product_info['image'], '.'));
            $product_info['big_image'] ="https://www.myled.com/image/cache/".substr($product_info['image'],0,strrpos($product_info['image'], '.'))."-455x455".$ext;
            $objActSheet->setCellValue('A'.$i, $product_info['model']);
            $objActSheet->setCellValue('B'.$i, $product_info['catagory']);
            $objActSheet->setCellValue('C'.$i, $product_info['supplier_code']);
            $objActSheet->setCellValue('D'.$i, $product_info['product_code']);
            $objActSheet->setCellValue('E'.$i, $product_info['name']);
            $objActSheet->setCellValue('F'.$i, $product_info['url_path']);
            $objActSheet->setCellValue('G'.$i, $product_info['big_image']);
            $objActSheet->setCellValue($col_str.$i, $row_sku['option_value']);
    }
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);

}

function get_product_info($product_id){
    $query =mysql_query("select p.model,pd.name,p.url_path,p.supplier_code,p.product_code,p.image from oc_product as p left join oc_product_description as pd on p.product_id=pd.product_id where p.product_id='".$product_id."' and pd.language_id=1 limit 1");
    $row =mysql_fetch_assoc($query);
    if($row){
        return $row;
    }
    else{
        return false;
    }
}
to_excel();
?>


