<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class G_catagory extends CI_Controller {

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
        $this->load->model('Guideline_catagory_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_guideline'] =true;
        $this->data['is_guideline_catalog'] =true;
    }
	public function index()
	{   
        $this->data['title'] ="Guideline Catagory";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Catagory',
             'href' =>'index.php/g_catagory',
             'sep'  =>false
        );
        $catagory_list =$this->Guideline_catagory_model->get_all_catagorys();
        $this->data['catagory_lists'] =array();
        foreach($catagory_list as $catagory){
            $cata_article_count =0;
            foreach($catagory['child'] as $key=>$child){
                $child_article_count =count($this->Guideline_catagory_model->get_catagory_articles($child['catagory_id']));
                $catagory['child'][$key]['article_count'] =$child_article_count;
                $cata_article_count+=$child_article_count;
            }
            $catagory['article_count'] =$cata_article_count;
            $this->data['catagory_lists'][] =$catagory;
        }
        //$this->data['catagory_lists'] =$this->Guideline_catagory_model->get_all_catagorys();
        $this->load->view('header',$this->data);
		$this->load->view('g_catagory_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="Guideline Catagory--add catagory";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Catagory',
             'href' =>'index.php/g_catagory',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'add catagory',
             'href' =>'index.php/g_catagory/add',
             'sep'  =>false
        );
        
        $this->data['catagory_lists'] =$this->Guideline_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('catagory_name', 'catagory name', 'required');
            $this->form_validation->set_rules('parent_id', 'parent catagory', 'required');
            //$this->form_validation->set_rules('url_path', 'url path', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $post_data['url_path'] =get_url_path($post_data['catagory_name']);
                if(!empty($_FILES['image']['name'])){
                    $file_path =ROOT_PATH.'images/g_catagory/';
                    $new_name = $_FILES['image']['name'];
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
                        if(move_uploaded_file($_FILES['image']['tmp_name'],$new_file_path)){
                            $post_data['image'] ="images/g_catagory/".$new_name;
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
                $this->Guideline_catagory_model->add_catagory($post_data);
                redirect(base_url('index.php/g_catagory/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('g_catagory_add');
        $this->load->view('footer');
    }
    public function update(){
        $catagory_id =$this->input->get('id');
        $catagory_info =$this->Guideline_catagory_model->get_catagory_info($catagory_id);
        $this->data['title'] ="Guideline Catagory--Edit Catagory";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Catagory',
             'href' =>'index.php/g_catagory',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Edit Catagory',
             'href' =>'index.php/g_catagory/update?id='.$catagory_id,
             'sep'  =>false
        );
        $this->data['catagory_info'] =$catagory_info;
        $this->data['catagory_lists'] =$this->Guideline_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('catagory_name', 'catagory name', 'required');
            $this->form_validation->set_rules('parent_id', 'parent catagory', 'required');
            //$this->form_validation->set_rules('url_path', 'url path', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $post_data['url_path'] =get_url_path($post_data['catagory_name']);
               if(!empty($_FILES['image']['name'])){
                    $file_path =ROOT_PATH.'images/g_catagory/';
                    $new_name = $_FILES['image']['name'];
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
                        if(move_uploaded_file($_FILES['image']['tmp_name'],$new_file_path)){
                            $post_data['image'] ="images/g_catagory/".$new_name;
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
                    else{
                        $this->data['error_message'] =$message;
                    }
                    
                }
                $this->Guideline_catagory_model->edit_catagory($post_data['catagory_id'],$post_data);
                redirect(base_url('index.php/g_catagory/update?id='.$post_data['catagory_id']));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('g_catagory_edit');
        $this->load->view('footer');
    }
    
    
    public function delete(){
        $catagory_id =$this->input->get('id');
        //检查是否可以删除
        $valid_del =$this->validateDel($catagory_id);
        if($valid_del){
                $this->session->set_userdata('error_message',$valid_del);
        }
        else{
            $this->Guideline_catagory_model->delete($catagory_id);
        }
        
        redirect(base_url('index.php/g_catagory/'));
    }
    public function all_del(){
        $catagory_ids =$this->input->post('selected');
        foreach($type_ids as $id){
            if(!$this->validateDel($id)){
                $this->Guideline_catagory_model->delete($catagory_id);
            }
        }
        redirect(base_url('index.php/g_catagory/'));
    }
    
   
   public function validateDel($catagory_id){
        $catagory_info =$this->Guideline_catagory_model->get_catagory_info($catagory_id);
        $error_message ='';
        if($catagory_info['level']==1){
            $child =$this->Guideline_catagory_model->get_catagory_by_parent_id($catagory_id);
            if($child){
               $error_message =$catagory_info['catagory_name'].'该分类下还存在子分类，请删除子分类后重试！';
            }
        }
        $catagory_articles =$this->Guideline_catagory_model->get_catagory_articles($catagory_id);
        if($catagory_articles){
            $error_message ='<br>'.$catagory_info['catagory_name'].'该分类下还存在文章信息，请删除后重试！';
        }
         return $error_message;
   }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */