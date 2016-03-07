<?php
require_once('config.php');
require_once(DIR_SYSTEM.'library/edm.php');
date_default_timezone_set('Asia/Chongqing');
ini_set('memory_limit','1024M');
ini_set('post_max_size','100M');
ini_set('max_file_uploads',100);
set_time_limit(0); //脚本不超时
$path_info =pathinfo(__FILE__);
$edm =new Edm($path_info['dirname']);
$_GET['act'] =isset($_GET['act'])?$_GET['act']:'default';
if($_GET['act'] =='default' ){
	$file =$edm->base_path.'/edm/templete/select_temp.tpl';
	echo $edm->show($file);
}
if($_GET['act'] =='temp1' ){
	$file =$edm->base_path.'/edm/templete/edm_upload.tpl';
	echo $edm->show($file);
}
if($_GET['act'] =='temp2'){
    $file =$edm->base_path.'/edm/templete/edm_upload_2.tpl';
	echo $edm->show($file);
}
if($_GET['act'] =='temp3'){
    $file =$edm->base_path.'/edm/templete/edm_upload_3.tpl';
	echo $edm->show($file);
}
if(isset($_GET['act'])&&$_GET['act']=='upload'){
    header("Content-type: text/html; charset=utf-8");
    //选择模板类型
    if(!$_POST['temple_type']){
        echo "<script>alert('请选择文件类型！');location.href='/edm.php'</script>";
		exit;
    }
     if(!is_dir($edm->file_pth)){
		mkdir($edm->file_pth,0777,true);
    }
    //删除当天的图片文件
    if(!is_dir($edm->file_pth."/banner")){
        mkdir($edm->file_pth."/banner",0777,true);
    }
    $line_count =$edm->get_line_count($_POST['temple_type']);
	//head 标题
	$head_title =$edm->get_head_title($_POST['head_title']);
    $edm_track =$edm->get_edm_track($_POST['edm_track']);
	//头部banner
	$top_banner_info =$edm->get_banner_info('top_banner','top_banner_link','top_banner_title');
	//底部banner
	$foot_banner_info =$edm->get_banner_info('foot_banner','foot_banner_link','foot_banner_title');

	foreach($edm->store_arr as $key=>$store_code){
		
		$pro_array =array();
         if(isset($_POST['d_lang'])&&$_POST['d_lang']){   //不同语言不同商品
            if(empty($_POST['pro_sku'])){
                 echo "<script>alert('请上传商品！');location.href='/edm.php?act=temp2'</script>";
				 exit;
            }
            if(isset($_POST['df_zhekou'])&&$_POST['df_zhekou']){  //不同折扣  
                $pro_array =$edm->get_sku_info($store_code,$line_count,3);
            }else{
                $pro_array =$edm->get_sku_info($store_code,$line_count,2);
            }
        }
        else{
            $pro_array =$edm->get_sku_info($store_code,$line_count,1);
        }
		$header_title = $head_title[$store_code];
		$edm_track_text = $edm_track[$store_code];
		$top_banner = $top_banner_info[$store_code];
		$foot_banner = $foot_banner_info[$store_code];
		$products_info = $pro_array;
        if($_POST['temple_type']==1){
            $file_name ='2-1-4/edm_'.$store_code.'.html';
        }elseif($_POST['temple_type']==2){
            $file_name ='2-1-3/edm_'.$store_code.'.html';
        }elseif($_POST['temple_type']==3){
            $file_name ='2-2-4/edm_'.$store_code.'.html';
        }elseif($_POST['temple_type']==4){
            $file_name ='3-2-4/edm_'.$store_code.'.html';
        }elseif($_POST['temple_type']==5){
            $file_name ='4-2-4/edm_'.$store_code.'.html';
        }else{
            echo "<script>alert('选择的模板不存在');location.href='/edm.php'</script>";
            exit;
        }
       
		$file =$edm->base_path.'/edm/templete/'.$file_name;
		
		$content =$edm->show($file,compact('header_title','edm_track_text','top_banner','foot_banner','products_info'));
		$end_file =$edm->base_path.'/edm/'.$edm->today.'/edm_'.$store_code.".html";
		file_put_contents($end_file,$content);
	}	
}
?>