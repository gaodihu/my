<?php
//网站数据库数据

$dir = str_replace("\\",'/',__DIR__);
$dir = substr($dir,0,0 - strlen('Crontab'));

date_default_timezone_set('Asia/Chongqing');

$host =array(
    'host'=>'127.0.0.1',
    'user'=>'root',
    'password'=>'',
    'dbname' => 'moresku',
);

$db = mysqli_connect($host['host'],$host['user'],$host['password'],$host['dbname']) or die("Unable to connect to the mageto MySQL!");

mysqli_query($db,"SET NAMES UTF8");

mysqli_select_db($db,"new_myled");


?>