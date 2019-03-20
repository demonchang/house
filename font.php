<?php 
require 'vendor/autoload.php';


function fontDecode($html){
	//传入页面内容
	if(empty($html)) return $html;
	//截取加密部分
	preg_match('#data:application/font-ttf;charset=utf-8;base64,(.*?)\'\) format#',$html,$out);
	if(!isset($out[1]) || empty($out[1])) return $html;
	//还原字体文件
	file_put_contents('font.ttf',base64_decode($out[1]));
	//解析字体文件
	$font = \FontLib\Font::load('font.ttf');
	$font->parse();
	$font_json = json_encode($font->getTableObject('cmap'),JSON_FORCE_OBJECT);
	$font_arr = json_decode($font_json,true);
	$data = $font_arr['data']['subtables'][0]['glyphIndexArray'];
	$new_arr = array();
	foreach ($data as $key => $value) {
		$k = dechex($key);
		$v = $value-1;
		$new_arr[$k] = $v;
	}

	//var_dump($new_arr);
	
	//对原页面加密内容替换
	foreach ($new_arr as $ks => $vs) {
		$pres_str = "&#x{$ks};";
		$html = preg_replace("/$pres_str/",$vs,$html);
	}
	return $html;
}

// $html = file_get_contents('./ganji/detail.html');
// $new_html = fontDecode($html);
// file_put_contents('detail1.html',$new_html);


 ?>