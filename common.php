<?php 

require_once(dirname(__FILE__).'/curl_multi.php');
require_once(dirname(__FILE__).'/ua.php');
require_once(dirname(__FILE__).'/mysqls.php');


$agent_class = new Agent();
$curl_class = new Curl($agent_class);
$sql_class = new Mysqlis();
date_default_timezone_set('Asia/Shanghai'); 



/**proxy IP 
* @param url your proxy_url
*return json 
**/

function getProxys(){
	global $curl_class;
	
	$proxy_url = 'http://112.124.117.191/workerman/get_proxy.php?count=50';
	$proxys = json_decode($curl_class->request($proxy_url), true); //json2Array
	return $proxys;
}

 ?>