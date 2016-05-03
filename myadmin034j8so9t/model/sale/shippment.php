<?php
class ModelSaleShippment extends Model {
	public function addShippment($order_id,$data) {
        //增加shippment
        $sql="INSERT INTO ".DB_PREFIX."order_shipment SET store_id='".$data['store_id']."', order_id='".$order_id."',order_number='".$data['order_number']."',shippment_number='".$this->getShippmentNumber($data['store_id'])."',customer_id='".$data['customer_id']."',email_send=0,created_at=NOW(),updated_at=NOW(),comment='".$data['comment']."' ";
        $this->db->query($sql);
        $shippment_id= $this->db->getLastId();
        //增加shippment_tack
        if(isset($data['tarck'])){
            foreach($data['tarck'] as $track){
                $sql="INSERT INTO ".DB_PREFIX."order_shipment_track SET shippment_id='".$shippment_id."',track_number='".$track['namber']."',title='".$track['title']."',carrier_code='".$track['carrier']."',created_at=NOW(),updated_at=NOW()";
                $this->db->query($sql);
            }
        }
        //增加shippment_item
        foreach($data['shipment_items'] as $key=>$shippment_item){
            $shippment_item['name'] = $this->db->escape($shippment_item['name']);
            $sql="INSERT INTO ".DB_PREFIX."order_shipment_item SET shippment_id='".$shippment_id."',row_total='".$shippment_item['qty_shipped']*$shippment_item['price']."',	price='".$shippment_item['price']."',qty='".$shippment_item['qty_shipped']."',product_id='".$key."',name='".$shippment_item['name']."',sku='".$shippment_item['sku']."',created_at=NOW(),updated_at=NOW()";
            $this->db->query($sql);
        }
        return $shippment_id;
	 }
    public function getShippmentInfo($shippment_id){
        $query = $this->db->query("SELECT *  FROM ".DB_PREFIX."order_shipment where shippment_id=".$shippment_id);
        if($query->num_rows){
            return $query->row;
        }
        else{
            return false;    
        }
    }
    public function updateEmailSend($shippment_id){
        $this->db->query("UPDATE ".DB_PREFIX."order_shipment SET email_send=1 where shippment_id=".$shippment_id);    
      }
      
      //运单号生成规则
      public function getShippmentNumber($store_id){
        $query= $this->db->query("SELECT MAX(shippment_number) as max_number FROM ".DB_PREFIX."order_shipment where store_id=".$store_id);
        if($query->row['max_number']){
            return $query->row['max_number']+1;
        }
        else{
            return  $store_id.'00000001';
        } 
      }

    public function getShippmentTrack($shippment_id){
        $query= $this->db->query("SELECT * FROM ".DB_PREFIX."order_shipment_track where shippment_id=".$shippment_id);
        if($query->num_rows){
            return $query->rows;
        }
        else{
            return  array();
        } 
      }
    public function updateShippmentTrack($data){
        if($data){
         foreach($data as $track){
                $query= $this->db->query("update  ".DB_PREFIX."order_shipment_track set carrier_code='".$track['carrier_code']."',title='".$track['title']."',track_number='".$track['track_number']."' where track_id=".$track['track_id']);
            }
        }
     }

    public function deleteOrderShipment($shippment_id){
           $this->db->query("delete from ".DB_PREFIX."order_shipment where shippment_id='".$shippment_id."' ");
           $this->db->query("delete from ".DB_PREFIX."order_shipment_item where shippment_id='".$shippment_id."' ");
           $this->db->query("delete from ".DB_PREFIX."order_shipment_track where shippment_id='".$shippment_id."' ");
     }

    public function haveTrack($tarck_number){
        $query =$this->db->query("select track_id,shippment_id from ".DB_PREFIX."order_shipment_track where track_number='".$tarck_number."' ");
        if($query->num_rows){
            return $query->row;
        }
        else{
            return false;
        }
        

     }


    public function getShippmentTrackByOrderId($order_id){
        $data = array();
        $query = $this->db->query("SELECT *  FROM ".DB_PREFIX."order_shipment where order_id=".$order_id);
        if($query->num_rows){
           foreach($query->rows as $row){
                $shippment_id = $row['shippment_id'];
                $tracks = $this->getShippmentTrack($shippment_id);
                $data =  array_merge($data,$tracks);
            }
            return $data;
        }
        else{
            return false;
        }
    }

}
?>