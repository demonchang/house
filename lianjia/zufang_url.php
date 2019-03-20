<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from lianjia_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];

	$content_fields = "select * from lianjia_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		$url = $base_url.str_replace('ershoufang','zufang',$v['url']);
		//var_dump($key.$url);
		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');

		preg_match('#<span class="content__title--hl">(.*?)</span>#', $html_city, $out);
		if(!isset($out[1]) || empty($out[1])){
			file_put_contents('lianjia_all_count.log',$url.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out);exit();

		$all_count = $out[1];

		preg_match('#data-totalPage=(\d*?) data-curPage#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			file_put_contents('lianjia_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}

		$page_count = $out1[1];

		$content_fieldss = "update lianjia_area set all_count_zufang={$all_count},page_count_zufang={$page_count} where id={$v['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."pg{$page}/";
				//var_dump($new_url);
				$html = $curl_class->request($new_url);
				if(empty($html)) continue;

			}

			preg_match_all('#<a class="content__list--item--aside".*?href="(.*?)".*?#', $html, $out2);
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('lianjia_url_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2[1]);exit();

			foreach ($out2[1] as $keys => $values) {
				$zufang_url = $base_url.$values;
				//var_dump($zufang_url);exit();
				$content_url_field = "select * from lianjia_url_zufang where url='{$zufang_url}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into lianjia_url_zufang(url,parent_id) values('{$zufang_url}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}
}


 ?>