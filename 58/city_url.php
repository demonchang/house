<?php 

require_once(dirname(__FILE__).'./../common.php');

//$proxy = getProxys();
// $url = 'https://www.58.com/changecity.html?catepath=ershoufang&catename=%E4%BA%8C%E6%89%8B%E6%88%BF&fullpath=1,12&PGTID=0d30000c-0000-2e30-026a-4b3d35b1ed63&ClickID=1';
// $html_list = $curl_class->request($url);
// file_put_contents('detail.html',$html_list);exit();
$html = file_get_contents('detail.html');
$json = json_decode($html,true);


//var_dump($out);

foreach ($json as $key => $value) {
	//var_dump($value);exit();
	foreach ($value as $k => $v) {
		$name = $k;
		$arr = explode('|', $v);
		//https://hf.58.com
		$url = 'https://'.$arr[0].'.58.com/';
		$other = $arr[1];
		$content_field = "insert into wuba_city (url,name,other) value('{$url}','{$name}','{$other}')";
		//var_dump($content_field);exit();
		$insert_field = $sql_class->insertContent($url,'wuba_city',$content_field);
	}
}

		

 ?>