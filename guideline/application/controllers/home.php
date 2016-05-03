<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
        $this->load->helper('image');
        $this->load->model('banner_model');
        $this->load->model('faq_model');
        $this->load->model('applications_model');
        //得到所有分类
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $data['title'] ='MyLED.com GuideLine & Applications';
        $data['description'] ='MyLED.com Guideline, FAQ, LED applications and LED designs';
        $data['keywords'] ='LED applications and designs, LED Guideline, LED decorations';
        $data['flash_banner_list'] =$this->banner_model->get_banners_by_code('home_flash_banner');
        $data['featured_banner_list'] =array();
        $featured_banner_list   =$this->banner_model->get_banners_by_code('home_featured_product');
        //大小合适，不进行压缩
        /*
        $width=390;$height=150;
        foreach($featured_banner_list as $banner){
            
            $source_image = ROOT_PATH.$banner['banner_image'];
            $info = pathinfo($source_image);
            $new_image = ROOT_PATH.'images/thumb/b'.$banner['banner_id'].'-'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
            if(!file_exists($new_image)){
                 img2thumb($source_image,$new_image,$width,$height);
            }
            $banner['banner_image'] ='images/thumb/b'.$banner['banner_id'].'-'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
            $data['featured_banner_list'][]=$banner;
        }
        */
        $data['featured_banner_list']=$featured_banner_list;
        //得到最新的20条faq
        $filter_faq_data =array(
            'start' =>0,
            'offset' =>20
        );
        $data['lasted_faqs'] =$this->faq_model->get_all_faq_article($filter_faq_data);
        //得到最新的2条application
        $filter_application_data =array(
            'start' =>0,
            'offset' =>3
        );
        $data['lasted_applications'] =array();
        
        $lasted_applications=$this->applications_model->get_all_application_articles($filter_application_data);
        $width =420;
        $height =260;
        foreach($lasted_applications as $app){
            
            $source_image = ROOT_PATH.$app['effect_image'];
            if(is_file($source_image)){
                $info = pathinfo($source_image);
                $new_image = ROOT_PATH.'images/thumb/app'.$app['article_id'].'-'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
                if(!file_exists($new_image)){
                     img2thumb($source_image,$new_image,$width,$height);
                }
                $app['effect_image'] ='images/thumb/app'.$app['article_id'].'-'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
            }
            $data['lasted_applications'][]=$app;
        }
        
        
        $this->load->view('common/header',$data);
		$this->load->view('home');
        $this->load->view('common/footer');
	}

    public function getLasterBlogs(){
        $this->load->model('blog_model');
        $this->load->helper('string');
        //读取最新的5条blog
        $lasted_blog=$this->blog_model->get_laster_blog(3);
        $laster_blog_array =array();
        foreach($lasted_blog as $blog){
            $preg = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/i";
            preg_match_all($preg,$blog['post_content'],$res);
            $f_img_src =$res[1][0];
            $blog_content =strip_tags(preg_replace($preg,'',$blog['post_content']));
            $url_path = preg_replace('/[^\d\w\-]/','-',strtolower(trim($blog['post_title'])));
            $url_path = preg_replace('/(\-+)/','-',$url_path);
            $laster_blog_array[] =array(
                'f_img_src' =>$f_img_src,
                'url_path' =>'https://www.myled.com/ledblog/'.$url_path.'/',
                'title' =>$blog['post_title'],
                'content' =>sub($blog_content,400),
                'post_date' =>$blog['post_date']
            );
        }
        $data['laster_blog_array'] =$laster_blog_array;
       
        $string =$this->load->view('common/blog',$data,true);
        echo json_encode($string);

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */