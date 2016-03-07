<?php
class ModelToolEmail extends Model {	
    //增加邮件到邮件队列
	public function addEmailList($data) {
        //兼容以前有地方进行addslashes
        $data['email_subject'] = stripslashes($data['email_subject']);
        //特殊字符转码
        $data['email_subject'] = $this->db->escape($data['email_subject']);
		$this->db->query("INSERT INTO " . DB_PREFIX . "email SET store_id ='".$data['store_id']."',email_from='".$data['email_from']."',email_to='".$data['email_to']."',email_subject='".$data['email_subject']."',email_content='".$data['email_content']."',is_html='".$data['is_html']."',attachments='".$data['attachments']."',add_time=NOW() ");
        return $this->db->getLastId();
	}
}
?>