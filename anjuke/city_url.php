<?php 

require_once(dirname(__FILE__).'./../common.php');

//$proxy = getProxys();
// $url = 'https://www.anjuke.com/sy-city.html';
// $html_list = $curl_class->request($url);
// file_put_contents('detail.html',$html_list);exit();
$html = file_get_contents('detail.html');
$content_preg = '#<a href="(.*?)">(.*?)</a>#';
preg_match_all($content_preg, $html, $out);
//var_dump($out);exit();
$count = count($out[0]);
for ($i=0; $i < $count; $i++) { 
	$url = $out[1][$i];
	$name = $out[2][$i];


	$content_field = "insert into anjuke_city (url,name) value('{$url}','{$name}')";
	//var_dump($content_field);exit();
	$insert_field = $sql_class->insertContent($url,'anjuke_city',$content_field);
	
	
	

}



 ?>