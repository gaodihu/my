<?php  
class ControllerProductDeals extends Controller {
	private $error = array(); 
    private $limit =2; 
	public function index() { 
		$lang =$this->language->load('product/deals');
        $this->data =array_merge($this->data,$lang);
        $this->document->addScript('mobile/view/js/pagescroll.js');
        $this->data['title'] = $this->document->setTitle($this->language->get('heading_title'));
        $this->data['description'] = $this->document->setDescription($this->language->get('description'));
        $this->data['keyword'] = $this->document->setKeywords($this->language->get('keyword'));
        
        //限时抢购列表
        $this->load->model('catalog/product');
        $filter=array(
            'page'=>1,
            'limit'  =>$this->limit
        );
        $total =$this->model_catalog_product->getTotalProductSpecials();
        $this->data['special_lists'] =$this->getSpecialList($filter);
        if($total>$filter['limit']){
            $this->data['show_ajax_page'] =1;
        }else{
            $this->data['show_ajax_page'] =0;
        }
        $this->data['json_list_url'] = $this->url->link('product/deals/PageList','','SSL');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/deals.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/deals.tpl';
        } else {
            $this->template = 'default/template/product/deals.tpl';
        }

        $this->children = array(
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
	}

    public function getSpecialList($filter){
        $this->language->load('product/deals');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $data =array();
        $page =isset($filter['page'])?(int)$filter['page']:1;
        $limit=isset($filter['limit'])?(int)$filter['limit']:$this->limit;
        $data['start'] =($page - 1) * $limit;
        $data['limit'] =$limit;
        $delas_list =$this->model_catalog_product->getProductSpecials($data,date('Y-m-d H:i:s',time()),'');
        $deal_res =array();
        foreach($delas_list as $product){
            if($product['image']){
                $image =$this->model_tool_image->resize($product['image'], 207, 160);
            }
            else{
                $image =false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$format_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$format_price = false;
			}
			
			if ((float)$product['special']) {
				$format_special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$format_special = false;
			}
            if($product['special']){
                //特价倒计时
                $special_info = $this->model_catalog_product->getProductSpecial($product['product_id']);
                $end_time = $special_info['date_end'];
                $now =time();
                $end_time_scr =strtotime($end_time);
                if($end_time =='0000-00-00 00:00:00'){
                    $end_time_scr	=$now+24*3600;
                }
                $left_time =$end_time_scr-$now;
                $day = floor($left_time/(3600*24));
                $hours =floor(($left_time%(3600*24))/3600);
                $min =floor(($left_time%3600)/60);
                $sec = ($left_time%3600)%60;
                //$left_time_js = $day.":".$hours.":".$min.":".$sec;
            }
            $deal_res[] =array(
                'product_id' =>   $product['product_id'],
                'name' =>   $product['name'],
                'sku' =>   $product['model'],
                'price' =>   $product['price'],
                'format_price' =>   $format_price,
                'special' =>   $product['special'],
                'format_special' =>   $format_special,
                'image' =>   $image,
                'left_time'    =>$left_time,
                'left_time_days'    =>$day,
                'left_time_hours'    =>$hours,
                'left_time_min'    =>$min,
                'left_time_sec'    =>$sec,
                'quantity' =>$product['quantity'],
                'href' =>   $this->url->link('product/product','&product_id='.$product['product_id'])
            );
        }
        return $deal_res;
    }

    public function PageList(){
        $page =isset($this->request->get['page'])?$this->request->get['page']:0;
        $limit =$this->limit;
        if($page){
            $filter = array(
                    'page' => $page,
                    'limit' => $limit,
            );
            $pro_list =$this->getSpecialList($filter);
            $json['error']=0;
            $json['data']=$pro_list;
        }else{
            $json['error']=1;
            $json['message']='load fialed! please try again';
        }
        $this->response->setOutput(json_encode($json));
    }
}
?>