<?php
class Custom_tag_model extends CI_Model {
    var $db;
    var $myled_db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default',TRUE);
        $this->myled_db=$this->load->database('myled',TRUE);
    }
    
    
    public function get_all_tags($filter = array())
    {
        $this->myled_db->select('*');
        $this->myled_db->from('custom_tag');
        if($filter['filter_custom_tag']){
            $this->myled_db->like('tag',$filter['filter_custom_tag']);
        }
        if($filter['lang_id']){
            $this->myled_db->where(array('lang_id'=>$filter['lang_id']));
        }
        if($filter['filter_custom_tag_start_time']){
            $this->myled_db->where(array('add_time >='=>$filter['filter_custom_tag_start_time']));
        }
        if($filter['filter_custom_tag_end_time']){
            $this->myled_db->where(array('add_time <='=>$filter['filter_custom_tag_end_time']));
        }
        
        $this->myled_db->limit($filter['offset'],$filter['start']);


        $query = $this->myled_db->get();
        /*
                $sql = $this->myled_db->last_query();
        echo  $sql;
        */
      
        return $query->result_array();
    }

    public function get_count($filter){
        $this->myled_db->select('count(*) as total');
        $this->myled_db->from('custom_tag');
        if($filter['filter_custom_tag']){
           $this->myled_db->like('tag',$filter['filter_custom_tag']);
        }
        if($filter['lang_id']){
            $this->myled_db->where(array('lang_id'=>$filter['lang_id']));
        }
      
        if($filter['filter_custom_tag_start_time']){
            $this->myled_db->where(array('add_time >='=>$filter['filter_custom_tag_start_time']));
        }
        if($filter['filter_custom_tag_end_time']){
            $this->myled_db->where(array('add_time <='=>$filter['filter_custom_tag_end_time']));
        }

       
        $query = $this->myled_db->get();
         /*
         $sql = $this->myled_db->last_query();
        echo  $sql;
        */
        return $query->row_array();
    }

    public function get_tag($id){
        $query = $this->myled_db->get_where('custom_tag',array('id'=>$id));
        return $query->row_array();
    }
    
    public function get_tag_by_tag($tag,$lang_id){
        $query = $this->myled_db->get_where('custom_tag',array('lower(tag)'=>$tag,'lang_id'=>$lang_id));
        return $query->row_array();
    }
  
    public function add_custom($data){
        $add_data =array(
            'tag'              => $data['tag'],
            'link'             => $data['link'],
            'lang_id'          => $data['lang_id'],
            'add_time'         => date('Y-m-d H:i:s',time())
        );
        if($data['tag'] == '' || !$data['lang_id']){
            return false;
        }
        if(!$this->get_tag_by_tag($data['tag'],$data['lang_id'])){
            $this->myled_db->insert('custom_tag', $add_data);
            $id = $this->myled_db->insert_id();
            return $id;
        }
    }

    
    public function delete($id){
         $this->myled_db->delete('custom_tag', array('id' => $id)); 
    }


}
