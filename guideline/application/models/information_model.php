<?php
class information_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
     public function get_information_info($info_id){
        $this->db->select('*');
        $this->db->from('information');
       $this->db->where(array('info_id'=>$info_id));
        $query = $this->db->get();
        return $query->row_array();
    }

}
