<?php
// Version
define('VERSION', '1.5.6.1');


// Configuration
if (file_exists('../Application/config.php')) {
	require_once('../Application/config.php');
}  

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Config
$config = new Config($registry);
$registry->set('config', $config);

// Request
$request = new Request();
$registry->set('request', $request);


// Language Detection

$languages = $config->getLanguages();
$detect = '';
if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			if ($value['status']) {
				$locale = explode(',', $value['locale']);
				if (in_array($browser_language, $locale)) {
					$detect = $key;
				}
			}
		}
	}
}

//var_dump($config->store_id);
//var_dump($_SERVER);

// 没有找到对应的域名，跳转到默认的域名
if($config->store_id === "" ){
	$url = $config->getDomainByLanguage($detect);
	if(!$url){
		$url = $config->getDomain(0) ;
	}
	header("Location:".$url);
	die;
}



if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
	$code = $request->cookie['language'];
} elseif ($detect) {
	$code = $detect;
}else{
	$code = $config->get('config_language');
}
$code = $config->get('config_language');

$lang_domain = $config->getDomainByLanguage($code);
$domain = $config->getDomain();

if($lang_domain != $domain){
	//header("Location:".$lang_domain);
	//die;
}
// Url
$url = new Url($config->get('config_url'), $config->getDomain());
$registry->set('url', $url);


// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
	if ($config->get('config_error_display')) {
		//echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
       if($error != 'Notice'){
		    $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
        }
	}

	return true;
}

// Error Handler
set_error_handler('error_handler');


// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response); 

// Cache
$cache = new Cache(3600,$config->get('store_id'),$config->get('config_language_id'));
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);


$session->data['language']= $config->get("config_language");

/*
switch($_SERVER['HTTP_HOST']) {
	case 'de.moresku.com':
		$session->data['language']='DE';
		break;
	case 'fr.moresku.com':
		$session->data['language']='FR';
		break;
	case 'es.moresku.com':
		$session->data['language']='ES';
		break;
	case 'it.moresku.com':
		$session->data['language']='IT';
		break;
	case 'pt.moresku.com':
		$session->data['language']='PT';
		break;
	case 'jp.moresku.com':
		$session->data['language']='JP';
		break;
	default:
		$session->data['language']='EN';
		break;
}
*/


if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}
if (!isset($session->data['session_id'])) {
	$session->data['session_id'] = $session->getId();
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {	  
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', COOKIE_DOMAIN);
}			



$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);



// Language	
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);	
$registry->set('language', $language); 

// Document
$registry->set('document', new Document()); 		

// Customer
$registry->set('customer', new Customer($registry));

// Affiliate
$registry->set('affiliate', new Affiliate($registry));

if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/',COOKIE_DOMAIN);
}
		
// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));


// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));
// Front Controller 
$controller = new Front($registry);


// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));
// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));


// Output
$response->output();
?>