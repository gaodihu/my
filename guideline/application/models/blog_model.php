<?php
class blog_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('blog',TRUE);
    }
    
    public function get_laster_blog($num)
    {
        $this->db->select('post_title,post_content,post_date');
        $this->db->from('posts');
        $this->db->order_by('post_date DESC');
        $this->db->limit($num,0);
        $this->db->where(array('post_status'=>'publish','post_type'=>'post'));
        $query = $this->db->get();
        return $query->result_array();
    }
}
