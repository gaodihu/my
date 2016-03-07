<?php
class banner_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    public function get_banners_by_code($banner_code)
    {
        $this->db->select('t.width,t.height,b.*');
        $this->db->from('banner_type as t');
        $this->db->join('banner as b','t.type_id=b.type_id');
        $this->db->order_by('b.sort DESC,b.banner_id DESC');
        $this->db->where(array('t.type_code'=>$banner_code,'t.status'=>'1','b.status'=>'1',));
        $query = $this->db->get();
        return $query->result_array();
    }
}
