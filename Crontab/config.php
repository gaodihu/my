<?php
//网站数据库数据
$host =array(
    'host'=>'127.0.0.1:7709',
    'user'=>'myled',
    'password'=>'232ZFit52brxaN4n'
);

$db_connect_magento=mysql_connect($host['host'],$host['user'],$host['password']) or die("Unable to connect to the mageto MySQL!");
//$db_connect_opencart=mysql_connect($opencart_db['host'],$opencart_db['user'],$opencart_db['password'],true) or die("Unable to connect to the opencart MySQL!");
mysql_query("SET NAMES UTF8");
mysql_select_db("new_myled",$db_connect_magento);
date_default_timezone_set('Asia/Chongqing');
?>