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
	}else{
		$newtitle = substr($base_url,0,-9);
		$base_url = $newtitle.'zu.fang.com';

	}
		$url = $base_url.'/house/a210/';
		//var_dump($key.'-'.$url);
		$html_city = $curl_class->request($url,true);
		$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
		//file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');

		//houseallcount
		// preg_match("#houseallcount = '(\d*?)';#", $html_city, $out);
		// if(!isset($out[1]) || empty($out[1])){
		// 	file_put_contents('lianjia_all_count.log',$url.PHP_EOL,FILE_APPEND);
		// 	continue;
		// }

		$all_count = 0;

		preg_match('#共(\d*?)页#', $html_city, $out1);
		//var_dump($out1);exit();
		if(!isset($out1[1]) || empty($out1[1])){
			file_put_contents('fangtianxia_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}
		
		$page_count = $out1[1];
		$content_fieldss = "update fangtianxia_city set all_count={$all_count},page_count={$page_count} where id={$value['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = substr($url,0,-1)."-i3{$page}/";
				//var_dump($new_url);
				$html = $curl_class->request($new_url,true);
				$html = mb_convert_encoding($html, "UTF-8", "GBK");
				if(empty($html)) continue;

			}
			if(preg_match("#很抱歉，没有找到相符的房源，您可能对以下房源感兴趣#",$html)){
				file_put_contents('fangtianxia_gongyu.log',$url.PHP_EOL,FILE_APPEND);
				continue;
			}
			preg_match_all('#<p class="title"[\s\S]*?href="(.*?)"[\s\S]*?</p>#', $html, $out2);
			//var_dump($out2);exit();
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('fangtianxia_url_gongyu_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2);exit();

			foreach ($out2[1] as $keys => $values) {
				$detialurl = $base_url.$values;

				$content_url_field = "select * from fangtianxia_url_gongyu where url='{$detialurl}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into fangtianxia_url_gongyu(url,parent_id) values('{$detialurl}',{$value['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}


}


 ?>