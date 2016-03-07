<?php
class Guideline_program_model extends CI_Model {
    var $db;
    var $myled_db;

    public function __construct()
    {
        parent::__construct();
        $this->db=$this->load->database('default',TRUE);
        $this->myled_db=$this->load->database('myled',TRUE);
    }
    
    
    public function get_all_articles($filter)
    {
        $this->db->select('*');
        $this->db->from('applications_article');
        if($filter['filter_article_name']){
            $this->db->where(array('title'=>$filter['filter_article_name']));
        }
        if($filter['filter_article_catagory_name']){
            //得到所有的子分类
            $sql ="select distinct a.catagory_id as p_id,b.catagory_id as child_id from gl_applications_catagory as a left join gl_applications_catagory as b on a.catagory_id =b.parent_id where a.catagory_name ='".$filter['filter_article_catagory_name']."' ";
            $query=$this->db->query($sql);
            $in_catagory_id =array();
            foreach($query->result_array() as $row){
                if($row['p_id']&&!in_array($row['p_id'],$in_catagory_id)){
                    $in_catagory_id[] =$row['p_id'];
                }
                if($row['child_id']&&!in_array($row['child_id'],$in_catagory_id)){
                    $in_catagory_id[] =$row['child_id'];
                }
            }
           
            if($in_catagory_id){
                $this->db->where_in('app_catagory_id',$in_catagory_id);
            }
        }
        
        if($filter['filter_article_catagory']){
            $this->db->where(array('app_catagory_id'=>$filter['filter_article_catagory']));
        }
        if($filter['filter_article_status']!==false){
            $this->db->where(array('status'=>$filter['filter_article_status']));
        }
        if($filter['filter_article_start_time']){
            $this->db->where(array('add_time >='=>$filter['filter_article_start_time']));
        }
        if($filter['filter_article_end_time']){
            $this->db->where(array('add_time <='=>$filter['filter_article_end_time']));
        }
        $this->db->order_by($filter['order'].' '.$filter['sort']);
        $this->db->limit($filter['offset'],$filter['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_count_articles($filter){
        $this->db->select('count(*) as total');
        $this->db->from('applications_article');
        if($filter['filter_article_name']){
            $this->db->where(array('title'=>$filter['filter_article_name']));
        }
        if($filter['filter_article_catagory']){
            $this->db->where(array('app_catagory_id'=>$filter['filter_article_catagory']));
        }
        if($filter['filter_article_status']!==false){
            $this->db->where(array('status'=>$filter['filter_article_status']));
        }
        if($filter['filter_article_start_time']){
            $this->db->where(array('add_time >='=>$filter['filter_article_start_time']));
        }
        if($filter['filter_article_end_time']){
            $this->db->where(array('add_time <='=>$filter['filter_article_end_time']));
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_program_info($id){
        $query = $this->db->get_where('applications_article',array('article_id'=>$id));
        return $query->row_array();
    }
    public function get_program_products($article_id){
        $this->db->select('product_sku');
        $this->db->from('app_article_product');
        $this->db->where (array('app_article_id'=>$article_id));
        $this->db->order_by('rec_id ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function add_program($data){
        $add_data =array(
            'app_catagory_id'            =>$data['app_catagory_id'],
            'title'                             =>$data['title'],
            'tag'                             =>$data['tag'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_descrpition'],
            'content'                       =>$data['content'],
            'effect_image'                =>$data['image'],
            'sort'                            =>(int)$data['sort'],
            'status'                        =>(int)$data['status'],
            'add_time'                   =>date('Y-m-d H:i:s',time()),
            'update_time'               =>date('Y-m-d H:i:s',time())
        );
        $this->db->insert('applications_article', $add_data);
        $article_id =$this->db->insert_id();
        $update_data =array(
            'url_path'                             =>'a'.$article_id."-".$data['url_path']
        );
        $this->db->where('article_id', $article_id);
        $this->db->update('applications_article', $update_data); 
        if($data['product']){
            $program_products =explode(',',$data['product']);
            foreach($program_products as $product_sku){
                $product_id =$this->get_product_id($product_sku);
                if($product_id){
                    $add_pro =array(
                        'app_article_id'  =>$article_id,
                        'product_sku'  =>$product_sku,
                        'product_id'  =>$product_id['product_id']
                    );
                    $this->db->insert('app_article_product', $add_pro);
                }
            }
        }
    }
    public function edit_program($article_id,$data){
        $update_data =array(
            'app_catagory_id'            =>$data['app_catagory_id'],
            'title'                             =>$data['title'],
            'url_path'                             =>$data['url_path'],
            'tag'                             =>$data['tag'],
            'meta_keyword'             =>$data['meta_keyword'],
            'meta_description'          =>$data['meta_descrpition'],
            'content'                       =>$data['content'],
            'status'                        =>(int)$data['status'],
            'sort'                        =>(int)$data['sort'],
            'update_time'                   =>date('Y-m-d H:i:s',time())
        );
        if(isset($data['image'])){
            $update_data['effect_image'] =$data['image'];
        }
        $this->db->where('article_id', $article_id);
        $this->db->update('applications_article', $update_data); 
        if($data['product']){
            $this->db->delete('app_article_product', array('app_article_id' => $article_id)); 
            $program_products =explode(',',trim($data['product']));
            foreach($program_products as $product_sku){
                $product_id =$this->get_product_id(trim($product_sku));
                if($product_id){
                    $add_pro =array(
                        'app_article_id'  =>$article_id,
                        'product_sku'  =>$product_sku,
                        'product_id'  =>$product_id['product_id']
                    );
                    $this->db->insert('app_article_product', $add_pro);
                }
            }
        }
    }
    
    public function delete($article_id){
         $this->db->delete('applications_article', array('article_id' => $article_id)); 
    }

    public function get_product_id($sku){
        $this->myled_db->select('product_id');
        $this->myled_db->from('product');
        $this->myled_db->where(array('model'=>$sku));
        $query = $this->myled_db->get();
        return $query->row_array();
    }
    
}
