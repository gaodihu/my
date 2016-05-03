<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends CI_Controller {

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
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if(!isset($this->session->userdata['user_name'])){
            redirect(base_url('index.php/login/'));
        }
        $this->load->model('Banner_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_banner'] =true;
        $this->data['is_banner_upload'] =true;
    }
	public function index()
	{   
        $this->data['title'] ="Banner";
        $this->data['breadcrumbs'][] =array(
            'text' =>'banner',
             'href' =>'index.php/banner',
             'sep'  =>false
        );
        $this->data['banner_type_list'] =$this->Banner_model->get_all_type();
        $this->load->view('header',$this->data);
		$this->load->view('banner_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="Banner--add banner";
        $this->data['breadcrumbs'][] =array(
            'text' =>'banner',
             'href' =>'index.php/banner',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'add banner',
             'href' =>'index.php/banner/add',
             'sep'  =>false
        );
        if($this->input->post()){
            $this->form_validation->set_rules('banner_name', 'banner name', 'required');
            $this->form_validation->set_rules('banner_code', 'banner code', 'required');
            $this->form_validation->set_rules('width', 'width', 'required');
            $this->form_validation->set_rules('height', 'height', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                if(!empty($_FILES['banner_info']['name'])){
                    $file_path =ROOT_PATH.'images/banner/';
                    if(!is_dir($file_path)){
                        mkdir($file_path,0777,1);
                    }
                    $count =count($_FILES['banner_info']['name']['image']);
                    for($i=0;$i<$count;$i++){
                        $new_name = $_FILES['banner_info']['name']['image'][$i];
                        $ext = substr($new_name, strrpos($new_name, '.'));
                        $image_type_allow =$this->config->item('image_allow_type');
                        if(in_array($ext,$image_type_allow)){
                            $new_file_path =$file_path.$new_name;
                            if(file_exists($new_file_path)){
                                $name = substr($new_name, 0, strrpos($new_name, '.'));
                                $new_name = $name."_".time().$ext;
                                $new_file_path =$file_path.$new_name;
                            }

                            if(move_uploaded_file($_FILES['banner_info']['tmp_name']['image'][$i],$new_file_path)){
                                $post_data['banner_info']['image'][$i] ="images/banner/".$new_name;
                            }
                            else{
                                $message.="<br>第".$i."个图片上传错误";
                            }
                        }
                        else{
                            $message.="<br>第".$i."个图片格式错误";
                        }
                    }
                    if(!$message){
                        $this->data['error_message'] ='sucess';
                    }
                    
                }
                $this->Banner_model->add_banner($post_data);
                redirect(base_url('index.php/banner/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('banner_form');
        $this->load->view('footer');
    }
    public function update(){
        $type_id =$this->input->get('id');
        $banner_info =$this->Banner_model->get_banner_info($type_id);
        $this->data['title'] ="Banner--add banner";
        $this->data['breadcrumbs'][] =array(
            'text' =>'banner',
             'href' =>'index.php/banner',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'update banner',
             'href' =>'index.php/banner/update?id='.$type_id,
             'sep'  =>false
        );
        $this->data['banner_info'] =$banner_info;
        if($this->input->post()){
            $this->form_validation->set_rules('banner_name', 'banner name', 'required');
            $this->form_validation->set_rules('banner_code', 'banner code', 'required');
            $this->form_validation->set_rules('width', 'width', 'required');
            $this->form_validation->set_rules('height', 'height', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                if(!empty($_FILES['banner_info']['name'])){
                    $file_path =ROOT_PATH.'images/banner/';
                    if(!is_dir($file_path)){
                        mkdir($file_path,0777,1);
                    }
                    $count =count($_FILES['banner_info']['name']['image']);
                    for($i=0;$i<$count;$i++){
                        $new_name = $_FILES['banner_info']['name']['image'][$i];
                        if($new_name){
                            $ext = substr($new_name, strrpos($new_name, '.'));
                            $image_type_allow =$this->config->item('image_allow_type');
                            if(in_array($ext,$image_type_allow)){
                                $new_file_path =$file_path.$new_name;
                                if(file_exists($new_file_path)){
                                    $name = substr($new_name, 0, strrpos($new_name, '.'));
                                    $new_name = $name."_".time().$ext;
                                    $new_file_path =$file_path.$new_name;
                                }

                                if(move_uploaded_file($_FILES['banner_info']['tmp_name']['image'][$i],$new_file_path)){
                                    $post_data['banner_info']['image'][$i] ="images/banner/".$new_name;
                                }
                                else{
                                    $message.="<br>第".$i."个图片上传错误";
                                }
                            }else{
                                $message.="<br>第".$i."个图片格式错误";
                            }
                            
                        }
                    }
                    if(!$message){
                        $this->data['error_message'] ='sucess';
                    }
                    
                }
                $this->Banner_model->update_banner($type_id,$post_data);
                redirect(base_url('index.php/banner/update?id='.$type_id));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('banner_form');
        $this->load->view('footer');
    }

    public function delete_type(){
        $type_id =$this->input->get('id');
        $this->Banner_model->del_banner_type($type_id);
        redirect(base_url('index.php/banner/'));
    }
    public function all_del_type(){
        $type_ids =$this->input->post('selected');
        foreach($type_ids as $id){
            $this->Banner_model->del_banner_type($id);
        }
        redirect(base_url('index.php/banner/'));
    }

    public function delete_banner_img(){
        $banner_id =$this->input->post('banner_id');
        $this->Banner_model->del_banner_img($banner_id);
    }
   

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */