<?php
define('VERSION', '1.5.6.1');

/* Testing non-beta */

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  
$now = date('Y-m-d H:i:s');
$data = array($now);

$callback=$_GET['callback']; 
echo  $callback.'(' .json_encode($data) . ")";
?>

