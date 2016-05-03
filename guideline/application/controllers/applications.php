<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends CI_Controller {

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
        $this->load->model('applications_model');
        $data['title'] ='MyLED Guideline Applications And Designs';
        $data['description'] ='Here are the best LED applications and designs,and only on MyLED.com can you find the best LED products to match these applications.';
        $data['keywords'] ='MyLED Designs, MyLED Applications, LED Guide Application';
        $breadcrumbs[] =array(
                'text' =>'Guideline',
                'href'  =>base_url("guideline.html"),
                'sep'  =>true
        );
        $breadcrumbs[] =array(
            'text' =>'Applications',
            'href'  =>base_url("applications.html"),
            'sep'  =>false
        );
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $data['breadcrumbs'] =$breadcrumbs;
        $this->load->view('common/header',$data);
        $this->load->view('common/breadcrumb');
		$this->load->view('applications');
        $this->load->view('common/footer');
	}
    public function catagory()
	{  
        $this->load->helper('string');
        $this->load->model('applications_model');
        
        //得到所有分类
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        //得到具体分类ID
        $catagory_url =$this->uri->segment(3);
        if($this->uri->segment(4)){
            $catagory_url =$this->uri->segment(3)."/".$this->uri->segment(4);
        }
        $catagory_info =$this->applications_model->get_applications_info_by_url($catagory_url);
        if(!$catagory_info){
            show_404();
        }
        $catagory_id =$catagory_info['catagory_id'];
        $data['title'] =$catagory_info['catagory_name'];
        $data['description'] =$catagory_info['meta_description'];
        $data['keywords'] =$catagory_info['meta_keyword'];
        if($catagory_info['level']==1){
            $data['catagory_info'] =$catagory_info;
            //得到下属所有分类
            $child_catagory_info =$this->applications_model->get_applications_by_parent_id($catagory_id);
            $data['child_catagory_info'] =$child_catagory_info;
            $breadcrumbs[] =array(
                'text' =>'Guideline',
                'href'  =>base_url("guideline.html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>'Applications',
                'href'  =>base_url("applications.html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>$catagory_info['catagory_name'],
                'href'  =>base_url("applications/c/".$catagory_info['url_path'].".html"),
                'sep'  =>false
            );
            $data['breadcrumbs'] =$breadcrumbs;
            $this->load->view('common/header',$data);
            $this->load->view('common/breadcrumb');
            $this->load->view('applications_view');
            $this->load->view('common/footer');
        }
        elseif($catagory_info['level']==2){
            $data['catagory_info'] =$catagory_info;
            $parent_catagory_id =$catagory_info['parent_id'];
            $parent_catagory_info =$this->applications_model->get_applications_info($parent_catagory_id);
            $data['parent_catagory_info'] =$parent_catagory_info;
            $breadcrumbs[] =array(
                'text' =>'Guideline',
                'href'  =>base_url("guideline.html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>'Applications',
                'href'  =>base_url("applications.html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>$parent_catagory_info['catagory_name'],
                'href'  =>base_url("applications/c/".$parent_catagory_info['url_path'].".html"),
                'sep'  =>true
            );
            $breadcrumbs[] =array(
                'text' =>$catagory_info['catagory_name'],
                'href'  =>base_url("applications/c/".$catagory_info['url_path'].".html"),
                'sep'  =>false
            );
            $data['breadcrumbs'] =$breadcrumbs;
            //得到该分类下的方案
            $filter =array(
                'catagory_id' => $catagory_id,
                'start' =>0,
                'offset' =>10
            );
            $data['articles_list'] =$this->applications_model->get_all_application_articles($filter);
            $this->load->view('common/header',$data);
            $this->load->view('common/breadcrumb');
            $this->load->view('applications_info');
            $this->load->view('common/footer');
        }
	}

    public function detail(){
        $this->load->model('applications_model');
        //得到prograom 的url_path
        $article_url =$this->uri->segment(2);
        $article_info=$this->applications_model->get_article_info_by_url($article_url);
        if(!$article_info){
            //是否符合url 规则，如果符合，301重定向到正确的地址
            $url_p_arr =explode('-',$article_url);
            if(preg_match("#^a(\d+)#",$url_p_arr[0],$data)){
               $article_id=$data[1];
               $article_info=$this->applications_model->get_article_info($article_id);
               redirect(base_url('applications/'.$article_info['url_path'].".html"));
            }
            show_404();
        }
        $catagory_id =$article_info['app_catagory_id'];
        $catagory_info =$this->applications_model->get_applications_info($catagory_id);
        $parent_catagory_info =array();
        if($catagory_info['level']==2){
            $parent_catagory_info =$this->applications_model->get_applications_info($catagory_info['parent_id']);
        }
        else{
            $parent_catagory_info=$catagory_info;
        }
        $breadcrumbs[] =array(
                'text' =>'Guideline',
                'href'  =>base_url("guideline.html"),
                'sep'  =>true
        );
        $breadcrumbs[] =array(
            'text' =>'Applications',
            'href'  =>base_url("applications.html"),
            'sep'  =>true
        );
        if($parent_catagory_info){
            $breadcrumbs[] =array(
                'text' =>$parent_catagory_info['catagory_name'],
                'href'  =>base_url("applications/c/".$parent_catagory_info['url_path'].".html"),
                'sep'  =>true
            );
        }
        
       $breadcrumbs[] =array(
            'text' =>$catagory_info['catagory_name'],
            'href'  =>base_url("applications/c/".$catagory_info['url_path'].".html"),
            'sep'  =>true
         );
        $breadcrumbs[] =array(
            'text' =>$article_info['title'],
            'href'  =>base_url("/applications/".$article_info['url_path'].".html"),
            'sep'  =>false
         );
       
        $this->load->model('custom_tag_model');
        
        $custom_tag = $this->custom_tag_model->get_all_tags();

        $content = $article_info['content'];
        $_count = 0;
        foreach($custom_tag as $_item){
            if($_count > 5){
                break;
            }
            $_tag  = ' '.$_item['tag'].' ';
            $_link = $_item['link'];
            $_pos = strpos(strtolower($content),strtolower($_tag));
            if($_pos !== false){
                $_replace = '<a target="_blank" href="'.$_link .'">' . $_tag . "</a>";
                $content = substr_replace($content,$_replace,$_pos,  strlen($_tag));
                $_count ++;
            }
        }
        $article_info['content'] = $content;
      
         
        $data['breadcrumbs'] =$breadcrumbs;
        $data['article_info'] =$article_info;
        $data['title'] =$article_info['title'];
        $data['description'] =$article_info['meta_description'];
        $data['keywords'] =$article_info['meta_keyword'];
        //分类列表
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $data['catagory_info'] =$catagory_info;
        $data['parent_catagory_info'] =$parent_catagory_info;

        //关联商品
        $this->load->helper('image');
        $width=50;
        $height=50;
        $user_products =$this->applications_model->get_product_for_used($article_info['article_id']);
        $data['user_products'] =array();
        foreach($user_products as $product){
            if($product['image']){
                $info = pathinfo($product['image']);
                $source_image = 'https://www.myled.com/image/'.$product['image'];
                $new_image = ROOT_PATH.'images/data/'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
                if(!file_exists($new_image)){
                     $img_do =img_create_small($source_image,$width,$height,$new_image);
                }
                $product['image'] ='images/data/'.$info['filename']."-".$width."x".$height.'.'.$info['extension'];
            }
            $data['user_products'][] =$product;
        }
        $this->load->view('common/header',$data);
        $this->load->view('common/breadcrumb');
        $this->load->view('applications_detail');
        $this->load->view('common/footer');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */