<?php 
class ControllerFestivalEaster extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('活动奖项'));
		$this->load->model('festival/easter');
		$this->getList();
	} 
    
    public function send(){
        $this->load->model('festival/easter');
        $id =$this->request->get['id'];
        $is_send =$this->request->get['send'];
        $this->model_festival_easter->updatePrizeDetailSend($id,$is_send);
        $this->redirect($this->url->link('festival/easter', 'token=' . $this->session->data['token'], 'SSL'));
    }

	protected function getList() {

		$this->load->model('festival/lottery');

		if (isset($this->request->get['filter_prize_name_id'])) {
			$filter['filter_prize_name_id'] = $this->request->get['filter_prize_name_id'];
		} else {
			$filter['filter_prize_name_id']= null;
		}



		if (isset($this->request->get['filter_prize_token'])) {
				$filter['filter_prize_token'] = $this->request->get['filter_prize_token'];
		} else {
				$filter['filter_prize_token']= null;
		}
		if (isset($this->request->get['filter_nickname'])) {
				$filter['filter_nickname'] = $this->request->get['filter_nickname'];
		} else {
				$filter['filter_nickname']= null;
		}
		if (isset($this->request->get['filter_is_send'])) {
			$filter['filter_is_send'] = $this->request->get['filter_is_send'];
		} else {
			$filter['filter_is_send']= null;
		}

		if (isset($this->request->get['filter_prize_id'])) {
			$filter['filter_prize_id'] = $this->request->get['filter_prize_id'];
		} else {
			$filter['filter_prize_id']= null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter['filter_email'] = $this->request->get['filter_email'];
		} else {
			$filter['filter_email']= null;
		}
       
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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
			'text'      => $this->language->get('奖品详情'),
			'href'      => $this->url->link('festival/easter', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['to_excel'] = $this->url->link('festival/easter/to_excel', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('festival/easter/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['other_products'] = array();
		$this->data['filter'] = $filter;
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$data =array_merge($data,$filter);
		$total_prize_detail = $this->model_festival_easter->getTotalPrizeDetail($data);

		$results = $this->model_festival_easter->getPrizeDList($data);
		foreach ($results as $result) {
			$action = array();
            if($result['is_send']){
                $action[] = array(
                    'text' => $this->language->get('取消发送'),
                    'href' => $this->url->link('festival/easter/send', 'token=' . $this->session->data['token'] . '&id=' . $result['id']."&send=0" . $url, 'SSL')
                );
            }else{
                $action[] = array(
                    'text' => $this->language->get('发送'),
                    'href' => $this->url->link('festival/easter/send', 'token=' . $this->session->data['token'] . '&id=' . $result['id']."&send=1" . $url, 'SSL')
                );
            }
            /*
            $action[] = array(
                'text' => $this->language->get('删除'),
                'href' => $this->url->link('festival/easter/delete', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
            );
            */
			$prize_row = $this->model_festival_lottery->getPrizeName($result['prize_name_id']);
			$this->data['prize_detai_info'][] = array(
				'id'  => $result['id'],
				'prize_name' =>$prize_row['name'],
                'prize_token'     => $result['prize_token'],
				'nickname'       => $result['nickname']?$result['nickname']:'N/A',
				'email'          => $result['email'],
				'prize_id'     => $result['prize_id'],
				'is_send'     => $result['is_send'],
				'add_time'     => $result['add_time'],
                'order_created_time'     => $result['order_created_time']?$result['order_created_time']:'N/A',
				'selected'   => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
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

		$this->data['sort_id'] = $this->url->link('festival/easter', 'token=' . $this->session->data['token'] . '&sort=id' . $url, 'SSL');
		
		$this->data['sort_prize_id'] = $this->url->link('festival/easter', 'token=' . $this->session->data['token'] . '&sort=prize_id' . $url, 'SSL');
        $this->data['sort_is_send'] = $this->url->link('festival/easter', 'token=' . $this->session->data['token'] . '&sort=is_send' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		$prize_list = $this->model_festival_lottery->getPrizeNameList();
		$this->data['prize_list'] = $prize_list;

		$pagination = new Pagination();
		$pagination->total = $total_prize_detail;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('festival/easter', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'festival/easter_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

    public function to_excel(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        $this->load->model('festival/easter');
        $results = $this->model_festival_easter->getPrizeDList(1,$data=array());

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '订单号');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', '昵称');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '奖项等级');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '是否发送');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '中奖时间');
        $i=2;
        foreach($results as $res){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $res['prize_token']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $res['nickname']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $res['prize_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $res['is_send']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $res['add_time']);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle("复活节");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=复活节砸蛋中奖名单.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save("D://gogo_beian.xlsx");
		//$objWriter->save('/home/www/www.myled.com/script/8月份销售单.xlsx');
        $objWriter->save('php://output');
        exit;
    }

}
?>