<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
        $lang =$this->language->load('common/header');
        $this->data = array_merge($this->data,$lang);
        //联盟cookie
        
        if(isset($this->request->get['source'])&&$this->request->get['source']=='webgains'){
            setcookie('source', 'webgains', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'webgains';
        }
        elseif(isset($this->request->get['ssid'])&&$this->request->get['ssid']=='506edX'){
            setcookie('source', 'shareasale', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'shareasale';
        }
        elseif(isset($this->request->get['network'])&&$this->request->get['network']=='adcellled'){
            setcookie('source', 'adcellled', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'adcellled';
        }
        elseif(isset($this->request->get['source'])&&$this->request->get['source']=='tdr'){
            setcookie('source', 'tdr', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'tdr';
        }
        elseif(isset($this->request->get['utm_source'])&&$this->request->get['utm_source']=='EDM'){
            setcookie('source', 'EDM', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'EDM';
        }
        elseif(isset($this->request->get['source'])&&$this->request->get['source']=='CJled'){
            setcookie('source', 'CJled', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'CJled';
        }
        elseif(isset($this->request->get['gclid'])){
            setcookie('source', 'PPC', time() +60*24*3600, '/', COOKIE_DOMAIN);
            $_COOKIE['source'] = 'PPC';
        }
        if(isset($_SERVER['HTTP_REFERER'])){
            $url_ref = parse_url($_SERVER['HTTP_REFERER']);
            if($url_ref && isset($url_ref['host']) &&  !preg_match('/myled\.com/',$url_ref['host'])){
                
                if(!isset($this->request->get['source']) && isset($_COOKIE['source']) && in_array($_COOKIE['source'],array('webgains','tdr','CJled'))){
                   setcookie('source', '', time() - 5000, '/', COOKIE_DOMAIN);
               }

               if(!isset($this->request->get['ssid']) && isset($_COOKIE['source']) && in_array($_COOKIE['source'],array('shareasale'))){
                   setcookie('source', '', time() - 5000, '/', COOKIE_DOMAIN);
               }

               if(!isset($this->request->get['network']) && isset($_COOKIE['source'])  && in_array($_COOKIE['source'],array('adcellled'))){
                   setcookie('source', '', time() - 5000, '/', COOKIE_DOMAIN);
               }

               if(!isset($this->request->get['utm_source']) && isset($_COOKIE['source'])  && in_array($_COOKIE['source'],array('EDM')) ){
                   setcookie('source', '', time() - 5000, '/', COOKIE_DOMAIN);
               }
               if(!isset($this->request->get['gclid']) && isset($_COOKIE['source'])  && in_array($_COOKIE['source'],array('PPC')) ){
                   setcookie('source', '', time() - 5000, '/', COOKIE_DOMAIN);
               }
            }
        }
       

      
		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}



        //专属渠道的cookie
        /*
        *  判断是否是从专属渠道URL 过来的流量
        *
        */
        $this->load->model('catalog/product');
        $href_refre =isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        $source_id =$this->model_catalog_product->if_have_exclusive($href_refre);
        if(!$source_id){
            //是否是edm渠道
            if(isset($this->request->get['utm_source'])&&$this->request->get['utm_source']=='EDM'){
                if(isset($this->request->get['utm_medium'])){
                    $source_str =str_replace(array('-en','-de','-es','-fr','-it','-pt'),'',trim($this->request->get['utm_medium']));
                    $source_str ="EDM/".$source_str;
                    $source_id =$this->model_catalog_product->if_have_exclusive($source_str);
                }
            }
        }
        $exclusive_source_cookie= array();
        if($source_id){
            if(isset($_COOKIE['exclusive_source'])){
                $exclusive_source_cookie =explode(',',$_COOKIE['exclusive_source']);
                if(!in_array($source_id, $exclusive_source_cookie)){
                    $exclusive_source_cookie[] =$source_id;
                }
            }
            else{
                $exclusive_source_cookie[] =$source_id;
            }
            $exclusive_source_cookie_str =implode(',',$exclusive_source_cookie);
            setcookie('exclusive_source',$exclusive_source_cookie_str, time() +60*24*3600, '/', COOKIE_DOMAIN);
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
