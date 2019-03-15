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


	$content_fields = "select * from fangtianxia_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		preg_match('#\/house-a0(\d*?)-b0(\d*?)\/#',$v['url'],$housing);
		
		$url = $base_url."housing/{$housing[1]}_{$housing[2]}_0_0_0_0_1_0_0_0/";
		//var_dump($url);exit();
		$html_city = $curl_class->request($url,true,$cookie);
		$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
		// file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');

		preg_match('#<span class="txt">共(.*?)页</span>#', $html_city, $out);
		if(!isset($out[1]) || empty($out[1])){
			file_put_contents('fangtianxia_all_count.log',$url.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out);exit();

		$page_count = $out[1];

		// preg_match('#{"totalPage":(.*?),#', $html_city, $out1);
		// if(!isset($out1[1]) || empty($out1[1])){
		// 	file_put_contents('fangtianxia_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		// 	continue;
		// }

		$all_count = 0;

		$content_fieldss = "update fangtianxia_area set all_count_xiaoqu={$all_count},page_count_xiaoqu={$page_count} where id={$v['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				//https://esf.fang.com/housing/1_1121_0_0_0_0_1_0_0_0/
				$new_url = preg_replace('#0_0_0_0_\d*?_0_0_0#', "0_0_0_0_{$page}_0_0_0", $url);

				//var_dump($new_url);exit();
				$html = $curl_class->request($new_url,true);
				$html = mb_convert_encoding($html, "UTF-8", "GBK");

			}

			preg_match_all('#<dt>[\s]*?<a href="(.*?)".*?><img#', $html, $out2);
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('fangtianxia_url_xiaoqu_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2[1]);exit();

			foreach ($out2[1] as $keys => $values) {
				if(!strpos($values,'fang.com')){continue;}
				$values = 'https:'.$values;
				$content_url_field = "select * from fangtianxia_url_xiaoqu where url='{$values}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into fangtianxia_url_xiaoqu(url,parent_id) values('{$values}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}

			
			unset($html);


		}



	}
	unset($select_results);
}


 ?>