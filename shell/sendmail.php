<?php
include_once('/home/www/new_myled.com/config.php');
define('CHEETAMAIL_USER','myled_cn@api');
define('CHEETAMAIL_PASSWD','Fordward%1');
define('CHEETAMAIL_EID','225803');
define('COOKIE_PATH',DIR_BASE.'shell/');
class CheetahmailTool {
	public $_cookiePubAuth;
	public $_cookieHead;
	
	public function requestSend($url,$method=false,$curlPost=''){
		$cookie_file = COOKIE_PATH.'session/cookie.txt';
		$ch = curl_init();
		
		if($method){
			curl_setopt($ch,CURLOPT_URL,'https://ebm.cheetahmail.com/ebm/ebmtrigger1');
		}else{
			curl_setopt($ch, CURLOPT_URL,$url);
		}
		curl_setopt($ch, CURLOPT_PORT , 443);  
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		if($method){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		}
		
		$result=curl_exec($ch);
		//echo 'requestSend';print_r($result);
		if(trim($result)=='OK'){
			curl_close($ch);
			return true;
		}else{
			curl_close($ch);
			return false;
		}
	}
	public function requestCurl($url,$method=false,$curlPost=''){
		$cookie_file =  COOKIE_PATH.'session/cookie.txt';
		try{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			
			//curl_setopt($ch, CURLOPT_PORT , 443);  
			curl_setopt($ch, CURLOPT_HEADER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie_file);
			$result=curl_exec($ch);
		
			list($header, $body) = explode("\r\n\r\n", $result);
			preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);
			//echo 'requestCurl';print_r($result);
            curl_close($ch);
			if(strpos($matches[1],'PubAuth1')){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e) {
				
				return false;
		}
		
	}
	public function Cheetahmail($email, $name=null,$content){
		
		//$_content = $variables['data'];
		$login1name = CHEETAMAIL_USER;
		$cleartext  = CHEETAMAIL_PASSWD;
		$enabledpost = 1;
		$url='https://app.cheetahmail.com/api/login1?name='.$login1name.'&cleartext='.$cleartext;
		$eid = CHEETAMAIL_EID;
		$eid = $eid?$eid:'225803';
		
		$GETurl='email='.$email.'&eid='.$eid.'&SUBJECT='.urlencode($content['title']).'&CONTENT='.urlencode($content['content']);
		//$GETurl='email='.$email.'&eid='.$eid.'&SUBJECT='.urlencode('title').'&CONTENT='.urlencode('content');
	
		
		$getResult = $this->requestCurl($url);
       
		if($getResult){
			$ebmtrigger1Url = 'https://ebm.cheetahmail.com/ebm/ebmtrigger1?'.$GETurl;
			$getResult  = $this->requestSend($ebmtrigger1Url,$enabledpost,$GETurl);
			//$getResult = $this->requestSend($GETurl,true,$GETurl);
			
			return $getResult;
		}else{
			return $getResult;
		}
	}
    public function send($email, $sender_name, $content)
    {  
       $getResult = $this->Cheetahmail($email,$sender_name,$content );
       return $getResult;  
    }
}

$db  = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
if(!$db){
    echo 'db connect error';
    exit();
}

$cheetahmail = new CheetahmailTool();
mysql_select_db(DB_DATABASE);
mysql_query('set names utf8');

$sql = "SELECT *  FROM oc_email  where email_to != '' AND status = 1  order by email_id desc limit  10";
$rs  = mysql_query($sql);
while($row = mysql_fetch_assoc($rs)){
    $content =  array('title' => $row['email_subject'],'content' => $row['email_content']);
    $is_send = $cheetahmail->send($row['email_to'], 'MyLED.com',$content);
    $email_id = $row['email_id'];
    if($is_send){
        $sql_d = "delete from oc_email where email_id = '{$email_id}'";
        
        mysql_query($sql_d);
    }else{
        $sql_d = "update  oc_email set status = 0 where email_id = '{$email_id}'";
        mysql_query($sql_d);
    }
}
