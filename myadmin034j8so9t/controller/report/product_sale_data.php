<?php
class ControllerReportProductSaleData extends Controller { 
	public function index() {  
		$this->document->setTitle('商品销售统计');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
            $filter_date_start = date('Y-m-d 00:00:00');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d H:i:s');
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
        $this->data['export'] = $this->url->link('report/product_sale_data/export', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->load->model('report/sale');

		$this->data['product_sale_info'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
        $total_product_sale =$this->model_report_sale->getTotalProductSale($data);
        $product_sale_info =$this->model_report_sale->getProductSaleInfo($data);

        $this->data['product_sale_info'] =$product_sale_info;
        //var_dump($product_sale_info);exit;

   

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $total_product_sale;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_sale_data', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		

		$this->template = 'report/product_sale_data.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function export(){
        $this->load->model('report/sale');
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
            $filter_date_start = date('Y-m-d 00:00:00');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d H:i:s');
		}
        $export_data = array(
            'filter_date_start'	     => $filter_date_start, 
            'filter_date_end'	     => $filter_date_end
        );
        $export_product_sale_info =$this->model_report_sale->getProductSaleInfo($export_data);
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'product_id');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'sku');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'www销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'www订单频次');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'de销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'de订单频次');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'es销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'es订单频次');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'fr销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'fr订单频次');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'it销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'it订单频次');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'pt销售总量');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'pt订单频次');
        $i=2;
        foreach($export_product_sale_info as $info){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $info['product_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $info['model']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, isset($info['0']['order_product_count'])?$info['0']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, isset($info['0']['order_count'])?$info['0']['order_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, isset($info['52']['order_product_count'])?$info['52']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, isset($info['52']['order_count'])?$info['52']['order_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, isset($info['53']['order_product_count'])?$info['53']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, isset($info['53']['order_count'])?$info['53']['order_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, isset($info['54']['order_product_count'])?$info['54']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, isset($info['54']['order_count'])?$info['54']['order_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, isset($info['55']['order_product_count'])?$info['55']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, isset($info['55']['order_count'])?$info['55']['order_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, isset($info['56']['order_product_count'])?$info['56']['order_product_count']:0);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, isset($info['56']['order_count'])?$info['56']['order_count']:0);
            $i++;
        }
        $file_name ="产品销售统计";
       
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
}
?>