<?php
class Information_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    //按照等级得到FAQ的分类
    public function get_informations()
    {
        $this->db->select('*');
        $this->db->from('information');
        $this->db->order_by('created_at DESC');
        $query = $this->db->get();
        return $query->result_array();
       
    }

    public function get_information_info($info_id){
        $this->db->select('*');
        $this->db->from('information');
       $this->db->where(array('info_id'=>$info_id));
        $query = $this->db->get();
        return $query->row_array();
    }
    public function add_information($data){
        if(!isset($data['url_path'])){
            $data['url_path']=NULL;
        }
        $add_data =array(
            'title'            =>$data['title'],
            'url_path'                             =>$data['url_path'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'content'                       =>$data['content'],
            'status'                        =>(int)$data['status'],
            'created_at'                   =>date('Y-m-d H:i:s',time()),
            'update_at'                   =>date('Y-m-d H:i:s',time())
        );
        $this->db->insert('information', $add_data);
    }

    public function edit_information($info_id,$data){
         if(!isset($data['url_path'])){
            $data['url_path']=NULL;
        }
        $edit_data =array(
            'title'                             =>$data['title'],
            'url_path'                             =>$data['url_path'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'content'                       =>$data['content'],
            'status'                        =>(int)$data['status'],
            'update_at'                   =>date('Y-m-d H:i:s',time())
        );
        $this->db->where('info_id', $info_id);
        $this->db->update('information', $edit_data);
    }
    
}
