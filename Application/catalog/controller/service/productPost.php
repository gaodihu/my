<?php 
class ControllerServiceProductPost extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('service/product_post');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		}
        $this->document->addStyle('css/stylesheet/reviews_write.css');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' =>$this->language->get('text_separator')
		);
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('service/productPost', $url, 'SSL'),        	
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_desc'] = $this->language->get('text_desc');      
        $this->data['enter_user_name'] = $this->language->get('enter_user_name');
        $this->data['enter_user_email'] = $this->language->get('enter_user_email');
        $this->data['enter_product_name'] = $this->language->get('enter_product_name');
        $this->data['enter_color'] = $this->language->get('enter_color');
        $this->data['enter_product_image'] = $this->language->get('enter_product_image');
        $this->data['enter_expected_price'] = $this->language->get('enter_expected_price');
        $this->data['enter_product_description'] = $this->language->get('enter_product_description');
        $this->data['enter_url'] = $this->language->get('enter_url');
        $this->data['enter_anticipant_shipment'] = $this->language->get('enter_anticipant_shipment');
        $this->data['enter_product_description'] = $this->language->get('enter_product_description');
        $this->data['enter_additional_comment'] = $this->language->get('enter_additional_comment');
        $this->data['text_submit'] = $this->language->get('text_submit');
        $this->data['text_file_type_limit'] = $this->language->get('text_file_type_limit');
        $this->data['text_uplaod_image'] = $this->language->get('text_uplaod_image');
        $this->data['text_add_new'] = $this->language->get('text_add_new');
        $this->data['text_delete'] = $this->language->get('text_delete');
        


        $this->data['empty_user_name'] = $this->language->get('empty_user_name');
        $this->data['empty_user_email'] = $this->language->get('empty_user_email');
        $this->data['empty_product_name'] = $this->language->get('empty_product_name');
        $this->data['empty_product_image'] = $this->language->get('empty_product_image');
        $this->data['empty_product_link'] = $this->language->get('empty_product_link');
        $this->data['empty_shipment'] = $this->language->get('empty_shipment');

        //边栏special 专题banner
        $this->load->model('tool/image');
        $this->data['text_hot'] = $this->language->get('text_hot');
        $this->load->model('design/banner');
        $special_lists = $this->model_design_banner->getBannerByCode('special_history_baner');
        if ($special_lists) {
            foreach ($special_lists as $side_banner) {
                if ($side_banner['image']) {
                    $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                } else {
                    $image = false;
                }
                $this->data['special_lists'][] = array(
                    'link' => $side_banner['link'],
                    'image' => $image,
                    'title' => $side_banner['title']
                );
            }
        } else {
            $this->data['special_lists'] = array();
        }
        //边栏bananer 
            $this->load->model('design/banner');
            $this->data['top_seller'] = $this->language->get('top_seller');
            $side_banner_info = $this->model_design_banner->getBannerByCode('side_banner');
            if ($side_banner_info) {
                foreach ($side_banner_info as $side_banner) {
                    if ($side_banner['image']) {
                        $image = $this->model_tool_image->resize($side_banner['image'], $side_banner['banner_width'], $side_banner['banner_height']);
                    } else {
                        $image = false;
                    }
                    $this->data['side_banner'][] = array(
                        'link' => $side_banner['link'],
                        'image' => $image,
                        'title' => $side_banner['title']
                    );
                }
            } else {
                $this->data['side_banner'] = array();
            }
         if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$upload_image=$this->validateForm()) {
            $this->load->model('service/product_post');
            $data =$this->request->post;
            $data['language_code'] =$this->session->data['language'];
            $data['product_img'] =$upload_image;
            $new_post_id =$this->model_service_product_post->addNewProductPost($data);
             $this->session->data['success'] =$this->language->get('post_success');
             $this->redirect($this->url->link('service/productPost'));
        }
        $this->load->model('localisation/currency');

		$this->data['currencies'] = array();

		$results = $this->model_localisation_currency->getCurrencies();	

		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
        if($this->customer->isLogged()){
           $this->load->model('account/customer');
           $customer_info = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
           $this->data['user_name'] =$customer_info['firstname'];
           $this->data['user_email'] =$customer_info['email'];
        }
        else{
            if(isset($this->request->post['user_name'])){
                $this->data['user_name'] =$this->request->post['user_name'];
            }
            else{
                $this->data['user_name'] ='';
            }
            if(isset($this->request->post['user_email'])){
                $this->data['user_email'] =$this->request->post['user_email'];
            }
            else{
                $this->data['user_email'] ='';
            }
        }
       
       if(isset($this->request->post['product_name'])){
            $this->data['product_name'] =$this->request->post['product_name'];
        }
        else{
            $this->data['product_name'] =array();
        }
        if(isset($this->request->post['product_color'])){
            $this->data['product_color'] =$this->request->post['product_color'];
        }
        else{
            $this->data['product_color'] =array();
        }
         if(isset($this->request->post['currency'])){
            $this->data['product_currency'] =$this->request->post['currency'];
        }
        else{
            $this->data['product_currency'] =array();
        }

        if(isset($this->request->post['product_price'])){
            $this->data['product_price'] =$this->request->post['product_price'];
        }
        else{
            $this->data['product_price'] =array();
        }
        if(isset($this->request->post['product_description'])){
            $this->data['product_description'] =$this->request->post['product_description'];
        }
        else{
            $this->data['product_description'] =array();
        }
        if(isset($this->request->post['product_link'])){
            $this->data['product_link'] =$this->request->post['product_link'];
        }
        else{
            $this->data['product_link'] =array();
        }

        $shipping_method =array('Standard Shipping (7-9 Working Days)','Super Saver Shipping (10-20 Working Days)','Expedited Shipping (3-6 Working Days)','EMS Shipping (9-15 Working Days)');
        if(isset($this->request->post['shipment'])){
            $this->data['shipment'] =$this->request->post['shipment'];
        }
        else{
            $this->data['shipment'] =array();
        }
        $this->data['shipment_method'] =$shipping_method;
         if(isset($this->request->post['comment'])){
            $this->data['comment'] =$this->request->post['comment'];
        }
        else{
            $this->data['comment'] =array();
        }
        
        if(isset($this->session->data['success'])){
            $this->data['success'] =$this->session->data['success'];
            unset($this->session->data['success']);
        }
        else{
            $this->data['success'] ='';
        }
        if(isset($this->session->data['error'])){
            $this->data['error'] =$this->session->data['error'];
            unset($this->session->data['error']);
        }
        else{
            $this->data['error'] =array();
        }
        $this->data['action']=$this->url->link('service/productPost');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/service/product_post.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/service/product_post.tpl';
		} else {
			$this->template = 'default/template/service/product_post.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());				
	}

  private function validateForm(){
        $this->language->load('service/product_post');
        $user_name =isset($this->request->post['user_name'])?$this->request->post['user_name']:'';
        $user_email = isset($this->request->post['user_email'])?$this->request->post['user_email']:'';
        $product_name = isset($this->request->post['product_name'])?$this->request->post['product_name']:array();
        $product_image = isset($_FILES['product_image']['name'])?$_FILES['product_image']['name']:'';
        $product_link =isset($this->request->post['product_link'])?$this->request->post['product_link']:'';
        $shipment = isset($this->request->post['shipment'])?$this->request->post['shipment']:'';
        if(!$user_name){
            $this->error['empty_user_name'] = $this->language->get('empty_user_name');
        }
        if(!$user_email){
            $this->error['empty_user_email'] = $this->language->get('empty_user_email');
        }
        if(!empty($user_email)&&!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $user_email)){
            $this->error['error_user_email'] = $this->language->get('error_user_email');
        }
        $count =count($product_name);
        $uplaod_fiel_type =array();
        for($i=0;$i<$count;$i++){
            if(!$product_name[$i]){
                $this->error['empty_product_name'] = $this->language->get('empty_product_name');
            }
            if(!$product_image[$i]){
                $this->error['empty_product_image'] = $this->language->get('empty_product_image');
            }
            if(!$product_link[$i]){
                $this->error['empty_product_link'] = $this->language->get('empty_product_link');
            }
            if(!$shipment[$i]){
                 $this->error['empty_shipment'] = $this->language->get('empty_shipment');
            }
            $file_type =substr(strrchr($product_image[$i], '.'), 1);
            $uplaod_fiel_type[] =$file_type;
            $accept_type = array('jpg','jpeg','bmp','gif','png');
            if(!in_array(strtolower($file_type),$accept_type)){
                $this->error['error_product_image_type'] = $this->language->get('error_product_image_type');
            }
            
            //图片大小限制 2MB
            if($_FILES['product_image']['size'][$i]>2097150){
                $this->error['error_product_image_size'] = $this->language->get('error_product_image_size');
            }
        }
        $new_iamge_path =array();
        if(!$this->error){
             //处理图片
            $save_image_path =DIR_IMAGE."customer_upload/new_product_post/";
            if(!is_dir($save_image_path)){
                mkdir($save_image_path,0777,1);
            }
            foreach($_FILES['product_image']['tmp_name'] as $key=>$tmp_name){
                $new_file_name ='new_product_post_'.time()."_".$key.".".$uplaod_fiel_type[$key]; 
                if(!move_uploaded_file($tmp_name, $save_image_path.$new_file_name)){
                    $this->error['error_product_image_upload'] = $this->language->get('error_product_image_upload');
                }
                else{
                    $new_iamge_path[] = 'customer_upload/new_product_post/'.$new_file_name;
                }
            }
           
        }
        if(!$this->error){
            return $new_iamge_path;
        }
        else{
            $this->session->data['error'] =$this->error;
            return false;
        }
  }
}
?>