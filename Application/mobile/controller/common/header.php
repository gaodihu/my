<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
        $lang =$this->language->load('common/header');
        $this->data = array_merge($this->data,$lang);
        //联盟cookie

      
		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}


        $this->data['search_action'] =$this->url->link('product/search','','SSL');
        //$this->data['search_keyword'] =$this->data['search'];
		$this->children = array(
			'common/head',
			'module/language',
			'module/currency',
		);
        $this->data['cart_totals_num'] = $this->cart->countProducts();
        if(isset($this->session->data['customer_id'])){
            $this->data['is_login']=1;
        }
        else{
            $this->data['is_login']=0;
        }
        $this->data['login_url']=$this->url->link('account/login');
        $this->data['join_free_url']=$this->url->link('account/register');
        $this->data['all_categorys_url']=$this->url->link('product/category/all');
        $this->data['about_url'] = '/about-us.html';
        $this->data['cart_link'] = $this->url->link('checkout/cart');
        $this->data['account_link'] = $this->url->link('account/account');

        //当前语言和当前货币
        $this->load->model('localisation/language');

        $current_lang =$this->session->data['language'];
        $current_lang_info =$this->model_localisation_language->getLanguageByCode($current_lang);
        $this->data['current_lang_info'] =$current_lang_info;
        $this->data['currency_code'] = $this->currency->getCode();
        //wed 50%
        $show_wed_50_off = 0;
        $_web_50_off_link = '';
        $_cur_w = date('w');
        $_cur_w = intval($_cur_w);
        $_cur_h = date('H');
        $_cur_h = intval($_cur_h);
        if($_cur_w == 3 || ($_cur_w == 4 && date('H')<=12) ){
            $show_wed_50_off = 1;
            $_lang_code = $this->session->data['language'];
            $_lang_code = strtolower($_lang_code);
			$_url_time = '';
			if($_cur_w == 3){
				$_url_time  = date('Ymd');
			}else{
				$_url_time  = date('Ymd',strtotime("-1 day"));
			}
            $_web_50_off_link = "/wed/{$_url_time}/myled_{$_lang_code}.html";
        }
        $this->data['show_wed_50_off'] = $show_wed_50_off;
        $this->data['_web_50_off_link'] = $_web_50_off_link;
        $route =isset($this->request->get['route'])?$this->request->get['route']:'';
        if(strpos($route,'account',0)!==false){
            $this->data['menu_active'] ='account';
        }elseif($route=='product/category/all'){
            $this->data['menu_active'] ='category';
        }elseif(strpos($route,'checkout',0)!==false){
            $this->data['menu_active'] ='cart';
        }else{
             $this->data['menu_active']='';
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/common/header.tpl';
        } else{
            $this->template ='default/template/common/header.tpl';
        }
		$this->render();
	} 	
}
?>
