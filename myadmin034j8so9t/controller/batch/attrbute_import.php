<?php 
class ControllerBatchAttrbuteImport extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle($this->language->get('编辑新属性')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "编辑新属性",
			'href'      => $this->url->link('batch/attrbute_import', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download_add'] = $this->url->link('batch/attrbute_import/download', 'type=add&token=' . $this->session->data['token'], 'SSL');
        $this->data['download_update'] = $this->url->link('batch/attrbute_import/download', 'type=update&token=' . $this->session->data['token'], 'SSL');
		$this->data['upload_add'] = $this->url->link('batch/attrbute_import/upload', 'act=add&token=' . $this->session->data['token'], 'SSL');
        $this->data['upload_update'] = $this->url->link('batch/attrbute_import/upload', 'act=update&token=' . $this->session->data['token'], 'SSL');
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
	    $this->template = 'batch/attrbute_import.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('属性值批量修改')); 
        $act =isset($this->request->get['act'])?$this->request->get['act']:'';
        if($act=='add'){
            $upload_inptu_file_name ='uplaod_add_file';
        }
        elseif($act=='update'){
            $upload_inptu_file_name ='uplaod_update_file';
        }
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES[$upload_inptu_file_name]['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/attrbute_import', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            $lang =array(1,4,5,6,7,8,99);
            
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
			$file_path = DIR_DATA . "upload/attrbute/";
            
            
            /*
            $file_path ="D://XAMPP/htdocs/new_myled/upload/attrbute/";
            */
			$file_name = date('Y-m-d_H_i_s',time())."_attrbute.".$file_type;
            
			if($_FILES[$upload_inptu_file_name]['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES[$upload_inptu_file_name]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    $i=2;
                    if($act=='add'){
                        foreach($file_content as $info){
                            header("Content-type: text/html; charset=utf-8");
                            $attr_id =$this->get_attr_id($info[0]);
                            $attr_value =$info[1];
                            
                            if($attr_id){
                                //判断属性值是否存在
                                $query_exit =$this->db->query("select naov.option_id from ".DB_PREFIX."new_attribute_option_value as naov 
                                     left join ".DB_PREFIX."new_attribute_option as nao on naov.option_id=nao.option_id  where naov.language_id=1 and naov.option_value='".trim($attr_value)."' and nao.attribute_id='".$attr_id."' limit 1");
                                if($query_exit->row['value_id']){
                                    $this->error[$i] =$i;
                                    echo "<p style='color:red'>第".$i."行已经存在该属性值，请检查！</p>";
                                }
                                else{
                                    $query =$this->db->query("INSERT INTO ".DB_PREFIX."new_attribute_option set attribute_id='".(int)$attr_id."',is_show_front =0,sort_order=0");
                                    $option_id =$this->db->getLastId();
                                    foreach($lang as $lang_id){
                                        $this->db->query("INSERT INTO ".DB_PREFIX."new_attribute_option_value set option_id='".(int)$option_id."',language_id ='".(int)$lang_id."',option_value='".$this->db->escape(trim($attr_value))."' ");
                                    }
                                }
                                
                            }
                            else{
                                $this->error[$i] =$i;
                                echo "<p style='color:red'>第".$i."行无此属性名，请检查！</p>";
                            }
                         
                           $i++;
                         }
                    }
                    elseif($act=='update'){
                        foreach($file_content as $info){
                            header("Content-type: text/html; charset=utf-8");
                            $attr_id =$this->get_attr_id($info[0]);
                            $old_attr_value =$info[1];
                            $new_attr_value =$info[2];
                            if($attr_id){
                                //是否存在旧的属性值
                                $query_exit =$this->db->query("select naov.option_id from ".DB_PREFIX."new_attribute_option_value as naov 
                                left join ".DB_PREFIX."new_attribute_option as nao on naov.option_id=nao.option_id  where naov.language_id=1 and naov.option_value='".trim($old_attr_value)."' and nao.attribute_id='".$attr_id."' ");
                                if($query_exit->row['option_id']){
                                    $option_id =$query_exit->row['option_id'];
                                    $query =$this->db->query("UPDATE ".DB_PREFIX."new_attribute_option_value set option_value='".$this->db->escape(trim($new_attr_value))."' where option_id='".$option_id."' ");
                                    
                                }
                                else{
                                    $this->error[$i] =$i;
                                    echo "<p style='color:red'>第".$i."行属性名下不存在旧的属性值，请检查！</p>";
                                }
                                
                            }
                            else{
                                $this->error[$i] =$i;
                                echo "<p style='color:red'>第".$i."行无此属性名，请检查！</p>";
                            }
                         
                           $i++;
                         }
                    }
                 
                     $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('batch/attrbute_import', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".($i-2)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->session->data['success'] ="共".($i-2)."条数据上传成功";
                        $this->redirect($this->url->link('batch/attrbute_import', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
                    
				}
			}
		}
    }

   
    public function download(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel2007.php'); // 用于 excel-2007 格式
        
        $objPHPExcel = new PHPExcel();
        $i=1;
        $type =isset($this->request->get['type'])?$this->request->get['type']:'';
        if($type=='add'){
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '属性名');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '新增属性值');
            $file_name ="新属性值新增模板";
        }
        elseif($type=='update'){
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '属性名');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '旧的属性值');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '新的属性值');
            $file_name ="新属性值更新模板";
        }
        
        
		$objPHPExcel->getActiveSheet()->setTitle($file_name);
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=".$file_name.".xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
                 //富文本转换字符串  
                 if($content instanceof PHPExcel_RichText){    
                    $content = $content->__toString();  
                 }
                $excelData[$row][] = $content; 
            }  
        }  
        return $excelData;  
    }


    public function get_attr_id($attr_name){
        $query =$this->db->query("select attribute_id from ".DB_PREFIX."new_attribute_description where language_id=1 and name='".$this->db->escape(trim($attr_name))."' limit 1");
        if($query->row){
            return $query->row['attribute_id'];
        }
        else{
            return false;
        }

    }
}
?>