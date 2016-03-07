<?php 
class ControllerBatchBannerImport extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle($this->language->get('网站banner批量上传')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "网站banner批量上传",
			'href'      => $this->url->link('batch/banner_import', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/banner_import/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/banner_import/upload', 'token=' . $this->session->data['token'], 'SSL');	
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
	    $this->template = 'batch/banner_import.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        header("Content-type: text/html; charset=utf-8");
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('网站banner批量上传')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/banner_import', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            $lang_array =array(1,4,5,6,7,8);
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
			$file_name = date('Y-m-d_H_i_s',time())."_deals_product.".$file_type;
			if($_FILES['uplaod_file']['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES['uplaod_file']['tmp_name']);
                $i=1;
                foreach($file_content as $info){
                    if(!in_array($info[0],$lang_array)){
                        $this->error['error'] =1;
                        echo "第".$i."行语言lang_id".$info[0]."不存在<br>";
                    }
                    elseif(!$banner_info =$this->get_banner_info($info[1])){
                        $this->error['error'] =1;
                        echo "第".$i."行banner_id不存在<br>";
                    }elseif(!file_exists(DIR_IMAGE.$info[5])){
                        $this->error['error'] =1;
                        echo "第".$i."行图片路径不存在<br>";
                    }else{
                       $query=$this->db->query("INSERT INTO ".DB_PREFIX."banner_image_description set language_id='".(int)$info[0]."',banner_id='".(int)$info[1]."',title='".$this->db->escape(trim($info[2]))."',link='".$this->db->escape(trim($info[3]))."',sort='".(int)$info[4]."',image='".$this->db->escape(trim($info[5]))."',start_time='".$info[6]."',end_time='".$info[7]."',status='".(int)$info[8]."',category_id='".(int)$info[9]."' ");
                    }
                  $i++;
                }
                if($this->error['error']){
                    $back_url =  $this->url->link('batch/banner_import', 'token=' . $this->session->data['token'], 'SSL');  
                    echo "<p><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                    $this->session->data['success'] ="数据上传成功";
                    $this->redirect($this->url->link('batch/banner_import', 'token=' . $this->session->data['token'], 'SSL'));  
                }
			}
		}
    }

   
    public function download(){
        
        
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        //得到网站的banner code
        $banner_info =$this->get_banners();
        $str ='';
        foreach($banner_info  as $banner){
            $str .=$banner['name']."对应的banner_id 是".$banner['banner_id'].";";
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Language(EN:1,DE:4,FR:5,ES:6,IT:7,PT:8)');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'banner_id('.$str.')');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'title');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'link');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'sort');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'image');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'start time');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'end time');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'status(1,启用 0,停用)');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'category_id(用于目录banner,没有则填写0)');

        
		$objPHPExcel->getActiveSheet()->setTitle("网站banner批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=网站banner批量上传模板.xlsx" );
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
                if($content instanceof PHPExcel_RichText){    
                        $content = $content->__toString();  
                 }
                 if($content&&($col==6||$col==7)){
                   $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                   $content =gmdate("Y-m-d H:i:s", $content);
                }
                $excelData[$row][] = $content;
                
            }  
        }  
        return $excelData;  
    }


    public function get_banners(){
        $query =$this->db->query("select * from ".DB_PREFIX."banner");
        return $query->rows;
    }

    public function get_banner_info($banner_id){
        $query =$this->db->query("select * from ".DB_PREFIX."banner where banner_id=".$banner_id);
        if($query->num_rows){
            return $query->row;
        }
        else{
            return false;
        }
    }
}
?>