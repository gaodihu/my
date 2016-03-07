<?php 
class ControllerAdditionalExclusivePrice extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('专属商品'));
		$this->load->model('additional/exclusive');
		$this->getList();
	} 

    public function add(){
       $this->document->setTitle($this->language->get('插入新商品'));

		$this->load->model('additional/exclusive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_exclusive->addExclusiveProduct($this->request->post);

			$this->session->data['success'] = "插入成功！";

			$this->redirect($this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }

    public function edit(){
        $this->document->setTitle($this->language->get('编辑渠道商品'));

		$this->load->model('additional/exclusive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_additional_exclusive->editExclusiveProduct($this->request->get['id'],$this->request->post);

			$this->session->data['success'] = "编辑成功！";

			$this->redirect($this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }
	public function delete() { 
		$this->document->setTitle($this->language->get('渠道商品'));

		$this->load->model('additional/exclusive');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $new_p_id) {
				$this->model_additional_exclusive->deleteExclusiveProduct($new_p_id);
			}

			$this->session->data['success'] = "删除成功";

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        
        if (isset($this->request->get['filter_product_id'])) {
				$filter['filter_product_id'] = $this->request->get['filter_product_id'];
		} else {
				$filter['filter_product_id']= null;
		}
		if (isset($this->request->get['filter_from_url'])) {
				$filter['filter_from_url'] = $this->request->get['filter_from_url'];
		} else {
				$filter['filter_from_url']= null;
		}
        if (isset($this->request->get['filter_start_time'])) {
				$filter['filter_start_time'] = $this->request->get['filter_start_time'];
		} else {
				$filter['filter_start_time']= null;
		}
		if (isset($this->request->get['filter_end_time'])) {
				$filter['filter_end_time'] = $this->request->get['filter_end_time'];
		} else {
				$filter['filter_end_time']= null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pep_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        foreach($filter as $key=>$filter_list){
			if($filter_list){
				$url .= '&'.$key.'=' . $filter_list;
			}
			
		}
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('渠道商品'),
			'href'      => $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
        $this->data['upload'] = $this->url->link('additional/exclusive_price/upload', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['download'] = $this->url->link('additional/exclusive_price/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_url'] = $this->url->link('additional/exclusive_price/add', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['delete'] = $this->url->link('additional/exclusive_price/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('additional/exclusive_price/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['exclusive_prices'] = array();
        $this->data['filter'] = $filter;
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
        $data =array_merge($data,$filter);
		$exclusive_price_url_total = $this->model_additional_exclusive->getTotalExclusiveProducts();
		$results = $this->model_additional_exclusive->getExclusiveProducts($data);

		foreach ($results as $result) {
			$action = array();
                $action[] = array(
                    'text' => $this->language->get('编辑'),
                    'href' => $this->url->link('additional/exclusive_price/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['pep_id'], 'SSL')
                );
             $from_url_str='';
			 $from_urls =   explode(',',$result['from_url']);
             foreach($from_urls as $from_url_id){
                    $url_link_info =$this->model_additional_exclusive->getExclusiveUrl($from_url_id);
                    $from_url_str .=$url_link_info['s_id']."(".$url_link_info['url']."),";
             }
			$this->data['exclusive_prices'][] = array(
				'id'  => $result['pep_id'],
                'product_id'  => $result['product_id'],
                'price'     => $result['price'],
                'from_url'     => rtrim($from_url_str,',') ,
                'limit_number' =>intval($result['limit_number']),
                'start_time'     => $result['start_time'],
                'end_time'     => $result['end_time'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['new_pro_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	

         if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
        else{
            $this->data['error_warning'] ='';
        }
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        
        $this->data['sort_id'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . '&sort=pep_id' . $url, 'SSL');
		$this->data['sort_product_id'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . '&sort=product_id' . $url, 'SSL');
        $this->data['sort_limit'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . '&sort=limit_number' . $url, 'SSL');
        $this->data['sort_start_time'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . '&sort=start_time' . $url, 'SSL');
        $this->data['sort_end_time'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . '&sort=sort_end_time' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $exclusive_price_url_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'additional/exclusive_product.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		
        
        if (!isset($this->request->get['id'])) {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('新增渠道商品'),
                'href'      => $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );

			$this->data['action'] = $this->url->link('additional/exclusive_price/add', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['action_text'] ='新增渠道商品';
		} else {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('编辑渠道商品'),
                'href'      => $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
		    );
            $this->data['action_text'] ='编辑渠道商品';
			$this->data['action'] = $this->url->link('additional/exclusive_price/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}
		$this->data['cancel'] = $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id'])) {
			$exclusive_product_info = $this->model_additional_exclusive->getExclusiveProduct($this->request->get['id']);
		}
        else{
            $exclusive_product_info =array();
        }
        $this->data['exclusive_product_info'] =$exclusive_product_info;
        if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'additional/exclusive_product_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        $this->document->setTitle($this->language->get('上传渠道商品表格')); 
        $this->data['action'] =$this->url->link('additional/exclusive_price/uploadAct', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] =$this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL');
        $this->template = 'additional/exclusive_product_upload.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
    }
    public function uploadAct(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('上传渠道商品表格')); 
        $upload_inptu_file_name ='uplaod_file';
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES[$upload_inptu_file_name]['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('additional/exclusive_price/upload', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
            
			$file_path ="/home/www/new_myled.com/upload/exclusive/";
            //$file_path ="D://XAMPP/htdocs/new_myled/upload/exclusive/";
 
			$file_name = date('Y-m-d_H_i_s',time())."_exclusive_product.".$file_type;
            
			if($_FILES[$upload_inptu_file_name]['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES[$upload_inptu_file_name]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
                    header("Content-type: text/html; charset=utf-8");
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    $i=1;
                    $this->load->model('additional/exclusive');
                    foreach($file_content as $info){
                        $product_id =$this->get_product_id(trim($info[0]));
                        if(!$product_id){
                            echo "<p>".$info[0]." 商品不存在!<br></p>";
                            $this->error[$i] =$i;
                        }
                        else{
                            $data =array();
                            $data['product_id'] = $product_id;
                            $data['price'] = $info[1];
                            $data['from_url'] = $info[2];
                            $data['limit_number'] = $info[3];
                            $data['start_time'] = $info[4];
                            $data['end_time'] = $info[5];
                            $this->model_additional_exclusive->addExclusiveProduct($data);
                        }
                        
                        $i++;
                    }
                     $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".($i-1)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                        $this->session->data['success'] ="共".($i-1)."条数据上传成功";
                        $this->redirect($this->url->link('additional/exclusive_price', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
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
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'price');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '渠道url(填写渠道ID，多个用逗号隔开)');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '限制数量(无，留空或写0)');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '开始时间');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '结束时间');
        $file_name ="渠道商品模板";
       
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
                  if($col==4||$col==5){
                       $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                       $content =gmdate("Y-m-d H:i:s", $content);
                 }
                $excelData[$row][] = $content; 
            }  
        }  
        return $excelData;  
    }
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'additional/exclusive_price')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

   protected function validateForm(){
        if (!$this->user->hasPermission('modify', 'additional/exclusive_price')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
   }
    protected function get_product_id($sku){
        $query =$this->db->query("select product_id from ".DB_PREFIX."product where model ='".trim($sku)."'");
        if($query->num_rows){
            return $query->row['product_id'];
        }
        else{
            return false;
        }
        
    }
}
?>