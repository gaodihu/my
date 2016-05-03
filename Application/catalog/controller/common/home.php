<?php  
class ControllerCommonHome extends Controller {
	public function index() {
        $this->language->load('common/home');
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keywords'));

		$this->data['heading_title'] = $this->config->get('config_title');
		$this->document->addStyle('css/stylesheet/flexslider.css');
		$this->document->addScript('js/jquery/jquery.flexslider.js');
		$this->data['text_security_supplier'] = $this->language->get('text_security_supplier');
        $this->data['text_security_professional'] = $this->language->get('text_security_professional');
        $this->data['text_security_mcafee'] = $this->language->get('text_security_mcafee');
        $this->data['text_security_ensures'] = $this->language->get('text_security_ensures');
        $this->data['text_security_verified'] = $this->language->get('text_security_verified');
        $this->data['text_security_price'] = $this->language->get('text_security_price');
        $this->data['text_security_points'] = $this->language->get('text_security_points');
        $this->data['text_security_regularly'] = $this->language->get('text_security_regularly');
		//加载banner 模块
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		//首页幻灯片
		$home_flash_banner_info =$this->model_design_banner->getBannerByCode('home_falsh');
		if($home_flash_banner_info){
			foreach($home_flash_banner_info as $home_flash_banner){
				if ($home_flash_banner['image']) {
					$image = $this->model_tool_image->resize($home_flash_banner['image'], $home_flash_banner['banner_width'], $home_flash_banner['banner_height']);
				} else {
					$image = false;
				}
				$this->data['home_flash_banner_info'][] = array(
					'link' =>	$home_flash_banner['link'],
					'image' =>	$image,
					'title' =>	$home_flash_banner['title']
				);
			}
		}
		else{
			$this->data['home_flash_banner_info'] = array();
		}
		//幻灯片右边图片
		$home_flash_right_banner_info =$this->model_design_banner->getBannerByCode('home_falsh_right_banner');
		if($home_flash_right_banner_info){
			foreach($home_flash_right_banner_info as $home_flash_right_banne){
				if ($home_flash_right_banne['image']) {
					$image = $this->model_tool_image->resize($home_flash_right_banne['image'], $home_flash_right_banne['banner_width'], $home_flash_right_banne['banner_height']);
				} else {
					$image = false;
				}
				$this->data['home_flash_right_banner_info'][] = array(
					'link' =>	$home_flash_right_banne['link'],
					'image' =>	$image,
					'title' =>	$home_flash_right_banne['title']
				);
			}
		}
		else{
			$this->data['home_flash_right_banner_info'] = array();
		}
		
		$this->children = array(
			'module/special',
			'module/bestseller',
			'module/latest',
			'module/hotcatalog',
			'common/footer',
			'common/header'
		);
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>