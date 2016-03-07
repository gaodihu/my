<?php 
class ControllerDataProImgUpdate extends Controller {
	private $error = array();
    //private $root_path ="D:/tinker0609/upload/product/";
    private $root_path =DIR_DATA ."/upload/product/";
	public function index() {
		
		$this->document->setTitle("修改商品图片");
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "修改商品图片",
			'href'      => $this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);


        $this->data['token'] = $this->session->data['token'];

        $this->data['update_img_download'] = $this->url->link('data/pro_img_update/updownload', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['add_img_download'] = $this->url->link('data/pro_img_update/adddownload', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['update_img_upload'] = $this->url->link('data/pro_img_update/update', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['add_img_upload'] = $this->url->link('data/pro_img_update/add', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	    $this->template = 'data/pro_img_update.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
    public function update(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle("批量更改商品图片");
        $upload_inptu_file_name ='update_file';
        $accpet_file = array('xls','xlsx');
        $file_name = $_FILES[$upload_inptu_file_name]['name'];
        $file_type = substr($file_name,strrpos($file_name,'.')+1);
        if(!in_array($file_type,$accpet_file )){
            $this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else{
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
            if($_FILES[$upload_inptu_file_name]['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES[$upload_inptu_file_name]['tmp_name']);
                $this->do_product_photo($this->root_path);
                $i=2;
                foreach($file_content as $info){
                    header("Content-type: text/html; charset=utf-8");
                    $sku =trim($info[0]);
                    $product_id =$this->get_pro_id($sku);
                    if($product_id){
                        //插入商品图片集
                        $gallery_path =$this->getPorImagePath(substr($info[2],1));
                        $res_product_image=$this->db->query("update ".DB_PREFIX."product_image set image='".$this->db->escape(trim($gallery_path))."' where product_id='".$product_id."' and image='".trim($info[1])."' ");
                         if(!$res_product_image){
                            $this->error[$i] =$i;
                            echo "<p style='color:red'>第".$i."行更新product_image表失败，image='".$info[1]."'</p>";
                        }
                  
                    }else{
                        $this->error[$i] =$i;
                        echo "<p style='color:red'>第".$i."行sku不存在，请检查！</p>";
                    }
                    $i++;
                }
                $error_count =count($this->error);
                if($error_count>0){
                    $back_url =  $this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL');
                    echo "<p>共上传".($i-2)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                    $this->session->data['success'] ="共".($i-2)."条数据上传成功";
                    $this->redirect($this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL'));
                }


            }
        }
    }
    public function add(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle("批量增加商品图片");
        $upload_inptu_file_name ='add_file';
        $accpet_file = array('xls','xlsx');
        $file_name = $_FILES[$upload_inptu_file_name]['name'];
        $file_type = substr($file_name,strrpos($file_name,'.')+1);
        if(!in_array($file_type,$accpet_file )){
            $this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else{
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
            if($_FILES[$upload_inptu_file_name]['tmp_name']){
                $this->do_product_photo($this->root_path);
                $file_content =$this->getexcelcontent($_FILES[$upload_inptu_file_name]['tmp_name']);
                $i=2;
                foreach($file_content as $info){
                    header("Content-type: text/html; charset=utf-8");
                    $sku =trim($info[0]);
                    $product_id =$this->get_pro_id($sku);
                    if($product_id){
                        //插入商品图片集
                        $pro_image =explode(';',trim($info[1]));
                        foreach($pro_image as $pro_gallery){
                            $gallery_path =$this->getPorImagePath(substr($pro_gallery,1));
                            $sort_order=(int)$info[2];
                            $res_product_image=$this->db->query("INSERT INTO ".DB_PREFIX."product_image set product_id='".$product_id."',image='".$this->db->escape($gallery_path)."',sort_order=".$sort_order);
                             if(!$res_product_image){
                                $this->error[$i] =$i;
                                echo "<p style='color:red'>第".$i."行插入product_image表失败，image='".$gallery_path."'</p>";
                            }
                        }
                    }else{
                        $this->error[$i] =$i;
                        echo "<p style='color:red'>第".$i."行sku不存在，请检查！</p>";
                    }
                    $i++;
                }
                $error_count =count($this->error);
                if($error_count>0){
                    $back_url =  $this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL');
                    echo "<p>共上传".($i-2)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                    $this->session->data['success'] ="共".($i-2)."条数据上传成功";
                    $this->redirect($this->url->link('data/pro_img_update', 'token=' . $this->session->data['token'], 'SSL'));
                }


            }
        }
    }

    public function updownload(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', '旧的图片路径');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '新的图片路径');
        $file_name ="商品图片批量更新";



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
    public function adddownload(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', '图片路径');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '排序');
        $file_name ="增加商品图片";



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
    public function get_pro_id($sku){
        $query =$this->db->query("select product_id from ".DB_PREFIX."product where model='".$this->db->escape(trim($sku))."' limit 1");
        if($query->row){
            return $query->row['product_id'];
        }
        else{
            return false;
        }
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
        $dir_path =$file_path.date("Ymd",time())."-product-update-photo/";
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