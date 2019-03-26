<?php
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }
    
    $postUrl = $url;
    $curlPost = $param;
    $curl = curl_init();//初始化curl
    curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($curl);//运行curl
    curl_close($curl);
    
    return $data;
}

function getAccessToken(){
    $url = 'https://aip.baidubce.com/oauth/2.0/token';
    $post_data['grant_type']       = 'client_credentials';
    $post_data['client_id']      = 'h3fVdXkSI6vKKzSKwfX8jlT5';
    $post_data['client_secret'] = '2KLfqk3Gn6OZdQUGUyIInT8GBvM8gOxM';
    $o = "";
    foreach ( $post_data as $k => $v ) 
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);
    
    $res = request_post($url, $post_data);

    $arr = json_decode($res,true);
    $token = $arr['access_token'];
    return $token;
}	

function getNum($imgurl){
    $token = getAccessToken();
    $apiurl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token='.$token;
    $post = array(
        'url' => $imgurl
        );
    $post_str = http_build_query($post);
    $res = request_post($apiurl, $post_str);
    $arr = json_decode($res,true);
    $words_result = $arr['words_result'];

    return $words_result[0]['words'];
}
//$imgurl = 'http://static8.ziroom.com/phoenix/pc/images/price/d4c2fe6d83edbc2ba65f9f05df63f8bcs.png';
//echo getNum($imgurl);   



?>