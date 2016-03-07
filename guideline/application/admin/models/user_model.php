<?php
class User_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    //按照等级得到FAQ的分类
    public function check_login($user_name,$password)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where(array('user_name'=>$user_name,'status'=>'1','user_password'=>md5($password)));
        $query = $this->db->get();
        return $query->row_array();
    }

    
}
