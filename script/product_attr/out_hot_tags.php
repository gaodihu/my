<?php

//导出网站所有的A-Z，0-9tags

//define('ROOT_PATH','D://tinker0511/');
define('ROOT_PATH','/home/www/new_myled.com/');
include_once(ROOT_PATH.'script/conf.php');
require_once(ROOT_PATH."system/lib/PHPExcel/PHPExcel.php");
require_once (ROOT_PATH."system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
require_once (ROOT_PATH."system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式

function getData($lang_code){
    $data =array();
    $sql ="select tags,tags_sign from oc_hot_tags_".strtolower(trim($lang_code))." order by tags_sign asc";
    $query=mysql_query($sql);
    while($row =mysql_fetch_assoc($query)){
       $search = preg_replace("/[\`\~!\@#\$%\^&*()_+=|\\\{\}\[\];:\"'<,>.?\/]/",' ',trim($row['tags']));
       $search = preg_replace("/^\s+|\s+$/",' ',$search);
        
        if (strlen( $search)>=2) {
            $search_words = explode(" ",$search);
            $search_url =implode("+",$search_words);
            $search_url = preg_replace("/\++/",'+',$search_url);
            $search_url = substr($search_url,0,-1);
            
            $url = '/s/'.$search_url. '.html' ;
        }else{
            $url ='';
        }
        $data[] =array(
            'tags'    =>$row['tags'],
            'tags_sign'    =>$row['tags_sign'],
            'url'    =>$url ,
        );
    }
    return $data;
}
function out_excel(){
    $lang_code =array("en",'de','es','fr','it');
    $url_array =array('en'=>'https://www.myled.com','de'=>"https://de.myled.com",'es'=>"https://es.myled.com",'fr'=>"https://fr.myled.com",'it'=>"https://it.myled.com");
    $objPHPExcel = new PHPExcel();
    $out_file =ROOT_PATH."script/product_attr/out_tags.xlsx";
    foreach($lang_code as $key=>$code){
        $data =getData($code);
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($key);
        $objActSheet=$objPHPExcel->getActiveSheet();
        $objPHPExcel->getSheet($key)->setTitle($code."_tags");
        $objActSheet->setCellValue('A1', "标记");
        $objActSheet->setCellValue('B1', "tags");
        $objActSheet->setCellValue('C1', "url");

        $i=2;
        foreach($data as $item){
           $objActSheet->setCellValue("A".$i, $item['tags_sign']);
           $objActSheet->setCellValue("B".$i, $item['tags']);
           $objActSheet->setCellValue("C".$i, $url_array[$code].$item['url']);
            
           $i++;
        }
        
    }
    
  
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($out_file);
}
out_excel();
?>


