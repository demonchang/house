<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from ziroom_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];
	$url = $base_url;
	//var_dump($url);
	$html_city = $curl_class->request($url);
	//file_put_contents('detail1.html',$html_city);exit();
	//$html_city = file_get_contents('detail1.html');

	preg_match('#区域：([\s\S]*?)地铁：#', $html_city, $out);
	if(empty($out)){
		file_put_contents('ziroom_city.log',$url.PHP_EOL,FILE_APPEND);
		continue;
	}
	//var_dump($out);exit();

	$date_role = $out[1];
	preg_match_all('#<a href="(//.*?.ziroom.com/z/nl/z3-d.*?-b.*?.html)" >(.*?)</a>#', $date_role, $out1);
	if(empty($out1)){
		file_put_contents('ziroom_city.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		continue;
	}

	//var_dump($out1[1]);exit();
	$count = count($out1[0]);
	for ($i=0; $i < $count; $i++) { 
			$url = 'http:'.$out1[1][$i];
			$name = $out1[2][$i];
			//var_dump($name,$url);exit();
			$content_url_field = "select * from ziroom_area where url='{$url}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into ziroom_area(name,url,parent_id) values('{$name}','{$url}','{$value['id']}')";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
			
		}
	}


}


 ?>