<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from anjuke_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];
	$url = $base_url.'/sale/?from=navigation';
	//var_dump($url);
	$html_city = $curl_class->request($url);
	//file_put_contents('detail1.html',$html_city);exit();
	//$html_city = file_get_contents('detail1.html');

	preg_match('#<!-- 区域 begin-->([\s\S]*?)<!-- 区域 end-->#', $html_city, $out);
	//var_dump($out);exit();
	if(empty($out)){
		file_put_contents('anjuke_city.log',$url.PHP_EOL,FILE_APPEND);
		continue;
	}
	

	$date_role = $out[1];
	preg_match_all("#<a href='(.*?)'.*?>(.*?)</a>#", $date_role, $out1);
	//var_dump($out1);exit();
	if(empty($out1)){
		file_put_contents('anjuke_city.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		continue;
	}

	
	$count = count($out1[0]);
	for ($i=0; $i < $count; $i++) { 
		$url_area = $out1[1][$i];
		//file_put_contents('area_url.txt',$url_area.','.$out1[2][$i].PHP_EOL,FILE_APPEND);
		$html_area = $curl_class->request($url_area);

		//file_put_contents('detail.html',$html_area);exit();
		//$html_area = file_get_contents('detail.html');
		preg_match('#<div class="sub-items">([\s\S]*?)<!-- 区域 end-->#', $html_area, $out2);
		if(empty($out2)){
			file_put_contents('anjuke_city.log',$url_area.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out2);exit();

		$date_area = $out2[1];
		preg_match_all("#<a href='(.*?)'.*?>(.*?)</a>#", $date_area, $out3);
		if(empty($out3)){
			file_put_contents('anjuke_city.log',$url_area.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out3);exit();
		$count_qu = count($out3[0]);
		for ($j=0; $j < $count_qu; $j++) { 
			$url_field = $out3[1][$j];
			//var_dump($url_field);
			$content_url_field = "select * from anjuke_area where url='{$url_field}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into anjuke_area(name,url,parent_id) values('{$out3[2][$j]}','{$url_field}','{$value['id']}')";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
			}
			
		}
	}


}


 ?>