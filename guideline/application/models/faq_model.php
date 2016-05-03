<?php
class faq_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    //按照等级得到FAQ的分类
    public function get_faq_catagory_by_level($level)
    {
        $this->db->select('*');
        $this->db->from('faq_catagory');
        $this->db->where(array('level'=>$level,'status'=>'1'));
        $this->db->order_by('sort ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    //按照父子关系得到FAQ的分类
    public function get_faq_catagory_by_parent_id($parent_id=0)
    {  
        $this->db->select('*');
        $this->db->from('faq_catagory');
        $this->db->where(array('parent_id'=>$parent_id,'status'=>'1'));
        $this->db->order_by('sort ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    //得到FAQ分类信息
    public function get_faq_catagory_info($id){
        $query = $this->db->get_where('faq_catagory',array('catagory_id'=>$id,'status'=>'1'));
        return $query->row_array();
    }
    public function get_faq_catagory_info_by_url($url_path){
        $query = $this->db->get_where('faq_catagory',array('url_path'=>$url_path,'status'=>'1'));
        return $query->row_array();
    }
    //得到复合条件的FAQ列表
    public function get_all_faq_article($data){
        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where(array('status'=>'1'));
        if(isset($data['catagory_id'])&&$data['catagory_id']){
            $this->db->where(array('faq_catagory_id'=>(int)$data['catagory_id']));
        }
        if(isset($data['start'])||isset($data['offset'])){
            if($data['start']<0){
                $data['start'] =0;
            }
            $this->db->limit($data['offset'],$data['start']);
        }
        $this->db->order_by('add_time DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    //得到复合条件的FAQ列表总数
    public function get_count_faq_articles($data){
        $this->db->select('count(*) as total');
        $this->db->from('faq_article');
        $this->db->where(array('status'=>'1'));
        if(isset($data['catagory_id'])&&$data['catagory_id']){
            $this->db->where(array('faq_catagory_id'=>(int)$data['catagory_id']));
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    ////得到FAQ具体信息
    public function get_faq_article($faq_id){
        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where(array('status'=>'1','faq_id'=>$faq_id));
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_faq_article_by_url($url_path){
        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where(array('status'=>'1','url_path'=>$url_path));
        $query = $this->db->get();
        return $query->row_array();
    }


    
    //得到所有的FAQ分类信息
    public function get_all_faq_catagorys(){
        $all_app_info =array();
        $all_applications_list =$this->get_faq_catagory_by_level(1);
        foreach($all_applications_list as $applications){
                $child =$this->get_faq_catagory_by_parent_id($applications['catagory_id']);
                $applications['child'] =$child;
                $all_app_info[] =$applications;
        }
        return $all_app_info;
    }
    
    //得到faq相关联的其他FAQ
    public function get_related_faq($catagory_id,$faq_id){
        $id_datas =$this->get_related_other_faq($catagory_id,$faq_id);
        $in_array =$this->get_laster_number($faq_id,$id_datas);

        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where_in('faq_id',$in_array);
        $this->db->where(array('faq_catagory_id'=>$catagory_id,'status'=>1));
        $this->db->order_by('faq_id ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    //得到分类下其他所有的ID
    public function get_related_other_faq($catagory_id,$faq_id){
        $data =array();
        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where(array('faq_catagory_id'=>$catagory_id,'faq_id !='=>$faq_id,'status'=>1));
        $this->db->order_by('faq_id ASC');
        $query = $this->db->get();
        foreach($query->result_array() as $val){
            $data[] =$val['faq_id'];
        }
        return $data;
    }

    //得到最靠近某个数的后面3个数
    public function get_laster_number($mumber,$mun_arr){
        $return_data =array();
        $count =count($mun_arr);
        foreach($mun_arr as $key=>$value){
            if($mumber<$value){
                if($count-$key-3>=0){
                    $return_data =array($value,$mun_arr[$key+1],$mun_arr[$key+2]);
                    break;
                }elseif($count-$key==1){
                    $return_data =array($value,$mun_arr[0],$mun_arr[1]);
                    break;   
                }else{
                    $return_data =array($value,$mun_arr[$key+1],$mun_arr[0]);
                    break; 
                }
            }
        }
        if(empty($return_data)){
            $return_data =array($mun_arr[0],$mun_arr[1],$mun_arr[2]);
        }
        return $return_data;
    }
}
