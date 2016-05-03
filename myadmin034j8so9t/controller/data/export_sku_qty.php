<?php 
class ControllerDataExportSkuQty extends Controller {
	private $error = array(); 
	public function index() {
		
		$this->document->setTitle("导出sku销售数量信息");
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "导出sku销售数量信息",
			'href'      => $this->url->link('data/export_sku_qty', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);
		$this->data['action'] = $this->url->link('data/export_sku_qty/download', 'token=' . $this->session->data['token'], 'SSL');

		
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
	    $this->template = 'data/export_sku_qty.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function download(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $start_time =isset($this->request->post['start_time'])?trim($this->request->post['start_time']):'';
        $end =isset($this->request->post['end_time'])?trim($this->request->post['end_time']):'';
        if(!$start_time||!$end){
            $this->error['warning'] ="请正确填写日期信息";
        }else {
            $start_time = date('Y-m-d 00:00:00', strtotime($start_time));
            $end = date('Y-m-d 24:00:00', strtotime($end));
            require_once(DIR_SYSTEM . "lib/PHPExcel/PHPExcel.php");
            require_once(DIR_SYSTEM . 'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once(DIR_SYSTEM . 'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SKU');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', '成交数量');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', '成交次数');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'product_code');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'supplier_code');
            $i = 2;
            $product_datas = $this->get_sku_sale_data($start_time, $end);
            if ($product_datas) {
                foreach ($product_datas as $sku => $item) {
                    $pro_info = $this->get_product_info($sku);
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['model']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['sale_qty']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['sale_cishu']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $pro_info['product_code']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $pro_info['supplier_code']);

                    $i++;
                }
            }
            $file_name = "sku销售数量信息";
            $objPHPExcel->getActiveSheet()->setTitle($file_name);
            $objPHPExcel->setActiveSheetIndex(0);
            //Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=" . $file_name . ".xlsx");
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;

        }
    }
    public function get_sku_sale_data($start,$end){
        $data =array();
        //得到所有完成的订单
        $sql_order ="select order_id from oc_order
    where order_status_id in (2,5) and date_added>='".$start."' and date_added<='".$end."' ";
        $query_order =$this->db->query($sql_order);

        foreach($query_order->rows as $row_order){
            //得到订单下的商品
            $sql_order_poroduct ="select model,quantity,total,base_price,original_price from oc_order_product where order_id=".$row_order['order_id'];
            $query_pro =$this->db->query($sql_order_poroduct);
            foreach($query_pro->rows as $row_pro){
                $data[$row_pro['model']]['model'] =$row_pro['model'];
                $data[$row_pro['model']]['sale_qty'] +=$row_pro['quantity'];
                $data[$row_pro['model']]['sale_cishu'] +=1;
                $data[$row_pro['model']]['sale_total'] +=$row_pro['total'];
            }
        }
        return $data;
    }

    public function get_product_info($sku){
        $sql ="select product_code,supplier_code from oc_product where model='".$sku."' limit 1";
        $query =$this->db->query($sql);
        return $query->row;

    }
}
?>