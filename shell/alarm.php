<?php
	error_reporting(7);
	ini_set('display_errors',1);
	date_default_timezone_set('Asia/Shanghai');
	define('ROOT','/home/www/new_myled.com/');
	$message_arr = array();
	$send_to =array(
		array('13728770334@139.com','charles'),
		//array('13424247937@139.com','tinker'),
		);
	$db = mysql_connect('127.0.0.1:7709','myled','232ZFit52brxaN4n');
	if(!$db){
		$message_arr[] = '数据库连接不上';
	}
	if($db){
		mysql_select_db('new_myled');
		mysql_query('set names utf8');

		//取得mysql的进程数
		$sql = "show processlist";
		$rs  = mysql_query($sql);
		$num = 0;
		while($row = mysql_fetch_assoc($rs)){
			$num ++ ;
			$state = $row['State']; 
			$state = strtolower($state);
			if(strpos($state, 'lock') !== false){
				$message_arr[] = "数据库锁表";

			}

		}
		if($num>20){
			$message_arr[] = " 数据库进程[{$num}]过多";

		}

		//取得订单数据，一个小时内没有订单就发送通知
		$n_2 = date('Y-m-d H:i:s',time() - 45*60);
		//去掉时区
		$n_10 = date('Y-m-d H:i:s',time() -  45*60);
			
		$sql = "SELECT count(*) as cnt FROM  `oc_order` where date_added >= '{$n_10}'  ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$cnt = $row['cnt'];
		if($cnt < 1){
			$message_arr[] = '45m没有订单生成';
		}
		
		
	    //取得订单数据，2个小时内没有订单支付就发送通知
		$n_2 = date('Y-m-d H:i:s',time() -  45*60);
		//去掉时区
		$n_10 = date('Y-m-d H:i:s',time() -  45*60);
			
		$sql = "SELECT count(*) as cnt FROM  `oc_order` where date_added >= '{$n_10}' and  order_status_id in (2,5) ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$cnt = $row['cnt'];
		if($cnt < 1) {
			$message_arr[] = '45m到没有订单支付';
		}


	   //取得订单数据，2个小时内没有订单支付就发送通知
		$n_2 = date('Y-m-d H:i:s',time() -  2*60);
		//去掉时区
		$n_10 = date('Y-m-d H:i:s',time() -  2*60);
			
		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'pp_onestep' AND  date_added >= '{$n_10}' and  order_status_id in (2,5) ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal_pay = $row['cnt'];

		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'pp_onestep' AND  date_added >= '{$n_10}'  ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal = $row['cnt'];

		if($order_paypal>0){
			$l = $order_paypal_pay/$order_paypal ;
			if($l < 0.6){
				$message_arr[] = '2H内PayPal成功率'.sprintf('%0.2f',$l);
			}
		}

	    //取得订单数据，2个小时内没有订单支付就发送通知
		$n_2 = date('Y-m-d H:i:s',time() -  2*60);
		//去掉时区
		$n_10 = date('Y-m-d H:i:s',time() -  2*60);
			
		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'pp_express' AND  date_added >= '{$n_10}' and  order_status_id in (2,5) ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal_pay = $row['cnt'];

		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'pp_express' AND  date_added >= '{$n_10}'  ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal = $row['cnt'];

		if($order_paypal>0){
			$l = $order_paypal_pay/$order_paypal ;
			if($l < 0.6){
				$message_arr[] = '2H内快速PayPal成功率'.sprintf('%0.2f',$l);
			}
		}

		//取得订单数据，2个小时内没有订单支付就发送通知
		$n_2 = date('Y-m-d H:i:s',time() -  2*60);
		//去掉时区
		$n_10 = date('Y-m-d H:i:s',time() -  2*60);
			
		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'globebill_credit' AND  date_added >= '{$n_10}' and  order_status_id in (2,5) ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal_pay = $row['cnt'];

		$sql = "SELECT count(*) as cnt FROM  `oc_order` where payment_code = 'globebill_credit' AND  date_added >= '{$n_10}'  ";
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$order_paypal = $row['cnt'];

		if($order_paypal>0){
			$l = $order_paypal_pay/$order_paypal ;
			if($l < 0.3){
				$message_arr[] = '2H内快速PayPal成功率'.sprintf('%0.2f',$l);
			}
		}
		
	}

	if($message_arr){

		$messages = implode('<br/>', $message_arr);
		foreach($send_to as $item){
				send_alarm($item[0],$item[1],'myled alarm',$messages);
		}
	}

 function send_alarm($to_email,$to_name,$subject = "",$body = ""){
 	require_once( ROOT .'system/lib/PHPMailer/class.phpmailer.php');
    include_once( ROOT ."system/lib/PHPMailer/class.smtp.php"); 
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    //$body             = preg_replace("[\\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                                           // 2 = messages only
   
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
   // $mail->SMTPSecure = "ssl";                 // 安全协议
    $mail->Host       = "smtp.myled.com";      // SMTP 服务器
    $mail->Port       = 25;                   // SMTP服务器的端口号
    $mail->Username   = "charles@myled.com";  // SMTP服务器用户名
    $mail->Password   = "chenwei@123";            // SMTP服务器密码
   
    $mail->SetFrom("charles@myled.com","charles");
    $mail->AddReplyTo("charles@myled.com","charles");
    $mail->Subject    = $subject;
    //$mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From www.jiucool.com"; // optional, comment out and test
    $mail->MsgHTML($body);
    $address = $to_email;
    $mail->AddAddress($address,$to_name);
	   if(!$mail->Send()) {
	        //echo "Mailer Error: " . $mail->ErrorInfo;
	    } else {
           //echo "Message sent!恭喜，邮件发送成功！";
        }
}
?>
