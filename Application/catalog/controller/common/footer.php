<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');

        $this->data['text_product_tags'] = $this->language->get('text_product_tags');
        $this->data['text_hot_keywords'] = $this->language->get('text_hot_keywords');
        $this->data['text_join_our_community'] = $this->language->get('text_join_our_community');
        $this->data['text_subscribe_to'] = $this->language->get('text_subscribe_to');
        $this->data['text_get_updates'] = $this->language->get('text_get_updates');
        $this->data['text_copyright'] = $this->language->get('text_copyright');
        $this->data['text_enter_emial_address'] = $this->language->get('text_enter_emial_address');

		$this->load->model('catalog/information');

		$this->data['informations'] = array();
        $information_group_name=array();
		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['information_group_id']) {
                $group_name =$this->model_catalog_information->getGroupName($result['information_group_id']);
                $information_group_name[$result['information_group_id']]['name']=$group_name['name'];
                $information_group_name[$result['information_group_id']]['information'][]=array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                    //'href'  =>$this->model_catalog_information->getInformationUrl($result['information_id'])
				);
			}
		}
        $this->data['informations'] =$information_group_name;

		$this->data['contact'] = $this->url->link('information/contact');
		$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
		$this->data['sitemap'] = $this->url->link('information/sitemap');
		$this->data['manufacturer'] = $this->url->link('product/manufacturer');
		$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['special'] = $this->url->link('product/special');
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['order'] = $this->url->link('account/order', '', 'SSL');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');		
        $this->data['fourm'] = $this->url->link('service/forumProgram', '', 'SSL');	
		$this->data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		//登录弹出框的JS语言项
		$this->language->load('account/login_form');
        $this->data['text_sign_in'] = $this->language->get('text_sign_in');
        $this->data['text_register_olny'] = $this->language->get('text_register_olny');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['text_forgot_password'] = $this->language->get('text_forgot_password');
        $this->data['entry_nickname'] = $this->language->get('entry_nickname');
        $this->data['entry_confirm'] = $this->language->get('entry_confirm');
        $this->data['text_newsletter'] = $this->language->get('text_newsletter');
        $this->data['text_agree'] = sprintf($this->language->get('text_agree'),'','');
        $this->data['text_sign_facebook'] = $this->language->get('text_sign_facebook');
        $this->data['text_sign_google'] = $this->language->get('text_sign_google');
        $this->data['text_checkout_as_guest'] = $this->language->get('text_checkout_as_guest');
        $this->data['guest_checkout_link'] = $this->url->link('checkout/checkout','','SSL');

        

        
        $is_show_coupon = false;
        $lang = $this->session->data['language'];
        $lang = strtoupper($lang);
        if(isset($_GET['clickfrom']) && !in_array($lang,array('IT','PT')) ) { 
          if(!isset($_GET['route']) || (isset($_GET['route']) && $_GET['route'] != 'checkout/checkout') ) {
              $is_show_coupon = true;
          }
        }
        $this->data['lang'] =  $lang;


		$this->data['error_email'] = $this->language->get('error_email');
		$this->data['error_nickname'] = $this->language->get('error_nickname');
		$this->data['error_exists'] = $this->language->get('error_exists');
		$this->data['error_password'] = $this->language->get('error_password');
		$this->data['error_confirm'] = $this->language->get('error_confirm');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];	
			} else {
				$ip = ''; 
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];	
			} else {
				$referer = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}

        //product tags 
        $this->data['tag_href'] =$this->url->link('product/popular');
        $tags_array=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0-9');
        $this->data['product_tags'] =array();
        foreach($tags_array as $key=>$value){
            $this->data['product_tags'][$key]['text'] =$value;
            $this->data['product_tags'][$key]['href'] =$this->url->link('product/popular/tag','tag='.$value);
        }

        //热搜分类，取一级分类
        $this->load->model('catalog/category');
        $hot_catlog =$this->model_catalog_category->getCategories();
        $this->data['hot_search_catlog'] =array();
        foreach($hot_catlog as $category){
            if($category['top']){
                $this->data['hot_search_catlog'][] =array(
                    'name'=>    $category['name'],
                    'href'=>    $this->url->link('product/search','category_id='.$category['category_id'])
                );
            }
            
        }
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/footer.tpl';
		} else {
			$this->template = 'default/template/common/footer.tpl';
		}

		$this->render();
	}
}
?>