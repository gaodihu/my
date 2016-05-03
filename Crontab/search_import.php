<?php
$dir = str_replace("\\",'/',__DIR__);
$dir = substr($dir,0,0 - strlen('Crontab'));

include_once($dir.'/Application/config.php');
error_reporting(E_ALL);
ini_set('dispaly_errors',1);
require_once DIR_SYSTEM . 'lib/Elasticsearch/vendor/autoload.php';

require_once DIR_SYSTEM . 'library/search.php';


//53-57
$data = array(
    array(1,0),
    array(4,52),
    array(5,54),
    array(6,53),
    array(7,55),
    array(8,56),   
);
foreach($data as $item){
    $search = new Search(array('127.0.0.1:9200'), $item[0], $item[1]);
    //$search->create();
    $search->importData();
}

/*
echo '<pre>';
$search = new Search(array('172.168.90.236:9200'), 1, 0);    
//$result = $search->search('3w led',119,array(28=>array(1720),59=>array(2104)),array(),0,123);
$result = $search->priceRang('3w led','',array(),array(),0,123);
var_dump($result);
die;
$search = new Search(array('172.168.90.236:9200'), 4, 52);    
$result = $search->suggest('3w led');
var_dump($result);
*/
