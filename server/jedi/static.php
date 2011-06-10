<?php


function validip($ip) {
 	

	if (!( !empty($ip) && ip2long($ip)!=-1)) {
		return false;
	}
 
	$reserved_ips = array (

		array('0.0.0.0','2.255.255.255'),

		array('10.0.0.0','10.255.255.255'),

		array('127.0.0.0','127.255.255.255'),

		array('169.254.0.0','169.254.255.255'),

		array('172.16.0.0','172.31.255.255'),

		array('192.0.2.0','192.0.2.255'),

		array('192.168.0.0','192.168.255.255'),

		array('255.255.255.0','255.255.255.255')

	);


	foreach ($reserved_ips as $r) {

		$min = ip2long($r[0]);

		$max = ip2long($r[1]);

		if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;

	}

	return true;
 
}
 
function getip() {

	if ( isset($_SERVER["HTTP_CLIENT_IP"]) && validip($_SERVER["HTTP_CLIENT_IP"])) {
		return $_SERVER["HTTP_CLIENT_IP"] ;
	}

	if( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){
		foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
	 		if (validip(trim($ip))) {
	 			return $ip ;
	 		}
	 	}		
	}

 
	if ( isset($_SERVER["HTTP_X_FORWARDED"]) && validip($_SERVER["HTTP_X_FORWARDED"])) {
 
		return $_SERVER["HTTP_X_FORWARDED"] ;
 
	} elseif ( isset($_SERVER["HTTP_FORWARDED_FOR"]) && validip($_SERVER["HTTP_FORWARDED_FOR"])) {
 
		return $_SERVER["HTTP_FORWARDED_FOR"] ;
 
	} elseif ( isset($_SERVER["HTTP_FORWARDED"]) && validip($_SERVER["HTTP_FORWARDED"])) {
 
		return $_SERVER["HTTP_FORWARDED"] ;
 
	} elseif ( isset($_SERVER["HTTP_X_FORWARDED"]) && validip($_SERVER["HTTP_X_FORWARDED"])) {
 
		return $_SERVER["HTTP_X_FORWARDED"] ;
 
	} else {
 
		return $_SERVER["REMOTE_ADDR"] ;
 
	}

}