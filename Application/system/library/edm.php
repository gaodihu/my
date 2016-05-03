<?php
require_once(DIR_SYSTEM.'library/db.php');
class Edm {
	private $db;
    public $base_path;
    public $store_arr =array('en','de','es','fr','it');
    public $today;
    public $in_path;
	public $file_pth;
	public function __construct($path) {
		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $this->base_path =$path;
        $this->today =date('Ymd',time());
        $this->in_path =$this->base_path."/edm/$this->today";
        $this->file_pth =$this->base_path."/edm/$this->today/images";
	}

    public function set($key, $value) {
		$this->$key = $value;
	}
    public function get($key) {
		return (isset($this->$key) ? $this->$key : null);
	}
    
    //得到不同模板下一行商品的数量
    public function get_line_count($type){
        switch($type){
            case 1:
                return 4;
                break;
            case 2:
                return 3;
                break;
            case 3:
                return 4;
                break;
            case 4:
                return 4;
                break;
            case 5:
                return 4;
                break;
            default:
                return 4;
                break; 
        }
    }
    //得到模板标题
    public function get_head_title($head_title){
        $data =array();
        foreach($head_title as $key=>$value){
            $data[$this->store_arr[$key]]=$value;
        }
        return $data;
    }
    //得到EDM追踪码
    public function get_edm_track($edm_track){
        $data =array();
        foreach($edm_track as $key=>$value){
            $data[$this->store_arr[$key]]=$value;
        }
        return $data;
    }
    //得到 banner info
    /* 
    ** &file_upload_name  文件上传控件名称
    ** &link_input_name  banner link 框控件名称
    ** & title_input_name  banner title 框控件名称
    **
    **
    */
    public function get_banner_info($file_upload_name,$link_input_name,$title_input_name){
        $banner_info =array();
        $banner_count = count($_FILES[$file_upload_name]['tmp_name']);
        if($banner_count){
            for($i=0;$i<$banner_count;$i++){
                if(!empty($_FILES[$file_upload_name]['tmp_name'][$i])){
                    $stroe_code =$this->store_arr[$i];
                    if(!is_dir($this->file_pth.'/banner/'.$stroe_code)){
                        mkdir($this->file_pth.'/banner/'.$stroe_code,0777);
                    }
                    $banner_name =$this->file_pth.'/banner/'.$stroe_code.'/'.$_FILES[$file_upload_name]['name'][$i];
                    $img_name = "https://www.moresku.com/edm/$this->today/images/banner/".$stroe_code."/".$_FILES[$file_upload_name]['name'][$i];
                    if(file_exists($banner_name)){
                        unlink($banner_name);
                    }
                    if(!move_uploaded_file($_FILES[$file_upload_name]['tmp_name'][$i],$banner_name)){
                        echo "<script>alert('文件上传失败，请重新上传！');location.href='/edm.php'</script>";
                        exit;
                    }
                    $link =isset($_POST[$link_input_name][$i])?$_POST[$link_input_name][$i]:'';
                    $title =isset($_POST[$title_input_name][$i])?trim($_POST[$title_input_name][$i]):'';
                    $banner_info[$stroe_code]['link']=$link;
                    $banner_info[$stroe_code]['title']=$title;
                    $banner_info[$stroe_code]['img']=$img_name;
                }
            }
        }
        return  $banner_info;
    }

