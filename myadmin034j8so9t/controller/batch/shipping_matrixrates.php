<?php 
class ControllerBatchShippingMatrixrates extends Controller {
	private $error = array(); 
	public function index() {

        $this->document->setTitle($this->language->get('增加物流数据')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "增加物流数据",
			'href'      => $this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/shipping_matrixrates/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/shipping_matrixrates/upload', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['delete'] = $this->url->link('batch/shipping_matrixrates/deletematrixrate', 'token=' . $this->session->data['token'], 'SSL');
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
	    $this->template = 'batch/add_matrixrates.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}
    
    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('增加物流数据')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            
            require_once(DIR_SYSTEM . "/lib/PHPExcel/PHPExcel.php");
            require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
			$file_path = DIR_DATA . "/upload/matrixrates/";
            
            

			$file_name = date('Y-m-d_H_i_s',time())."addmatrixrates.".$file_type;
			if($_FILES['uplaod_file']['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES["uplaod_file"]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    foreach($file_content as $info){
                        $query =$this->db->query("INSERT INTO ".DB_PREFIX."shipping_matrixrate set website_id=1,dest_country_id='".$info[1]."',condition_name='".$info[6]."',condition_from_value='".$info[7]."',condition_to_value='".$info[8]."',price='".$info[9]."',cost=0,delivery_type='".$info[11]."',delivery_method='".$info[12]."' ");
                    }


                    $this->session->data['success'] ="数据上传成功";
                    $this->redirect($this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
		}
    }

    public function download(){
        /*
        require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
        require_once ('D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once ('D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        */
        require_once(DIR_SYSTEM . "/lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM . '/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
        $objPHPExcel = new PHPExcel();
        $time =date('Y-m-d',time());
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'pk_id');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '国家');
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '地区');
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '城市');
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'dest_zip');
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'dest_zip_to');
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'condition_name(package_weight)');
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, '重量起点');
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, '重量终点');
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, '运费金额');
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, '运费cost(0)');
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, '运输方式');
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, '物流渠道');
       
		$objPHPExcel->getActiveSheet()->setTitle("新增物流数据模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=新增物流数据模板.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save("D://gogo_beian.xlsx");
		//$objWriter->save('/home/www/www.myled.com/script/8月份销售单.xlsx');
        $objWriter->save('php://output');
        exit;
    }
    
    public function deletematrixrate(){
        $this->document->setTitle($this->language->get('删除物流数据')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "增加物流数据",
			'href'      => $this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);
        
        $this->data['shipping_matrixrates'] = $this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action'] = $this->url->link('batch/shipping_matrixrates/deletematrixrate', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['token'] = $this->session->data['token'];
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validateDelete()) {
            $country_code_str =$this->request->post['country_code'];
            $delivery_type_str =$this->request->post['delivery_type'];
            $country_code_array =explode(",",$country_code_str);
            $country_code_new_array =array();
            foreach($country_code_array as $country_code){
                $country_code_new_array[] ="'".trim($country_code)."'";
            }
            $in_country_code_str =implode(",",$country_code_new_array);
            $delivery_type_arr =explode(",",$delivery_type_str);
            $delivery_type_new_array =array();
             foreach($delivery_type_arr as $delivery_type){
                $delivery_type_new_array[] ="'".trim($delivery_type)."'";
            }
           $in_delivery_type_str =implode(",",$delivery_type_new_array);
            $this->db->query("delete from ".DB_PREFIX."shipping_matrixrate where dest_country_id in (".$in_country_code_str.") and delivery_type in (".$in_delivery_type_str.")");

            //检查是否全部删除
            $query_exist =$this->db->query("select count(*) as total from ".DB_PREFIX."shipping_matrixrate where dest_country_id in (".$in_country_code_str.") and delivery_type in (".$in_delivery_type_str.")");
            if($query_exist->row['total']>0){
                $this->session->data['success'] ="共有".$query_exist->row['total']."行数据没有清空";
            }
            else{
                $this->session->data['success'] ="数据删除成功";
                $this->redirect($this->url->link('batch/shipping_matrixrates', 'token=' . $this->session->data['token'], 'SSL'));
            }
		}
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
	    $this->template = 'batch/delete_matrixrates.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
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
            //for ($row = 2; $row <= 10; ++$row) { 
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {  
                $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $excelData[$row][] = $content;
                
            }  
        }  
        
        return $excelData;  
    }

    public function validateDelete(){
        $country_code =isset($this->request->post['country_code'])?$this->request->post['country_code']:'';
        $delivery_type =isset($this->request->post['delivery_type'])?$this->request->post['delivery_type']:'';
        if(!$country_code||!$delivery_type){
            $this->error['warning'] ="请填写国家代码和delivery_type";
        }
        if($this->error){
            return false;
        }
        else{
             return true;
        }
    }
}
?>