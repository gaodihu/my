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
	$file =$edm->base_path.'/edm/weekend_templete/select_temp.tpl';
	echo $edm->show($file);
}
if($_GET['act'] =='temp1' ){
	$file =$edm->base_path.'/edm/weekend_templete/edm_upload.tpl';
	echo $edm->show($file);
}
if($_GET['act'] =='temp2'){
    $file =$edm->base_path.'/edm/weekend_templete/edm_upload_2.tpl';
	echo $edm->show($file);
}
if(isset($_GET['act'])&&$_GET['act']=='upload'){
    header("Content-type: text/html; charset=utf-8");

    $line_count =2;
	if(!is_dir($edm->file_pth)){
		mkdir($edm->file_pth,0777,true);
	}
	//删除当天的图片文件
	if(!is_dir($edm->file_pth."/banner")){
		mkdir($edm->file_pth."/banner",0777,true);
	}
	//head 标题
	//head 标题
	$head_title =$edm->get_head_title($_POST['head_title']);
    $edm_track =$edm->get_edm_track($_POST['edm_track']);
	//头部banner
    $top_banner_info =$edm->get_banner_info('top_banner','top_banner_link','top_banner_title');
    
    $back_url ='/weekend_edm.php?act=temp1';
    if($_POST['temple_type']==2){
        $coupon_1_info =$edm->get_banner_info('coupon_1','coupon_1_link','coupon_1_title');
        $coupon_2_info =$edm->get_banner_info('coupon_2','coupon_2_link','coupon_2_title');
        $back_url ='/weekend_edm.php?act=temp2';
    }
	foreach($edm->store_arr as $key=>$store_code){
		
		$pro_array =array();
        $sku_data =trim($_POST['pro_sku'][$store_code]);
        if(empty($sku_data)){
            //echo "<script>alert('请上传".$store_code."商品！');location.href='".$back_url."'</script>";
            //exit;
        }
        $pro_array =$edm->get_sku_info($store_code,$line_count,2);
        
		$header_title = $head_title[$store_code];
		$edm_track_text = $edm_track[$store_code];
		$top_banner = $top_banner_info[$store_code];
        if($_POST['temple_type']==2){
            $shop_now =trim($_POST['shop_now'][$key]);
            $coupon_1 = isset($coupon_1_info[$store_code])?$coupon_1_info[$store_code]:'';
            $coupon_2 = isset($coupon_2_info[$store_code])?$coupon_2_info[$store_code]:'';
        }
		$products_info = $pro_array;
        if($_POST['temple_type']==1){
            $file_name ='week_1/edm_'.$store_code.'.html';
        }elseif($_POST['temple_type']==2){
            $file_name ='week_2/edm_'.$store_code.'.html';
        }
		$file =$edm->base_path.'/edm/weekend_templete/'.$file_name;
        if($_POST['temple_type']==1){
            $content =$edm->show($file,compact('header_title','edm_track_text','top_banner','products_info'));
        }elseif($_POST['temple_type']==2){
            $content =$edm->show($file,compact('header_title','edm_track_text','top_banner','coupon_1','coupon_2','shop_now','products_info'));
        }
        
		$end_file =$edm->base_path.'/edm/'.$edm->today.'/edm_'.$store_code.".html";
		file_put_contents($end_file,$content);
	}
	
}
?>