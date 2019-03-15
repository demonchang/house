<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from anjuke_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = '';

	$content_fields = "select * from anjuke_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		$url = $base_url.$v['url'];
		//var_dump($key.$url);
		$html_city = $curl_class->request($url);
		// file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		//preg_match('#<h2 class="total fl">共找到<span> (.*?) </span>.*?</h2>#', $html_city, $out);
		// if(!isset($out[1]) || empty($out[1])){
		// 	file_put_contents('anjuke_all_count.log',$url.PHP_EOL,FILE_APPEND);
		// 	continue;
		// }
		//var_dump($out);exit();

		$all_count = 0;

		//preg_match('#{"totalPage":(.*?),#', $html_city, $out1);
		// if(!isset($out1[1]) || empty($out1[1])){
		// 	file_put_contents('anjuke_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
		// 	continue;
		// }

		$page_count = 0;

		// $content_fieldss = "update anjuke_area set all_count={$all_count},page_count={$page_count} where id={$v['id']}";
		// $sql_class->update($content_fieldss);

		$page_count=50;
		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."p{$page}/#filtersort";
				
				$html = $curl_class->request($new_url);
				if(empty($html)) continue;

			}

			preg_match_all('#<a data-from=".*?" data-company=".*?" href="(.*?)"#', $html, $out2);
			//var_dump($out2[1]);exit();
			if(!isset($out2[1]) || empty($out2[1])){
				break;
			}
			

			foreach ($out2[1] as $keys => $values) {
				$content_url_field = "select * from anjuke_url where url='{$values}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into anjuke_url(url,parent_id) values('{$values}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}

	unset($select_results);
}


 ?>