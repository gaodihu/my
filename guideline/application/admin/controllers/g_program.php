<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class G_program extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public $data=array();
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','common'));
        $this->load->library('form_validation');
        if(!isset($this->session->userdata['user_name'])){
            redirect(base_url('index.php/login/'));
        }
        $this->load->model('Guideline_program_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_guideline'] =true;
        $this->data['is_guideline_program'] =true;
    }
	public function index()
	{   
        $this->load->library('pagination');
        $this->data['title'] ="Guideline Program";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Program',
             'href' =>'index.php/g_program',
             'sep'  =>false
        );
        
        $filter_article_name =$this->input->get('filter_article_name');
        $filter_article_catagory =$this->input->get('filter_article_catagory');
        $filter_article_catagory_name =$this->input->get('filter_article_catagory_name');
        $filter_article_status =$this->input->get('filter_article_status');
        $filter_article_start_time =$this->input->get('filter_article_start_time');
        $filter_article_end_time =$this->input->get('filter_article_end_time');
        $this->data['filter_article_name'] =$filter_article_name;
        $this->data['filter_article_catagory'] =$filter_article_catagory;
        $this->data['filter_article_catagory_name'] =$filter_article_catagory_name;
        $this->data['filter_article_status'] =$filter_article_status;
        $this->data['filter_article_start_time'] =$filter_article_start_time;
        $this->data['filter_article_end_time'] =$filter_article_end_time;
        if($this->input->get('page')){
            $page =intval($this->input->get('page'));
        }else{
            $page=1;
        }
        if($this->input->get('order')){
            $order =$this->input->get('order');
        }else{
            $order='article_id';
        }
        if($this->input->get('sort')){
            $sort =$this->input->get('sort');
        }else{
            $sort='DESC';
        }
        $config['base_url'] = base_url('index.php/g_program/index');
        $config['per_page'] = 10;
        $filter_data =array(
            'filter_article_name'           =>    $filter_article_name,
            'filter_article_catagory'       =>    $filter_article_catagory,
            'filter_article_catagory_name'       =>   $filter_article_catagory_name,
            'filter_article_status'           =>    $filter_article_status,
            'filter_article_start_time'      =>    $filter_article_start_time,
            'filter_article_end_time'       =>    $filter_article_end_time,
            'sort'                           =>    $sort,
            'order'                         =>$order,
            'start'                          =>($page-1)*$config['per_page'],
            'offset'                         =>$config['per_page'],
        );
        //进行查询内容计算总数
        $article_total = $this->Guideline_program_model->get_count_articles($filter_data);
        $config['total_rows'] = $article_total['total'];
        $this->pagination->initialize($config);
         //传参数给VIEW
        $this->data['page_links'] = $this->pagination->create_links();

        $this->data['article_lists'] =array();
        $this->load->model('Guideline_catagory_model');
        $article_lists =$this->Guideline_program_model->get_all_articles($filter_data);
        foreach($article_lists as $article){
            $article['catagory_name']='';
            $catagory_info =$this->Guideline_catagory_model->get_catagory_info($article['app_catagory_id']);
            if($catagory_info['parent_id']){
                $parent_info =$this->Guideline_catagory_model->get_catagory_info($catagory_info['parent_id']);
            }
            if(isset($parent_info)&&$parent_info){
                $article['catagory_name'] =$parent_info['catagory_name'].">>".$catagory_info['catagory_name'];
            }
            else{
                 $article['catagory_name'] =$catagory_info['catagory_name'];
            }
            $this->data['article_lists'][] =$article;
        }
         //排序url
        if($sort=='DESC'){
            $sort_url ="ASC";
        }
        else{
            $sort_url ="DESC";
        }
        $this->data['sort_id'] =base_url('index.php/g_program/index?')."order=article_id&sort=".$sort_url;
        $this->load->view('header',$this->data);
		$this->load->view('g_program_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="Guideline Program--Add program";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Program',
             'href' =>'index.php/g_program',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Add Program',
             'href' =>'index.php/g_program/add',
             'sep'  =>false
        );
        $this->load->model('Guideline_catagory_model');
        $this->data['catagory_lists'] =$this->Guideline_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('app_catagory_id', 'catagory', 'required');
            $this->form_validation->set_rules('tag', 'Tag', 'required');
            //$this->form_validation->set_rules('url_path', 'SEO Url', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $url_path =trim($post_data['title']);
                $url_path=get_url_path($url_path);
                $post_data['url_path'] =$url_path;
                if(!empty($_FILES['effect_image']['name'])){
                    $file_path =ROOT_PATH.'images/g_catagory/applications/';
                    $new_name = $_FILES['effect_image']['name'];
                    $new_file_path =$file_path.$new_name;
                    if(!is_dir($file_path)){
                        mkdir($file_path,0777,1);
                    }
                    $ext = substr($new_name, strrpos($new_name, '.'));
                    $image_type_allow =$this->config->item('image_allow_type');
                    if(in_array($ext,$image_type_allow)){
                        if(file_exists($new_file_path)){
                            $name = substr($new_name, 0, strrpos($new_name, '.'));
                            $new_name = $name."_".time().$ext;
                            $new_file_path =$file_path.$new_name;
                        }
                        if(move_uploaded_file($_FILES['effect_image']['tmp_name'],$new_file_path)){
                            $post_data['image'] ="images/g_catagory/applications/".$new_name;
                        }
                        else{
                            $message.="<br>图片上传错误";
                        }
                    }
                    else{
                        $message.="<br>图片格式错误";
                    }
                    
                    if(!$message){
                        $this->data['error_message'] ='sucess';
                    }
                    
                }
                $this->Guideline_program_model->add_program($post_data);
                redirect(base_url('index.php/g_program/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('g_program_add');
        $this->load->view('footer');
    }
    public function update(){
        $article_id =$this->input->get('id');
        $program_info =$this->Guideline_program_model->get_program_info($article_id);
        $article_products =$this->Guideline_program_model->get_program_products($article_id);
        $article_products_in =array();
        if($article_products){
            foreach($article_products as $pro){
                $article_products_in[] =$pro['product_sku'];
            }
        }
        $this->data['title'] ="Guideline Program--Edit Program";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Program',
             'href' =>'index.php/g_program',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Edit Program',
             'href' =>'index.php/g_program/update?id='.$article_id,
             'sep'  =>false
        );
        $this->data['program_info'] =$program_info;
        $this->data['program_products'] =implode(',',$article_products_in);
        $this->load->model('Guideline_catagory_model');
        $this->data['catagory_lists'] =$this->Guideline_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('app_catagory_id', 'catagory', 'required');
            $this->form_validation->set_rules('tag', 'Tag', 'required');
            //$this->form_validation->set_rules('url_path', 'SEO Url', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $url_path =trim($post_data['title']);
                $url_path=get_url_path($url_path);
                $post_data['url_path'] ='a'.$article_id."-".$url_path;
                if(!empty($_FILES['effect_image']['name'])){
                    $file_path =ROOT_PATH.'images/g_catagory/applications/';
                    $new_name = $_FILES['effect_image']['name'];
                    $new_file_path =$file_path.$new_name;
                    if(!is_dir($file_path)){
                        mkdir($file_path,0777,1);
                    }
                    $ext = substr($new_name, strrpos($new_name, '.'));
                    $image_type_allow =$this->config->item('image_allow_type');
                    if(in_array($ext,$image_type_allow)){
                        if(file_exists($new_file_path)){
                            $name = substr($new_name, 0, strrpos($new_name, '.'));
                            $new_name = $name."_".time().$ext;
                            $new_file_path =$file_path.$new_name;
                        }
                        if(move_uploaded_file($_FILES['effect_image']['tmp_name'],$new_file_path)){
                            $post_data['image'] ="images/g_catagory/applications/".$new_name;
                        }
                        else{
                            $message.="<br>图片上传错误";
                        }
                    }
                    else{
                        $message.="<br>图片格式错误";
                    }
                    
                    if(!$message){
                        $this->data['error_message'] ='sucess';
                    }
                    
                }
                $this->Guideline_program_model->edit_program($post_data['article_id'],$post_data);
                redirect(base_url('index.php/g_program/update?id='.$post_data['article_id']));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('g_program_edit');
        $this->load->view('footer');
    }
    
    
    public function delete(){
        $id =$this->input->get('id');
        $this->Guideline_program_model->delete($id);
        redirect(base_url('index.php/g_program/'));
    }
    public function all_del(){
        $faq_ids =$this->input->post('selected');
        foreach($faq_ids as $id){
             $this->Guideline_program_model->delete($id);
        }
        redirect(base_url('index.php/g_program/'));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */