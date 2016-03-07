<?php 
class ControllerBatchProductDescBatch extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle($this->language->get('商品描述板块批量修改')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "商品描述板块批量修改",
			'href'      => $this->url->link('batch/product_desc_batch', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/product_desc_batch/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/product_desc_batch/upload', 'token=' . $this->session->data['token'], 'SSL');	
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
	    $this->template = 'batch/product_desc_batch.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','512M');
        set_time_limit(0);
        header("Content-type: text/html; charset=utf-8");
        $this->document->setTitle($this->language->get('商品描述板块批量修改')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/product_desc_batch', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{

            
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
			if($_FILES['uplaod_file']['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES['uplaod_file']['tmp_name']);
                $i=1;
                foreach($file_content as $info){
                    $query_product_exist =$this->db->query("SELECT product_id FROM ".DB_PREFIX."product where model ='".$info[0]."'");
                    if(!$query_product_exist->num_rows){
                        $this->error[$i] =$i;
                        echo "<p style='color:red'>第".$i."行SKU不存在</p>";
                    }else{
                        $product_id =$query_product_exist->row['product_id'];
                        $query_desc_exit =$this->db->query("SELECT * FROM ".DB_PREFIX."product_description where product_id ='".(int)$product_id."'");
                        if(!$query_product_exist->num_rows){
                            $this->error[$i] =$i;
                            echo "<p style='color:red'>第".$i."行SKU不存在描述和标题信息，请完善</p>";
                        }
                        else{
                            if($info[7]){
                                $video ='<iframe frameborder="0" height="350" src="'.trim($info[7]).'" width="425"></iframe>';
                            }else{
                                $video='';
                            }
                            for($k=1;$k<=8;$k++){
                                $info[$k] =str_replace(array("\r\n", "\r", "\n"), "</br>", $info[$k]); 
                                preg_match_all('#img:(.*?):end#i',$info[$k],$macth);
                                $img_count =count($macth[1]);
                                if($img_count>0){
                                    for($j=0;$j<$img_count;$j++){
                                        $replace_str='';
                                        $img_str =$macth[1][$j];
                                        $img_arr =explode("||",$img_str);
                                        foreach($img_arr as $item){
                                            $item ="/image/product_desc/".$item;
                                            $replace_str .='<img src="'.$item.'" >';
                                        }
                                        $info[$k] =str_replace($macth[0][$j],$replace_str,$info[$k]);

                                    }
                                } 
                            }
                            $sql_update ="UPDATE ".DB_PREFIX."product_description set ";
                            $update_str =array();
                            if($info[1]){
                                $update_str[]="packaging_list='".$this->db->escape(trim($info[1]))."'";
                            }
                            if($info[2]){
                                $update_str[]="read_more='".$this->db->escape(trim($info[2]))."'";
                            }
                            if($info[3]){
                                $update_str[]="application_image='".$this->db->escape(trim($info[3]))."'";
                            }
                            if($info[4]){
                                $update_str[]="size_image='".$this->db->escape(trim($info[4]))."'";
                            }
                            if($info[5]){
                                $update_str[]="features='".$this->db->escape(trim($info[5]))."'";
                            }
                            if($info[6]){
                                $update_str[]="installation_method='".$this->db->escape(trim($info[6]))."'";
                            }
                            if($info[7]){
                                $update_str[]="video='".$this->db->escape($video)."'";
                            }
                            if($info[8]){
                                $update_str[]="notes='".$this->db->escape(trim($info[8]))."'";
                            }
                            $sql_update .= implode(',',$update_str);
                            $sql_update .=" where product_id= ".$product_id;
                            $this->db->query($sql_update);
                        }
                        
                    }
                   $i++;
                }
                 $error_count =count($this->error);
                 if($error_count>0){
                    $back_url =  $this->url->link('batch/product_desc_batch', 'token=' . $this->session->data['token'], 'SSL');  
                    echo "<p>共上传".($i-1)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                    $this->session->data['success'] ="共".($i-1)."条数据上传成功";
                    $this->redirect($this->url->link('batch/product_desc_batch', 'token=' . $this->session->data['token'], 'SSL'));  
                }   
			}
		}
    }

   
    public function download(){
       
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SKU');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Packaging List');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Read More');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Application Image');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Size Image');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Features');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Installation Method/Usage');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Video');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Notes');
		$objPHPExcel->getActiveSheet()->setTitle("商品描述板块批量修改模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品描述板块批量修改模板.xlsx" );
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