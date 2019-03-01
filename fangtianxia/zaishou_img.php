<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from fangtianxia_url where imgstatus=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		$path = "images/{$value['id']}/";
		if (!is_dir($path)) mkdir($path);
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url,true);
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update fangtianxia_url set imgstatus={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<div id="sfbdetaildesimgs">([\s\S]*?)<!-- 小区信息开始 -->#', $html_city, $out2);
		//var_dump($out2);exit();
		if(!isset($out2[1]) || empty($out2[1])){
			$status = 3; //解析标题失败
			continue;
		}else{

			preg_match_all('#data-src="([\s\S]*?)"#', $out2[1], $out3);
			if(!isset($out3[1]) || empty($out3[1])){
				$status = 3; //解析标题失败
				continue;
			}else{
				//var_dump($out3[1]);exit();
				foreach($out3[1] as $url) {
				    download('https:'.$url,$path);
				}
				$status = 1; 
			}
		}

		//exit();


		
		$content_fieldss = "update fangtianxia_url set imgstatus={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}



function download($url, $path=''){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
    $file = curl_exec($ch);
    curl_close($ch);
    $exf = pathinfo($url, PATHINFO_EXTENSION);
    $filename =  date("YmdHis").uniqid().'.'.$exf;
   	
    $resource = fopen($path . $filename, 'a');
    fwrite($resource, $file);
    fclose($resource);
}


 ?>