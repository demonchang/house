<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from fangtianxia_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];
	$base_url = str_replace("http://", "https://", $base_url);
	if($base_url == 'https://bj.fang.com/'){
		$base_url = 'https://esf.fang.com/';
		$cookie = 'sf_source=; s=; global_cookie=oz8hf0limvjzkpa3pf7jr28hk1njr8kwy5z; Integrateactivity=notincludemc; lastscanpage=0; g_sourcepage=undefined; logGuid=d6d9eee6-527b-48fa-a0fd-118cc1ad1b33; integratecover=1; Captcha=7A72525472746D52542B5779594E4D7238522F6456755A554D37446164665957584669536F4D323649666A686B374268686F7851466E2F5167316B4D563868776268334E6D4F6B393475733D; city=www; unique_cookie=U_oz8hf0limvjzkpa3pf7jr28hk1njr8kwy5z*53';
	}else{
		$newtitle = substr($base_url,0,-9);
		$base_url = $newtitle.'esf.fang.com/';
		$cookie = '';
	}

	$url = $base_url;

	
	var_dump($url);
	$html_city = $curl_class->request($url,true,$cookie);
	$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
	//file_put_contents('detail.html',$html_city);exit();
	//$html_city = file_get_contents('detail.html');

	preg_match('#区域</span>(.*?)[\s\S]*?总价#', $html_city, $out);
	if(empty($out)){
		file_put_contents('fangtianxia_city.log',$url.PHP_EOL,FILE_APPEND);
		continue;
	}
	//var_dump($out);exit();

	$date_role = $out[0];
	preg_match_all('#href="(.*?)"#', $date_role, $out1);
	if(empty($out1)){
		file_put_contents('fangtianxia_city.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		continue;
	}

	//var_dump($out1[1]);exit();
	$count = count($out1[0]);
	for ($i=0; $i < $count; $i++) { 
		$url_area = substr($base_url,0,-1).$out1[1][$i];
		//var_dump($url_area);exit();
		file_put_contents('area_url.txt',$url_area.PHP_EOL,FILE_APPEND);
		$html_area = $curl_class->request($url_area,true,$cookie);
	    $html_area = mb_convert_encoding($html_area, "UTF-8", "GBK");
		//file_put_contents('detail.html1',$html_area);exit();
		//$html_area = file_get_contents('detail.html1');
		
		preg_match('#<li class="area_sq">([\s\S]*?)总价#', $html_area, $out2);
		if(empty($out2)){
			file_put_contents('fangtianxia_city.log',$url_area.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out2);exit();

		$date_area = $out2[1];
		preg_match_all('#<a href="(.*?)">(.*?)</a>#', $date_area, $out3);
		if(empty($out3)){
			file_put_contents('fangtianxia_city.log',$url_area.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}

		//var_dump($out3);exit();
		$count_qu = count($out3[0]);
		for ($j=0; $j < $count_qu; $j++) { 
			$url_field = $out3[1][$j];
			//var_dump($url_field);
			$content_url_field = "select * from fangtianxia_area where url='{$url_field}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into fangtianxia_area(name,url,parent_id) values('{$out3[2][$j]}','{$url_field}','{$value['id']}')";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
			}
			
		}
	}


}


 ?>