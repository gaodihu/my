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

		$this->myled_db->where('lang_id',1);

        if(isset($filter['filter_custom_tag']) && $filter['filter_custom_tag']){
            $this->myled_db->like('tag ',$filter['filter_custom_tag']);
        }

        if(isset($filter['filter_custom_tag_start_time']) && $filter['filter_custom_tag_start_time']){
            $this->myled_db->where(array('add_time >='=>$filter['filter_custom_tag_start_time']));
        }
        if(isset($filter['filter_custom_tag_end_time']) && $filter['filter_custom_tag_end_time']){
            $this->myled_db->where(array('add_time <='=>$filter['filter_custom_tag_end_time']));
        }
        if(isset($filter['offset']) && isset($filter['start'])){
            $this->myled_db->limit($filter['offset'],$filter['start']);
        }

        $query = $this->myled_db->get();
      
        return $query->result_array();
    }

  

  



}
