<?php
include_once('/home/www/new_myled.com/config.php');
$host = MEMCACHE_HOSTNAME;
$port = MEMCACHE_PORT;
$mem = new Memcache();
$mem->connect(MEMCACHE_HOSTNAME, MEMCACHE_PORT);
$items = $mem->getExtendedStats('items');
$items = $items["$host:$port"]['items'];
// $items2 = $mem->getExtendedStats('stats detail dump');
if (!empty($items)) {
    foreach ($items as $item) {
        $number = $item['number'];
        $str = $mem->getExtendedStats("cachedump", $number, 0);
        $line = $str["$host:$port"];
        foreach ($line as $key => $value) {
            echo $key."\n";
            if(strpos($key,'myled') !== false){
                $mem->delete($key);
            }
           
        }
    }
}
?>
