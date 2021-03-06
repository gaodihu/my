<?php
// HTTP
define('HTTP_SERVER', 'http://admin.mydev.com/');
define('HTTP_CATALOG', 'http://www.mydev.com/');

// HTTPS
define('HTTPS_SERVER', 'http://admin.mydev.com/');
define('HTTPS_CATALOG', 'http://www.mydev.com/');

// DIR

$dir = str_replace("\\",'/',__DIR__);
$dir = substr($dir,0, 0 - strlen('myadmin034j8so9t'));
define('DIR_BASE', $dir);


define('DIR_APPLICATION', DIR_BASE . '/myadmin034j8so9t/');
define('DIR_SYSTEM',      DIR_BASE . 'Application/system/');
define('DIR_DATABASE',    DIR_BASE . 'Application/system/database/');
define('DIR_LANGUAGE',    DIR_BASE . '/myadmin034j8so9t/language/');
define('DIR_TEMPLATE',    DIR_BASE .  '/myadmin034j8so9t/view/template/');
define('DIR_CONFIG',      DIR_BASE . 'Application/system/config/');
define('DIR_IMAGE',       DIR_BASE . 'Public/image/');
define('DIR_CACHE',       DIR_BASE . 'Data/cache/');
define('DIR_DOWNLOAD',    DIR_BASE . '/download/');
define('DIR_LOGS',        DIR_BASE . 'Data/logs/');
define('DIR_CATALOG',     DIR_BASE . 'Application/catalog/');
define('DIR_MODEL',       DIR_BASE . '/myadmin034j8so9t/model/');
define('DIR_DATA',        DIR_BASE . 'Data/');

// DB

define('DB_DRIVER', 'mysqli');

define('DB_HOSTNAME', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'charles');

define('COOKIE_DOMAIN','mydev.com');

define('DB_PREFIX', 'oc_');
define('CACHE_DRIVER', 'memcached');
define('MEMCACHE_HOSTNAME', '127.0.0.1');
define('MEMCACHE_PORT', '1121133');
define('MEMCACHE_NAMESPACE', 'mydev');

define('DHL_URL', 'http://www.dhl.com/en.html');
define('GLOBALMAIL_URL', 'http://webtrack.dhlglobalmail.com/?locale=en-US');
define('EMS_URL', 'http://www.ems.com.cn/mailtracking/e_you_jian_cha_xun.html');
define('UPS_URL', 'http://www.ups.com/tracking/tracking.html');
define('SG_URL', 'http://www.singpost.com');
define('AU_URL', 'http://auspost.com.au/track/');
define("USPS_URL",'https://tools.usps.com/go/TrackConfirmAction_input');
define("SF_URL",'http://www.sfb2c.com/?a=trackEn');

?>
