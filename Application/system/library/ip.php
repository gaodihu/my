<?php
class Ip {
	private $ip;
    private $GeoIP;
   
    public function __construct() {
		$this->ip = $this->getIp();
        $this->GeoIP = DIR_SYSTEM.'lib/GeoIP.dat/GeoIP.dat';
	}
	public function getIp(){
        if(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function getCountryName($ip){
        require_once(DIR_SYSTEM . 'lib/geoip.inc.php');
        $gi = geoip_open($this->GeoIP,GEOIP_STANDARD);
        // 获取国家代码
        //$country_code = geoip_country_code_by_addr($gi, $ip);
        // 获取国家名称
        $country_name = geoip_country_name_by_addr($gi, $ip);
        // 关闭文件
        geoip_close($gi);
        return $country_name;
    }
    public function getCountryCode($ip){
        require_once(DIR_SYSTEM . 'lib/geoip.inc.php');
        $gi = geoip_open($this->GeoIP,GEOIP_STANDARD);
        // 获取国家代码
        $country_code = geoip_country_code_by_addr($gi, $ip);
        // 获取国家名称
       // $country_name = geoip_country_name_by_addr($gi, $ip);
        // 关闭文件
        geoip_close($gi);
        return $country_code;
    }
}
?>