<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from wuba_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];

	$content_fields = "select * from wuba_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		$url = $v['url'];
		//var_dump($key.$url);
		
		usleep(800000);
		$html_city = $curl_class->request($url);

		
		//file_put_contents('detail.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');



		$all_count = 0;
		$page_count = 0;
		preg_match('#<span>(\d*?)</span></a>[\s]*?<a class="next"#', $html_city, $out1);

		if(isset($out1[1]) && !empty($out1[1])){
			$page_count = $out1[1];
		}else{
			file_put_contents('wuba_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
			$page_count = 1;
		}
		//var_dump($page_count);exit();
		

		$content_fieldss = "update wuba_area set all_count={$all_count},page_count={$page_count} where id={$v['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."pn{$page}/";
				usleep(800000);
				$html = $curl_class->request($new_url);
				if(empty($html)) continue;

			}

			preg_match_all('#<h2.*?>[\s]*?<a.*?href="([\s\S]*?)"[\s\S]*?</a>#', $html, $out2);

			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('wuba_url_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2[1]);exit();

			foreach ($out2[1] as $keys => $values) {
				$real_url = $values;
				$content_url_field = "select * from wuba_url where url='{$real_url}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into wuba_url(url,parent_id) values('{$real_url}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}
	unset($select_results);
}


 ?>