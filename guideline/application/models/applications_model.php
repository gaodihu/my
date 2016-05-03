<?php
class applications_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
        $this->myled_db=$this->load->database('myled',TRUE);
    }
    
    public function get_applications_by_level($level)
    {
        $this->db->select('*');
        $this->db->from('applications_catagory');
        $this->db->where(array('level'=>$level,'status'=>'1'));
        $this->db->order_by('sort ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_applications_by_parent_id($parent_id=0)
    {  
        $this->db->select('*');
        $this->db->from('applications_catagory');
        $this->db->where(array('parent_id'=>$parent_id,'status'=>'1'));
        $this->db->order_by('sort ASC');
         $query = $this->db->get();
        return $query->result_array();
    }

    public function get_applications_info($id){
        $query = $this->db->get_where('applications_catagory',array('catagory_id'=>$id,'status'=>'1'));
        return $query->row_array();
    }
    public function get_applications_info_by_url($url){
        $query = $this->db->get_where('applications_catagory',array('url_path'=>$url,'status'=>'1'));
        return $query->row_array();
    }
    

    public function get_all_applications(){
        $all_app_info =array();
        $all_applications_list =$this->get_applications_by_level(1);
        foreach($all_applications_list as $applications){
                $child =$this->get_applications_by_parent_id($applications['catagory_id']);
                $applications['child'] =$child;
                $all_app_info[] =$applications;
        }
        return $all_app_info;
    }

    public function get_all_application_articles($filter){
        $this->db->select('*');
        $this->db->from('applications_article');
        if(isset($filter['catagory_id'])&&$filter['catagory_id']){
            $this->db->where(array('app_catagory_id'=>$filter['catagory_id'],'status'=>'1'));
        }
         if(isset($filter['start'])||isset($filter['offset'])){
            if($filter['start']<0){
                $filter['start'] =0;
            }
            $this->db->limit($filter['offset'],$filter['start']);
        }
        $this->db->order_by('sort DESC,add_time DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_article_info_by_url($url){
        $query = $this->db->get_where('applications_article',array('url_path'=>$url,'status'=>'1'));
        return $query->row_array();
    }
    public function get_article_info($article_id){
        $query = $this->db->get_where('applications_article',array('article_id'=>$article_id,'status'=>'1'));
        return $query->row_array();
    }

    //得到方案使用到的商品列表
    public function get_product_for_used($article_id){
        $this->db->select('product_id');
        $this->db->from('app_article_product');
        $this->db->where(array('app_article_id'=>$article_id));
        $query = $this->db->get();
        $product_array =$query->result_array();
        $return_data =array();
        foreach($product_array as $item){
            $product_id =$item['product_id'];
            $this->myled_db->select("url_path,price,(SELECT price FROM oc_product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) LIMIT 1) AS special,image,pd.name");
            $this->myled_db->from('product as p');
            $this->myled_db->join('product_description as pd','p.product_id=pd.product_id');
            $this->myled_db->where(array('p.product_id'=>$product_id,'pd.language_id'=>1));
            $query = $this->myled_db->get();
            $product_info =$query->row_array();
            $return_data[] =$product_info;
        }
        return $return_data;
    }

}
