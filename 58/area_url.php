<?php 

require_once(dirname(__FILE__).'./../common.php');


// $content_field = "select * from wuba_city";
// $select_result = $sql_class->querys($content_field);
$select_result = file_get_contents('wuba_city.log');
$select_result = explode(PHP_EOL,$select_result);


foreach ($select_result as $key => $value) {
	//$base_url = $value['url'];
	if(empty($value)) continue;
	$url = $value;

	preg_match('#(.*\.58\.com).*?#',$url,$out11);
	
	$base_url = $out11[1].'/';
	
	//$url = $base_url.'ershoufang/';
	

	$type = 0;
	// $base_url = $value['url'];
	// $url = $base_url.'ershoufang/';
	//var_dump($url);exit();
	$html_city = $curl_class->request($url);
	sleep(1);
	//file_put_contents('detail1.html',$html_city);exit();
	//$html_city = file_get_contents('detail.html');

	preg_match("#id='qySelectFirst'>([\s\S]*?)id='dtSelectFirst'>#", $html_city, $out);
	//var_dump($out);exit();
	if(empty($out)){
		// preg_match("#<dt>区域：([\s\S]*?) <dt>总价#", $html_city, $out11);
		// //
		// if(!empty($out11)){
		// 	$out = $out11;
		// 	$type = 1;
		// }else{
			file_put_contents('wuba_city1.log',$url.PHP_EOL,FILE_APPEND);
			continue;
		//}
		
	}
	//var_dump($out);exit();

	$date_role = $out[1];
	var_dump(111);
	preg_match_all('#<a[\s\S]*?href="(.*?)"[\s\S]*?>([\s\S]*?)</a>#', $date_role, $out1);
	//var_dump($date_role);exit();
	if(empty($out1)){
		file_put_contents('wuba_city1.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		continue;
	}

	
	$count = count($out1[1]);
	for ($i=0; $i < $count; $i++) { 
		$name = trim($out1[2][$i]);
		if($name == '不限') continue;
		$url_area = $base_url.substr($out1[1][$i],1);
		//var_dump($url_area);exit();
		
		if($type){
			$content_url_field = "select * from wuba_area where url='{$url_area}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into wuba_area(name,url,parent_id) values('{$name}','{$url_area}','{$value['id']}')";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
			}
		}else{
			//file_put_contents('area_url.txt',$url_area.','.$out1[2][$i].PHP_EOL,FILE_APPEND);

			$html_area = $curl_class->request($url_area);
			sleep(1);
			//var_dump(333);
			//file_put_contents('detail.html',$html_area);exit();
			//$html_area = file_get_contents('detail.html');
			preg_match("#id='qySelectSecond'>([\s\S]*?)id='dtSelectFirst'>#", $html_area, $out2);
			var_dump(222);
			if(empty($out2)){
				file_put_contents('wuba_city1.log',$url_area.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2);exit();

			$date_area = $out2[1];
			preg_match_all('#<a.*?href="([\s\S]*?)"[\s\S]*?>([\s\S]*?)</a>#', $date_area, $out3);
			var_dump(333);
			//var_dump($out3);exit();
			if(empty($out3)){
				file_put_contents('wuba_city1.log',$url_area.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			$count_qu = count($out3[0]);
			for ($j=0; $j < $count_qu; $j++) { 
				$url_area = $base_url.substr($out3[1][$j],1);
				var_dump(555);
				$name_area = trim($out3[2][$j]);
				$content_url_field = "select * from wuba_area where url='{$url_area}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into wuba_area(name,url,parent_id) values('{$name_area}','{$url_area}','{$value['id']}')";
					var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
				
			}
		}
		
	}


}


 ?>