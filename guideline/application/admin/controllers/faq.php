<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller {

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
        $this->load->model('Faq_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_faq'] =true;
        $this->data['is_faq_article'] =true;
    }
	public function index()
	{   
        $this->load->library('pagination');
        $this->data['title'] ="Faq List";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Faq List',
             'href' =>'index.php/faq',
             'sep'  =>false
        );
        
        $filter_faq_name =$this->input->get('filter_faq_name');
        $filter_faq_catagory =$this->input->get('filter_faq_catagory');
        $filter_faq_status =$this->input->get('filter_faq_status');
        $filter_faq_start_time =$this->input->get('filter_faq_start_time');
        $filter_faq_end_time =$this->input->get('filter_faq_end_time');
        $this->data['filter_faq_name'] =$filter_faq_name;
        $this->data['filter_faq_catagory'] =$filter_faq_catagory;
        $this->data['filter_faq_status'] =$filter_faq_status;
        $this->data['filter_faq_start_time'] =$filter_faq_start_time;
        $this->data['filter_faq_end_time'] =$filter_faq_end_time;
        if($this->input->get('page')){
            $page =intval($this->input->get('page'));
        }else{
            $page=1;
        }
        if($this->input->get('order')){
            $order =$this->input->get('order');
        }else{
            $order='faq_id';
        }
        if($this->input->get('sort')){
            $sort =$this->input->get('sort');
        }else{
            $sort='DESC';
        }
        $config['base_url'] = base_url('index.php/faq/index');
        $config['per_page'] =20;
        $filter_data =array(
            'filter_faq_name'           =>    $filter_faq_name,
            'filter_faq_catagory'       =>    $filter_faq_catagory,
            'filter_faq_status'           =>    $filter_faq_status,
            'filter_faq_start_time'      =>    $filter_faq_start_time,
            'filter_faq_end_time'       =>    $filter_faq_end_time,
            'sort'                           =>    $sort,
            'order'                         =>$order,
            'start'                          =>($page-1)*$config['per_page'],
            'offset'                         =>$config['per_page'],
        );
        //进行查询内容计算总数
        $faq_total = $this->Faq_model->get_count_faq_articles($filter_data);
        $config['total_rows'] = $faq_total['total'];
        $this->pagination->initialize($config);
         //传参数给VIEW
        $this->data['page_links'] = $this->pagination->create_links();

        $this->data['faq_lists'] =array();
        $faq_lists =$this->Faq_model->get_all_faqs($filter_data);
        $this->load->model('Faq_catagory_model');
        foreach($faq_lists as $faq){
            $faq['catagory_name']='';
            $catagory_info =$this->Faq_catagory_model->get_catagory_info($faq['faq_catagory_id']);
            if($catagory_info['parent_id']){
                $parent_info =$this->Faq_catagory_model->get_catagory_info($catagory_info['parent_id']);
            }
            if(isset($parent_info)&&$parent_info){
                $faq['catagory_name'] =$parent_info['catagory_name'].">>".$catagory_info['catagory_name'];
            }
            else{
                 $faq['catagory_name'] =$catagory_info['catagory_name'];
            }
            $this->data['faq_lists'][] =$faq;
        }

        //排序url
        if($sort=='DESC'){
            $sort_url ="ASC";
        }
        else{
            $sort_url ="DESC";
        }
        $this->data['sort_id'] =base_url('index.php/faq/index?')."order=faq_id&sort=".$sort_url;
        $this->load->view('header',$this->data);
		$this->load->view('faq_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="FAQ --Add a FAQ";
        $this->data['breadcrumbs'][] =array(
            'text' =>'FAQ',
             'href' =>'index.php/faq',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Add a FAQ',
             'href' =>'index.php/faq/add',
             'sep'  =>false
        );
        $this->load->model('Faq_catagory_model');
        $this->data['catagory_lists'] =$this->Faq_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('faq_catagory_id', 'catagory', 'required');
            //$this->form_validation->set_rules('url_path', 'SEO Url', 'required');
            $this->form_validation->set_rules('tag', 'Tag', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $post_data['url_path'] =get_url_path($post_data['title']);
                $this->Faq_model->add_faq($post_data);
                redirect(base_url('index.php/faq/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('faq_add');
        $this->load->view('footer');
    }
    public function update(){
        $faq_id =$this->input->get('id');
        $faq_info =$this->Faq_model->get_faq_info($faq_id);
        $this->data['title'] ="FAQ--Edit Faq";
        $this->data['breadcrumbs'][] =array(
            'text' =>'FAQ',
             'href' =>'index.php/faq',
             'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Edit Faq',
             'href' =>'index.php/faq/update?id='.$faq_id,
             'sep'  =>false
        );
        $this->data['faq_info'] =$faq_info;
        $this->load->model('Faq_catagory_model');
        $this->data['catagory_lists'] =$this->Faq_catagory_model->get_all_catagorys();
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('faq_catagory_id', 'catagory', 'required');
            //$this->form_validation->set_rules('url_path', 'SEO Url', 'required');
            $this->form_validation->set_rules('tag', 'Tag', 'required');
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data =$this->input->post();
                $post_data['url_path'] =get_url_path($post_data['title']);
                $this->Faq_model->edit_faq($post_data['faq_id'],$post_data);
                redirect(base_url('index.php/faq/'));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('faq_edit');
        $this->load->view('footer');
    }
    
    
    public function delete(){
        $faq_id =$this->input->get('id');
        $this->Faq_model->delete($faq_id);
        redirect(base_url('index.php/faq/'));
    }
    public function all_del(){
        $faq_ids =$this->input->post('selected');
        foreach($faq_ids as $id){
             $this->Faq_model->delete($id);
        }
        redirect(base_url('index.php/faq/'));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */