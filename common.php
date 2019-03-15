<?php 

require_once(dirname(__FILE__).'/curl_multi.php');
require_once(dirname(__FILE__).'/ua.php');
require_once(dirname(__FILE__).'/mysqls.php');


$agent_class = new Agent();
$curl_class = new Curl($agent_class);
$sql_class = new Mysqlis();
date_default_timezone_set('Asia/Shanghai'); 




 ?>