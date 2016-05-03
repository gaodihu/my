<?php
class Faq_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    //按照等级得到FAQ的分类
    public function get_all_faqs($filter)
    {
        $this->db->select('*');
        $this->db->from('faq_article');
        if($filter['filter_faq_name']){
            $this->db->where(array('title'=>$filter['filter_faq_name']));
        }
        if($filter['filter_faq_catagory']){
            $this->db->where(array('faq_catagory_id'=>$filter['filter_faq_catagory']));
        }
        if($filter['filter_faq_status']!==false){
            $this->db->where(array('status'=>$filter['filter_faq_status']));
        }
        if($filter['filter_faq_start_time']){
            $this->db->where(array('add_time >='=>$filter['filter_faq_start_time']));
        }
        if($filter['filter_faq_end_time']){
            $this->db->where(array('add_time <='=>$filter['filter_faq_end_time']));
        }
        $this->db->order_by($filter['order'].' '.$filter['sort']);
        $this->db->limit($filter['offset'],$filter['start']);
        $query = $this->db->get();
        return $query->result_array();
       
    }

    public function get_count_faq_articles($filter){
        $this->db->select('count(*) as total');
        $this->db->from('faq_article');
         if($filter['filter_faq_name']){
            $this->db->where(array('title'=>$filter['filter_faq_name']));
        }
        if($filter['filter_faq_catagory']){
            $this->db->where(array('faq_catagory_id'=>$filter['filter_faq_catagory']));
        }
        if($filter['filter_faq_status']!==false){
            $this->db->where(array('status'=>$filter['filter_faq_status']));
        }
        if($filter['filter_faq_start_time']){
            $this->db->where(array('add_time >='=>$filter['filter_faq_start_time']));
        }
        if($filter['filter_faq_end_time']){
            $this->db->where(array('add_time <='=>$filter['filter_faq_end_time']));
        }
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function get_faq_info($id){
        $this->db->select('*');
        $this->db->from('faq_article');
        $this->db->where(array('faq_id'=>$id));
        $query = $this->db->get();
        return $query->row_array();
    }
    public function add_faq($data){
        $add_data =array(
            'faq_catagory_id'            =>$data['faq_catagory_id'],
            'title'                             =>$data['title'],
            'url_path'                             =>$data['url_path'],
            'tag'                             =>$data['tag'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'content'                       =>$data['content'],
            'status'                        =>(int)$data['status'],
            'add_time'                   =>date('Y-m-d H:i:s',time()),
            'update_time'                   =>date('Y-m-d H:i:s',time())
        );
        $this->db->insert('faq_article', $add_data);
    }

    public function edit_faq($faq_id,$data){
        $edit_data =array(
            'faq_catagory_id'            =>$data['faq_catagory_id'],
            'title'                             =>$data['title'],
            'url_path'                             =>$data['url_path'],
            'tag'                             =>$data['tag'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'content'                       =>$data['content'],
            'status'                        =>(int)$data['status'],
            'update_time'                   =>date('Y-m-d H:i:s',time())
        );
        $this->db->where('faq_id', $faq_id);
        $this->db->update('faq_article', $edit_data);
    }
    public function delete($faq_id){
      $this->db->delete('faq_article', array('faq_id' => $faq_id)); 
    }
    
}
