<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if(isset($_COOKIE['language'])){
    $language = $_COOKIE['language'];
}else{
    $language = 'EN';
}


$host = 'https://www.moresku.com/';
$language = strtoupper($language);
switch($language) {
	case 'DE':
            $host = 'https://de.moresku.com/';
            break;
	case 'FR':
            $host = 'https://fr.moresku.com/';
            break;
	case 'ES':
            $host = 'https://es.moresku.com/';
            break;
	case 'IT':
            $host = 'https://it.moresku.com/';
            break;
	case 'PT':
            $host = 'https://pt.moresku.com/';
            break;
	case 'EN':
            $host = 'https://www.moresku.com/';
            break;
	default: 
            $host = 'https://www.moresku.com/';
            break;
}
$paypal_auth_code = $_REQUEST['code'];
$paypal_auth_scope = $_REQUEST['scope'];


$to_url = 'https://www.moresku.com/index.php?route=account/paypal&scope='.$paypal_auth_scope.'&code='.$paypal_auth_code;
header('Location:'.$to_url);
?>

