<?php 
class ControllerBatchProductImport extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle($this->language->get('商品批量上传')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "商品批量上传",
			'href'      => $this->url->link('batch/product_import', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/product_import/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/product_import/upload', 'token=' . $this->session->data['token'], 'SSL');	
        $this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	    $this->template = 'batch/product_import.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('商品批量上传')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/product_import', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{

            
            require_once(DIR_SYSTEM . "/lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
			$file_path = DIR_DATA . "/upload/product/";
            
            

			$file_name = date('Y-m-d_H_i_s',time())."_product.".$file_type;
			if($_FILES['uplaod_file']['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES["uplaod_file"]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
                    $this->do_product_photo($file_path);
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    $i=1;
                    foreach($file_content as $info){
                        header("Content-type: text/html; charset=utf-8");
                        if($info[18]==1){
                            $stock_status_id =7;
                        }
                        else{
                            $stock_status_id =5;
                        }
                        //上传商品基础数据

                        /*我们后台的sku跟product code都是唯一的，如果这两个不小心跟后台已有的数据重复了，能不能报个错，提示第几行是重复的？*/
                        //如果sku或者product code 存在，提示错误，跳出本次循环
                        $query_product_exist =$this->db->query("SELECT product_id FROM ".DB_PREFIX."product where model ='".$info[2]."' or product_code='".$info[3]."' ");
                        if($query_product_exist->num_rows){
                            $this->error[$i] =$i;
                            echo "<p style='color:red'>第".$i."行SKU或者product code 已存在</p>";
                        }
                        else{
                            $image_name =$info['11'];
                            $image_path =$this->getPorImagePath(substr($image_name,1));
                            //$url_path =str_replace(' ','-',strtolower($info[8]));
                            //$url_path =str_replace('.','-',$url_path);
                            //$url_path =str_replace('(','-',$url_path);
                            //$url_path =str_replace(')','',$url_path);
                            $url_path = preg_replace('/[^\d\w\-]/','-',strtolower($info[8]));
                            $url_path = preg_replace('/(\-+)/','-',$url_path);
                            $last_url_path = substr($url_path,-1,1);
                            if($last_url_path == '-'){
                                $url_path = substr($url_path,0,-1);
                            }
                            $battery_type = 0;
                            if(isset($info[22])){
                                $_tmp_battery_type = trim($info[22]);
                                if($_tmp_battery_type == ''){
                                    $battery_type = 0;
                                }else if(in_array($_tmp_battery_type,array(0,1,2,3,4))){
                                    $battery_type = $_tmp_battery_type;
                                }else{
                                    $battery_type = 0; 
                                }
                            }
                           
                            $res_product= $this->db->query("INSERT INTO ".DB_PREFIX."product set url_path='',model='".str_replace("\r\n","",trim($info[2]))."',product_code='".str_replace("\r\n","",trim($info[3]))."',supplier_code='".str_replace("\r\n","",trim($info[7]))."',quantity='".$info[17]."',stock_status_id='".$stock_status_id."',image='".$image_path."',shipping=1,price='".$info[12]."',points='".floor($info[12])."',date_available=NOW(),weight='".$info[16]."',weight_class_id=2,length='".$info[4]."',width='".$info[5]."',height='".$info[6]."',length_class_id=1,subtract=1,minimum=1,status=1,date_added=NOW(),date_modified=NOW(),battery_type ='{$battery_type}'");
                            $product_id = $this->db->getLastId();
                            if(!$res_product){
                                $this->error[$i] =$i;
                                echo "<p style='color:red'>第".$i."行插入product表失败</p>";
                            }
                            if($product_id){
                                $url_path ="p".$product_id."-".$url_path;
                                $this->db->query("UPDATE ".DB_PREFIX."product set url_path='".$url_path."' where product_id=".$product_id);
                                //插入商品图片集
                                $pro_image =explode(';',$info['10']);
                                foreach($pro_image as $pro_gallery){
                                    $gallery_path =$this->getPorImagePath(substr($pro_gallery,1));
                                    $sort_order=intval(substr($pro_gallery,-6,2));
                                    $res_product_image=$this->db->query("INSERT INTO ".DB_PREFIX."product_image set product_id='".$product_id."',image='".$gallery_path."',sort_order=".$sort_order);
                                     if(!$res_product_image){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行插入product_image表失败，image='".$gallery_path."'</p>";
                                    }
                                }
                               //插入商品分类
                               $category_ids =explode(',',$info[1]);
                               array_pop($category_ids);
                               if($category_ids){
                                   foreach($category_ids as $category_id){
                                        $res_product_to_category=$this->db->query("INSERT INTO ".DB_PREFIX."product_to_category set product_id='".$product_id."',category_id='".$category_id."',position=1");
                                         if(!$res_product_to_category){
                                             $this->error[$i] =$i;
                                             echo "<p style='color:red'>第".$i."行插入product_to_category表失败，category_id='".$category_id."'</p>";
                                        }
                                   }
                               }else{
                                   $this->error[$i] =$i;
                                    echo "<p style='color:red'>第".$i."行插入product_to_category表失败，category_id='".$info[1]."'</p>";
                               }
                               //插入product_to_store 
                               //en:0;de:52;fr:54;es:53;it:55;pt:56
                               $_store_ref_arr = array(
                                   'en' => 0,
                                   'de' => 52,
                                   'fr' => 54,
                                   'es' => 53,
                                   'it' => 55,
                                   'pt' => 56,
                               );
                               
                               $store_query =$this->db->query("SELECT store_id FROM ".DB_PREFIX."store ");
                               $_store = $info[21];
                               $_store = trim($_store);
                               $_store = strtolower($_store);
                               if($_store == ''){
                                   $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=0,sales_num=0"); 
                                   foreach($store_query->rows as $store_res){
                                     $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=".$store_res['store_id'].",sales_num=0"); 
                                   }
                               }else{
                                   $_tmp_store_arr = explode(',',$_store);
                                   $_store_arr = array();
                                   foreach($_tmp_store_arr as $_item){
                                       if(isset($_store_ref_arr[$_item])){
                                           $_store_arr[] = $_store_ref_arr[$_item];
                                       }
                                   }
                                   foreach($store_query->rows as $store_res){
                                       if(in_array($store_res['store_id'],$_store_arr)){
                                           $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=".$store_res['store_id'].",sales_num=0"); 
                                       }
                                   }
                                   if(in_array(0,$_store_arr)){
                                        $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=0,sales_num=0"); 
                                   }
                               }
                               
                               
                               //插入product_special
                                if($info[13]){
                                    $res_product_special =$this->db->query("INSERT INTO ".DB_PREFIX."product_special  set product_id='".$product_id."',customer_group_id=0,priority=1,price='".$info[13]."',date_start='".$info[14]."',date_end='".$info[15]."' ");
                                    if(!$res_product_special){
                                        $this->error[$i] =$i;
                                         echo "<p style='color:red'>第".$i."行插入product_special表失败</p>";
                                    }
                                }

                                //插入product_discount
                                $data_end =date("Y-m-d H:i:s",time()+360*24*3600);
                                $this->db->query("insert into ".DB_PREFIX."product_discount set product_id='".$product_id."',customer_group_id=0,quantity=2,priority=1,price='".($info[12]*0.97)."',date_start=NOW(),date_end ='".$data_end."'");
                                $this->db->query("insert into ".DB_PREFIX."product_discount set product_id='".$product_id."',customer_group_id=0,quantity=10,priority=1,price='".($info[12]*0.93)."',date_start=NOW(),date_end ='".$data_end."'");
                                $this->db->query("insert into ".DB_PREFIX."product_discount set product_id='".$product_id."',customer_group_id=0,quantity=50,priority=1,price='".($info[12]*0.88)."',date_start=NOW(),date_end ='".$data_end."'");

                                //product_description
                                $lang_query =$this->db->query("SELECT language_id  FROM ".DB_PREFIX."language ");
                                $description =str_replace(array("\r\n", "\r", "\n"), "<br>", $info[19]);
                                foreach($lang_query->rows as $lang){
                                    $res_product_description =$this->db->query("INSERT INTO ".DB_PREFIX."product_description  set product_id='".$product_id."',language_id=".$lang['language_id'].",name='".$this->db->escape(trim($info[8]))."',title='".$this->db->escape(trim($info[8]))."',description='".$this->db->escape($description)."',meta_description='".$this->db->escape(trim($info[9]))."', meta_keyword='".$this->db->escape(trim($info[20]))."' ");
                                    if(!$res_product_description){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行插入product_description表失败,lang_id=".$lang['language_id']."</p>";
                                    }
                               }
                               $attribute_group_query =$this->db->query("SELECT attribute_group_id FROM ".DB_PREFIX."attribute_group where attribute_group_code='".trim($info[0])."' ");
                               if($attribute_group_query->row['attribute_group_id']){
                                    $attribute_group_id = $attribute_group_query->row['attribute_group_id'];
                                    //插入product_attribute_group
                                    $this->db->query("INSERT INTO ".DB_PREFIX."product_attribute_group set product_id ='".$product_id."',attribute_group_id='".$attribute_group_id."' ");
                                }
                                else{
                                    $this->error[$i] =$i;
                                    echo "<p style='color:red'>第".$i."行插入attribute_set表失败</p>";
                                }
                            }
                        }
                       $i++;
                    }
                     $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('batch/product_import', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".$i."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->session->data['success'] ="共".$i."条数据上传成功";
                        $this->redirect($this->url->link('batch/product_import', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
                    
				}
			}
		}
    }

   
    public function download(){
        /*
        require_once("E:/www/code/branches/charles0601/system/lib/PHPExcel/PHPExcel.php");
        require_once ('E:/www/code/branches/charles0601/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once ('E:/www/code/branches/charles0601/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        */
        
        require_once(DIR_SYSTEM . "/lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel2007.php'); // 用于 excel-2007 格式
      
        $objPHPExcel = new PHPExcel();
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'attribute_set');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'category_ids');
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'product_code');
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'package_length');
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'package_width');
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'package_height');
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'supplier_code');
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'name');
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'meta_description');
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'gallery');
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'image');
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'price');
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'special_price');
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'special_from_date');
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'special_to_date');
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'weight');
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'qty');
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'is_in_stock');
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'description');
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'meta_keyword');
        $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'store');
        $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'battery_type');
		$objPHPExcel->getActiveSheet()->setTitle("商品批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品批量上传模板.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save("D://gogo_beian.xlsx");
		//$objWriter->save('/home/www/www.myled.com/script/8月份销售单.xlsx');
        $objWriter->save('php://output');
        exit;
    }
    public function getexcelcontent($file){			
        $objReader = new PHPExcel_Reader_Excel2007(); 
        if(!$objReader->canRead($file)){
            $objReader = new PHPExcel_Reader_Excel5(); 
        }
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet();  
        
        $highestRow = $objWorksheet->getHighestRow();   
        $highestColumn = $objWorksheet->getHighestColumn();   
         
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
         
        $excelData = array();  
         
        for ($row = 2; $row <= $highestRow; ++$row) { 
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($col==0&&!$content){
                    break;
                }
                else{
                     if($content&&($col==14||$col==15)){
                       $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                       $content =gmdate("Y-m-d H:i:s", $content);
                    }
                }
                $excelData[$row][] = $content;
                
            }  
        }  
        return $excelData;  
    }
    
    public function getPorImagePath($image){
        $image_array =explode('_',$image);
        $image_sku =$image_array[0];
        $file_path_1 =substr($image_sku,0,1);
        $file_path_2 =substr($image_sku,1,1);
        $image_path ="product/".$file_path_1."/".$file_path_2."/".$image;
        return $image_path;
    }

    public function do_product_photo($file_path){
        $dir_path =$file_path.date("Ymd",time())."-product-photo/";
        $file_arr =scandir($dir_path);
        foreach($file_arr as $file_name){
            if($file_name!='.'&&$file_name!='..'){
                $upload_path =$this->getPorImagePath($file_name);
                $upload_path_dir =substr($upload_path,0,strrpos($upload_path,'/')+1);
                if(!is_dir(DIR_IMAGE.$upload_path_dir)){
                    mkdir(DIR_IMAGE.$upload_path_dir,0777,1);
                }
                copy($dir_path.$file_name,DIR_IMAGE.$upload_path);
            }
        }
        
    }
}
?>