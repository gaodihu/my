<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_tag extends CI_Controller {

    public $data=array();
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','common'));
        $this->load->library('form_validation');
        if(!isset($this->session->userdata['user_name'])){
            redirect(base_url('index.php/login/'));
        }
        $this->load->model('Custom_tag_model');
        if(isset($this->session->userdata['error_message'])){
            $this->data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $this->data['error_message'] ='';
        }
        $this->data['user_name'] =$this->session->userdata['user_name'];
        $this->data['is_guideline_custom'] =true;
        
        $is_guideline_custom_lang_id = $this->input->get('lang_id');
        $is_guideline_custom_lang_id = intval($is_guideline_custom_lang_id);
        $this->data['is_guideline_custom_lang_id'] = $is_guideline_custom_lang_id;
    }
	public function index()
	{   
        $this->load->library('pagination');
        $this->data['title'] ="Custom Tag";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Custom Tag',
             'href' =>'index.php/custom_tag',
             'sep'  =>false
        );
        
        $filter_custom_tag =$this->input->get('filter_custom_tag');
        
        $filter_custom_tag_start_time =$this->input->get('filter_custom_tag_start_time');
        $filter_custom_tag_end_time =$this->input->get('filter_custom_tag_end_time');
        $this->data['filter_custom_tag'] = $filter_custom_tag;
        $this->data['filter_custom_tag_start_time'] =$filter_custom_tag_start_time;
        $this->data['filter_custom_tag_end_time'] =$filter_custom_tag_end_time;
        
        $lang_id = $this->input->get('lang_id');
        $lang_id = intval($lang_id);
        $this->data['lang_id'] = $lang_id;
        
        if($this->input->get('page')){
            $page =intval($this->input->get('page'));
        }else{
            $page=1;
        }
        if($this->input->get('order')){
            $order =$this->input->get('order');
        }else{
            $order='tag_id';
        }
        if($this->input->get('sort')){
            $sort =$this->input->get('sort');
        }else{
            $sort='DESC';
        }
        $config['base_url'] = base_url('index.php/custom_tag/index');
        $config['per_page'] = 50;
        $config['reuse_query_string'] = true;
        $config['suffix'] = "&lang_id=".$lang_id."&filter_custom_tag=".urlencode($filter_custom_tag)."&filter_custom_tag_start_time=".$filter_custom_tag_start_time."&filter_custom_tag_end_time=".$filter_custom_tag_end_time;
        $filter_data =array(
            'filter_custom_tag'                =>  $filter_custom_tag,
            'lang_id'                          =>  $lang_id,
            'filter_custom_tag_start_time'     =>  $filter_custom_tag_start_time,
            'filter_custom_tag_end_time'       =>  $filter_custom_tag_end_time,
            'sort'                             =>  $sort,
            'order'                            =>  $order,
            'start'                            =>  ($page-1)*$config['per_page'],
            'offset'                           =>  $config['per_page'],
        );
        //进行查询内容计算总数
        $tag_total = $this->Custom_tag_model->get_count($filter_data);
        $config['total_rows'] = $tag_total['total'];
        $this->pagination->initialize($config);
         //传参数给VIEW
        $this->data['page_links'] = $this->pagination->create_links();

        $this->data['tag_lists'] = array();
        
        $tag_lists = $this->Custom_tag_model->get_all_tags($filter_data);
        
        $this->data['tag_lists'] = $tag_lists;
         //排序url
        if($sort == 'DESC'){
            $sort_url = "ASC";
        }
        else{
            $sort_url ="DESC";
        }
        $this->data['sort_id'] =base_url('index.php/custom_tag/index?lang_id='.$lang_id."&")."order=tag_id&sort=".$sort_url;
        $this->load->view('header',$this->data);
		$this->load->view('custom_tag_list');
        $this->load->view('footer');
	}
    public function add(){
        $this->data['title'] ="Add Custom Tag";
        $this->data['breadcrumbs'][] =array(
            'text' =>'Custom Tag',
            'href' =>'index.php/custom_tag',
            'sep'  =>true
        );
        $this->data['breadcrumbs'][] =array(
            'text' =>'Add Custom Tag',
             'href' =>'index.php/custom_tag/add',
             'sep'  =>false
        );
        $this->load->model('Custom_tag_model');
        $lang_id = $this->input->get('lang_id');
        $lang_id = intval($lang_id);
        $this->data['lang_id'] = $lang_id;
        if($this->input->post()){
            $this->form_validation->set_rules('content', 'content', 'required');
           
             if ($this->form_validation->run() == FALSE)
            {
                $message=validation_errors();
                $this->data['error_message'] =$message;
            }
            else
            {  
                $message ='';
                $post_data = $this->input->post();
                $content   = $post_data['content'];
                $tag_lists = explode("\n",$content);
                foreach($tag_lists as $item){
                    if(strpos($item,',')!==false){
                        $tag_arr = explode(',',$item); 
                        $_tag  = trim($tag_arr[0]);
                        $_link = trim($tag_arr[1]);
                        if($_tag && $_link ){
                            $this->Custom_tag_model->add_custom(array('tag'=>$_tag,'link'=>$_link,'lang_id'=>$lang_id));
                        }
                    }
                }
                
                redirect(base_url('index.php/custom_tag/index?lang_id='.$lang_id));
            }
        }
        $this->load->view('header',$this->data);
		$this->load->view('custom_tag_add');
        $this->load->view('footer');
    }

    public function delete(){
        $lang_id = $this->input->get('lang_id');
        $lang_id = intval($lang_id);
        $this->data['lang_id'] = $lang_id;
        $id =$this->input->get('id');
        $this->Custom_tag_model->delete($id);
        redirect(base_url('index.php/custom_tag/index?lang_id='.$lang_id));
    }
    public function all_del(){
        $lang_id = $this->input->get('lang_id');
        $lang_id = intval($lang_id);
        $this->data['lang_id'] = $lang_id;
        $faq_ids =$this->input->post('selected');
        foreach($faq_ids as $id){
             $this->Custom_tag_model->delete($id);
        }
        redirect(base_url('index.php/custom_tag/index?lang_id='.$lang_id));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */