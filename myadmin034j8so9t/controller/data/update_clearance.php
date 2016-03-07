<?php 
class ControllerDataUpdateClearance extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle("更换Clearance商品");
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "更换Clearance商品",
			'href'      => $this->url->link('data/update_clearance', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);
		$this->data['upload'] = $this->url->link('data/update_clearance/upload', 'token=' . $this->session->data['token'], 'SSL');
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
	    $this->template = 'data/update_clearance.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle("更换Clearance商品");
        $upload_inptu_file_name ='uplaod_add_file';
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES[$upload_inptu_file_name]['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('data/update_clearance', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
			if($_FILES[$upload_inptu_file_name]['tmp_name']){
		            $file_content =$this->getexcelcontent($_FILES[$upload_inptu_file_name]['tmp_name']);
                    $i=2;
                    $update_sku =array();

                    foreach($file_content as $info){
                        header("Content-type: text/html; charset=utf-8");
                        $sku =$info[0];
                        $product_id =$this->get_pro_id($sku);
                        if($product_id){
                            $update_sku[] =$product_id;
                        }else{
                            $this->error[$i] =$i;
                            echo "<p style='color:red'>第".$i."行sku不存在，请检查！</p>";
                        }
                       $i++;
                     }
                     $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('data/update_clearance', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".($i-2)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->db->query("delete from ".DB_PREFIX."product_to_category where category_id=254");
                        foreach($update_sku as $pid){
                            $this->db->query("insert into ".DB_PREFIX."product_to_category set product_id='".$pid."',category_id=254,position=0");
                        }
                        $this->session->data['success'] ="共".($i-2)."条数据上传成功";
                        $this->redirect($this->url->link('data/update_clearance', 'token=' . $this->session->data['token'], 'SSL'));  
                    }

                    
				}
		}
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
    public function get_pro_id($sku){
        $query =$this->db->query("select product_id from ".DB_PREFIX."product where model='".$this->db->escape(trim($sku))."' limit 1");
        if($query->row){
            return $query->row['product_id'];
        }
        else{
            return false;
        }
    }
}
?>