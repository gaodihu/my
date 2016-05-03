<?php 
class ControllerAccountRightTop extends Controller { 
	public function index() {
		//得到 用户中心顶部banner
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$account_banner_info = $this->model_design_banner->getBannerByCode('accout_banner');
		if($account_banner_info){
			foreach($account_banner_info as $account_banner){
				if ($account_banner['image']) {
					$image = $this->model_tool_image->resize($account_banner['image'], $account_banner['banner_width'], $account_banner['banner_height']);
				} else {
					$image = false;
				}
				$this->data['account_banner'][] = array(
					'link' =>	$account_banner['link'],
					'image' =>	$image,
					'title' =>	$account_banner['title']
				);
			}
		}
		else{
			$this->data['account_banner'] = array();
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/right_top.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/right_top.tpl';
		} else {
			$this->template = 'default/template/account/right_top.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>