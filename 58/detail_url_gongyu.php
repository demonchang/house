<?php 

require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from wuba_city";
$select_result = $sql_class->querys($content_field);
//var_dump($select_result);exit();
foreach ($select_result as $key => $value) {

		$url = $value['url'].'pinpaigongyu/';
		//var_dump($key.$url);
		
		sleep(1);
		$html_city = $curl_class->request($url);
		//file_put_contents('detail.html',$html_city);exit();

		//$html_city = file_get_contents('detail.html');


		$pagesize = 20;
		$all_count = 0;
		$page_count = 1;
		//<span>28</span></a><a class="next"
		preg_match('#post_count = "(\d*?)",#', $html_city, $out1);
		//var_dump($out1);exit;
		if(isset($out1[1]) && !empty($out1[1])){
			$all_count = $out1[1];
		}

		$page_count = intval(ceil(intval($all_count)/$pagesize));

		$content_fieldss = "update wuba_city set all_count_gongyu={$all_count},page_count_gongyu={$page_count} where id={$value['id']}";
		$sql_class->update($content_fieldss);

		//var_dump($content_fieldss);exit();
		
		for ($i=0; $i < $page_count; $i++) { 
			$page = $i+1;
			if($page == 1){
				$html = $html_city;
			}else{
				$new_url = $url."pn{$page}/";
				sleep(1);
				$html = $curl_class->request($new_url);
				if(empty($html)) continue;

			}

			preg_match_all('#<li.*?>[\s]*?<a.*?href="([\s\S]*?)"#', $html, $out2);
			//var_dump($out2[1]);exit();
			if(!isset($out2[1]) || empty($out2[1])){
				file_put_contents('wuba_url_list.log',$new_url.' -lianjie'.PHP_EOL,FILE_APPEND);
				continue;
			}
			

			foreach ($out2[1] as $keys => $values) {
				$real_url = $values;
				$content_url_field = "select * from wuba_url_gongyu where url='{$real_url}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into wuba_url_gongyu(url,parent_id) values('{$real_url}',{$value['id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
				}
			}


		}

}


 ?>