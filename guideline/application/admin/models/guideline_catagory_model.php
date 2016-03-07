<?php
class Guideline_catagory_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    
    public function get_catagory_by_level($level)
    {
        $this->db->select('*');
        $this->db->from('applications_catagory');
        $this->db->where(array('level'=>$level));
        $this->db->order_by('sort ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_catagory_by_parent_id($parent_id=0)
    {  
        $this->db->select('*');
        $this->db->from('applications_catagory');
        $this->db->where(array('parent_id'=>$parent_id));
        $this->db->order_by('sort ASC');
         $query = $this->db->get();
        return $query->result_array();
    }

    public function get_catagory_info($id){
        $query = $this->db->get_where('applications_catagory',array('catagory_id'=>$id));
        return $query->row_array();
    }
    
    //得到所有的分类情况
    public function get_all_catagorys(){
        $all_app_info =array();
        $all_applications_list =$this->get_catagory_by_level(1);
        foreach($all_applications_list as $applications){
                $child =$this->get_catagory_by_parent_id($applications['catagory_id']);
                $applications['child'] =$child;
                $all_app_info[] =$applications;
        }
        return $all_app_info;
    }

    public function add_catagory($data){
        $add_data =array(
            'catagory_name'            =>$data['catagory_name'],
            'url_path'                      =>$data['url_path'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'catagory_description'     =>$data['catagory_description'],
            'parent_id'                    =>(int)$data['parent_id'],
            'level'                           =>(int)$data['catagory_level'],
            'sort'                           =>isset($data['sort'])?(int)$data['sort']:1,
            'image'                        =>$data['image'],
            'status'                        =>(int)$data['status']
        );
        $this->db->insert('applications_catagory', $add_data);
        $catagory_id =$this->db->insert_id();
        if($data['parent_id']){
            $catagory_path ='0/'.$data['parent_id'].'/'.$catagory_id;
        }
        else{
            $catagory_path ='0/'.$catagory_id;
        }
        
        $update_data = array(
            'path' => $catagory_path
        );
        $this->db->where('catagory_id', $catagory_id);
        $this->db->update('applications_catagory', $update_data); 
    }
    public function edit_catagory($catagory_id,$data){
         if($data['parent_id']){
            $catagory_path ='0/'.$data['parent_id'].'/'.$catagory_id;
        }
        else{
            $catagory_path ='0/'.$catagory_id;
        }
        $update_data =array(
            'catagory_name'            =>$data['catagory_name'],
            'url_path'                      =>$data['url_path'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_description'],
            'catagory_description'     =>$data['catagory_description'],
            'parent_id'                    =>(int)$data['parent_id'],
            'path'                          =>$catagory_path,
            'level'                           =>(int)$data['catagory_level'],
            'sort'                           =>isset($data['sort'])?(int)$data['sort']:1,
            'status'                        =>(int)$data['status']
        );
        if($data['image']){
            $update_data['image'] =$data['image'];
        }
        $this->db->where('catagory_id', $catagory_id);
        $this->db->update('applications_catagory', $update_data); 
    }
    
    public function delete($catagory_id){
         $this->db->delete('applications_catagory', array('catagory_id' => $catagory_id)); 
    }

    public function get_catagory_articles($catagory_id){
        $this->db->select('*');
        $this->db->from('applications_article');
        $this->db->where(array('app_catagory_id'=>$catagory_id));
        $this->db->order_by('add_time DESC');
         $query = $this->db->get();
        return $query->result_array();
    }
}
