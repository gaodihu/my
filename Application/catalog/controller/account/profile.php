<?php
class ControllerAccountProfile extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/profile', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/profile');
		$this->load->model('account/customer');
		$customer =$this->model_account_customer->getCustomer($this->session->data['customer_id']);
		$this->data['customer'] =$customer;
		$this->document->setTitle($this->language->get('heading_title'));
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('account/customer');
            $path =$this->get_upload_path();
            if($path){
                $this->request->post['avatar'] =$path;
            }
            else{
                 $this->request->post['avatar'] =$customer['avatar'];
            }
			
			$this->model_account_customer->editCustomer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/profile', '', 'SSL'));
		}
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/profile', '', 'SSL'),
			'separator' => false
		);

		$this->document->addScript('js/jquery/ui/jquery-ui.min.js');
		$this->document->addStyle('js/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_nickname'] = $this->language->get('entry_nickname');
		$this->data['entry_frist_name'] = $this->language->get('entry_frist_name');
		$this->data['entry_last_name'] = $this->language->get('entry_last_name');
		$this->data['entry_telphone'] = $this->language->get('entry_telphone');
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_birthday'] = $this->language->get('entry_birthday');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_image_upload'] = $this->language->get('entry_image_upload');
		$this->data['entry_image_preview'] = $this->language->get('entry_image_preview');
		$this->data['entry_image_advised'] = $this->language->get('entry_image_advised');
        $this->data['entry_image_limit'] = $this->language->get('entry_image_limit');

		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('localisation/country');
	
		$this->data['countries'] = $this->model_localisation_country->getCountries();


		$this->data['action'] = $this->url->link('account/profile', '', 'SSL');


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/profile.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/profile.tpl';
		} else {
			$this->template = 'default/template/account/profile.tpl';
		}

		$this->children = array(
			'account/menu',
			'account/right_top',
			'account/right_bottom',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());			
	}


	protected function get_upload_path() {
		if($_FILES['imageupload']){
			$img_name =$_FILES['imageupload']['name'];
			$accpect =array('jpg','jpeg','gif','bmp');	
			$ext =substr($img_name, strrpos($img_name, '.')+1);
			if(in_array($ext,$accpect)){
				$new_path = DIR_TEMPLATE."/default/images/user_tx/".$this->session->data['customer_id'].".".$ext;
				$path ='css/images/user_tx/'.$this->session->data['customer_id'].".".$ext;
				if(move_uploaded_file($_FILES['imageupload']['tmp_name'],$new_path)){
					return $path;
				}
				else{
					return false;
				}
			}
		}
	}
}
?>
