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

	public function index()
	{  
        $this->load->helper('string');
        $this->load->model('faq_model');
        $this->load->library('pagination');
        $this->load->model('applications_model');
        //得到所有分类
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $catagory_url =$this->uri->segment(3);
        if($catagory_url){
            $catagory_info =$this->faq_model->get_faq_catagory_info_by_url($catagory_url);
            $catagory_id =$catagory_info['catagory_id'];
            if(!$catagory_info){
                show_404();
            }
            $data['title'] =$catagory_info['catagory_name'];
            $data['description'] =$catagory_info['meta_description'];
            $data['keywords'] =$catagory_info['meta_keyword'];
            $data['catagory_info'] =$catagory_info;

            $breadcrumbs[] =array(
                'text' =>'FAQ',
                'href'  =>base_url("faq.html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>$catagory_info['catagory_name'],
                'href'  =>base_url('faq/c/'.$catagory_url.".html"),
                'sep'  =>false
            );
            $config['base_url'] = base_url('faq/c/'.$catagory_url.".html");
        }
        else{
            $catagory_id =0;
            $data['title'] ='MyLED FAQs, Basic Information And Use Helps';
            $data['description'] ='Get The Most LED Lights Information Here, The Various LED FAQs Will Tell You The Advantages of LED, The LED Basic Terms, The Useful Helps And Others. ';
            $data['keywords'] ='LED FAQ, LED Information, LED Helps';
            $breadcrumbs[] =array(
                'text' =>'FAQ',
                'href'  =>base_url("faq.html"),
                'sep'  =>true
            );
            $config['base_url'] = base_url("faq.html");
        }
        
        
        
        if($this->input->get('page')){
            $page =intval($this->input->get('page'));
        }else{
            $page=1;
        }
        $config['per_page'] =10;
        $filter_data =array(
            'catagory_id'  =>$catagory_id,
            'start' =>($page-1)*$config['per_page'],
            'offset' =>$config['per_page'],
        );
        
        //进行查询内容计算总数
        $faq_total = $this->faq_model->get_count_faq_articles($filter_data);
        $config['total_rows'] = $faq_total['total'];
        $this->pagination->initialize($config);
         //传参数给VIEW
        $data['page_links'] = $this->pagination->create_links();
        $data['catagory_id'] = $catagory_id;
        $data['faq_catagorys'] =$this->faq_model->get_all_faq_catagorys();
        $data['faq_articles'] =$this->faq_model->get_all_faq_article($filter_data);
        $data['breadcrumbs'] =$breadcrumbs;
        $this->load->view('common/header',$data);
        $this->load->view('common/breadcrumb');
		$this->load->view('faq');
        $this->load->view('common/footer');
	}
   
    public function info()
	{  
        $this->load->helper('string');
        $this->load->helper('url');
        $this->load->model('faq_model');
        $this->load->model('applications_model');
        //得到所有分类
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $faq_url =$this->uri->segment(2);
        $faq_info=$this->faq_model->get_faq_article_by_url($faq_url);
        if(!$faq_info){
            show_404();
        }
        $this->load->model('custom_tag_model');
        
        $custom_tag = $this->custom_tag_model->get_all_tags();

        $content = $faq_info['content'];
        $_count = 0;
        foreach($custom_tag as $_item){
            if($_count > 5){
                break;
            }
            $_tag  = ' '.$_item['tag'].' ';
            $_link = $_item['link'];
            $_pos = strpos(strtolower($content),  strtolower($_tag));
            if($_pos !== false){
                $_replace = '<a target="_blank" href="'.$_link .'">' . $_tag . "</a>";
                $content = substr_replace($content,$_replace,$_pos,  strlen($_tag));
                $_count ++;
            }
        }
        $faq_info['content'] = $content;
 
        
        $faq_id =$faq_info['faq_id'];
        $data['title'] =$faq_info['title'];
        $data['description'] =$faq_info['meta_description'];
        $data['keywords'] =$faq_info['meta_keyword'];
        $data['faq_info'] =$faq_info;
        $category_id =$faq_info['faq_catagory_id'];
        $parent_catagory_info =array();
        $catagory_info =$this->faq_model->get_faq_catagory_info($category_id);
        if($catagory_info['level']==2){
            $parent_catagory_info =$this->faq_model->get_faq_catagory_info($catagory_info['category_id']);
        }
        $breadcrumbs[] =array(
                'text' =>'FAQ',
                'href'  =>base_url("faq.html"),
                'sep'  =>true
            );
        if($parent_catagory_info){
            $breadcrumbs[] =array(
                'text' =>$parent_catagory_info['catagory_name'],
                'href'  =>base_url("faq/c/".$parent_catagory_info['url_path'].".html"),
                'sep'  =>true
            );
        }
        $breadcrumbs[] =array(
            'text' =>$catagory_info['catagory_name'],
            'href'  =>base_url("faq/c/".$catagory_info['url_path'].".html"),
            'sep'  =>true
        );
        $breadcrumbs[] =array(
            'text' =>$faq_info['title'],
            'href'  =>base_url("faq/".$faq_info['url_path'].".html"),
            'sep'  =>false
        );
        $data['catagory_id'] =$category_id;
        $data['faq_catagorys'] =$this->faq_model->get_all_faq_catagorys();
        
        $related_info =array();
        //得到同类别的其他faq
        
        $related_ids =$this->faq_model->get_related_faq($category_id,$faq_id);
       //分页
       /*
        $this->load->library('pagination');
        $config['per_page'] = 3;
        if($this->input->get('page')){
            $page =intval($this->input->get('page'));
        }else{
            $page=1;
        }
        $config['base_url'] = base_url("faq/".$faq_info['url_path'].".html");
        $config['total_rows'] = count($related_ids);
        $this->pagination->initialize($config);
         //传参数给VIEW
        $data['page_links'] = $this->pagination->create_links();
         //得到相关性FAQ
         $array_start =($page-1)*$config['per_page'];
         $array_end =$array_start+$config['per_page'];
        
        foreach($related_ids as $key=>$r_id){
            if($key>=$array_start&&$key<$array_end){
                $related_info[] =$r_id;
            }
        }
         */
        $data['related_info'] =$related_ids;
        $data['breadcrumbs'] =$breadcrumbs;
        $this->load->view('common/header',$data);
        $this->load->view('common/breadcrumb');
		$this->load->view('faq_info');
        $this->load->view('common/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */