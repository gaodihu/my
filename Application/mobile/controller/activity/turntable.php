<?php
class ControllerActivityTurntable extends Controller {
    private $error = array();
    private $prize_name_id = 4;
    public function index()
    {
        $this->language->load('activity/turntable');
        $this->language->load('common/footer');
        $this->data['page_title'] = $this->language->get('page_title');
        $this->data['page_description'] = $this->language->get('page_description');
        $this->data['page_keyword'] = $this->language->get('page_keyword');
        $this->data['text_new_arrives'] = $this->language->get('text_menu_new_arrivals');
        $this->data['text_top_sellers'] = $this->language->get('text_menu_top_sellers');
        $this->data['text_deals'] = $this->language->get('text_menu_deals');
        $this->data['text_clearance'] = $this->language->get('text_menu_clearance');

        $this->data['text_sucess_01'] = $this->language->get('text_sucess_01');
        $this->data['text_sucess_02'] = $this->language->get('text_sucess_02');
        $this->data['text_sucess_03'] = $this->language->get('text_sucess_03');
        $this->data['text_sucess_04'] = $this->language->get('text_sucess_04');
        $this->data['text_05'] = $this->language->get('text_05');
        $this->data['text_06'] = $this->language->get('text_06');
        $this->data['text_07'] = $this->language->get('text_07');
        $this->data['text_08'] = $this->language->get('text_08');

        $this->data['text_conditions'] = $this->language->get('text_conditions');

        $this->data['text_rule_01'] = $this->language->get('text_rule_01');
        $this->data['text_rule_02'] = $this->language->get('text_rule_02');
        $this->data['text_rule_03'] = $this->language->get('text_rule_03');
        $this->data['text_rule_04'] = $this->language->get('text_rule_04');


        $this->data['text_mail_send_tip'] = $this->language->get('text_mail_send_tip');
        $this->data['no_cookie_tip'] = $this->language->get('no_cookie_tip');


        $this->data['text_awards_list'] = $this->language->get('text_awards_list');

        $lang_code = $this->session->data['language'];
        $lang_code = strtolower($lang_code);
        $this->data['lang_code'] = $lang_code;


        $this->load->model('activity/prize');
        //检查是否到达活动时间
        $this->data['show_erroe_data'] = false;
        $prize_name_id = $this->prize_name_id;
        $action_info = $this->model_activity_prize->get_action_info($prize_name_id);
        if (time() < strtotime($action_info['start_time']) || time() > strtotime($action_info['end_time'])) {
            $this->data['show_erroe_data'] = true;
        }
        //得到获奖人列表
        $this->data['prize_get_list'] = array();
        $prize_user_list = $this->model_activity_prize->getAllPrizeDetails($prize_name_id,20);

        foreach ($prize_user_list as $detail) {
            $detail['prize_name'] = $this->language->get('text_oc_prize_' . $detail['prize_id']);
            $detail['add_time'] = date("Y.m.d", strtotime($detail['add_time']));
            $this->data['prize_get_list'][] = sprintf($this->language->get('text_congrats'), $detail['nickname'], $detail['prize_name']);
        }
        $this->load->model('catalog/information');

        $this->data['informations'] = array();
        $information_group_name = array();
        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['information_group_id']) {
                $group_name = $this->model_catalog_information->getGroupName($result['information_group_id']);
                $information_group_name[$result['information_group_id']]['name'] = $group_name['name'];
                $information_group_name[$result['information_group_id']]['information'][] = array(
                    'title' => $result['title'],
                    'href' => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                    //'href'  =>$this->model_catalog_information->getInformationUrl($result['information_id'])
                );
            }
        }
        if (isset($_COOKIE['ttfirst'])) {
            $this->data['try'] = $ttfirst = $_COOKIE['ttfirst'];
        }else{
            $this->data['try'] = 0;
        }

        $this->data['informations'] =$information_group_name;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/activity/turntable.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/activity/turntable.tpl';
        } else {
            $this->template = 'default/template/activity/turntable.tpl';
        }
        $this->children = array(
            'common/footer',
        );
        $this->response->setOutput($this->render());
    }
    public function get() {
        unset($this->session->data['tt_prize']) ;
        $this->load->model('activity/prize');
        $this->load->model('checkout/order');
        $this->language->load('activity/turntable');

        $prize_name_id =$this->prize_name_id;

        $json =array();
        $json['error'] =0;


        //检查是否到达活动时间
        $action_info =$this->model_activity_prize->get_action_info($prize_name_id);

        if(time()<strtotime($action_info['start_time'])||time()>strtotime($action_info['end_time'])){
            $json['error'] =1;
            $json['message'] =$this->language->get('error_action_data');
            echo json_encode($json);
            die;
        }

        $ttfirst = $_COOKIE['ttfirst'];
        $iflogin = $this->customer->isLogged();

        if($ttfirst == 0) {
        }else {

            $prize_token = $this->request->post['order_number'];
            require_once(DIR_SYSTEM . 'library/validate.php');

            //对输入的订单号进行处理
            if (!$prize_token) {
                $json['error'] = 1;
                $json['message'] = $this->language->get('error_order_number');
                echo json_encode($json);
                die;
            }
            $validate = new Validate();
            $prize_token = $validate->eInput($prize_token);

            $parent_preg = "/^[\d]*$/";
            if (!$validate->volidatFormat($parent_preg, $prize_token)) {
                $json['error'] = 2;
                $json['message'] = $this->language->get('error_order_number');
                echo json_encode($json);
                die;

            }
            //查询订单号是否正确
            $order_info = $this->model_checkout_order->getOrderByNumber($prize_token);
            if (!$order_info || ($order_info['order_status_id'] != 2 && $order_info['order_status_id'] != 5)) {
                $json['error'] = 2;
                $json['message'] = $this->language->get('error_order_number');
                echo json_encode($json);
                die;
            }
            $nickname =$order_info['shipping_firstname'];
            $order_created_time =$order_info['date_added'];
            $order_total =$order_info['base_grand_total'];

            //查看该订单是否以抽奖，一个订单号只能抽奖一次
            $prize_detail =$this->model_activity_prize->get_prize_detail($prize_name_id,$prize_token);
            if($prize_detail){
                $json['error'] =2;
                $json['message'] =$this->language->get('error_twe_order_number');
                echo json_encode($json);
                die;
            }
        }




        if(!$json['error']){
            //得到奖品的设置
            $prize_set =$this->model_activity_prize->get_prize_set($prize_name_id);
            foreach($prize_set as $item){
                //根据数据库得奖记录，查看某项奖项是否还有剩余奖品，如果已送完，去除该奖项
                $alrealy_prize_total =$this->model_activity_prize->get_prize_num($prize_name_id,$item['prize_id']);
                if($alrealy_prize_total<$item['prize_num']){
                    $prize_arr[$item['prize_id']]=array(
                        'id'=>   $item['prize_id'],
                        'prize'=>$item['prize_name'],
                        'v'=>$item['prize_chance']
                    );
                }
            }

            foreach ($prize_arr as $key => $val) {
                $arr[$val['id']] = $val['v'];
            }
            ksort($arr);
            $rid = $this->getRand($arr); //根据概率获取奖项id
            //把抽奖数据放入数据库
            $insert_data = array(
                'prize_name_id' => $prize_name_id,
                'nickname'=>$nickname,
                'prize_token'=>$prize_token,
                'prize_id'=>$rid,
                'order_created_time'=>$order_created_time
            );


            //发送客户订单邮件

            if($this->customer->isLogged()){
                if($this->customer->isLogged()) {
                    $email = $this->customer->getEmail();

                    $nickname = $this->customer->getNickName();
                }
                $insert_data['email'] = $email;
                $detail_id  = $this->model_activity_prize->add_prize_detail($insert_data);
                $this->session->data['detail_id'] = $detail_id;
                $this->SendPrizeEmail($email,$insert_data['prize_id'],$nickname);
            }else{
                $this->session->data['my_prize'] = $insert_data;
            }

            //输出奖品信息的json数据到前台
            $json['prize_id'] =$prize_arr[$rid]['id'];
            //$json['prize_name'] =$this->language->get('text_oc_prize_'.$prize_arr[$rid]['id']);
            $input_email = 0;
            if($this->customer->isLogged()){
                $input_email = 1;
            }

            if($order_info){
                $order_email = $order_info["email"];

            }else{
                $order_email = "";
            }

            $json['prize_name'] = $prize_arr[$rid]['prize'];
            $json['login'] = $input_email;
            $json['email'] = $order_email;
            setcookie("ttfirst",1,time()+60*24*60*60,"/",".myled.com");

        }
        echo json_encode($json);
    }

    /**
     * @todo 发送邮件，并记录邮件
     */
    public function send()
    {
        $json = array();
        if (!isset($this->request->post['email'])) {
            $json['error'] = 1;
            echo json_encode($json);
            die;
        }
        $tt_email = $this->request->post['email'];


        $this->session->data['tt_email'] = $tt_email;
        if ($this->customer->isLogged()) {
            $nickname = $this->customer->getNickName();
        } else {
            $nickname = substr($tt_email, 0, strpos($tt_email, '@'));
        }


        $this->load->model('activity/prize');

        $insert_data = $this->session->data['my_prize'];
        if ($insert_data) {
            $detail_id = $this->model_activity_prize->add_prize_detail($insert_data);
            $this->session->data['detail_id'] = $detail_id;

            $this->model_activity_prize->update_prize_detail_email_nickname($this->session->data['detail_id'], $tt_email, $nickname);

            $this->SendPrizeEmail($tt_email, $insert_data['prize_id'], $nickname);
            unset($this->session->data['my_prize']);
        }
        $json['error'] = 0;
        echo  json_encode($json);

    }


    public function flushList(){
        $this->language->load('activity/turntable');
        $this->load->model('activity/prize');
        //得到获奖人列表
        $prize_name_id = $this->prize_name_id;
        $this->data['prize_get_list'] =array();
        $prize_user_list =$this->model_activity_prize->getAllPrizeDetails($prize_name_id,20);


        foreach($prize_user_list as $detail){
            $detail['prize_name'] =$this->language->get('text_oc_prize_'.$detail['prize_id']);
            $detail['add_time'] =date("Y.m.d",strtotime($detail['add_time']));
            $this->data['prize_get_list'][] =sprintf($this->language->get('text_congrats'),$detail['nickname'],$detail['prize_name']);
        }
        echo json_encode($this->data['prize_get_list']);
    }

    //计算概率
    public function getRand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {

            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);

        return $result;
    }

    public function SendPrizeEmail($email,$prize_id,$nickname){
        $this->load->model('tool/email');
        $this->language->load('activity/turntable');
        $prize_name = $prize_id;
        $data = "";
        $coupon = "";
        switch($prize_id){
            case  1:
                $coupon = "anni125";
                $temple = 2;
                break;
            case  2:
                $coupon = "anni512";
                $temple = 2;
                break;
            case  3:
                $coupon = "anni362";
                $temple = 2;
                break;
            case  4:
                $coupon = "anni254";
                $temple = 2;
                break;
            case  5:
            case  6:
            case  7:
            case  8:
                $coupon = "";

                $temple = 1;
                break;
        }
        $data = $this->language->get('text_oc_prize_'.$prize_id);

        $email_data =array();
        $email_data['store_id'] =$this->config->get('config_store_id');
        $email_data['email_from'] ='MyLED ';
        $email_data['email_to'] =$email;

        $email_data['email_subject'] =$this->language->get('text_subject');

        $lang_code = $this->session->data['language'];
        $lang_code = strtolower($lang_code);

        $path = DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/turntable/email_'.$temple.'/email_'.$lang_code.'.html';


        $content = file_get_contents($path);

        $content = str_replace('{{$data}}',$data,$content);

        $content = str_replace('{{$nickname}}',$nickname,$content);

        $content = str_replace('{{$coupon}}',$coupon,$content);

        $email_data['email_content'] =addslashes($content);
        $email_data['is_html'] =1;
        $email_data['attachments'] ='';
        $this->model_tool_email->addEmailList($email_data);
    }
}
