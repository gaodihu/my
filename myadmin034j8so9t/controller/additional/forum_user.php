<?php 
class ControllerAdditionalForumUser extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('用户发表'));
		$this->load->model('additional/forum');
		$this->getList();
	} 

    public function edit(){
        $this->document->setTitle($this->language->get('编辑用户发表信息'));

		$this->load->model('additional/forum');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') &&$image_path =$this->validateForm()) {
            $post_info =$this->model_additional_forum->getForumUserPost($this->request->get['id']);
            $data =$this->request->post;
            if($image_path=='exits'){
                $data['image_path'] ='';
            }
            else{
                $data['image_path'] =$image_path;
            }
            
			$this->model_additional_forum->editForumUserPost($this->request->get['id'],$data);
            //通过时发送邮件,如果有积分填写,赠送积分
            if($post_info['status']!=2&&$this->request->post['status']==2){
                $this->sendEmail($this->request->get['id'],2);
            }
            //通过时发送邮件,如果有积分填写,赠送积分
            if($post_info['status']!=4&&$this->request->post['status']==4){
                $this->sendEmail($this->request->get['id'],4);
                if($this->request->post['points']){
                    $this->sendPoints($post_info['user_id'],$this->request->post['points']);
                }
            }
			$this->session->data['success'] = "编辑成功！";

			$this->redirect($this->url->link('additional/forum_user', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
    }
	public function delete() { 
		$this->document->setTitle($this->language->get('用户发表'));

		$this->load->model('additional/forum');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $new_p_id) {
				$this->model_additional_forum->deleteForumUserPost($new_p_id);
			}

			$this->session->data['success'] = "删除成功";

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('additional/forum_user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
        if (isset($this->request->get['filter_fourm_ga_id'])) {
				$filter['filter_fourm_ga_id'] = $this->request->get['filter_fourm_ga_id'];
		} else {
				$filter['filter_fourm_ga_id']= null;
		}
		if (isset($this->request->get['filter_user_email'])) {
                $this->load->model('sale/customer');
                $user_info =$this->model_sale_customer->getCustomerByEmail($this->request->get['filter_user_email']);
				$filter['filter_user_email'] = $this->request->get['filter_user_email'];
                $filter['filter_user_id'] = $user_info['customer_id'];
		} else {
				$filter['filter_user_email']= null;
		}
        if (isset($this->request->get['filter_status'])) {
				$filter['filter_status'] = $this->request->get['filter_status'];
		} else {
				$filter['filter_status']= null;
		}
		if (isset($this->request->get['filter_email_send'])) {
				$filter['filter_email_send'] = $this->request->get['filter_email_send'];
		} else {
				$filter['filter_email_send']= null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
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
			'text'      => $this->language->get('用户发表信息'),
			'href'      => $this->url->link('additional/forum_user', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

        $this->data['delete'] = $this->url->link('additional/forum_user/delete', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['form_pro_list'] = $this->url->link('additional/forum', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('additional/forum_user/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['forum_user_list'] = array();
        $this->data['filter'] = $filter;
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
        $data =array_merge($data,$filter);
		$forum_user_total = $this->model_additional_forum->getTotalForumUserList();
		$results = $this->model_additional_forum->getForumUserList($data);

		foreach ($results as $result) {
			$action = array();
                $action[] = array(
                    'text' => $this->language->get('编辑'),
                    'href' => $this->url->link('additional/forum_user/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['forum_user_id'], 'SSL')
                );
            $this->load->model('sale/customer');
            $user_info =$this->model_sale_customer->getCustomer($result['user_id']);
            $result['user_email'] =$user_info['email'];
			$result['selected'] =isset($this->request->post['selected']) && in_array($result['new_pro_id'], $this->request->post['selected']);
            $result['action'] =$action;
			$this->data['forum_user_list'][] = $result;
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
		$pagination = new Pagination();
		$pagination->total = $forum_user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('additional/forum_user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->template = 'additional/forum_post_list.tpl';
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
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('编辑用户上传信息'),
            'href'      => $this->url->link('additional/forum_user', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
		$this->data['action'] = $this->url->link('additional/forum_user/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		$this->data['cancel'] = $this->url->link('additional/forum_user', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id'])) {
			$forum_user_post_info = $this->model_additional_forum->getForumUserPost($this->request->get['id']);
            $this->load->model('sale/customer');
            $user_info =$this->model_sale_customer->getCustomer($forum_user_post_info['user_id']);
            $this->data['user_info'] =$user_info;
		}
        else{
            $forum_user_post_info =array();
            $this->data['user_info'] =array();
        }
        $this->data['status'] =array('1'=>'Waiting','2'=>'Approved','3'=>'Remove','4'=>'Paid');
        $this->data['forum_user_post_info'] =$forum_user_post_info;
        if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'additional/forum_post_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'additional/forum_user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

   protected function validateForm(){
        if (!$this->user->hasPermission('modify', 'additional/forum_user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        $new_iamge_path='exits';
       if($_FILES['ga_click_screenshot']['tmp_name']){
            $save_image_path =DIR_IMAGE."customer_upload/forum/";
            $file_type =substr(strrchr($_FILES['ga_click_screenshot']['name'], '.'), 1);
            if(!is_dir($save_image_path)){
                mkdir($save_image_path,0777,1);
            }
            $new_file_name ='ga_click_screenshot_'.time()."_".$key.".".$file_type; 
            if(!move_uploaded_file($_FILES['ga_click_screenshot']['tmp_name'], $save_image_path.$new_file_name)){
                $this->error['warning'] ='上传图片失败';
            }
            else{
                $new_iamge_path = 'customer_upload/forum/'.$new_file_name;
            }
         }
		if (!$this->error) {
			return $new_iamge_path;
		}
        else {
			return false;
		}
   }

   public function sendEmail($id,$status){
        $this->load->model('tool/email');
        $this->load->model("additional/forum");
        $this->load->model("setting/store");
        $info =$this->model_additional_forum->getForumUserPost($id);
        switch($info['lang_code']){
             case 'EN':
                $lang_directory ='english';
                $store_id=0;
                break;
            case 'DE':
                $lang_directory ='de';
                $store_id=52;
                break;
            case 'ES':
                $lang_directory ='es';
                $store_id=53;
                break;
            case 'FR':
                $lang_directory ='fr';
                $store_id=54;
                break;
            case 'IT':
                $lang_directory ='it';
                $store_id=55;
                break;
            case 'PT':
                $lang_directory ='pt';
                $store_id=56;
                break;
            default:
                $lang_directory ='english';
                $store_id=0;
                break;

        }
        $this->load->model('sale/customer');
        $user_info =$this->model_sale_customer->getCustomer($info['user_id']);
        $language = new Language($lang_directory);
        $language->load($lang_directory);
        $language->load('mail/make_forum_program');
        $email_data =array();
        $email_data['store_id'] =$store_id;
        $email_data['email_from'] ='MyLED';
        $email_data['email_to'] =$user_info['email'];
        if($store_id==0){
            $store_info['store_name'] ="MyLED";
            $store_info['store_url'] ="https://www.myled.com";
        }
        else{
            $store_info =$this->model_setting_store->getStore($store_id);
        }
        $template = new Template();	
        $template->data['store_id'] = $store_id;
        $template->data['store_name'] = $store_info['store_name'];
        $template->data['store_url'] = $store_info['store_url']."/";	
        $template->data['text_home'] =$language->get('text_home');
        $template->data['text_menu_new_arrivals'] =$language->get('text_menu_new_arrivals');
        $template->data['text_menu_top_sellers'] =$language->get('text_menu_top_sellers');
        $template->data['text_menu_deals'] =$language->get('text_menu_deals');
        $template->data['text_menu_clearance'] =$language->get('text_menu_clearance');

        if($status==2){
            $template->data['text_main_content'] = sprintf($language->get('text_main_content_1'),$user_info['firstname'], $store_info['store_url']."/index.php?route=service/forumProgram",$store_info['store_url']."/");
            $email_data['email_subject'] =$language->get('text_title_1');
        }
        elseif($status==4){
            $template->data['text_main_content'] = sprintf($language->get('text_main_content_2'),$user_info['firstname'], $store_info['store_url']."/index.php?route=service/forumProgram",$store_info['store_url']."/");
            $email_data['email_subject'] =$language->get('text_title_2');
        }
        $template->data['text_no_reply'] =$language->get('text_no_reply');
        $html = $template->fetch('mail/make_forum_program.tpl');
        $email_data['email_content'] =addslashes($html);
        $email_data['is_html'] =1;
        $email_data['attachments'] ="";
        $email_id =$this->model_tool_email->addEmailList($email_data);
        if($email_id){
            return true;
        }
        else{
            return false;
        }
   }

   public function sendPoints($user_id,$points){     
        $sql ="insert into ".DB_PREFIX."customer_reward set customer_id=".$user_id.",description='from Make Easy Money Program',points='".$points."',points_spent=0,status=1,date_added=NOW(),date_confirm=NOW()";
        $this->db->query($sql);
   }
}
?>