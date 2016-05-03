<?php 
class ControllerBatchHomeImport extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle($this->language->get('首页商品批量上传')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "首页商品批量上传",
			'href'      => $this->url->link('batch/home_import', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/home_import/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/home_import/upload', 'token=' . $this->session->data['token'], 'SSL');	
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
	    $this->template = 'batch/home_import.tpl';
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
        $this->document->setTitle($this->language->get('商品批量上传')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/home_import', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{

            
            require_once(DIR_SYSTEM . "/lib/PHPExcel/PHPExcel.php");
            require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
			$file_path = DIR_DATA . "upload/product/";
            
            
            
            /*
            require_once("D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel.php");
            require_once ('D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once ('D://XAMPP/htdocs/new_myled/system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
            $file_path ="D://XAMPP/htdocs/new_myled/upload/product/";
            */
			$file_name = date('Y-m-d_H_i_s',time())."_home_product.".$file_type;
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
                    foreach($file_content as $info){
                        $sku =trim($info[0]);
                        $query_id =$this->db->query("select product_id from ".DB_PREFIX."product where model='".$sku."' ");
                        if($query_id->num_rows){
                            $product_id =$query_id->row['product_id'];
                            $query_exist =$this->db->query("select rec_id from ".DB_PREFIX."home_products where product_id='".$product_id."' and type='".$info[1]."' ");
                            if($query_exist->num_rows){
                                $this->db->query("UPDATE ".DB_PREFIX."home_products set  start_time='".$info[3]."',end_time='".$info[4]."',sort_order='".$info[5]."' where rec_id=".$query_exist->row['rec_id']." ");
                            }else{
                                $this->db->query("INSERT INTO ".DB_PREFIX."home_products set  product_id='".$product_id."',type='".$info[1]."',start_time='".$info[3]."',end_time='".$info[4]."',sort_order='".$info[5]."' ");
                            }
                            if($info[1]=='1'){
                                //today deals
                                $this->db->query("delete from oc_product_special where product_id=".$product_id);
                                $this->db->query("insert into oc_product_special set  product_id='".$product_id."',customer_group_id=0,priority=1,price='".$info[2]."',date_start='".$info[3]."',date_end='".$info[4]."' ");
                            }
                        }
                        else{
                            $this->error['error'] =1;
                            echo "第".$i."行商品sku不存在<br>";
                        }
                      $i++;
                    }
                    if($this->error['error']){
                        $back_url =  $this->url->link('batch/home_import', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->session->data['success'] ="数据上传成功";
                        $this->redirect($this->url->link('batch/home_import', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
                    
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
        require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once(DIR_SYSTEM . '/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
     
        $objPHPExcel = new PHPExcel();
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'type(商品类型，1:特价，2热销，3新品)');
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'price(首页特价需要填写，其他类型不需要)');
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'start_time');
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'end_time');
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'sort_order');
        
		$objPHPExcel->getActiveSheet()->setTitle("首页商品批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=首页商品批量上传模板.xlsx" );
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
                     if($content&&($col==3||$col==4)){
                       $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                       $content =gmdate("Y-m-d H:i:s", $content);
                    }
                }
                $excelData[$row][] = $content;
                
            }  
        }  
        return $excelData;  
    }
}
?>