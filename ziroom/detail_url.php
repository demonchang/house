<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from ziroom_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];

	$content_fields = "select * from ziroom_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		$url = $v['url'];
		//var_dump($key.$url);
		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');

		preg_match('#<span>共(.*?)页</span>#', $html_city, $out);
		$page = 0;
		$all_count = 0;

		if(isset($out[1]) && !empty($out[1])){
			$page_count = $out[1];
		}
		
		

		$content_fieldss = "update ziroom_area set all_count={$all_count},page_count={$page_count} where id={$v['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."?p={$page}";
				var_dump($new_url);
				$html = $curl_class->request($new_url);
				if(empty($html)) continue;

			}

			preg_match_all('#<h3>[\s\S]*?href="(.*?)"#', $html, $out2);
			//var_dump($out2[1]);exit();
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('ziroom_url_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			

			foreach ($out2[1] as $keys => $values) {
				$urls = 'http:'.$values; 
				$content_url_field = "select * from ziroom_url where url='{$urls}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into ziroom_url(url,parent_id) values('{$urls}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}
}


 ?>