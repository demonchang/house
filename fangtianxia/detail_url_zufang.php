<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from fangtianxia_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];
	$base_url = str_replace("http://", "https://", $base_url);
	if($base_url === 'https://bj.fang.com/'){
		$base_url = 'https://zu.fang.com';
		//continue;
	}else{
		$newtitle = substr($base_url,0,-9);
		$base_url = $newtitle.'zu.fang.com';
		//var_dump($base_url);exit();
	}

	$content_fields = "select * from fangtianxia_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		$url = $base_url.$v['url'];
		//var_dump($key.'-'.$url);
		$html_city = $curl_class->request($url,true);
		$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
		//file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');

	

		$all_count = 0;

		preg_match('#<span class="txt">共(\d*?)页</span>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			file_put_contents('fangtianxia_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out);exit();
		$page_count = $out1[1];

		$content_fieldss = "update fangtianxia_area set all_count_zufang={$all_count},page_count_zufang={$page_count} where id={$v['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."i3{$page}/";
				//var_dump($new_url);
				$html = $curl_class->request($new_url,true);
				$html = mb_convert_encoding($html, "UTF-8", "GBK");
				if(empty($html)) continue;

			}
			preg_match_all('#<p class="title"[\s\S]*?href="(.*?)"[\s\S]*?</a>#', $html, $out2);
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('fangtianxia_url_zufang_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2);exit();

			foreach ($out2[1] as $keys => $values) {
				$detialurl = $base_url.$values;

				$content_url_field = "select * from fangtianxia_url_zufang where url='{$detialurl}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				
				if(empty($select_result)){
					$content_field = "insert into fangtianxia_url_zufang(url,parent_id) values('{$detialurl}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}
}


 ?>