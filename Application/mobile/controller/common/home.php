<?php  
class ControllerCommonHome extends Controller {
	public function index() {
        $LANG =$this->language->load('common/home');
        $this->data = array_merge($this->data,$LANG);
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keywords'));


		//加载banner 模块
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		//首页幻灯片
		$home_flash_banner_info =$this->model_design_banner->getBannerByCode('home_falsh');
		if($home_flash_banner_info){
			foreach($home_flash_banner_info as $home_flash_banner){
				if ($home_flash_banner['image']) {
					$image = $this->model_tool_image->resize($home_flash_banner['image'], 750, 250);
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
        $this->data['all_categorys_url']=$this->url->link('product/category/all');
		$this->children = array(
			'module/special',
			'module/bestseller',
			'common/footer',
			'common/header'
		);
       if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/common/home.tpl';
        } else{
            $this->template ='default/template/common/home.tpl';
        }

		$this->response->setOutput($this->render());
	}
}
?>