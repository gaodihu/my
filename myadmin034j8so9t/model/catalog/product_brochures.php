<?php
class ModelCatalogProductBrochures extends Model {
	//商品的质检报告
    public function getProductBrochures($product_id){
        $query =$this->db->query("select * from " . DB_PREFIX ."product_brochures where product_id=".(int)$product_id." order by add_time desc");
        return $query->rows;
    }
    //增加质检报告
    public function addProductBrochures($data){
        $this->db->query("INSERT INTO  " . DB_PREFIX ."product_brochures set product_id ='".(int)$data['product_id']."',brochures_path='".$this->db->escape($data['path'])."',add_time=NOW(),update_time=NOW()");
    }
    //改变商品的质检报告
    public function updateProductBrochures($id,$path){
        $this->db->query("UPDATE  " . DB_PREFIX ."product_brochures set brochures_path='".$this->db->escape($path)."',update_time =NOW() where id=".(int)$id);
    }

   //删除质检报告
   public function delProductBrochures($id){
        $this->db->query("DELETE FROM " . DB_PREFIX ."product_brochures where id=".(int)$id);
   }

   //判断是否有重复命名的质检报告
   public function HaveProductBrochures($path){
        $query =$this->db->query("select * from " . DB_PREFIX ."product_brochures where brochures_path='".$path."' ");
        if($query->num_rows){
            return true;
        }else{
            return false;
        }
   } 
}
?>