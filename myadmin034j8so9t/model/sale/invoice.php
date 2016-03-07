<?php
class ModelSaleInvoice extends Model {
	public function addInvoice($data) {
        $sql="INSERT INTO ".DB_PREFIX."order_invoice SET invoice_no='".$this->config->get('config_invoice_prefix')."-".$data['order_id']."',order_id='".$data['order_id']."',email_sent=0,created_at=NOW(),updated_at=NOW(),comment='".$data['comment']."' ";
        $this->db->query($sql);
        return $this->db->getLastId();
	 }
     
     public function getInvoiceOrderID($invoice_id){
        $query = $this->db->query("SELECT order_id FROM ".DB_PREFIX."order_invoice where invoice_id=".$invoice_id);
        return $query->row['order_id'];
     }
      public function updateEmailSend($invoice_id){
        $this->db->query("UPDATE ".DB_PREFIX."order_invoice SET email_sent=1 where invoice_id=".$invoice_id);    
      }
}
?>