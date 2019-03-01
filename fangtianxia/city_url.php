<?php 

require_once(dirname(__FILE__).'./../common.php');

//$proxy = getProxys();
// $url = 'https://www.fang.com/SoufunFamily.htm';
// $html_list = $curl_class->request($url);
// file_put_contents('detail.html',$html_list);
// exit();
$html = file_get_contents('detail.html');
$content_preg = '#<a href="(.*?)" .*?>(.*?)</a>#';
preg_match_all($content_preg, $html, $out);

$count = count($out[0]);
for ($i=0; $i < $count; $i++) { 
	$url = $out[1][$i];
	$name = $out[2][$i];
	$city_name = mb_convert_encoding($name, "UTF-8", "GBK");
	$content_field = "insert into fangtianxia_city (url,name) value('{$url}','{$city_name}')";
	//var_dump($content_field);exit();
	$insert_field = $sql_class->insertContent($url,'fangtianxia_city',$content_field);
	// $html_city = $curl_class->request($url);
	// //var_dump($html_city);exit();
	// //<a class="" href="https://sh.lianjia.com/ershoufang/">二手房</a>
	// preg_match('#li><a.*?href="(.*?)" >二手房</a></li>#', $html_city, $out1);
	// if(empty($out1)){
	// 	continue;
	// }else{

	// 	$detail_url = $out1[1];
	// 	var_dump($detail_url);
	// 	if($detail_url != $url.'ershoufang/'){
	// 		file_put_contents('lianjia_city.log',$detail_url.PHP_EOL,FILE_APPEND);
	// 	}
	// 	$ershoufang = 0;
	// 	$chengjiao = 0;
	// 	$xiaoqu = 0;
	// 	$html_city_detail = $curl_class->request($detail_url);
	// 	//file_put_contents('detail1.html',$html_city_detail);exit();
		
	// 	//<a class="" href="https://sh.lianjia.com/ershoufang/">二手房</a>
	// 	preg_match('#<a href="(.*?)"  title=".*?" >在售</a>#', $html_city_detail, $out2);
	// 	preg_match('#<a href="(.*?)"  title=".*?" >成交</a>#', $html_city_detail, $out3);
	// 	preg_match('#<a href="(.*?)"  title=".*?" >小区</a>#', $html_city_detail, $out4);
	// 	if(!empty($out2[1])){
	// 		$ershoufang = 1;
	// 	}
	// 	if(!empty($out3[1])){
	// 		$chengjiao = 1;
	// 	}
	// 	if(!empty($out4[1])){
	// 		$xiaoqu = 1;
	// 	}
	// 	//var_dump($ershoufang,$chengjiao,$xiaoqu);exit();

	// 	$content_field = "update fangtianxia_city set ershoufang={$ershoufang},chengjiao={$chengjiao},xiaoqu={$xiaoqu} where url='{$url}'";
	// 	var_dump($content_field);exit();
	// 	$insert_field = $sql_class->update($content_field);


	// }
	
	

}



 ?>