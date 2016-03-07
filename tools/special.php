<?php
$online_db =array(
    'host'=>'127.0.0.1:7709',
    'user'=>'myled',
    'password'=>'232ZFit52brxaN4n',
    'db'=>'new_myled'
);
$test_db =array(
    'host'=>'172.168.90.236',
    'user'=>'myled',
    'password'=>'123456',
    'db'=>'new_myled'
);
$use = $online_db;
$db_connect=mysql_connect($use['host'],$use['user'],$use['password']) or die("Unable to connect to the MySQL!");
mysql_select_db($use['db'],$db_connect); 
date_default_timezone_set('Asia/Chongqing');
ini_set('memory_limit','1024M');
ini_set('post_max_size','100M');
ini_set('max_file_uploads',100);
set_time_limit(0); //脚本不超时
$_GET['act'] =isset($_GET['act'])?$_GET['act']:'default';
$store_arr =array('en','de','es','fr','it');
//$base_path ='D:\XAMPP\htdocs\new_myled';
$base_path ='/home/www/new_myled.com';
if($_GET['act'] =='default' ){
    $code='this is text';
    $file =$base_path.'/special_templete/special_upload.tpl';
    ob_start();
    include($file);
    $content = ob_get_clean();
    echo $content;
}
if($_GET['act'] =='temp2'){
    $file =$base_path.'/special_templete/special_upload_2.tpl';
    ob_start();
    include($file);
    $content = ob_get_clean();
    echo $content;
}
if(isset($_GET['act'])&&$_GET['act']=='upload'){
    header("Content-type: text/html; charset=utf-8"); 
    //取得文件夹名称
    //$todya =date('Ymd',time());
    $special_dir_name = $_POST['special_dir_name'];
    
    if(is_dir($base_path."/".$special_dir_name)){
        echo "<script>alert('专题文件夹已存在，请重新命名！');location.href='/special.php'</script>";
        exit;
    }
    $in_path =$base_path."/special/$special_dir_name";
    $file_pth =$base_path."/special/$special_dir_name/images";
    if(!is_dir($file_pth)){
        mkdir($file_pth,0777,true);
    }
    //head 
    $head_title =array();
    foreach($_POST['head_title'] as $key=>$value){
        $head_title[$store_arr[$key]]=$value;
    }
    foreach($_POST['head_desc'] as $key=>$value){
        $head_desc[$store_arr[$key]]=$value;
    }
    foreach($_POST['head_keyword'] as $key=>$value){
        $head_keyword[$store_arr[$key]]=$value;
    }
    //头部banner
    $background_info =array();
    if(!empty($_FILES['top_background_banner']['tmp_name'])){
            
            $banner_name =$file_pth.'/banner.jpg';
            $img_name = "/$special_dir_name/images/banner.jpg";
            if(file_exists($banner_name)){
                unlink($banner_name);
            }
            if(!move_uploaded_file($_FILES['top_background_banner']['tmp_name'],$banner_name)){
                echo "<script>alert('文件上传失败，请重新上传！');location.href='/special.php'</script>";
                exit;
            }
            $background_info['image'] = $banner_name;
        }

    $top_banner_info =array();
    $top_banner_count = count($_FILES['top_banner']['tmp_name']);
    for($i=0;$i<$top_banner_count;$i++){
        if(!empty($_FILES['top_banner']['tmp_name'][$i])){
            $stroe_code =$store_arr[$i];
            $banner_name =$in_path.'/images/'.$_FILES['top_banner']['name'][$i];
            $img_name = "/special/$special_dir_name/images/".$_FILES['top_banner']['name'][$i];
            if(file_exists($banner_name)){
                unlink($banner_name);
            }
            if(!move_uploaded_file($_FILES['top_banner']['tmp_name'][$i],$banner_name)){
                echo "<script>alert('文件上传失败，请重新上传！');location.href='/special.php'</script>";
                exit;
            }
            $link =$_POST['top_banner_link'][$i];
            $title =trim($_POST['top_banner_title'][$i]);
            $top_banner_info[$stroe_code]['link']=$link;
            $top_banner_info[$stroe_code]['title']=$title;
            $top_banner_info[$stroe_code]['img']=$img_name;
        }
    }
    
    //底部banner
    $foot_banner_info =array();
    $foot_banner_count = count($_FILES['foot_banner']['tmp_name']);
    for($i=0;$i<$foot_banner_count;$i++){
        if(!empty($_FILES['foot_banner']['tmp_name'][$i])){
            $stroe_code =$store_arr[$i];
            $banner_name =$in_path.'/images/'.$_FILES['foot_banner']['name'][$i];
            $img_name = "/special/$special_dir_name/images/".$_FILES['foot_banner']['name'][$i];
            if(file_exists($banner_name)){
                unlink($banner_name);
            }
            if(!move_uploaded_file($_FILES['foot_banner']['tmp_name'][$i],$banner_name)){
                echo "<script>alert('文件上传失败，请重新上传！');location.href='/special.php'</script>";
                exit;
            }
            $link =$_POST['foot_banner_link'][$i];
            $title =trim($_POST['foot_banner_title'][$i]);
            $foot_banner_info[$stroe_code]['link']=$link;
            $foot_banner_info[$stroe_code]['title']=$title;
            $foot_banner_info[$stroe_code]['img']=$img_name;
        }
    }
    foreach($store_arr as $key=>$store_code){
        switch($store_code){
            case 'en':
                $store_id=1;
                $huilv =1;
                $decimalpoint ='.';
                $separator =',';
                break;
            case 'de':
                $store_id=4;
                $huilv =get_huilv('EUR');
                $decimalpoint =',';
                $separator ='.';
                break;
            case 'es':
                $store_id=6;
                $huilv =get_huilv('EUR');
                $decimalpoint =',';
                $separator ='.';
                break;
            case 'fr':
                $store_id=5;
                $huilv =get_huilv('EUR');
                $decimalpoint =',';
                $separator ='.';
                break;
            case 'it':
                $store_id=7;
                $huilv =get_huilv('EUR');
                $decimalpoint =',';
                $separator ='.';
                break;
            case 'pt':
                $store_id=8;
                $huilv =get_huilv('EUR');
                $decimalpoint =',';
                $separator ='.';
                break;
            default:
                $store_id=1;
                $huilv =1;
                $decimalpoint ='.';
                $separator =',';
                break;
        }
        $pro_array =array();
        if(isset($_POST['d_lang'])&&$_POST['d_lang']){
            if(empty($_POST['pro_sku'])){
                 echo "<script>alert('请上传商品！');location.href='/special.php?act=temp2'</script>";
                 exit;
            }
            $sku_data =trim($_POST['pro_sku'][$store_code]);
            if(empty($sku_data)){
                echo "<script>alert('请上传".$store_code."商品！');location.href='/special.php?act=temp2'</script>";
                exit;
            }
            $sku_arr = explode(',',$sku_data);
            foreach($sku_arr as $key=>$value){
                $pro_info =array();
                $sku =trim($value);
                $img_path = "/special/$special_dir_name/images/";
                $_pro =get_pro_info($value,$store_id,$huilv);
                $start =strrpos($_pro['image'],'/');
                $image_array =explode('/',$_pro['image']);
                $image_str =end($image_array);
                $name = substr($image_str, 0, strrpos($image_str, '.'));
                $pro_info['product_id']=$_pro['product_id'];
                $pro_info['sku']=$sku;
                $pro_info['img']=$img_path.$name."-453x453.jpg";
                $pro_info['name']=$_pro['name'];
                $pro_info['price']=number_format($_pro['price'],2,$decimalpoint,$separator);
                $pro_info['special_price']=$_pro['special_price']?number_format($_pro['special_price'],2,$decimalpoint,$separator):null;
                $pro_info['url']=$_pro['url_path'].".html";
                $list =ceil(($key+1)/4);
                $pro_array[$list][]=$pro_info;
            }
        }
        else{
            if(!empty($_POST['pro_sku'])){
                $sku_arr = explode(',',trim($_POST['pro_sku']));
                foreach($sku_arr as $key=>$value){
                    $pro_info =array();
                    $sku =trim($value);
                    $img_path = "/special/$special_dir_name/images/";
                    $_pro =get_pro_info($value,$store_id,$huilv);
                    $start =strrpos($_pro['image'],'/');
                    $image_array =explode('/',$_pro['image']);
                    $image_str =end($image_array);
                    $name = substr($image_str, 0, strrpos($image_str, '.'));
                    $pro_info['product_id']=$_pro['product_id'];
                    $pro_info['sku']=$sku;
                    $pro_info['img']=$img_path.$name."-453x453.jpg";
                    $pro_info['name']=$_pro['name'];
                    $pro_info['price']=number_format($_pro['price'],2,$decimalpoint,$separator);
                    $pro_info['special_price']=$_pro['special_price']?number_format($_pro['special_price'],2,$decimalpoint,$separator):null;
                    $pro_info['url']=$_pro['url_path'].".html";
                    $list =ceil(($key+1)/4);
                    $pro_array[$list][]=$pro_info;
                }
            }
            else{
                echo "<script>alert('请填写商品sku！');location.href='/special.php'</script>";
                exit;
            }
        }
        $header_title = $head_title[$store_code];
        $header_desc = $head_desc[$store_code];
        $header_keyword = $head_keyword[$store_code];
        $top_banner = $top_banner_info[$store_code];
        $foot_banner = $foot_banner_info[$store_code];
        $products_info = $pro_array;
        $file =$base_path.'/special_templete/special_'.$store_code.'.tpl';
        ob_start();
        include($file);
        $content = ob_get_clean();
        $end_file =$base_path.'/special/'.$special_dir_name.'/myled_'.$store_code.".html";
        file_put_contents($end_file,$content);
    }
}
function get_huilv($to_curreny_code){
    $query =mysql_query("SELECT value FROM oc_currency where code='".$to_curreny_code."' limit 1");
    $row=mysql_fetch_assoc($query);
    return $row['value'];
}
function get_pro_info($sku,$store_id,$huilv){
    $sql ="select p.product_id,p.image as image,pd.name,p.price as price,(SELECT price FROM oc_product_special ps WHERE ps.product_id = p.product_id AND  ps.customer_group_id=0 ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special_price,p.url_path 
    from oc_product  as p 
    left join oc_product_description as pd on p.product_id = pd.product_id and pd.language_id=".$store_id." 
    where  p.model='$sku'  limit 1";
    $query =mysql_query($sql);
    
    $row=mysql_fetch_assoc($query);
    if($row['product_id']){
        $row['price'] =$huilv*$row['price'];
        if($row['special_price']){
            $row['special_price'] =$huilv*$row['special_price'];
        }
    }
    
    return $row;
}
function template_fetch($file){
    if (file_exists($file)) {
            ob_start();
            include($file);
            $content = ob_get_clean();
            return $content;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
            exit();             
        }
}
?>