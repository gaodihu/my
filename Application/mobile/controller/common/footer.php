<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$lang =$this->language->load('common/footer');
    
        $this->data = array_merge($this->data,$lang);
        $this->data['all_category'] =$this->url->link("product/category/all");
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
            $this->template =$this->config->get('config_template') . '/template/common/footer.tpl';
        } else{
            $this->template ='default/template/common/footer.tpl';
        }
        
		$this->render();
	}
}
?>