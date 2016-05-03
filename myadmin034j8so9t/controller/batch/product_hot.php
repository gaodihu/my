<?php
class ControllerBatchProductHot extends Controller {
    private $error = array();
    public function index() {

        $this->document->setTitle($this->language->get('product hot标签'));
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => "product hot标签",
            'href'      => $this->url->link('batch/product_hot', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['download'] = $this->url->link('batch/product_hot/download', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['upload'] = $this->url->link('batch/product_hot/upload', 'token=' . $this->session->data['token'], 'SSL');
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
        $this->template = 'batch/product_hot.tpl';
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
        $this->document->setTitle($this->language->get('ProductHot批量上传'));
        $accpet_file = array('xls','xlsx');
        $file_name = $_FILES['uplaod_file']['name'];
        $file_type = substr($file_name,strrpos($file_name,'.')+1);
        if(!in_array($file_type,$accpet_file )){
            $this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/product_hot', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else{

            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel2007.php'); // 用于 excel-2007 格式
            $file_name = date('Y-m-d_H_i_s',time())."_product_hot.".$file_type;

            $this->load->model('catalog/product_hot');
            $this->model_catalog_product_hot->clear();
            if($_FILES['uplaod_file']['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES['uplaod_file']['tmp_name']);
                $i=1;
                foreach($file_content as $info) {

                    $data = array();
                    $data['sku'] = trim($info[0]);
                    if ($data['sku']) {
                        $data['start_time'] = trim($info[1]);
                        $data['end_time'] = trim($info[2]);

                        if ($this->model_catalog_product_hot->isExist($data['sku'])) {
                            $this->error['error'] = 1;
                            echo "第" . $i . "行" . $info[0] . "重复了<br>";
                        } else {
                            $this->model_catalog_product_hot->add($data);
                        }
                    }



                    $i++;
                }
                if($this->error['error']){
                    $back_url =  $this->url->link('batch/product_hot', 'token=' . $this->session->data['token'], 'SSL');
                    echo "<p><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                    $this->session->data['success'] ="数据上传成功";
                    $this->redirect($this->url->link('batch/product_hot', 'token=' . $this->session->data['token'], 'SSL'));
                }
            }
        }
    }


    public function download()
    {
        require_once(DIR_SYSTEM . "lib/PHPExcel/PHPExcel.php");
        require_once(DIR_SYSTEM . 'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once(DIR_SYSTEM . 'lib/PHPExcel/PHPExcel/Writer/Excel2007.php'); // 用于 excel-2007 格式


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'start_time');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'end_time');

        $this->load->model('catalog/product_hot');
        $data = $this->model_catalog_product_hot->getAll();
        $i = 2;
        if ($data) {
            foreach ($data as $item) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['sku']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['start_time']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['end_time']);
                $i++;
            }
         }


        $objPHPExcel->getActiveSheet()->setTitle("Product Hot标签批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
        //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Product-Hot标签批量上传模板.xlsx");
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

}
?>