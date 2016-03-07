<?php
class ModelCatalogProductHot extends Model{

    public function  getAll(){
        $query = $this->db->query("select * from oc_product_hot");
        if($query){
            return $query->rows;
        }

    }
    public function add($data){

        $this->db->query("INSERT INTO oc_product_hot(sku,start_time,end_time) values ('{$data['sku']}','{$data['start_time']}','{$data['end_time']}')");
    }
    public function clear(){
        $this->db->query("delete from oc_product_hot");
    }

    public function isExist($sku){

        $query = $this->db->query("select * from oc_product_hot where sku='{$sku}'");
        if($query){
            if(count($query->rows) > 0) {
                return true;
            }
        }
        return false;

    }

}

?>