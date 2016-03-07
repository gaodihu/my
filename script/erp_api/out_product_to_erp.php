<?php
define('ROOT_PATH','/home/www/new_myled.com/');

//include_once('/home/www/new_myled.com/script/conf.php');
include_once(ROOT_PATH .'script/conf.php');
ini_set('memory_limit','1024M');
set_time_limit(0);
function get_today_products(){
    $data =array();
    $today_start =date('Y-m-d 00:00:00',time()-24*60*60);
    $today_end =date('Y-m-d 24:00:00',time());
    //$today_start ='2015-01-20 00:00:00';
    //$today_end ='2015-01-20 24:00:00';
    $sql ="SELECT pp.product_id,pp.model,pp.product_code,pp.supplier_code,pp.image,pp.price,pp.weight,pp.length,pp.width,pp.height,pp.date_added,pd.name FROM oc_product pp LEFT JOIN oc_product_description pd ON pp.product_id = pd.product_id AND pd.language_id = 1 where pp.date_modified>='".$today_start."' and pp.date_modified<='".$today_end."' ";
    //$sql ="SELECT pp.product_id,pp.model,pp.product_code,pp.supplier_code,pp.image,pp.price,pp.weight,pp.length,pp.width,pp.height,pp.date_added,pd.name FROM oc_product pp LEFT JOIN oc_product_description pd ON pp.product_id = pd.product_id AND pd.language_id = 1";
    $query =mysql_query($sql);
    while($row =mysql_fetch_assoc($query)){
        //得到商品的规格属性
        $row['pro_sale_attr'] =implode('<br>',get_sale_pro_attr($row['product_id']));
        $data[] =$row;
    }
    return $data;
}
function get_sale_pro_attr($product_id){
    $have_attr_name =array('外观颜色','发光颜色','孔径尺寸','直径','是否可调光','输出电流','输入电流','输入电压','输出电压','水晶体尺寸');
    $data =array();
    $query = mysql_query("select attr_id,attr_name from oc_sale_product_attr where product_id=".$product_id);
    while($row =mysql_fetch_assoc($query)){
        $query_attr_value =mysql_query("select naov.option_value from  oc_new_product_attribute as npa left join oc_new_attribute_option_value as naov on npa.attr_option_value_id =naov.option_id where npa.product_id =".$product_id." and npa.attribute_id=".$row['attr_id']." and naov.language_id=99 limit 1");
        $res =mysql_fetch_assoc($query_attr_value);
        $attr_value =$res['option_value'];
        if(in_array($row['attr_name'],$have_attr_name)){
            $data[] =$row['attr_name'].":".$attr_value;
        }
        else{
            $data[] =$attr_value;
        }

    }
    return $data;
}
function get_excel(){
    require_once(ROOT_PATH ."system/lib/PHPExcel/PHPExcel.php");
    require_once (ROOT_PATH .'system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
    require_once (ROOT_PATH . 'system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
    /*
    require_once("D://tinker20150107/system/lib/PHPExcel/PHPExcel.php");
    require_once ("D://tinker20150107/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
    require_once ("D://tinker20150107/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式
    */
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'model(sku)');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'product_code(供应商产品编码)');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'name(产品名称)');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'supplier_code(供应商编码)');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', '产品规格属性');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'image(图片)');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'price(价格)');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'weight(重量)');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'length(长度)');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'width(宽度)');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'height(高度)');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'date_added(创建日期)');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'category(分类)');
    $product_datas =get_today_products();
    $i=2;
    foreach($product_datas as $item){
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $item['model']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $item['product_code']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $item['name']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $item['supplier_code']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $item['pro_sale_attr']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $item['image']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $item['price']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $item['weight']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,  $item['length']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,  $item['width']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,  $item['height']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,  $item['date_added']);
        
        $category     = get_category($item['product_id']);
        $category_str = implode(',',$category);
        
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,  $category_str);
        
        $i++;
    }
    $objPHPExcel->getActiveSheet()->setTitle("网站今日修改商品数据");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$file_path="D://erp_pro/".date('Y-m-d',time()).".xlsx";
    $file_path= ROOT_PATH ."/erp_pro/".date('Y-m-d',time()-24*3600).".xlsx";
	$objWriter->save($file_path);
}
function get_category($product_id){
    $sql = "select distinct category_id from oc_product_to_category where product_id = '{$product_id}'";
    $query = mysql_query($sql);
    $data = array();
    while($row = mysql_fetch_assoc($query)){
        $category_id = $row['category_id'];
        $data[] = $category_id;
    }
    return $data;
}

get_excel();
?>