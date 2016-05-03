<?php
//网站数据库数据

$dir = str_replace("\\",'/',__DIR__);
$dir = substr($dir,0,0 - strlen('Crontab'));

date_default_timezone_set('Asia/Chongqing');

$host =array(
<<<<<<< HEAD
    'host'=>'127.0.0.1:3306',
    'user'=>'root',
    'password'=>'123456'
);

$db_connect_magento=mysql_connect($host['host'],$host['user'],$host['password']) or die("Unable to connect to the mageto MySQL!");
//$db_connect_opencart=mysql_connect($opencart_db['host'],$opencart_db['user'],$opencart_db['password'],true) or die("Unable to connect to the opencart MySQL!");
mysql_query("SET NAMES UTF8");
mysql_select_db("new_myled",$db_connect_magento);
date_default_timezone_set('Asia/Chongqing');
?>
=======
    'host'=>'127.0.0.1',
    'user'=>'root',
    'password'=>'',
    'dbname' => 'moresku',
);

$db = mysqli_connect($host['host'],$host['user'],$host['password'],$host['dbname']) or die("Unable to connect to the mageto MySQL!");

mysqli_query($db,"SET NAMES UTF8");

mysqli_select_db($db,"new_myled");


?>
>>>>>>> 27f5783fc38dbc27417ee3e9b4c94fe3042b4fde
