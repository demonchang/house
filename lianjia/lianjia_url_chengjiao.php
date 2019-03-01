<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from lianjia_city where chengjiao=1";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {
	$base_url = $value['url'];

	$content_fields = "select * from lianjia_area where parent_id={$value['id']}";
	$select_results = $sql_class->querys($content_fields);
	foreach ($select_results as $k => $v) {
		if($v['id'] < 2962){
			continue;
		}
		$url = $base_url.str_replace('ershoufang','chengjiao',$v['url']);
		var_dump($key.' '.$url);
		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();

		//$html_city = file_get_contents('detail1.html');
		//<div class="total fl">共找到<span> 264 </span>套合肥成交房源</div

		preg_match('#<div class="total fl">共找到<span>(.*?)</span>.*?</div>#', $html_city, $out);
		if(!isset($out[1]) || empty($out[1])){
			file_put_contents('lianjia_all_count.log',$url.PHP_EOL,FILE_APPEND);
			continue;
		}
		//var_dump($out);exit();

		$all_count = trim($out[1]);

		preg_match('#{"totalPage":(.*?),#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			file_put_contents('lianjia_page_count.log',$url.' -lianjie'.PHP_EOL,FILE_APPEND);
			continue;
		}

		$page_count = $out1[1];

		$content_fieldss = "update lianjia_area set all_count_chengjiao={$all_count},page_count_chengjiao={$page_count} where id={$v['id']}";
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

			preg_match_all('#<div class="title">[\s]*?<a href="(.*?)" target="_blank">.*?</a>[\s]*?</div>#', $html, $out2);
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('lianjia_url_chengjiao_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			//var_dump($out2[1]);exit();

			foreach ($out2[1] as $keys => $values) {
				$content_url_field = "select * from lianjia_url_chengjiao where url='{$values}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into lianjia_url_chengjiao(url,parent_id) values('{$values}',{$v['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}



	}
}


 ?>