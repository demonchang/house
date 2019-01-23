<?php 

class Curl {
	public static $agent;
	public function __construct($ua){
		self::$agent = $ua;
	}
	
	public static function request($url, $gzip=false, $proxy='', $method='get', $fields = array(), $referer=''){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//curl_setopt($ch,CURLOPT_HTTPHEADER, array("Host: www.landchina.com" ,'Origin:http://www.landchina.com'));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		//curl_setopt($ch,CURLOPT_COOKIE,'ASP.NET_SessionId=nfcr1mdqwgg2msmkevuk2zaf; Hm_lvt_83853859c7247c5b03b527894622d3fa=1475997978; Hm_lpvt_83853859c7247c5b03b527894622d3fa=1476004958');
		if ($referer) {
			curl_setopt ($ch,CURLOPT_REFERER, $referer);
		}
		if ($proxy) {
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			//curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'adsl:public');
		}

		if($gzip) curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if ($method === 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, true );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		$result = curl_exec($ch);
		return $result;
		curl_close($ch);
	}

	function getProxys(){

		$proxy_url = 'http://112.124.117.191/workerman/get_proxy.php?count=35';
		$proxys = json_decode(self::request($proxy_url), true); //json2Array
		return $proxys;
	}

	public static function getMultiRequest($url_arr,  $proxy=array(), $gzip=false){
		if (!is_array($url_arr)) {
	        $temp[] = $url_arr;
	        $url_arr = $temp;
	    }
	    $handle = array();
	    $data    = array();
	    $mh = curl_multi_init();
	    $i = 0;
	    $start = rand(1,30);
	    //$url_handle = [];
	    foreach ($url_arr as $key=>$url) {
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $url);

	            if (!empty($proxy)) {
	            	
					curl_setopt($ch, CURLOPT_PROXY, $proxy[$value]);
					//curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'adsl:public');
	            }
	            //curl_setopt($ch,CURLOPT_HTTPHEADER, array("Host: www.landchina.com" ,'Origin:http://www.landchina.com'));
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
				//curl_setopt($ch,CURLOPT_COOKIE,'ASP.NET_SessionId=nfcr1mdqwgg2msmkevuk2zaf; Hm_lvt_83853859c7247c5b03b527894622d3fa=1475997978; Hm_lpvt_83853859c7247c5b03b527894622d3fa=1476004958');
	            curl_setopt($ch, CURLOPT_HEADER, 0);
				//curl_setopt ($ch,CURLOPT_REFERER, 'http://www.landchina.com/default.aspx?tabid=261&ComName=default');
	            if($gzip) curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	            //curl_setopt($ch, CURLOPT_PROXY, $proxy);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
	            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	            //curl_setopt($ch, CURLOPT_USERAGENT, self::$agent->getOneAgent());
	            curl_multi_add_handle($mh, $ch); 
	            $handle[$i++] = $ch;
	            //$url_handle[$ch] = $url;       
	        }
	    $active = null;
	    do {
	        $mrc = curl_multi_exec($mh, $active);
	    } while ($mrc == CURLM_CALL_MULTI_PERFORM);


	    while ($active and $mrc == CURLM_OK) {

	        if(curl_multi_select($mh) === -1){
	            usleep(100);
	        }
	        do {
	            $mrc = curl_multi_exec($mh, $active);
	        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

	    }

	    foreach($handle as $j=>$ch) {
	        $content  = curl_multi_getcontent($ch);
	        $url = $url_arr[$j];
	        if (curl_errno($ch) == 0 && $content != '') {
	        	
	            $data[$url] = $content;
	        }else{
	        	$data[$url] = ''; 

	        }
	        
	    }

	    foreach ($handle as $ch) {
	        curl_multi_remove_handle($mh, $ch);
	    }

	    curl_multi_close($mh);
	    //var_dump($data);
	    return $data;//返回抓取到的内同
		}
}





 ?>