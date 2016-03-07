<?php 
class ControllerBatchProductUpdateTranslate extends Controller {
	private $error = array();
    private $path = DIR_DATA;
    //private $path = 'D://XAMPP/htdocs/new_myled/'; 
	public function index() {
		
		$this->document->setTitle($this->language->get('商品批量翻译')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "商品批量翻译",
			'href'      => $this->url->link('batch/product_update_translate', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/product_update_translate/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/product_update_translate/upload', 'token=' . $this->session->data['token'], 'SSL');	
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
	    $this->template = 'batch/product_update_translate.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('商品批量修改')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/product_update_translate', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            require_once($this->path."system/lib/PHPExcel/PHPExcel.php");
            require_once ($this->path."system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
            require_once ($this->path."system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
			$file_path =$this->path."upload/product/";
			$file_name = date('Y-m-d_H_i_s',time())."_product_update_translate.".$file_type;
			if($_FILES['uplaod_file']['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES["uplaod_file"]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    $i=1;
                    foreach($file_content as $key=>$info){
                            header("Content-type: text/html; charset=utf-8");
                            $query_product_exist =$this->db->query("SELECT product_id FROM ".DB_PREFIX."product where model ='".$info[0]."'");
                            if($query_product_exist->num_rows){
                                $product_id =$query_product_exist->row['product_id'];
                                $lang_id =$info[1];
                                
                                //name
                                $name =$info[2];
                                if($lang_id=='1'&&$name){
                                    //$url_path =str_replace(' ','-',strtolower($name));
                                    //$url_path =str_replace('.','-',$url_path);
                                    //$url_path =str_replace('(','-',$url_path);
                                    //$url_path =str_replace(')','',$url_path);
                                    
                                    $url_path = preg_replace('/[^\d\w\-]/','-',strtolower($name));
                                    $url_path = preg_replace('/(\-+)/','-',$url_path);
                                    $last_url_path = substr($url_path,-1,1);
                                    if($last_url_path == '-'){
                                        $url_path = substr($url_path,0,-1);
                                    }
                                    
                                    $url_path ="p".$product_id."-".$url_path;
                                    $this->db->query("UPDATE ".DB_PREFIX."product set url_path='".$url_path."' where product_id=".$product_id);
                                    $this->db->query("UPDATE ".DB_PREFIX."product_description set  title='".$this->db->escape(trim($name))."' where product_id=".$product_id);
                                }
                                $query_name_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  name='".$this->db->escape(trim($name))."' where product_id=".$product_id." and language_id=".$lang_id);
                                if(!$query_name_update){
                                    $this->error[$i] =$i;
                                    echo "<p style='color:red'>第".$i."行更新product name失败</p>";
                                }

                                //description
                                $description =$info[3];
                                if($description){
                                    $description =str_replace(array("\r\n", "\r", "\n"), "<br>", $description);
                                    $query_description_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  description='".$this->db->escape(trim($description))."' where product_id=".$product_id." and language_id=".$lang_id);
                                    if(!$query_description_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product description失败</p>";
                                    }
                                }
                                //meta_description
                                $meta_description =$info[4];
                                if($meta_description){
                                    $query_meta_description_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  meta_description='".$this->db->escape(trim($meta_description))."' where product_id=".$product_id." and language_id=".$lang_id);
                                    if(!$query_meta_description_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product meta_description失败</p>";
                                    }
                                }
                                //meta_keyword
                                $meta_keyword =$info[5];
                                if($meta_keyword){
                                    $query_meta_keyword_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  meta_keyword='".$this->db->escape(trim($meta_keyword))."' where product_id=".$product_id." and language_id=".$lang_id);
                                    if(!$query_meta_keyword_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product meta_keyword失败</p>";
                                    }
                                }
                                
                                
                            }
                            else{
                                 $this->error[$i] =$i;
                                 echo "<p style='color:red'>第".$i."行SKU不存在</p>";
                            }
                       $i++;
                    }
                    $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('batch/product_update_translate', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".$i."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->session->data['success'] ="共".$i."条数据上传成功";
                        $this->redirect($this->url->link('batch/product_update_translate', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
				}
			}
		}
    }

    public function download(){
        require_once($this->path."system/lib/PHPExcel/PHPExcel.php");
        require_once ($this->path.'system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once ($this->path.'system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
     
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'lang_id(en:1;de:4;Fr:5;ES:6;IT:7;pt:8)');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'name');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'description');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'meta_description');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'meta_keyword');
        $objPHPExcel->getActiveSheet()->setTitle("首页商品批量翻译模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=首页商品批量翻译模板.xlsx" );
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
        $time_file =array(); 
        for ($row = 2; $row <= $highestRow; ++$row) { 
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($col==0&&!$content){
                    break;
                }
                  //富文本转换字符串  
                 if($content instanceof PHPExcel_RichText){    
                    $content = $content->__toString();  
                 }
                $excelData[$row][] = $content;
                
            }  
        }  
        return $excelData;  
    }
}
?>