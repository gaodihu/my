<?php
class ControllerReportSaleOrderData extends Controller { 
	public function index() {  
		$this->document->setTitle("销售统计数据");

		

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "销售统计数据",
			'href'      => $this->url->link('report/sale_order_data', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/sale');

		$this->data['orders'] = array();
         //使用美国时间

        //北京时间12点为分割线
        
        if(date('Y-m-d H:i:s',time())>=date('Y-m-d')." 12:00:00"){
             $time_start =time();
             $time_end =time()+24*60*60;
             $days =date("t", mktime(0, 0, 0, date('m',time()), 1, date('Y',time())));
             $today_start =date('Y-m-d 12:00:00',$time_start);
             $today_end =date('Y-m-d 11:59:00',$time_end);
             $yes_start =date('Y-m-d 12:00:00',$time_start-24*3600);
             $yes_end =date('Y-m-d 11:59:00',$time_end-24*3600);
             $week_start =date('Y-m-d 12:00:00',$time_start-7*24*3600);
             $week_end =date('Y-m-d 11:59:00',$time_end-24*3600);
           
        }
        else{
             $time_start =time()-12*60*60;
             $time_end =time();
             $days =date("t", mktime(0, 0, 0, date('m',time()), 1, date('Y',time())));
             $today_start =date('Y-m-d 12:00:00',$time_start);
             $today_end =date('Y-m-d 11:59:00',$time_end);
             $yes_start =date('Y-m-d 12:00:00',$time_start-24*3600);
             $yes_end =date('Y-m-d 11:59:00',$time_end-24*3600);
             $week_start =date('Y-m-d 12:00:00',$time_start-7*24*3600);
             $week_end =date('Y-m-d 11:59:00',$time_end-24*3600);
            
        }  
         $month_start =date('Y-m-01 00:00:00',time());
         $month_end =date('Y-m-'.$days.' 23:59:59',time());
         //上月的天数
         $last_month_start=date('Y-m-01 00:00:00',strtotime(date('Y',time()).'-'.(date('m',time())-1).'-01'));
         $last_month_end=date('Y-m-d 23:59:59',strtotime("$last_month_start +1 month -1 day"));

        $today_sale=$this->model_report_sale->getOrdersData($today_start,$today_end);
        $this->data['today_sale'] =$today_sale;
        $yestoday_sale=$this->model_report_sale->getOrdersData($yes_start,$yes_end);
        $this->data['yestoday_sale'] =$yestoday_sale;
        $weeky_sale = $this->model_report_sale->getOrdersData($week_start,$week_end); 
        $this->data['weeky_sale'] =$weeky_sale;
        $month_sale = $this->model_report_sale->getOrdersData($month_start,$month_end);
        $this->data['month_sale'] =$month_sale;
        $last_month_sale = $this->model_report_sale->getOrdersData($last_month_start,$last_month_end);
        $this->data['last_month_sale'] =$last_month_sale;
		
        //计算毛利
        //订单毛利率 = (销售额-运费-订单产品原价/2) / 销售额 * 100% 
        //产品毛利率 = (销售额-运费-订单产品原价/2) / (销售额-运费) * 100% 
		$today_sub_original =$this->model_report_sale->getOrderSubOriginal($today_start,$today_end);
        $today_maoli =round(($today_sale['total']-$today_sale['total_shipping_cost']-$today_sub_original/2)/$today_sale['total'],4)*100;
        $today_product_maoli =round(($today_sale['total']-$today_sale['total_shipping_cost']-$today_sub_original/2)/($today_sale['total']-$today_sale['total_shipping_cost']),4)*100;
        $yestoday_sub_original =$this->model_report_sale->getOrderSubOriginal($yes_start,$yes_end);
        $yestoday_maoli =round(($yestoday_sale['total']-$yestoday_sale['total_shipping_cost']-$yestoday_sub_original/2)/$yestoday_sale['total'],4)*100;
        $yestoday_product_maoli =round(($yestoday_sale['total']-$yestoday_sale['total_shipping_cost']-$yestoday_sub_original/2)/($yestoday_sale['total']-$yestoday_sale['total_shipping_cost']),4)*100;
        $weeky_sub_original =$this->model_report_sale->getOrderSubOriginal($week_start,$week_end);
        $weeky_maoli =round(($weeky_sale['total']-$weeky_sale['total_shipping_cost']-$weeky_sub_original/2)/$weeky_sale['total'],4)*100;
        $weeky_product_maoli =round(($weeky_sale['total']-$weeky_sale['total_shipping_cost']-$weeky_sub_original/2)/($weeky_sale['total']-$weeky_sale['total_shipping_cost']),4)*100;
        $month_sub_original =$this->model_report_sale->getOrderSubOriginal($month_start,$month_end);
        $month_maoli =round(($month_sale['total']-$month_sale['total_shipping_cost']-$month_sub_original/2)/$month_sale['total'],4)*100;
        $month_product_maoli =round(($month_sale['total']-$month_sale['total_shipping_cost']-$month_sub_original/2)/($month_sale['total']-$month_sale['total_shipping_cost']),4)*100;

        $last_month_sub_original =$this->model_report_sale->getOrderSubOriginal($last_month_start,$last_month_end);
        $last_month_maoli =round(($last_month_sale['total']-$last_month_sale['total_shipping_cost']-$last_month_sub_original/2)/$last_month_sale['total'],4)*100;
        $last_month_product_maoli =round(($last_month_sale['total']-$last_month_sale['total_shipping_cost']-$last_month_sub_original/2)/($last_month_sale['total']-$last_month_sale['total_shipping_cost']),4)*100;
        $this->data['today_sub_original'] =$today_sub_original;
        $this->data['yestoday_sub_original'] =$yestoday_sub_original;
        $this->data['weeky_sub_original'] =$weeky_sub_original;
        $this->data['month_sub_original'] =$month_sub_original;
        $this->data['last_month_sub_original'] =$last_month_sub_original;
		
        $this->data['today_maoli'] =$today_maoli;
        $this->data['today_product_maoli'] =$today_product_maoli;
        $this->data['yestoday_maoli'] =$yestoday_maoli;
        $this->data['yestoday_product_maoli'] =$yestoday_product_maoli;
        $this->data['weeky_maoli'] =$weeky_maoli;
        $this->data['weeky_product_maoli'] =$weeky_product_maoli;
        $this->data['month_maoli'] =$month_maoli;
        $this->data['month_product_maoli'] =$month_product_maoli;
        $this->data['last_month_maoli'] =$last_month_maoli;
        $this->data['last_month_product_maoli'] =$last_month_product_maoli;

		$this->template = 'report/sale_order_data.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>