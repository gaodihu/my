<?php
class ControllerModuleHotCatalog extends Controller {
	protected function index() {
		$this->language->load('module/hotcatalog');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['more'] = $this->language->get('more');
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->data['hot_catalog'] = array();
		$limit =4;
		$results = $this->model_catalog_category->getHotCatalogs($limit);

		
		foreach ($results as $result) {
            $child_tmp_array =array();
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'],210, 150);
			} else {
				$image = false;
			}
			foreach($result['child'] as $child){
				$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => false
				);
				$child['pro_total'] = $this->model_catalog_product->getTotalProducts($data);
				$child_tmp_array[] =$child;
			}
			$this->data['hot_catalogs'][] = array(
				'category_id' => $result['category_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'url'    	 => $result['url'],
				'child'    	 => $child_tmp_array
			);
		}
		//首页Hot Categories 左边banner
		$home_hot_cat_banner =$this->model_design_banner->getBannerByCode('home_hot_cat_banner');
		if($home_hot_cat_banner){
			foreach($home_hot_cat_banner as $hot_cat_banner){
				if ($hot_cat_banner['image']) {
					$image = $this->model_tool_image->resize($hot_cat_banner['image'], $hot_cat_banner['banner_width'], $hot_cat_banner['banner_height']);
				} else {
					$image = false;
				}
				$this->data['home_hot_cat_banner'][] = array(
					'link' =>	$hot_cat_banner['link'],
					'image' =>	$image,
					'title' =>	$hot_cat_banner['title']
				);
			}
		}
		else{
			$this->data['home_hot_cat_banner'] = array();
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/hotcatalog.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/hotcatalog.tpl';
		} else {
			$this->template = 'default/template/module/hotcatalog.tpl';
		}

		$this->render();
	}
}
?>