<?php 

require_once(dirname(__FILE__).'./../common.php');

$i = 1;
$filepath = './ganji/';

//select a.*,b.url from lianjia_ershou_xiaoqu as a left join lianjia_url_xiaoqu as b on a.parent_id = b.id limit 300
while($html_city = file_get_contents($filepath.$i.'.html')){
		
		$path = "./img/{$i}/";
		if (!is_dir($path)) mkdir($path);

		preg_match('#<div class="small-img">([\s\S]*?)<div class="basic-imgs-btn prev"#', $html_city, $out2);
		//var_dump($out2);exit();
		if(!isset($out2[1]) || empty($out2[1])){
			continue;
		}else{

			preg_match_all('#data-link="([\s\S]*?)w=600&h=450&crop=1"#', $out2[1], $out3);
			if(!isset($out3[1]) || empty($out3[1])){
				continue;
			}else{
				//var_dump($out3[1]);exit();
				foreach($out3[1] as $url) {
				    download('http:'.$url,$path);
				}
			}
		}

		$i++;
		//exit();
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
    if(empty($exf)){
    	$exf = 'jpg';
    }
    $filename =  date("YmdHis").uniqid().'.'.$exf;
   	
    $resource = fopen($path . $filename, 'a');
    fwrite($resource, $file);
    fclose($resource);
}


 ?>