    //得到 商品内容
    /* 
    ** &store_code  语言代码
    ** &post_data  商品上传数据
    ** &templete_type  edm模板邮件的类型(1,不同国家相同商品,2不同国家不同商品，3不同国家不同商品，且商品具有不同折扣)
    **
    **
    */
    public function get_sku_info($store_code,$line_count,$templete_type=1){
        $store_info =$this->get_store_info($store_code);
        $sku_info =array();
        switch($templete_type){
            case 1:
                $post_data =$_POST['pro_sku'];
                if(empty($post_data)){
                    echo "<script>alert('请上传商品！');location.href='/edm.php'</script>";
                    exit;
                }
                $sku_arr = explode(',',trim($post_data));
                foreach($sku_arr as $key=>$value){
                    $pro_info =$this->get_sku_detail($value,$store_info);
                    $list =ceil(($key+1)/$line_count);
                    $pro_array[$list][]=$pro_info;
                }
                $sku_info =$pro_array;
                break;
            case 2:
                $sku_data =trim($_POST['pro_sku'][$store_code]);
                if(empty($sku_data)){
                    echo "<script>alert('请上传".$store_code."商品！');location.href='/edm.php?act=temp2'</script>";
                    exit;
                }
                $sku_arr = explode(',',$sku_data);
                foreach($sku_arr as $key=>$value){
                    $pro_info =$this->get_sku_detail($value,$store_info);
                    $list =ceil(($key+1)/$line_count);
                    $pro_array[$list][]=$pro_info;
                }
                $sku_info =$pro_array;
                break;
            case 3:
                //商品标题
                foreach($_POST['pro_area_title'][$store_code] as $key=>$pro_title){
                    if($pro_title){
                        $pro_array[$key]['pro_title'] =$pro_title;
                    }
                }
                foreach($_POST['pro_sku'][$store_code] as $key=>$sku_data_arr){
                    if($sku_data_arr){
                        $sku_arr = explode(',',$sku_data_arr);
                        foreach($sku_arr as $col=>$value){
                            $pro_info =$this->get_sku_detail($value,$store_info);
                            $list =ceil(($col+1)/$line_count);
                            $pro_array[$key]['product'][$list][]=$pro_info;
                        }
                    }
                }
                $sku_info =$pro_array;
                break;
        }
        
        return  $sku_info;
    }
    public function get_sku_detail($sku,$store_info){
        $pro_info =array();
        $sku =trim($sku);
        $img_path = "https://www.moresku.com/edm/$this->today/images/";
        $_pro =$this->get_pro_info($sku,$store_info['store_id'],$store_info['huilv']);
        $image_array =explode('/',$_pro['image']);
        $image_str =end($image_array);
        $name = substr($image_str, 0, strrpos($image_str, '.'));
        $pro_info['sku']=$sku;
        $pro_info['img']=$img_path.$name."-453x453.jpg";
        $pro_info['name']=$_pro['name'];
        $pro_info['price']=number_format($_pro['price'],2,$store_info['decimalpoint'],$store_info['separator']);
        $pro_info['special_price']=$_pro['special_price']?number_format($_pro['special_price'],2,$store_info['decimalpoint'],$store_info['separator']):null;
        $pro_info['url']=$_pro['url_path'].".html";
        return $pro_info;
    }
    public function get_store_info($store_code){
        $data =array(
            'store_id'=>1,
            'huilv'=>$this->get_huilv('EUR'),
            'decimalpoint'=>',',
            'separator'=>'.'
        );
        switch($store_code){
			case 'en':
				$data['store_id']=1;
				$data['huilv'] =1;
                $data['decimalpoint'] ='.';
                $data['separator'] =',';
				break;
			case 'de':
				$data['store_id']=4;
				break;
			case 'es':
				$data['store_id']=6;
				break;
			case 'fr':
				$data['store_id']=5;
				break;
			case 'it':
				$data['store_id']=7;
				break;
			case 'pt':
				$data['store_id']=8;
				break;
			default:
				$data['store_id']=1;
				$data['huilv'] =1;
                $data['decimalpoint'] ='.';
                $data['separator'] =',';
				break;
		}
        return $data;
    }
    //输出模板内容
    public function show($file,$data=array()){
        if($data){
            foreach($data as $key=>$vale){
                $$key =$vale;
            }
       }
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

    //得到汇率
    public function get_huilv($to_curreny_code){
        $query =$this->db->query("SELECT	value FROM oc_currency where code='".$to_curreny_code."' limit 1");
        return $query->row['value'];
    }
    //得到商品信息
    public function get_pro_info($sku,$store_id,$huilv){
        $sql ="select p.product_id,p.image as image,pd.name,p.price as price,(SELECT price FROM oc_product_special ps WHERE ps.product_id = p.product_id AND  ps.customer_group_id=0  ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special_price,p.url_path 
        from oc_product  as p 
        left join oc_product_description as pd on p.product_id = pd.product_id and pd.language_id=".$store_id." 
        where  p.model='$sku'  limit 1";
        $query =$this->db->query($sql);
        
        $row=$query->row;
        if($row['product_id']){
            $row['price'] =$huilv*$row['price'];
            if($row['special_price']){
                $row['special_price'] =$huilv*$row['special_price'];
            }
        }
        
        return $row;
    }
	
}
?>