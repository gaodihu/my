<?php
class Banner_model extends CI_Model {
    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
    }
    
    //得到所有的banner type
    public function get_all_type()
    {
        $this->db->select('*');
        $this->db->from('banner_type');
        $this->db->where(array('status'=>'1'));
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_banner_info($type_id){
        $data =array();
        $this->db->select('*');
        $this->db->from('banner_type');
        $this->db->where(array('status'=>'1','type_id'=>$type_id));
        $query = $this->db->get();
        $data['type_info']=$query->row_array();
        $this->db->select('*');
        $this->db->from('banner');
        $this->db->where(array('type_id'=>$type_id));
        $this->db->order_by('sort asc');
        $query = $this->db->get();
        $data['banner_info']=$query->result_array();
        return $data;
    }
    public function add_banner($data){
        $type_data = array(
               'type_code' => $data['banner_code'] ,
               'type_name' =>$data['banner_name'] ,
               'width' => $data['width'], 
               'height' => $data['height'], 
               'status' => $data['status'] 
        );
        $this->db->insert('banner_type', $type_data);
        $type_id =$this->db->insert_id();
        if(isset($data['banner_info']['image'])){
            $count  =count($data['banner_info']['title']);
            for($i=0;$i<$count;$i++){
                $banner_data =array(
                    'type_id' =>$type_id,
                    'banner_name' =>$data['banner_info']['title'][$i] ,
                    'banner_url' => $data['banner_info']['link'][$i]  ,
                    'sort' =>$data['banner_info']['sort_order'][$i]?$data['banner_info']['sort_order'][$i]:1 ,
                    'banner_image' => $data['banner_info']['image'][$i] ,
                    'status' => $data['banner_info']['status'][$i] ,
                );
                $this->db->insert('banner', $banner_data);
            }
        }
    }
    

    public function update_banner($type_id,$data){
        $type_data = array(
               'type_code' => $data['banner_code'] ,
               'type_name' =>$data['banner_name'] ,
               'width' => $data['width'], 
               'height' => $data['height'], 
               'status' => $data['status'] 
        );
        $this->db->where('type_id', $type_id);
        $this->db->update('banner_type', $type_data);
        $count  =count($data['banner_info']['title']);
        for($i=0;$i<$count;$i++){
            $banner_id =isset($data['banner_info']['banner_id'][$i])?$data['banner_info']['banner_id'][$i]:'';
            $banner_data =array(
                'banner_name' =>$data['banner_info']['title'][$i] ,
                'banner_url' => $data['banner_info']['link'][$i]  ,
                'sort' =>$data['banner_info']['sort_order'][$i]?$data['banner_info']['sort_order'][$i]:1,
                'status' => $data['banner_info']['status'][$i] ,
            );
            if(isset($data['banner_info']['image'][$i])){
                $banner_data['banner_image'] = $data['banner_info']['image'][$i];
            }
            if($banner_id){
                $this->db->where('banner_id', $banner_id);
                $this->db->update('banner', $banner_data);
            }
            else{
                $banner_data['type_id'] =$type_id;
                $this->db->insert('banner', $banner_data);
            }
            
        }
    }
    public function del_banner_type($type_id){
        $this->db->delete('banner_type', array('type_id' => $type_id)); 
        $this->db->delete('banner', array('type_id' => $type_id)); 
    }

    public function del_banner_img($banner_id){
        $this->db->delete('banner', array('banner_id' => $banner_id)); 
    }
    
}
