<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Information extends CI_Controller {

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
        $this->load->model('Information_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_information'] =true;
        $this->data['is_information_list'] =true;
    }
	public function index()
	{   
        $this->data['title'] ="Guideline Information";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Information',
             'href' =>'index.php/information',
             'sep'  =>false
        );
        
        $this->data['information_lists'] =$this->Information_model->get_informations();
        $this->load->view('header',$this->data);
		$this->load->view('information_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="Guideline Information--Add Information";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline Information',
             'href' =>'index.php/information',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Add Information',
             'href' =>'index.php/information/add',
             'sep'  =>false
        );
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $this->Information_model->add_information($post_data);
                redirect(base_url('index.php/information/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('information_add');
        $this->load->view('footer');
    }
    public function update(){
        $info_id =$this->input->get('id');
        $information_info =$this->Information_model->get_information_info($info_id);
        $this->data['title'] ="Guideline information--Edit information";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Guideline information',
             'href' =>'index.php/information',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Edit information',
             'href' =>'index.php/information/update?id='.$info_id,
             'sep'  =>false
        );
        $this->data['information_info'] =$information_info;
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $this->Information_model->edit_information($post_data['info_id'],$post_data);
                redirect(base_url('index.php/information/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('information_edit');
        $this->load->view('footer');
    }
    
    
    public function delete(){
        $info_id =$this->input->get('id');
        //检查是否可以删除
        $valid_del =$this->validateDel($catagory_id);
        if($valid_del){
                $this->session->set_userdata('error_message',$valid_del);
        }
        else{
            $this->Information_model->delete($catagory_id);
        }
        
        redirect(base_url('index.php/information/'));
    }
    public function all_del(){
        $catagory_ids =$this->input->post('selected');
        foreach($type_ids as $id){
            if(!$this->validateDel($id)){
                $this->Information_model->delete($catagory_id);
            }
        }
        redirect(base_url('index.php/information/'));
    }
    
   
   public function validateDel($catagory_id){
        $catagory_info =$this->Information_model->get_catagory_info($catagory_id);
        $error_message ='';
        if($catagory_info['level']==1){
            $child =$this->Information_model->get_catagory_by_parent_id($catagory_id);
            if($child){
               $error_message =$catagory_info['catagory_name'].'该分类下还存在子分类，请删除子分类后重试！';
            }
        }
        $catagory_articles =$this->Information_model->get_catagory_articles($catagory_id);
        if($catagory_articles){
            $error_message ='<br>'.$catagory_info['catagory_name'].'该分类下还存在文章信息，请删除后重试！';
        }
         return $error_message;
   }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */