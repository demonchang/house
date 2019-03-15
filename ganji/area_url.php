<?php 

require_once(dirname(__FILE__).'./../common.php');


// $content_field = "select * from ganji_city";
// $select_result = $sql_class->querys($content_field);
$select_result = file_get_contents('ganji_city1.log');
$select_result = explode(PHP_EOL,$select_result);


foreach ($select_result as $key => $value) {
	//$base_url = $value['url'];
	//$url = $base_url.'ershoufang/';
	if(empty($value)) continue;
	$url = $value;
	//var_dump($url);exit();
	$html_city = $curl_class->request($url);
	sleep(1);
	//file_put_contents('detail1.html',$html_city);
	//$html_city = file_get_contents('detail1.html');

	preg_match('#<div class="thr-list">([\s\S]*?)<!--地铁筛选-->#', $html_city, $out);
	var_dump($out);//exit();
	if(empty($out)){
		file_put_contents('ganji_city.log',$url.PHP_EOL,FILE_APPEND);
		continue;
	}
	//var_dump($out);exit();

	$date_role = $out[1];
	preg_match_all('#<a.*?href="(.*?)".*?>(.*?)</a>#', $date_role, $out1);
	if(empty($out1)){
		file_put_contents('ganji_city.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		continue;
	}
	//var_dump(222);
	//var_dump($out1[1]);exit();
	$count = count($out1[0]);
	for ($i=0; $i < $count; $i++) { 
		$url_area = 'http:'.$out1[1][$i];
		
		$name = trim($out1[2][$i]);
		if($name == '不限') continue;
		//file_put_contents('area_url.txt',$url_area.','.$out1[2][$i].PHP_EOL,FILE_APPEND);

		$html_area = $curl_class->request($url_area);
		sleep(1);
		//var_dump(333);
		//file_put_contents('detail1.html',$html_area);exit();
		//$html_area = file_get_contents('detail1.html');
		preg_match('#<!-- 二级选项 -->([\s\S]*?)!--地铁筛选-->#', $html_area, $out2);
		if(empty($out2)){
			file_put_contents('ganji_city.log',$url_area.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out);exit();

		$date_area = $out2[1];
		preg_match_all('#<a.*?href="(.*?)".*?>(.*?)</a>#', $date_area, $out3);
		if(empty($out3)){
			file_put_contents('ganji_city.log',$url_area.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}
		$count_qu = count($out3[0]);
		for ($j=0; $j < $count_qu; $j++) { 
			$url_field = 'http:'.$out3[1][$j];
			//var_dump($url_field);
			$content_url_field = "select * from ganji_area where url='{$url_field}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into ganji_area(name,url,parent_id) values('{$out3[2][$j]}','{$url_field}','{$value['id']}')";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
			}
			
		}
	}


}


 ?>