<?php 
class ControllerAdditionalGetOtherProduct extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle($this->language->get('得到新品'));
		$this->load->model('additional/get_other_product');
		$this->getList();
	} 
	public function reply() {
		$this->document->setTitle($this->language->get('新品回复'));

		$this->load->model('additional/get_other_product');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRplyForm()) {
			$this->model_additional_get_other_product->editOtherProduct($this->request->get['id'],$this->request->post);
            if($this->send_reply_email($this->request->get['id'])){
                $this->model_additional_get_other_product->UpdateOtherProductEmail($this->request->get['id']);
            }
			$this->session->data['success'] = $this->language->get('回复成功');

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

			$this->redirect($this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            
		}

		$this->getReplyForm();
	}

	public function delete() { 
		$this->document->setTitle($this->language->get('得到新品'));

		$this->load->model('additional/get_other_product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $new_p_id) {
				$this->model_additional_get_other_product->deleteOtherProduct($new_p_id);
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

			$this->redirect($this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->request->get['filter_language_code'])) {
				$filter['filter_language_code'] = $this->request->get['filter_language_code'];
		} else {
				$filter['filter_language_code']= null;
		}
		if (isset($this->request->get['filter_status'])) {
				$filter['filter_status'] = $this->request->get['filter_status'];
		} else {
				$filter['filter_status']= null;
		}
        if (isset($this->request->get['filter_email'])) {
				$filter['filter_email'] = $this->request->get['filter_email'];
		} else {
				$filter['filter_email']= null;
		}
		if (isset($this->request->get['filter_email_send'])) {
				$filter['filter_email_send'] = $this->request->get['filter_email_send'];
		} else {
				$filter['filter_email_send']= null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'np.new_pro_id';
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
			'text'      => $this->language->get('得到新品'),
			'href'      => $this->url->link('additional/get_others_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['delete'] = $this->url->link('additional/get_other_product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'] ;	
		$this->data['current_url'] = $this->url->link('additional/get_other_product/', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['other_products'] = array();
		$this->data['filter'] = $filter;
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')

		);

		$data =array_merge($data,$filter);
		$other_products_total = $this->model_additional_get_other_product->getTotalOtherProducts($data);

		$results = $this->model_additional_get_other_product->getOtherProducts($data);

		foreach ($results as $result) {
			$action = array();
            if($result['email_send']){
                $action[] = array(
                    'text' => $this->language->get('查看'),
                    'href' => $this->url->link('additional/get_other_product/reply', 'token=' . $this->session->data['token'] . '&id=' . $result['new_pro_id'] . $url, 'SSL')
                );
            }else{
                $action[] = array(
                    'text' => $this->language->get('回复'),
                    'href' => $this->url->link('additional/get_other_product/reply', 'token=' . $this->session->data['token'] . '&id=' . $result['new_pro_id'] . $url, 'SSL')
                );
            }
			
			$this->load->model('tool/image');
            $this->load->model('localisation/currency');
            $image =$this->model_tool_image->resize($result['product_img'],50,50);
            $big_image = "/image/".$result['product_img'];
			$this->data['other_products'][] = array(
				'new_pro_id'  => $result['new_pro_id'],
                'language_code'     => $result['language_code'],
				'user_name'       => $result['user_name'],
				'email'     => $result['email'],
				'product_name'     => $result['product_name'],
				'product_color'     => $result['product_color'],
				'product_img'     => $image,
				'big_image'      => $big_image,
				'base_price'     =>'$'.$this->currency->convert($result['price'],$result['currency_code'],'USD'),
                'price'     =>$result['currency_code'].$result['price'],
				'url_link'     => $result['url_link'],
				'shipment' =>$result['shipment'] ,
                'status' =>$result['status'] ,
                'email_send' =>$result['email_send'] ,
                'created_at' =>$result['created_at'] ,
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

		$this->data['sort_new_pro_id'] = $this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . '&sort=np.new_pro_id' . $url, 'SSL');
		
		$this->data['sort_date_added'] = $this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . '&sort=np.created_at' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		$pagination = new Pagination();
		$pagination->total = $other_products_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'additional/get_other_product.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	protected function getReplyForm() {
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('新品回复'),
			'href'      => $this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		
		$this->data['action'] = $this->url->link('additional/get_other_product/reply', 'token=' . $this->session->data['token']."&id=".$this->request->get['id'], 'SSL');
		$this->data['cancel'] = $this->url->link('additional/get_other_product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['id'])) {
			$other_product_info = $this->model_additional_get_other_product->getOtherProduct($this->request->get['id']);
		}
        $this->data['other_product_info'] =$other_product_info;
        $this->load->model('tool/image');
        $image =$this->model_tool_image->resize($other_product_info['product_img'],250,250);
        $this->data['pro_img'] =$image;
        if(isset($this->error['warning'])){
            $this->data['error_warning'] =$this->error['warning'];
        }
        elseif(isset($this->error['reply_content'])){
            $this->data['error_warning'] =$this->error['reply_content'];
        }
        else{
            $this->data['error_warning'] ='';
        }
		//得到语言项
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'additional/get_other_product_reply_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	protected function validateRplyForm() {
		if (!$this->user->hasPermission('modify', 'additional/get_other_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (utf8_strlen($this->request->post['reply_content']) < 1) {
			$this->error['reply_content'] = "回复不能为空";
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'additional/get_other_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
    
    public function send_reply_email($new_pro_id){
        $this->load->model('tool/email');
        $this->load->model("additional/get_other_product");
        $this->load->model("setting/store");
        $get_others_product_info =$this->model_additional_get_other_product->getOtherProduct($new_pro_id);
        switch($get_others_product_info['language_code']){
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
        $language = new Language($lang_directory);
        $language->load($lang_directory);
        $language->load('mail/get_other_product_reply');
        $email_data =array();
        $email_data['store_id'] =$store_id;
        $email_data['email_from'] ='MyLED';
        $email_data['email_to'] =$get_others_product_info['email'];
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
        $template->data['text_main_content'] = sprintf($language->get('text_main_content'),$get_others_product_info['user_name'],$get_others_product_info['replay_content']);
        $template->data['text_no_reply'] =$language->get('text_no_reply');
        $email_data['email_subject'] =sprintf($language->get('text_title'),$get_others_product_info['product_name']);
        $html = $template->fetch('mail/get_other_product_reply.tpl');
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
}
?>