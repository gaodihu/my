<?php
class ControllerCatalogBrochures extends Controller {
    private $error = array();
    private $up_error = array();
    public function index() {
      
        $this->document->setTitle('商品质检报告');
        $this->data['token'] =$this->session->data['token'];
        $this->data['update'] =$this->url->link('catalog/brochures/update', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['import'] =$this->url->link('catalog/brochures/import', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['down_templete'] =$this->url->link('catalog/brochures/down_templete', 'token=' . $this->session->data['token'], 'SSL');
         if(isset($this->session->data['success'])){
            $this->data['success'] =$this->session->data['success'];
        }
        $this->template = 'catalog/brochures.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    } 
    
    public function delete(){
        $this->load->model('catalog/product_brochures');
        $sku =$this->request->get['sku'];
        $id =$this->request->get['id'];
        $this->model_catalog_product_brochures->delProductBrochures($id);
        $this->redirect($this->url->link('catalog/brochures/update', 'token=' . $this->session->data['token']."&filter_sku=".$sku, 'SSL'));
    }
    public function update() {
        $this->document->setTitle("编辑商品质检报告");

        $this->load->model('catalog/product_brochures');
        $sku =$this->request->get['filter_sku'];
        $this->data['update'] =$this->url->link('catalog/brochures/update', 'token=' . $this->session->data['token']."&filter_sku=".$sku, 'SSL');
        $this->data['cancel'] =$this->url->link('catalog/brochures', 'token=' . $this->session->data['token'], 'SSL');
        if(isset($this->session->data['success'])){
            $this->data['success'] =$this->session->data['success'];
        }
        if($sku){
            $product_id =$this->get_product_id($sku);
            $this->data['sku']  =$sku;
            //得到商品的所有质检报告
            $all_broches =$this->model_catalog_product_brochures->getProductBrochures($product_id);
            $this->data['all_broches']  =array();
            foreach($all_broches as $broches){
                $this->data['all_broches'][] =array(
                    'id' =>  $broches['id'], 
                    'brochures_path' =>  $broches['brochures_path'],  
                    'delete' =>  $this->url->link('catalog/brochures/delete', 'token=' . $this->session->data['token']."&id=".$broches['id']."&sku=".$sku, 'SSL') 
                );
            }
            
      
            $this->data['web_url'] =HTTP_CATALOG;
            if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                $pdf_path =str_replace('system','pdf',DIR_SYSTEM)."brochures/";
                if(!is_dir($pdf_path)){
                    mkdir($pdf_path,0777,1);
                }
                $accpet_file = array('pdf');
                
                if(isset($_FILES['old_broches'])){
                    foreach($_FILES['old_broches']['tmp_name'] as $key=>$temp_name){
                        if($temp_name){
                            $file_name = $_FILES['old_broches']['name'][$key];
                            $file_type = substr($file_name,strrpos($file_name,'.')+1);
                            if(!in_array($file_type,$accpet_file )){
                                $this->up_error['wring'][] =$_FILES['old_broches']['name'][$key];
                            }
                            else{
                                $is_have =$this->model_catalog_product_brochures->HaveProductBrochures($_FILES['old_broches']['name'][$key]);
                                if(!$is_have){
                                    if(move_uploaded_file($temp_name,$pdf_path.$_FILES['old_broches']['name'][$key])){
                                        $this->model_catalog_product_brochures->updateProductBrochures($key,$_FILES['old_broches']['name'][$key]);
                                    }else{
                                        $this->up_error['error'][] =$_FILES['old_broches']['name'][$key];
                                    }
                                }else{
                                    $this->up_error['message'][] =$_FILES['old_broches']['name'][$key];
                                }
                            }
                            
                        }
                    }
                }
                if(isset($_FILES['new_broches'])){
                    foreach($_FILES['new_broches']['tmp_name'] as $key=>$temp_name){
                        if($temp_name){
                            $file_name = $_FILES['new_broches']['name'][$key];
                            $file_type = substr($file_name,strrpos($file_name,'.')+1);
                            if(!in_array($file_type,$accpet_file )){
                                $this->up_error['wring'][] = $_FILES['new_broches']['name'][$key];
                            }
                            else{
                                $is_have =$this->model_catalog_product_brochures->HaveProductBrochures($_FILES['new_broches']['name'][$key]);
                                if(!$is_have){
                                    if(move_uploaded_file($temp_name,$pdf_path.$_FILES['new_broches']['name'][$key])){
                                        $data =array(
                                            'product_id'=>$product_id,
                                            'path' =>$_FILES['new_broches']['name'][$key]
                                        );
                                        $this->model_catalog_product_brochures->addProductBrochures($data);
                                    }else{
                                        $this->up_error['error'][] =$_FILES['new_broches']['name'][$key];
                                    }
                                }else{
                                    $this->up_error['message'][] =$_FILES['new_broches']['name'][$key];
                                }
                            }
                        }
                    }
                }
                $message ="";
                if($this->up_error['wring']){
                    $file_error_name =implode(',',$this->up_error['wring']);
                    $message .="以下文件".$file_error_name."不是pdf文件";
                }
                elseif($this->up_error['error']){
                    $file_error_name =implode(',',$this->up_error['error']);
                    $message .="以下文件".$file_error_name."上传失败";
                }elseif($this->up_error['message']){
                    $file_error_name =implode(',',$this->up_error['message']);
                    $message .="以下文件".$file_error_name."文件重名";
                }else{
                    $message ="上传成功";
                }
                $this->session->data['success'] = $message;
                $this->redirect($this->url->link('catalog/brochures/update', 'token=' . $this->session->data['token']."&filter_sku=".$sku, 'SSL'));
           }
           $this->template = 'catalog/brochures_update.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );
            $this->response->setOutput($this->render());
        }else{
            $this->redirect($this->url->link('catalog/brochures', 'token=' . $this->session->data['token'], 'SSL'));
        }

    }
    public function import(){
        $this->document->setTitle('上传质检报告');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => "上传质检报告",
            'href'      => $this->url->link('catalog/brochures', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );
        $this->data['heading_title'] ='上传质检报告';
        $this->data['action'] =$this->url->link('catalog/brochures/upload', 'token=' . $this->session->data['token'], 'SSL');
        $this->template = 'catalog/upload_brochures.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    public function upload(){
        $this->load->model('catalog/product_brochures');
        header("Content-type: text/html; charset=utf-8");
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('商品质检报告批量上传')); 
        $accpet_file = array('xlsx','xls');
        $file_name = $_FILES['uplaod_file']['name'];
        $file_type = substr($file_name,strrpos($file_name,'.')+1);
        if(!in_array($file_type,$accpet_file )){
            $this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('catalog/brochures/import', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else{
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
            if($_FILES['uplaod_file']['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES['uplaod_file']['tmp_name']);
                $i=1;
                foreach($file_content as $info){
                    $sku =trim($info[0]);
                    $good_id =$this->get_product_id($sku);
                    if(!$good_id){
                        $this->up_error[$i] =$i;
                        echo "第".$i."行sku不存在<br>";
                    }else{
                        $is_have =$this->model_catalog_product_brochures->HaveProductBrochures(trim($info[1]));
                        if(!$is_have){
                             $query=$this->db->query("INSERT INTO ".DB_PREFIX."product_brochures  set product_id='".(int)$good_id."',brochures_path='".$this->db->escape(trim($info[1]))."',add_time=NOW(),update_time=NOW() ");
                        }else{
                            $this->up_error[$i] =$i;
                            echo "第".$i."行pdf文件重名<br>";
                        }
                    }
                  $i++;
                }
                $error_count =count($this->up_error);
                if($error_count>0){
                    $back_url =  $this->url->link('catalog/brochures/import', 'token=' . $this->session->data['token'], 'SSL'); 
                    echo "<p>共上传".($i-1)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                     $this->session->data['success'] ="共".($i-1)."条数据上传成功";
                    $this->redirect($this->url->link('catalog/brochures', 'token=' . $this->session->data['token'], 'SSL'));  
                }
            }
        }
    }
    public function down_templete(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SKU');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'file name(避免重名情况)');
        $objPHPExcel->getActiveSheet()->setTitle("商品质检报告批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品质检报告批量上传模板.xlsx" );
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
                 if($col==6){
                   $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                   $content =gmdate("Y-m-d H:i:s", $content);
                }
                $excelData[$row][] = $content;
            }  
        }  
        return $excelData;  
    }
    protected function get_product_id($sku){
        $query =$this->db->query("select product_id from oc_product where model='".$sku."' limit 1");
        $row =$query->row;
        if($row){
            return $row['product_id'];
        }
        else{
            return false;
        }
    }
}
?>