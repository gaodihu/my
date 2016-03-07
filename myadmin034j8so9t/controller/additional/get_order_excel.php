<?php 
class ControllerAdditionalGetOrderExcel extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('下载订单表格'));
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('下载订单表格'),
			'href'      => $this->url->link('additional/get_order_excel', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['token'] = $this->session->data['token'] ;	
	    $this->data['action_sales_order_0'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=sales_order&time=00", 'SSL');
        $this->data['action_purchase_order_0'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=purchase_order&time=00", 'SSL');	
        $this->data['action_detail_sales_0'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=detail_sales_order&time=00", 'SSL');
        $this->data['action_sales_order_11'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=sales_order&time=11", 'SSL');
        $this->data['action_purchase_order_11'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=purchase_order&time=11", 'SSL');	
        $this->data['action_detail_sales_11'] = $this->url->link('additional/get_order_excel/download', 'token=' . $this->session->data['token']."&file=detail_sales_order&time=11", 'SSL');
         if(isset($this->session->data['warning'])){
            $this->data['error_warning'] =$this->session->data['warning'];
            unset($this->session->data['warning']);
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
		$this->template = 'additional/get_order_excel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

    public function download(){
        $file_name =isset($this->request->get['file'])?trim($this->request->get['file']):'';
        if($file_name&&$this->validateDownload()){
            $data=date("Y-m-d",time());
            $time =$this->request->get['time'];
            $file_name =$file_name.$data."-".$time."-00.csv";
            $file_path ="/data/logs/test_order_export/".$file_name;
            if(!file_exists($file_path)){
                $this->session->data['warning'] ="该选择下对应文件不存在！";
                $this->redirect($this->url->link('additional/get_order_excel', 'token=' . $this->session->data['token'], 'SSL'));
            }
            $fp =fopen($file_path,'r');
            // We'll be outputting a file
            header('Accept-Ranges: bytes');
            header('Accept-Length: ' . filesize($filename));
            // It will be called
            header('Content-Transfer-Encoding: binary');
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $file_name);
            header('Content-Type: application/octet-stream; name=' . $file_name);
            // The source is in filename
            echo fread($fp, filesize($file_path));
            fclose($fp);
            exit;
        }
        else{
            $this->redirect($this->url->link('additional/get_order_excel', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    protected function validateDownload() {
		if (!$this->user->hasPermission('modify', 'additional/get_order_excel')) {
			$this->error['warning'] = "您没有操作权限！";
            $this->session->data['warning'] ="您没有操作权限！";
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>