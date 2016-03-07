<?php  
class ControllerProductPopular extends Controller {
	private $error = array(); 
    private $tags_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0-9');
	public function index() { 
		$this->language->load('product/popular');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
        $this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('popular'),
			'href'      => $this->url->link('product/popular'),
			'separator' => false
		);
      
        $this->data['title'] = $this->document->setTitle($this->language->get('popular_title'));
        $this->data['description'] = $this->document->setDescription($this->language->get('description'));
        $this->data['keyword'] = $this->document->setKeywords($this->language->get('keyword'));
        
        $this->data['product_tags'] =array();
         foreach($this->tags_array as $key=>$value){
            $this->data['product_tags'][$key]['text'] =$value;
            $this->data['product_tags'][$key]['href'] =$this->url->link('product/popular/tag','tag='.$value);
        }
        $this->load->model('catalog/popular');
        $all_tags =$this->model_catalog_popular->getAllPopular($this->tags_array);
        
        $this->data['all_tags'] =array();
        foreach($all_tags as $key=>$res){
            $tags =array();
            foreach($res as $value){
                if(strlen($value['tags'])>20){
                    $query_text =utf8_substr(strip_tags(html_entity_decode(ucwords(strtolower($value['tags'])), ENT_QUOTES, 'UTF-8')), 0, 20) . '...';
                }else{
                    $query_text  =ucwords(strtolower($value['tags']));
                }
                $value['name'] =$query_text;
                $value['href'] =$this->url->link('product/search','search='.strtolower($value['tags']));
                $tags[] =$value;
       
            }
            $this->data['all_tags'][] =array(
                'name'  =>$key,
                'href'  =>$this->url->link('product/popular/tag','tag='.$key),
                'tags' =>$tags
            );
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/popular.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/popular.tpl';
        } else {
            $this->template = 'default/template/product/popular.tpl';
        }

        $this->children = array(
            'common/header'
        );

        $this->response->setOutput($this->render());
		
	}

    public function tag(){
        $tag =$this->request->get['tag'];
        $this->language->load('product/popular');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);
        $this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('popular'),
			'href'      => $this->url->link('product/popular'),
			'separator' => $this->language->get('text_separator')
		);
        $this->data['breadcrumbs'][] = array(
			'text'      => sprintf($this->language->get('popular_list'),strtoupper($tag)),
			'href'      => $this->url->link('product/popular/tag','tag='.$tag),
			'separator' => false
		);
        $this->data['title'] = $this->document->setTitle(sprintf($this->language->get('popular_list_title'),strtoupper($tag)));
        $this->data['description'] = $this->document->setDescription($this->language->get('description'));
        $this->data['keyword'] = $this->document->setKeywords($this->language->get('keyword'));
        
        $this->data['product_tags'] =array();
        foreach($this->tags_array as $key=>$value){
            $this->data['product_tags'][$key]['text'] =$value;
            $this->data['product_tags'][$key]['href'] =$this->url->link('product/popular/tag','tag='.$value);
        }

        $this->load->model('catalog/popular');
         if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $limit =100;
        
        $data =array(
            'tags' =>    $tag,
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
        );
        $total_populars =$this->model_catalog_popular->getTotalPopulars($data);
        $populars =$this->model_catalog_popular->getPopulars($data);
        $this->data['populars'] =array();
        
        foreach($populars as $item){
            $query_text  =strip_tags(html_entity_decode(ucwords(strtolower($item['tags']))));
            $this->data['populars'][]=array(
                'query_text'    =>$query_text,
                'href'   =>$this->url->link('product/search','search='.urlencode($item['tags']))
            );
        }
        $pagination = new Pagination();
        $pagination->total = $total_populars;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/popular/tag', 'tag=' . $tag. '&page={page}');
        $this->data['pagination'] = $pagination->render();
        $this->data['current_tag'] = $tag;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/popular_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/popular_list.tpl';
        } else {
            $this->template = 'default/template/product/popular_list.tpl';
        }

        $this->children = array(
            'common/header'
        );

        $this->response->setOutput($this->render());
        
    }

}
?>