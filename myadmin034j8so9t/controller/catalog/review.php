<?php
class ControllerCatalogReview extends Controller {
    private $error = array();
    private $up_error = array();
    public function index() {
        $this->language->load('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        $this->getList();
    } 

    public function insert() {
        $this->language->load('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $sku =$this->request->post['product_sku'];
            $product_id =$this->get_product_id($sku);
            $this->request->post['product_id'] =$product_id;
            $this->model_catalog_review->addReview($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function update() {
        $this->language->load('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $reviews_info =
            $sku =$this->request->post['product_sku'];
            $product_id =$this->get_product_id($sku);
            $this->request->post['product_id'] =$product_id;
            $status =$this->request->post['status'];
            $this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            /*
            **  网站2周年庆期间，所有发表的评论 积分翻倍
            ** 
            **   
            */
            
            //通过后，自动发送20积分到用户账户
            $if_send_point =$this->model_catalog_review->if_send_point($this->request->get['review_id']);
            if($status&&!$if_send_point){
                //赠送的积分数量
                $point_send =20;  
                // 判断是否为周年庆时间段内
                 if(isset($this->session->data['2nd_anniversary']) &&$this->session->data['2nd_anniversary']){
                    $point_send =40;
                }
                $this->sendPoints($this->request->post['customer_id'],$point_send);
                $this->model_catalog_review->update_send_point($this->request->get['review_id']);
            }
            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() { 
        $this->language->load('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $review_id) {
                $this->model_catalog_review->deleteReview($review_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_id'])) {
                $filter['filter_id'] = $this->request->get['filter_id'];
        } else {
                $filter['filter_id']= null;
        }
         if (isset($this->request->get['filter_store_id'])) {
                $filter['filter_store_id'] = $this->request->get['filter_store_id'];
        } else {
                $filter['filter_store_id']= null;
        }
        if (isset($this->request->get['filter_sku'])) {
                $filter['filter_sku'] = $this->request->get['filter_sku'];
        } else {
                $filter['filter_sku']= null;
        }
        if (isset($this->request->get['filter_author'])) {
                $filter['filter_author'] = $this->request->get['filter_author'];
        } else {
                $filter['filter_author']= null;
        }
        if (isset($this->request->get['filter_rating'])) {
                $filter['filter_rating'] = $this->request->get['filter_rating'];
        } else {
                $filter['filter_rating']= null;
        }
        if (isset($this->request->get['filter_status'])) {
                $filter['filter_status'] = $this->request->get['filter_status'];
        } else {
                $filter['filter_status']= null;
        }

        if (isset($this->request->get['filter_is_publish'])) {
            $filter['filter_is_publish'] = $this->request->get['filter_is_publish'];
        } else {
            $filter['filter_is_publish']= null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'r.review_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        foreach($filter as $key=>$filter_list){
            if($filter_list){
                $url .= '&'.$key.'=' . $filter_list;

            }
            
        }
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('catalog/review/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['import'] = $this->url->link('catalog/review/import', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['down_templete'] = $this->url->link('catalog/review/down_templete', 'token=' . $this->session->data['token'] . $url, 'SSL');    
        $this->data['token'] = $this->session->data['token'] ;  
        $this->data['current_url'] = $this->url->link('catalog/review/', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['reviews'] = array();
        $this->data['filter'] = $filter;
        $data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );
        $data =array_merge($data,$filter);

	    $review_total = $this->model_catalog_review->getTotalReviews($data);


        $results = $this->model_catalog_review->getReviews($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('catalog/review/update', 'token=' . $this->session->data['token'] . '&review_id=' . $result['review_id'] . $url, 'SSL')
            );
            $this->load->model('setting/store');
            $store_info =$this->model_setting_store->getStore($result['store_id']);
            $this->data['reviews'][] = array(
                'review_id'  => $result['review_id'],
                'store_id'  => $result['store_id'],
                'store_code'  => isset($store_info['name'])?$store_info['name']:'default',
                'sku'       => $result['model'],
                'author'     => $result['author'],
                'rating'     => $result['rating'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'is_publish'     => ($result['is_publish'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'selected'   => isset($this->request->post['selected']) && in_array($result['review_id'], $this->request->post['selected']),
                'action'     => $action
            );
        }   

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_product'] = $this->language->get('column_product');
        $this->data['column_author'] = $this->language->get('column_author');
        $this->data['column_rating'] = $this->language->get('column_rating');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_date_added'] = $this->language->get('column_date_added');
        $this->data['column_action'] = $this->language->get('column_action');       

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';







        if (isset($this->request->get['filter_is_publish'])) {
            $url .= '&filter_is_publish=' . $this->request->get['filter_is_publish'];
        }
        if (isset($this->request->get['filter_id'])) {
            $url .= '&filter_id=' . $this->request->get['filter_id'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_sku'])) {
            $url .= '&filter_sku=' . $this->request->get['filter_sku'];
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . $this->request->get['filter_author'];
        }

        if (isset($this->request->get['filter_rating'])) {
            $url .= '&filter_rating=' . $this->request->get['filter_rating'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        $link = $url;

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }


        if ($order == 'ASC') {
            $link .= '&order=DESC';
        } else {
            $link .= '&order=ASC';
        }

        $this->data['sort_review_id'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']  . '&sort=r.review_id' . $link, 'SSL');
        $this->data['sort_store_id'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']   . '&sort=r.store_id' . $link, 'SSL');
        $this->data['sort_author'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']     . '&sort=r.author' . $link, 'SSL');
        $this->data['sort_rating'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']     . '&sort=r.rating' . $link, 'SSL');
        $this->data['sort_status'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']     . '&sort=r.status' . $link, 'SSL');
        $this->data['sort_date_added'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $link, 'SSL');






        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/review_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function getForm() {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_select'] = $this->language->get('text_select');

        $this->data['entry_product'] = $this->language->get('entry_product');
        $this->data['entry_author'] = $this->language->get('entry_author');
        $this->data['entry_rating'] = $this->language->get('entry_rating');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_text'] = $this->language->get('entry_text');
        $this->data['entry_good'] = $this->language->get('entry_good');
        $this->data['entry_bad'] = $this->language->get('entry_bad');
        $this->data['entry_publish'] = $this->language->get('entry_publish');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['product'])) {
            $this->data['error_product'] = $this->error['product'];
        } else {
            $this->data['error_product'] = '';
        }

        if (isset($this->error['author'])) {
            $this->data['error_author'] = $this->error['author'];
        } else {
            $this->data['error_author'] = '';
        }
        
        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = '';
        }
        if (isset($this->error['text'])) {
            $this->data['error_text'] = $this->error['text'];
        } else {
            $this->data['error_text'] = '';
        }

        if (isset($this->error['rating'])) {
            $this->data['error_rating'] = $this->error['rating'];
        } else {
            $this->data['error_rating'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['review_id'])) { 
            $this->data['action'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('catalog/review/update', 'token=' . $this->session->data['token'] . '&review_id=' . $this->request->get['review_id'] . $url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL');
        if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $review_info = $this->model_catalog_review->getReview($this->request->get['review_id']);
            $this->data['customer_id'] =$review_info['customer_id'];
        }
       
        $this->load->model('setting/store');
        if($review_info['store_id']==0){
            $this->data['stores_info'] =array();
        }
        else{
            $this->data['stores_info'] =$this->model_setting_store->getStore($review_info['store_id']);
        }
		 $this->data['stores'] =$this->model_setting_store->getStores();
	    $this->data['token'] = $this->session->data['token'];


        $this->load->model('catalog/product');
        
        if (isset($this->request->post['store_id'])) {
            $this->data['store_id'] = $this->request->post['store_id'];
        } elseif (!empty($review_info)) {
            $this->data['store_id'] = $review_info['store_id'];
        } else {
            $this->data['store_id'] =0;
        }
        if (isset($this->request->post['product_sku'])) {
            $this->data['product_sku'] = $this->request->post['product_sku'];
        } elseif (!empty($review_info)) {
            $this->data['product_sku'] = $review_info['sku'];
        } else {
            $this->data['product_sku'] = '';
        }


        if (isset($this->request->post['author'])) {
            $this->data['author'] = $this->request->post['author'];
        } elseif (!empty($review_info)) {
            $this->data['author'] = $review_info['author'];
        } else {
            $this->data['author'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $this->data['email'] = $customer_info['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['title'])) {
            $this->data['title'] = $this->request->post['title'];
        } elseif (!empty($review_info)) {
            $this->data['title'] = $review_info['title'];
        } else {
            $this->data['title'] = '';
        }
        if (isset($this->request->post['text'])) {
            $this->data['text'] = $this->request->post['text'];
        } elseif (!empty($review_info)) {
            $this->data['text'] = $review_info['text'];
        } else {
            $this->data['text'] = '';
        }

        if (isset($this->request->post['rating'])) {
            $this->data['rating'] = $this->request->post['rating'];
        } elseif (!empty($review_info)) {
            $this->data['rating'] = $review_info['rating'];
        } else {
            $this->data['rating'] = '';
        }
        if($review_info['images']){
            $this->data['images'] =$review_info['images'];
        }
        if (isset($this->request->post['support'])) {
            $this->data['support'] = $this->request->post['support'];
        } elseif (!empty($review_info)) {
            $this->data['support'] = $review_info['support'];
        } else {
            $this->data['support'] =0;
        }
        if (isset($this->request->post['against'])) {
            $this->data['against'] = $this->request->post['against'];
        } elseif (!empty($review_info)) {
            $this->data['against'] = $review_info['against'];
        } else {
            $this->data['against'] =0;
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (!empty($review_info)) {
            $this->data['status'] = $review_info['status'];
        } else {
            $this->data['status'] = 1;
        }
        
        if (isset($this->request->post['is_publish'])) {
            $this->data['is_publish'] = $this->request->post['is_publish'];
        } elseif (!empty($review_info)) {
            $this->data['is_publish'] = $review_info['is_publish'];
        } else {
            $this->data['is_publish'] = 0;
        }

        $this->template = 'catalog/review_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    public function import(){
        $this->document->setTitle('import product review');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => "import product review",
            'href'      => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );
        $this->data['heading_title'] ='import product review';
        $this->data['action'] =$this->url->link('catalog/review/upload', 'token=' . $this->session->data['token'], 'SSL');
        $this->template = 'catalog/upload_review.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    public function upload(){
        header("Content-type: text/html; charset=utf-8");
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('商品评论批量上传')); 
        $accpet_file = array('xls','xlsx');
        $file_name = $_FILES['uplaod_file']['name'];
        $file_type = substr($file_name,strrpos($file_name,'.')+1);
        if(!in_array($file_type,$accpet_file )){
            $this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/banner_import', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else{
            $store_array =array('0','52','53','54','55','56');
            require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
            if($_FILES['uplaod_file']['tmp_name']){
                $file_content =$this->getexcelcontent($_FILES['uplaod_file']['tmp_name']);
                $i=1;
                foreach($file_content as $info){
                    $sku =trim($info[1]);
                    $good_id =$this->get_product_id($sku);
                    if(!in_array($info[0],$store_array)){
                        $this->up_error[$i] =$i;
                        echo "第".$i."行语言store_id".$info[0]."不存在<br>";
                    }
                    elseif(!$good_id){
                        $this->up_error[$i] =$i;
                        echo "第".$i."行sku不存在<br>";
                    }else{
                       $query=$this->db->query("INSERT INTO ".DB_PREFIX."review  set store_id='".(int)trim($info[0])."',product_id='".(int)$good_id."',author='".$this->db->escape(trim($info[2]))."', 	title='".$this->db->escape(trim($info[3]))."',text='".$this->db->escape(trim($info[4]))."',rating='".(int)trim($info[5])."',date_added='".$this->db->escape(trim($info[6]))."',date_modified='".$this->db->escape(trim($info[6]))."',status='".(int)$info[7]."' ");
                    }
                  $i++;
                }
                $error_count =count($this->up_error);
                if($error_count>0){
                    $back_url =  $this->url->link('catalog/review/import', 'token=' . $this->session->data['token'], 'SSL'); 
                    echo "<p>共上传".($i-1)."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                }
                else{
                     $this->session->data['success'] ="共".($i-1)."条数据上传成功";
                    $this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL'));  
                }
            }
        }
    }

   
    public function down_templete(){
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Store(EN:0,DE:52,FR:54,ES:53,IT:55,PT:56)');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SKU');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'nickname');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'title');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '内容');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '评分');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'add time');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'status(1,启用 0,停用)');


        $objPHPExcel->getActiveSheet()->setTitle("商品评论批量上传模板");
        $objPHPExcel->setActiveSheetIndex(0);
         //Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品评论批量上传模板.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //$objWriter->save("D://gogo_beian.xlsx");
        //$objWriter->save('/home/www/www.myled.com/script/8月份销售单.xlsx');
        $objWriter->save('php://output');
        exit;
    }
    public function getexcelcontent($file){         
        $objReader = new PHPExcel_Reader_Excel2007(); 
        if(!$objReader->canRead($file)){
            $objReader = new PHPExcel_Reader_Excel5(); 
        }
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet();  
        
        $highestRow = $objWorksheet->getHighestRow();   
        $highestColumn = $objWorksheet->getHighestColumn();   
         
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
         
        $excelData = array();  
         
        for ($row = 2; $row <= $highestRow; ++$row) { 
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($content instanceof PHPExcel_RichText){    
                        $content = $content->__toString();  
                 }
                 if($col==6){
                   $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                   $content =gmdate("Y-m-d H:i:s", $content);
                }
                $excelData[$row][] = $content;
            }  
        }  
        return $excelData;  
    }

    protected function validateForm() {
        $this->load->model('catalog/product');
        if (!$this->user->hasPermission('modify', 'catalog/review')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        if (!$this->request->post['product_sku']) {
            $this->error['product'] = $this->language->get('error_product');
        }
        $product_info = $this->model_catalog_product->getProductBySkuAndLang($this->request->post['product_sku'],1);
        if (!$product_info) {
            $this->error['product'] = $this->language->get('error_no_product');
        }    
        if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
            $this->error['author'] = $this->language->get('error_author');
        }
        if (utf8_strlen($this->request->post['title']) < 1||utf8_strlen($this->request->post['title'])>100) {
            $this->error['title'] = $this->language->get('error_title');
        }
        if (utf8_strlen($this->request->post['text']) < 1) {
            $this->error['text'] = $this->language->get('error_text');
        }
    
        if (!isset($this->request->post['rating'])) {
            $this->error['rating'] = $this->language->get('error_rating');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/review')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    protected function get_product_id($sku){
        $query =$this->db->query("select product_id from oc_product where model='".$sku."' limit 1");
        $row =$query->row;
        if($row){
            return $row['product_id'];
        }
        else{
            return false;
        }
    }

   public function sendPoints($user_id,$points){
       if(isset($this->session->data['2nd_anniversary']) &&$this->session->data['2nd_anniversary']){
           $sql ="insert into ".DB_PREFIX."customer_reward set customer_id=".$user_id.",description='Double points for reviews, only in 2nd Anniversary',points='".$points."',points_spent=0,status=1,date_added=NOW(),date_confirm=NOW()";
       }
       else{
           $sql ="insert into ".DB_PREFIX."customer_reward set customer_id=".$user_id.",description='product reviews',points='".$points."',points_spent=0,status=1,date_added=NOW(),date_confirm=NOW()";
       }
        
        $this->db->query($sql);
   }

   public function update_reply_status(){
       $this->load->model('catalog/review');
       $reply_id =intval($this->request->post['reply_id']);
       $value =intval($this->request->post['value']);
       $json =array();
       $json['error'] =0;
       $this->model_catalog_review->updateReplyReview($reply_id,$value);
       $this->response->setOutput(json_encode($json));
   }
}
?>